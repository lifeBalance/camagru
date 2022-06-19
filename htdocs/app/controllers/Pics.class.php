<?php

class Pics extends Controller
{
    public function upload($params)
    {
        $data = [
            'title' => 'gallery'
        ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $uploads_dir = UPLOADS_DIR;
            if ($_FILES["dickpic"]["error"] == UPLOAD_ERR_OK) {
                // Get name of the temporary server-side stored file (/tmp)
                $tmp_name = $_FILES["dickpic"]["tmp_name"];
                // Generate unique filename (uniqid())
                $name = uniqid();
                // Get extension of the file
                $ext = $this->get_extension($tmp_name);
                // Validate file: type, size, etc
                move_uploaded_file($tmp_name, "$uploads_dir/$name.$ext");
            }   else {
                echo 'Error: ' . $_FILES["dickpic"]["error"];
            }
        } else {
            $this->render('pics/new', $data);
        }
    }

    public function get_extension($img)
    {
        $mime_type = image_type_to_mime_type(exif_imagetype($img));
        return substr($mime_type, strpos($mime_type, "/") + 1);
    }
}