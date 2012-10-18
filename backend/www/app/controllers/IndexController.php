<?php

class IndexController extends BackendController {

  function indexAction() {

    $data['user'] = $this->userInfo['name'];
    $this->display('index', $data);
    
  }

}