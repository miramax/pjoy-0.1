<?php


class AjaxController extends EmptyController {

  function __construct() {
    if(!$this->isXHttp()){
      exit;
    }
    if($this->isPost()) {
      sleep(1);
    }
  }

  function FormFinanseAction() {

    $error = false;
    if($this->isPost()) {
      $code = $this->post('code');
      if( !empty($code) && $code !== $_SESSION['secretCode']) {
        $error = 'Неправильный код с картинки';
      }
    }

    $form = new WebFormFinanseOrder($error);

    $file = PjoyFile::init()
            ->get('attach')
            ->put(UPLOADS_FOLDER);

    $data['form'] = $form;
    $data['header'] = 'Заказ финансового анализа';
    $this->display('formhead', $data);
  }



  function FormCallbackAction() {

    $error = false;
    if($this->isPost()) {
      $code = $this->post('code');
      if( !empty($code) && $code !== $_SESSION['secretCode']) {
        $error = 'Неправильный код с картинки';
      }
    }

    $form = new WebFormCallbackOrder($error);

    $data['form'] = $form;
    $data['header'] = 'Заказ обратного звонка';
    $this->display('formhead', $data);
  }



  function FormAdminAction() {

    $error = false;
    if($this->isPost()) {
      $code = $this->post('code');
      if( !empty($code) && $code !== $_SESSION['secretCode']) {
        $error = 'Неправильный код с картинки';
      }
    }

    $form = new WebFormAdminOrder($error);

    $data['form'] = $form;
    $data['header'] = 'Связь с администрацией';
    $this->display('formhead', $data);
  }



}