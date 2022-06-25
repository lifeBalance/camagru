<?php

class Pics extends Controller
{
    public function upload()
    {
        $data = [
            'title' => 'pic it, boi!',
            'scripts' => [
                'main.js',
                'upload.js',
                'stickers.js',
                'dragQueen.js',
            ],
        ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['img']) {

                // Generate unique filename
                $name = uniqid();

                // Write decoded content to a file with unique name and '.png' extension
                file_put_contents(UPLOADS_DIR . "/$name.png", file_get_contents($_POST['img']));
                Flash::addFlashes([
                    'pic uploaded!' => 'success'
                ]);
            }
            $this->redirect('/');
        } else {
            Flash::addFlashes([
                'Select a sticker please!' => 'warning'
            ]);
            $this->render('pics/upload', $data);
        }
    }

    public function camera($params)
    {
        $data = [
            'title' => 'pic it, boi!',
            'scripts' => [
                'main.js',
                'camera.js',
                'stickers.js',
                'dragQueen.js',
            ],
        ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['img']) {
                // Generate unique filename
                $name = uniqid();

                // Write decoded content to a file with unique name and '.png' extension
                file_put_contents(UPLOADS_DIR . "/$name.png", file_get_contents($_POST['img']));

                Flash::addFlashes([
                    'pic uploaded!' => 'success'
                ]);
                $this->redirect('/');
                // die();
            }
        } else {
            Flash::addFlashes([
                'Select a sticker please!' => 'warning'
            ]);
            $this->render('pics/camera', $data);
        }
    }

    public function get_extension($img)
    {
        $mime_type = image_type_to_mime_type(exif_imagetype($img));
        return substr($mime_type, strpos($mime_type, "/") + 1);
    }
}