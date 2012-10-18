<?php

class CommentsController extends BackendController {

  function indexAction(){
    $comment = new Comment();
    $comment->find(':all');
    $rows = $comment->result();

    $content = new Content();
    $content->find(':all');
    $content=$content->result();

    $data['content'] = $content;
    $data['comments'] = $rows;
    $this->display('index', $data);
  }

  function editAction($id){
    $comment = new Comment();
    $comment->id = $id;
    $comment->find(':one');
    $row=$comment->result();

    $data['buttons'] = $this->buttons(array(
        'Назад::/comments/::Grey'
    ));

    $data['comment'] = $row;
    $this->display('edit', $data);
  }

  function statusOffAction($id){
    $comment = new Comment();
    $comment->id = $id;
    $comment->status = 0;
    $comment->update();
    $this->redirect('/auth/comments/edit/'.$id);
  }

  function statusOnAction($id){
    $comment = new Comment();
    $comment->id = $id;
    $comment->status = 1;
    $comment->update();
    $this->redirect('/auth/comments/edit/'.$id);
  }

  function deleteAction($id){
    $comment = new Comment();
    $comment->id = $id;
    $comment->delete();

    $this->redirect('/auth/comments/');
  }

}