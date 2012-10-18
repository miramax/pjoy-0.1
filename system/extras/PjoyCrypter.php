<?php
/**
* Pjoy Framework v0.8
* An open source application development framework for PHP 5.3 or newer
* @copyright	CopyLeft (cl) - 2012, no license
* @license no license, Public Domain
*/



class PjoyCrypter {


  /**
   *
   * @var string
   */
  private $key;

  /**
   *
   * @var int
   */
  private $alg;


  /**
   *
   * @param string $key
   * @param constant $algorithm
   */
  public function __construct( $key, $algorithm = MCRYPT_RC2 ) {
    $this->key = substr( $key, 0, mcrypt_get_key_size( $algorithm, MCRYPT_MODE_ECB ) );
    $this->alg = $algorithm;
  }


  /**
   * Encrypt input data
   * @param string $data
   * @return string
   */
  public function Encryption( $data ) {
    $size = mcrypt_get_iv_size( $this->alg, MCRYPT_MODE_ECB );
    $iv = mcrypt_create_iv( $size, MCRYPT_RAND );

    $result = mcrypt_encrypt($this->alg, $this->key, $data, MCRYPT_MODE_ECB, $iv);

    return trim( base64_encode($result) );
  }


  /**
   * Decript input data
   * @param string $data
   * @return string
   */
  public function Decription( $data ) {
    $data = base64_decode( $data );
    $size = mcrypt_get_iv_size( $this->alg, MCRYPT_MODE_ECB );
    $iv = mcrypt_create_iv( $size, MCRYPT_RAND );

    $result = mcrypt_decrypt( $this->alg, $this->key, $data, MCRYPT_MODE_ECB, $iv );

    return trim( $result );
  }



}