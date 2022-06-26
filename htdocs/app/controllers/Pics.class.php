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
            if (isset($_POST['img'])) {
                // Generate unique filename
                $name = uniqid();
                // Write decoded content to a file with unique name and '.png' extension
                file_put_contents(UPLOADS_DIR . "/$name.png", file_get_contents($_POST['img']));
                $stickers = json_decode($_POST['stickers']);
                error_log(print_r($stickers));
                $this->mergeImgs(UPLOADS_DIR . "/$name.png", $stickers);

                Flash::addFlashes([
                    'pic uploaded!' => 'success'
                ]);
            }
        } else {
            Flash::addFlashes([
                'Select a sticker please!' => 'warning'
            ]);
            $this->render('pics/upload', $data);
        }
    }

    public function mergeImgs($user_file, $stickers)
    {
        // Create  GdImage instance out of the user's image
        $usr_gdi = imagecreatefrompng($user_file);
        // Compute dimensions for the user's image
        $usr_width = imagesx($usr_gdi);
        $usr_height = imagesy($usr_gdi);

        // Create a black canvas with the specified size. 
        $canvas = imagecreatetruecolor($usr_width, $usr_height);
        // Save the transparency information in the black canvas
        imagesavealpha($canvas, true);
        // Create a fully transparent background
        $alpha = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        // Fill the canvas with a transparent background
        imagefill($canvas, 0, 0, $alpha);

        // Copy the user's GdI object on the canvas
        imagecopy($canvas, $usr_gdi, 0, 0, 0, 0, $usr_width, $usr_height);

        // Copy the stickers to the canvas
        foreach ($stickers as $sticker) {
            $name   = $sticker[0];
            $dst_x  = preg_replace("/[^0-9]/", '', $sticker[1]);
            $dst_y  = preg_replace("/[^0-9]/", '', $sticker[2]);

            // Load sticker file
            $stickers_folder = PUBLIC_DIR . '/assets/stickers';
            $sticker_file = $stickers_folder . "/$name.png";

            // Create sticker's GdImage instance
            $sticker_gdi = imagecreatefrompng($sticker_file);

            // Copy the sticker to the canvas
            imagecopy(
                $canvas,
                $sticker_gdi,
                $dst_x,     // X coord. where we want to start pasting
                $dst_y,     // Y coord. where we want to start pasting
                0,          // STARTING x coord. of sticker
                0,          // STARTING y coord. of sticker
                128,        // Width of the sticker
                128         // Height of the sticker
            );
        }
        // Store the result as a file in the uploads folder
        imagepng($canvas, UPLOADS_DIR . "/test.png");

        // Destroy image once the file is written
        imagedestroy($canvas);
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