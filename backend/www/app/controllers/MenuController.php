<?php

class MenuController extends BackendController {

  public function indexAction(){
    $menu = new Menu();

    $menu->level = 1;
    $menu->find(':all');
    $first = $menu->result();

    $menu->level = 2;
    $menu->find(':all');
    $second = $menu->result();

    $data['first_level'] = $first;
    $data['second_level'] = $second;
    $data['buttons'] = $this->buttons(
            array(
                '+ Добавить::/menu/add/::Green'
            ));
    $this->display('index', $data);
  }


  public function addAction(){

    if($this->isPost() && !empty($_POST['name']) && !empty($_POST['url'])){
      $this->insertAction();
      $this->redirect('/auth/menu/?success=1');
    }

    $menu = new Menu();
    $menu->level = 1;
    $menu->find(':all');
    $row2 = $menu->result();
    $parents = array();
    $parents[0] = '--- отсутствует ---';

    foreach($row2 as $r) {
      $parents[$r['id']]=$r['name'];
    }

    $data['webform'] = new WebFormMenuAdd($parents);
    $data['buttons'] = $this->buttons(array(
        'Назад::/menu/::Grey'
    ));
    $this->display('add', $data);
  }


  public function editAction($id) {

    if($this->isPost() && !empty($_POST['name']) && !empty($_POST['url'])){
      $this->updateAction();
      $this->redirect('/auth/menu/edit/'.$_POST['id']);
    }

    $menu = new Menu();
    $menu->id = $id;
    $menu->find(':one');
    $row = $menu->result();

    $menu->level = 1;
    $menu->find(':all');
    $row2 = $menu->result();
    $parents = array();
    $parents[0] = '--- отсутствует ---';
    foreach($row2 as $r) {
      $parents[$r['id']]=$r['name'];
    }

    $data['menu'] = $row;
    $data['webformEdit'] = new WebFormEditMenu($parents, $row);
    $data['webformDelete'] = new WebFormDeleteMenu($row['id']);

    $data['buttons'] = $this->buttons(array(
        'Назад::/menu/::Grey'
    ));

    $this->display('edit', $data);
  }

  public function insertAction() {
    if($this->isPost()){
      $menu = new Menu();
      # find parent
      $menu->name = $_POST['parent'];
      $menu->find(':one');
      $parent = $menu->result();
      if(empty($parent)){
        $parent['id'] = 0;
      }
      $menu->name = $_POST['name'];
      $menu->url = $_POST['url'];
      $menu->parentId = $parent['id'];
      $menu->level = $parent['id'] == 0?1:2;
      $menu->insert();
    }
  }


  public function updateAction() {
    if($this->isPost()){
      $menu = new Menu();
      # find parent
      $menu->name = $_POST['parent'];
      $menu->find(':one');
      $parent = $menu->result();
      if(empty($parent)){
        $parent['id'] = 0;
        $menu->level = 1;
      }else{
        $menu->level = 2;
      }


      # update menu row
      $menu->id = $_POST['id'];
      $menu->name = $_POST['name'];
      $menu->url = $_POST['url'];
      $menu->parentId = $parent['id'];
      $menu->update();
      $menu->clearResult();
    }
  }


  public function deleteAction() {
    if($this->isPost()){
      $menu = new Menu();
      $menu->id = $_POST['id'];
      $menu->delete();
      $this->redirect('/auth/menu/');
    }
  }

}