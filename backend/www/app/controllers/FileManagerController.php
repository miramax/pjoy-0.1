<?php

class FileManagerController extends BackendController {

  public function indexAction() {
    $files = new File();
    $pagination['view'] = '';
    if(!isset($_GET['filter'])) {

      $pagination = self::Component('Pagination',
                      array('count'=>$files->count(),
                            'onPage'=>5,
                            'link'=>'/auth/filemanager/?page=')
                      );

      $files->limit($pagination['offset'], $pagination['limit']);
    }
    
    $files->order('id', ':desc');
    $files->find(':all');
    $files = $files->result();

    # files filterting
    $c = array('Yellow', 'Grey', 'Grey', 'Grey');
    if (isset($_GET['filter'])) {
      $nFiles = array();
      if ($_GET['filter'] == 'img') {
        foreach ($files as $file) {
          if ($this->isImage($file['type'])) {
            $nFiles[] = $file;
          }
        }
        $c = array('Grey', 'Yellow', 'Grey', 'Grey');
      } elseif($_GET['filter'] == 'doc') {
        foreach ($files as $file) {
          if ($this->isFile($file['type'])) {
            $nFiles[] = $file;
          }
        }
        $c = array('Grey', 'Grey', 'Yellow', 'Grey');
      } else {
        foreach ($files as $file) {
          if ($this->isArchive($file['type'])) {
            $nFiles[] = $file;
          }
        }
        $c = array('Grey', 'Grey', 'Grey', 'Yellow');
      }
      $files = $nFiles;
    }

    $data['files'] = $files;
    $data['buttons'] = $this->buttons(array(
        'Все файлы::/filemanager/::' . $c[0],
        'Изображения::/filemanager/?filter=img::' . $c[1],
        'Документы::/filemanager/?filter=doc::' . $c[2],
        'Архивы::/filemanager/?filter=arch::' . $c[3],
        'Редактор изображений::/filemanager/selectImage/::Blue'
            ));
    $data['pagination'] = $pagination['view'];
    $this->display('index', $data);
  }

  public function uploadAction() {
    if (!isset($_FILES['file']['name'])) {
      return;
    }
    $file = PjoyFile::init()
            ->get('file')
            ->put(UPLOADS_FOLDER);


    if ($this->isImage($file['filetype'])) {


      # web-formating images
      PjoyImage::init()
              ->get($file['filepath'])
              ->put(UPLOADS_FOLDER)
              ->create();

      $image = PjoyImage::init()
              ->get($file['filepath'])
              ->put(UPLOADS_FOLDER . 'thumbnails/')
              ->crop()
              ->create();

      $image = PjoyImage::init()
              ->get(DOCUMENT_ROOT . $image)
              ->put(UPLOADS_FOLDER . 'thumbnails/')
              ->size(100, 100)
              ->create();
    }

    $errors = PjoyFile::errors();

    if (!$errors) {
      $newfile = new File();
      $newfile->path = str_replace(DOCUMENT_ROOT, '', $file['filepath']);
      $newfile->name = $file['filename'];
      $newfile->type = $file['filetype'];
      $newfile->size = ceil($file['filesize'] / 1000);
      $newfile->insert();
    }

    $data['file'] = $file;
    $data['errors'] = $errors;
    $this->display('lastfile', $data);
  }

  public function deleteAction() {

    $file = new File();
    $file->name = $_POST['file'];
    $file->find(':one');
    $row = $file->result();

    @unlink(DOCUMENT_ROOT . $row['path']);
    if ($this->isImage($row['type'])) {
      @unlink(UPLOADS_FOLDER . 'thumbnails/' . $row['name']);
    }

    $file->id = $row['id'];
    $file->delete();
  }

  public function selectImageAction() {
    $files = new File();
    $files->order('id', ':desc');
    $files->find(':all');
    $files = $files->result();

    $images = array();
    foreach ($files as $file) {
      if ($this->isImage($file['type'])) {
        $images[] = $file;
      }
    }
    $data['buttons'] = $this->buttons(array(
        'Файловый менеджер::/filemanager/::Grey',
            ));
    $data['images'] = $images;
    $this->display('selectImage', $data);
  }

  public function editorAction($id) {

    $error = isset($_GET['warning']) ? true : false;
    $success = isset($_GET['success']) ? true : false;

    if($success)
      $b = array('color' => 'Blue', 'name' => 'Готово');
    else
      $b = array('color' => 'Red', 'name' => 'Отмена');

    $file = new File();
    $file->id = $id;
    $file->find(':one');
    $file = $file->result();

    $data['buttons'] = $this->buttons(array(
        $b['name'].'::/filemanager/selectImage/::'.$b['color'],
            ));
    $data['image'] = $file;
    $data['error'] = $error;
    $data['success'] = $success;
    $this->display('editor', $data);
  }

  public function cropAction() {
    if (!empty($_POST['width']) && !empty($_POST['height'])) {
      $image = PjoyImage::init()
              ->get(UPLOADS_FOLDER . $_POST['file'])
              ->put(UPLOADS_FOLDER)
              ->imageCrop($_POST['width'], $_POST['height'], $_POST['x'], $_POST['y']);
      $this->redirect('/auth/filemanager/editor/' . $_POST['file_id'] . '/?success=1');
    } else {
      $this->redirect('/auth/filemanager/editor/' . $_POST['file_id'] . '/?warning=1');
    }
  }

}