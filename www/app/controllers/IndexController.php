<?php


class IndexController extends ApplicationController {


  function indexAction() {
    $content = new Content();
    $content->alias = '/';
    $content->find(':one');
    $row = $content->result();

    # update views count
    #$content->id = $row['id'];
    #$content->views = $row['views']+1;
    #$content->update();

    $this->seo = $row;
    $data['content'] = $row;
    $this->display('page', $data);
  }


  function pageAction($page) {
    $content = new Content();

    if(!is_numeric($page))
      $content->alias = $page;
    else
      AppHelper::NotFound();

    $content->find(':one');
    $content = $content->result();

    if(!$content)
      AppHelper::NotFound();

    $data['content'] = $content;
    $this->display('page', $data);
  }


}