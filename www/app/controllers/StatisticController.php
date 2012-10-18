<?php

class StatisticController extends EmptyController {

  function __construct() {
    // if not is ajaxHttp request
    // and not is the "post" http method:
    // shut down script
    if(!$this->isXHttp() || !$this->isPost()){
      exit;
    }
  }


  function updateAction(){
    $stat = new Statistic();

    // get data from request
    $stat->ua = $_POST['ua'];
    $stat->ip = $_POST['ip'];
    $stat->url = $_POST['url'];
    $stat->find(':one');
    $row = $stat->result();

    if(empty($row)) {
        $stat->os = $_POST['os'];
        $stat->language = $_POST['language'];
        $stat->date = time();
        $stat->ua = $_POST['ua'];
        $stat->ip = $_POST['ip'];
        $stat->url = $_POST['url'];
        $stat->referer = $_POST['referer'];

        // insert data
        $stat->insert();
        // clear db layer
        $stat->clearResult();
    } else {
      $stat->id = $row['id'];
      $stat->date = time();
      $stat->referer = $_POST['referer'];
      $stat->update();
      $stat->clearResult();
    }
  }


}