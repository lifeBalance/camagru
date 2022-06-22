<?php

class Pics extends Controller
{
    public function upload($params)
    {
        $data = [
            'title' => 'pic it, boi!',
            'scripts' => [
                'main.js',
                'upload.js',
                'stickers.js',
            ],
        ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_FILES["dickpic"]["error"] == UPLOAD_ERR_OK) {
                // Get name of the temporary server-side stored file (/tmp)
                $tmp_name = $_FILES["dickpic"]["tmp_name"];
                // Generate unique filename
                $name = uniqid();
                // Get extension of the file
                $ext = $this->get_extension($tmp_name);
                // Validate file: type, size, etc
                move_uploaded_file($tmp_name, UPLOADS_DIR . "/$name.$ext");
                Flash::addFlashes([
                    'pic uploaded!' => 'success'
                ]);
                $this->redirect('/');
            }   else {
                echo 'Error: ' . $_FILES["dickpic"]["error"];
            }
        } else {
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
            ],
        ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['img']) {
                // Generate unique filename
                $name = uniqid();

                // Write decoded content to a file with unique name and '.png' extension
                file_put_contents(UPLOADS_DIR . '/' . $name . '.png', file_get_contents($_POST['img']));

                Flash::addFlashes([
                    'pic uploaded!' => 'success'
                ]);
            }
            $this->redirect('/');
        } else {
            $this->render('pics/camera', $data);
        }
    }

    public function get_extension($img)
    {
        $mime_type = image_type_to_mime_type(exif_imagetype($img));
        return substr($mime_type, strpos($mime_type, "/") + 1);
    }
}