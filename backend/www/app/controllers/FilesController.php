<?php

class FilesController extends EmptyController {

  public function fileAction() {

    $file = PjoyFile::init()
            ->get('file')
            ->put(UPLOADS_FOLDER);
    $array = array(
        'filelink' => str_replace(DOCUMENT_ROOT, '', $file['filepath']),
        'filename' => $file['filename']
    );

    if (preg_match('/(jpeg|jpg|png|gif)/i', $file['filetype'])) {
      # web-formating images
      PjoyImage::init()
              ->get($file['filepath'])
              ->put(UPLOADS_FOLDER)
              ->create();
    }

    $newfile = new File();
    $newfile->path = $array['filelink'];
    $newfile->name = $array['filename'];
    $newfile->type = $file['filetype'];
    $newfile->size = ceil($file['filesize'] / 1000);
    $newfile->insert();

    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($array);
  }

  public function imageAction() {

    $file = PjoyFile::init()
            ->get('file')
            ->put(UPLOADS_FOLDER);

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

    $array = array(
        'filelink' => str_replace(DOCUMENT_ROOT, '', $file['filepath'])
    );

    $newfile = new File();
    $newfile->path = $array['filelink'];
    $newfile->name = $file['filename'];
    $newfile->type = $file['filetype'];
    $newfile->size = ceil($file['filesize'] / 1000);
    $newfile->insert();


    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($array);
  }

}