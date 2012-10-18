<?php

class CategoryController extends BackendController {

  function indexAction(){
    $category = new Category();

    $query1 = "
      SELECT category.id, category.name, COUNT(*) as `count`
      FROM #_category AS category
          JOIN #_content AS content
              ON category.id = content.categoryId
      GROUP BY category.name ORDER BY category.id
    ";

    $query2 = "
    SELECT category.id, category.name
      FROM #_category AS category
        WHERE category.id NOT IN
        (SELECT DISTINCT content.categoryId
            FROM #_content AS content)
    ";

    $category->query($query1);

    $row = $category->result();

    $category->query($query2);
    $empty = $category->result();

    $data['buttons'] = $this->buttons(
            array('+ Добавить::/category/add/::Green'));
    $data['category'] = $row;
    $data['empty'] = $empty;
    $this->display('index', $data);
  }


  function editAction($id) {

    if($this->isPost() && !empty($_POST['name'])){
      $this->update();
      $this->redirect('/auth/category/edit/'.$_POST['id']);
    }

    $category = new Category();
    $category->id = $id;
    $category->find(':one');
    $row = $category->result();

    $data['webformEdit'] = new WebFormCategoryEdit($row);

    $data['buttons'] = $this->buttons(
            array('Назад::/category/::Grey'));

    $this->display('edit', $data);
  }


  function addAction() {
    if($this->isPost() && !empty($_POST['name'])){
      $this->insert();
      $this->redirect('/auth/category/');
    }
    $data['webform'] = new WebFormCategoryAdd();

    $data['buttons'] = $this->buttons(
        array('Назад::/category/::Grey'));

    $this->display('add', $data);
  }


  function insert(){
    $category = new Category();
    $category->name = $_POST['name'];
    $category->insert();
    $category->clearResult();
  }


  function update(){

    $category = new Category();
    $category->id = $_POST['id'];
    $category->name = $_POST['name'];
    $category->update();
    $category->clearResult();

  }

  function deleteAction($id){
    $category = new Category();
    $category->id = $id;
    $category->delete();
    $category->clearResult();
    $this->redirect('/auth/category/');
  }

}