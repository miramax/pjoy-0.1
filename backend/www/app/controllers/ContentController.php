<?php

class ContentController extends BackendController {

  public function indexAction() {

    $categoryes = new Category();
    $categoryes->find(':all');

    $content = new Content();
    $content->find(':all');

    $data['categoryes'] = $categoryes->result();
    $data['content'] = $content->result();

    $data['buttons'] =
            $this->buttons(
            array(
                'Подробный список::/content/fullinfo/::Grey',
                '+ Добавить страницу::/content/add/::Green',
            ));

    $this->display('index', $data);
  }


  public function fullinfoAction() {
    $categoryes = new Category();
    $categoryes->find(':all');

    $content = new Content();
    isset($_GET['order'])?$content->order($_GET['order']):$content->order('id', ':desc');

    $pagination = self::Component('Pagination',
                        array('count'=>$content->count(),
                              'onPage'=>10,
                              'link' => '/auth/content/fullinfo/?page='));

    $content->limit($pagination['offset'], $pagination['limit']);
    $content->find(':all');

    $data['categoryes'] = $categoryes->result();
    $data['content'] = $content->result();
    $data['users'] = $this->allUsers;
    $data['pagination'] = $pagination['view'];
    $data['link'] = $pagination['link'];
    $data['buttons'] =
            $this->buttons(
            array(
                'Обозреватель страниц::/content/::Grey',
                '+ Добавить страницу::/content/add/::Green',
            ));

    $this->display('fullinfo', $data);
  }


  public function addAction() {

    $content = new Content();
    $id = false;
    $category = new Category();
    $category->find(':all');

    foreach ($category->result() as $value) {
      $categoryes[] = $value['name'];
    }

    if ($this->isPost()) {
      if ($this->post('content') && $this->post('name')) {

        $category->name = $this->post('categoryId');
        $category->find(':one');
        $category = $category->result();
        $catId = $category['id'];

        $content->categoryId = $catId;
        $content->alias = $this->post('alias');
        $content->name = $this->post('name');
        $content->content = $this->post('content');
        $content->title = $this->post('title');
        $content->keywords = $this->post('keywords');
        $content->description = $this->post('description');
        $content->status = $this->post('status') == 'Отображать' ? 1 : 0;
        $content->isComment = $this->post('isComment') == 'Включено' ? 1 : 0;
        $content->createDate = time();
        $content->modifyDate = time();
        $content->createdBy = $this->userInfo['id'];
        $content->modifyBy = $this->userInfo['id'];
        $content->views = 0;
        $content->insert();
        $content->clearResult();
        $content->name = $this->post('name');
        $content->createDate = time();
        $content->modifyDate = time();
        $content->createdBy = $this->userInfo['id'];
        $content->find(':one');
        $last = $content->result();
        $id = $last['id'];
      }
    }

    $data['buttons'] = $this->buttons(array(
        'Отмена::/content/::red',
            ));
    $data['WebFormContentAdd'] = new WebFormContentAdd($categoryes, $id);
    $data['user'] = $this->userInfo;
    $this->display('add', $data);
  }

  public function editAction($id) {

    $content = new Content();
    $content->id = $id;
    $content->find(':one');
    $row = $content->result();


    $category = new Category();
    $category->find(':all');

    foreach ($category->result() as $value) {
      $row['categoryId'] = ($value['id'] == $row['categoryId'] ? $value['name'] : $row['categoryId']);
      $categoryes[] = $value['name'];
    }

    if ($this->isPost()) {
      if ($this->post('content') && $this->post('name')) {

        $alias = $this->post('alias');

        if(isset($_POST['mainPage'])) {
          # find content main page
          $content->alias = '/';
          $content->find(':one');
          $r = $content->result();
          #update content alias
          $content->id = $r['id'];
          $content->alias = '';
          $content->update();
          $content->clearResult();

          #update alias current page
          $alias = '/';
        }

        $category->name = $this->post('categoryId');
        $category->find(':one');
        $category = $category->result();
        $catId = $category['id'];

        $content->categoryId = $catId;
        $content->alias = $alias;
        $content->id = $_POST['id'];
        $content->name = $this->post('name');
        $content->content = $this->post('content');
        $content->title = $this->post('title');
        $content->keywords = $this->post('keywords');
        $content->description = $this->post('description');
        $content->status = $this->post('status') == 'Отображать' ? 1 : 0;
        $content->isComment = $this->post('isComment') == 'Включено' ? 1 : 0;
        $content->modifyDate = time();
        $content->modifyBy = $this->userInfo['id'];
        $content->update();
      }
    }
    $data['success'] = isset($_GET['success']) ? true : false;
    $data['WebFormContentEdit'] = new WebFormContentEdit($row, $categoryes);
    $data['WebFormContentDelete'] = new WebFormContentDelete($row['id']);
    $data['content'] = $row;
    $data['users'] = $this->allUsers;
    $data['buttons'] = $this->buttons(array(
        'Обозреватель страниц::/content/::Grey',
        '+ Добавить страницу::/content/add/::Green',
            ));

    $this->display('edit', $data);
  }

  
  public function deleteAction() {

    $content = new Content();
    $content->id = $_POST['id'];
    $content->delete();

    $this->redirect('/auth/content');
  }

}