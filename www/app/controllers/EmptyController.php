<?php


class EmptyController extends Controllers {


  public function main() {
    $data['content'] = $this->content;
    echo $this->render( '../layouts/empty', $data);
  }
  

}
