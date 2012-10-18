<?php
/**
* Pjoy Framework v0.8
* An open source application development framework for PHP 5.3 or newer
* @copyright	CopyLeft (cl) - 2012, no license
* @license no license, Public Domain
*/



class PjoyMail {


    private $to,
            $subject,
            $body,
            $charset = "utf-8",
            $headers  = "MIME-Version: 1.0\r\n",
            $attachments = array(),
            $type = "plain";



    public function charset($charset) {
        $this->charset = $charset;
    }



    private function encode($string) {
        return chunk_split(base64_encode($string));
    }



    public function b($string) {
        return "=?{$this->charset}?B?" . base64_encode($string) . "?=";
    }



    private function recipient($email, $name) {
        return $name ? $this->b($name) . " <{$email}>" : $email;
    }



    private function recipients($emails) {
        $temp = array();
        foreach ($emails as $key => $val) {
            if (!is_int($key)) {
                $email = $key;
                $name  = $val;
            }
            else {
                $email = $val;
                $name  = false;
            }
            $temp[] = $this->recipient($email, $name);
        }
        return implode(", ", $temp);
    }



    private function addr($params) {
        if (is_array($params[0])) {
            return $this->recipients($params[0]);
        }
        else {
            $params[1] = @$params[1];
            return $this->recipient($params[0], $params[1]);
        }
    }



    /**
     * Mail()->to($email, $name = false);
     * Mail()->to(array $emails);
     *
     **/
    public function to() {
        $this->to = $this->addr(func_get_args());
    }



    public function add($key, $value) {
        $this->headers .= "{$key}: {$value}\r\n";
    }



    public function cc() {
        $this->add("Cc", $this->addr(func_get_args()));
    }



    public function bcc() {
        $this->add("Bcc", $this->addr(func_get_args()));
    }



    public function from() {
        $this->add("From", $this->addr(func_get_args()));
    }



    public function reply() {
        $this->add("Reply-To", $this->addr(func_get_args()));
    }



    public function subject($subject) {
        $this->subject = $this->b($subject);
    }



    public function body($body) {
        $this->body = $this->encode($body);
    }



    public function attach($data, $name, $type = "application/octet-stream") {
        $this->attachments[] = array(
            "data" => $this->encode($data),
            "name" => $this->b($name),
            "type" => $type
        );
    }




    public function html() {
        $this->type = "html";
    }



    public function plain() {
        $this->type = "plain";
    }



    /**
     * "text" or "html"
     * @param string $type
     */
    public function type($type) {
        $this->type = $type;
    }




    public function send() {
        if (!empty($this->attachments)) {
            $boundary = uniqid();
            $this->headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"";
            $this->body = "--{$boundary}\r\n" .
                          "Content-Type: text/{$this->type}; charset=\"{$this->charset}\"\r\n" .
                          "Content-Transfer-Encoding: base64\r\n\r\n{$this->body}";
            foreach ($this->attachments as $attachment) {
                $this->body .= "\r\n--{$boundary}\r\nContent-Type: {$attachment["type"]}; name=\"{$attachment["name"]}\"\r\n" .
                               "Content-Transfer-Encoding: base64\r\n" .
                               "Content-disposition: attachment; file=\"{$attachment["name"]}\"\r\n\r\n{$attachment["data"]}";
            }
            $this->body .= "--{$boundary}--";
        }
        else {
            $this->headers .= "Content-Type: text/{$this->type}; charset=\"{$this->charset}\"\r\n" .
                              "Content-Transfer-Encoding: base64";
        }
        #header('Content-Type: text/html;charset=utf-8');
        #print base64_decode($this->body);die;
        return mail($this->to, $this->subject, $this->body, $this->headers);
    }



}



/**
 * Примеры:
 *
 * // письмо с аттачем
 * $mail = new Mail;
 * $mail->charset("windows-1251"); // по умолчанию utf-8
 * $mail->to("recipient@example.com", "Имя получателя"); // имя можно опустить
 * $mail->subject("Заголовок");
 * $mail->body("Сообщение");
 * $filename = "path/to/file.rar";
 * $mail->attach(file_get_contents($filename), basename($filename), "application/x-rar-compressed");
 * $mail->attach("пусто", "Текстовый документ.txt", "text/plain"); // с распознанием русских символов в имени приложения проблем не возникнет
 * $mail->send();
 *
 * // несколько адресатов
 * $mail = new Mail;
 * $mail->to(array("recipient1@example.com" => "Имя первого получателя", "recipient2@example.com" => "Имя второго получателя"));
 * $mail->subject("Заголовок");
 * $mail->body("Сообщение");
 * $mail->send();
 *
 * // рассылаем копии письма
 * $mail = new Mail;
 * $mail->subject("Заголовок");
 * $mail->body("Сообщение");
 * $emails = array("recipient1@example.com", "recipient2@example.com");
 * foreach ($emails as $email) {
 *     $copy = copy $mail;
 *     $copy->to($email, "Лично");
 *     $copy->send();
 * }
 *
 * Поля From, Reply-To, Cc и Bcc можно задать с помощью одноименных методов
 * (в случае с Reply-To - reply) аналогичных по синтаксису методу to, существует
 * возможность добавления произвольного заголовков с помощью метода add.
 *
 */