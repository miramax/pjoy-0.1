<?php

class PaginationComponent extends Components {


  public function initialize($args=null)
  {

    $page = isset($_GET['page'])?$_GET['page']:1;

    $pagination = PjoyPagination::init()
                  ->quantity($args['count'])
                  ->listen($page)
                  ->onPage($args['onPage']);
    $pagination['link'] = $args['link'];

    return array(
        'offset' => $pagination['offset'],
        'limit' => $pagination['limit'],
        'view' => $this->display($pagination),
        'link' => $pagination['link'].$page,
    );

  }

}