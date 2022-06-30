<?php

class Posts extends Controller
{
    public function __construct()
    {
        $this->usrModel = $this->load('User');
        $this->picModel = $this->load('Pic');
        $this->likeModel = $this->load('Like');
        $this->commentModel = $this->load('Comment');
    }

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
                // Write base image to a file with unique name and '.png' extension
                file_put_contents(UPLOADS_DIR . "/$name.png", file_get_contents($_POST['img']));

                // Merge the user's image with the stickers
                $this->mergeImgs(UPLOADS_DIR . "/$name.png", $_POST['stickers']);
                // Pic intel
                $data = [
                    'comment'       => filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                    'filename'      => $name,
                ];
                $this->write_post_data($data);

                Flash::addFlashes([
                    'pic uploaded!' => 'success'
                ]);
            }
        } else {
            Flash::addFlashes([
                'Select a sticker please!' => 'warning'
            ]);
            $this->render('posts/upload', $data);
        }
    }

    public function camera()
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

                // Merge the user's image with the stickers
                $this->mergeImgs(UPLOADS_DIR . "/$name.png", $_POST['stickers']);

                // Pic intel
                $data = [
                    'comment'       => filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                    'filename'      => $name,
                ];
                $this->write_post_data($data);

                Flash::addFlashes([
                    'pic uploaded!' => 'success'
                ]);
            }
        } else {
            Flash::addFlashes([
                'Select a sticker please!' => 'warning'
            ]);
            $this->render('posts/camera', $data);
        }
    }

    public function mergeImgs($user_file, $stickers)
    {
        // Turn the 'stickers' string (name and coordinates) into array.
        $stickers = json_decode($stickers);

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
        imagepng($canvas, $user_file);

        // Destroy canvas once the file is written
        imagedestroy($canvas);
    }

    private function write_post_data($data)
    {
        // Add more pic intel
        $data['user_id']    = $_SESSION['user_id'];
        $data['created_at'] = date("Y-m-d H:i:s");
        // Write intel to 'pics' table and push the 'pic_id' to the 'data' array
        $data['pic_id'] = $this->picModel->new($data);
        // Write intel to 'comments' table
        $this->commentModel->new($data);
    }

    public function get_extension($img)
    {
        $mime_type = image_type_to_mime_type(exif_imagetype($img));
        return substr($mime_type, strpos($mime_type, "/") + 1);
    }

    public function index()
    {
        // Get all pics
        $allPics = $this->picModel->getAll();
        $data = [
            'scripts' => [
                'main.js',
            ],
            'posts' => [],
        ];
        // Iterate over all pics
        foreach($allPics as $pic) {
            // Get author of pic
            $author = $this->usrModel->findById($pic['user_id']);
            // echo '<pre>';
            // var_dump($author);
            // echo '</pre>';
            // die();
            // Get all comments for each pic_id
            $comments = $this->commentModel->getPicComments($pic['id']);
            // Transform filenames into urls
            $url = URLROOT . "/uploads/{$pic['filename']}.png";
            // Get number of likes for each pic_id
            $likes = $this->likeModel->getPicLikes($pic['id']);
            // Get if a pic has been liked by the logged in user
            if (isset($_SESSION['user_id']))
                $liked = $this->likeModel->getPicLiked($_SESSION['user_id'], $pic['id']);
            else
                $liked = 0;
            $tmp = [
                'pic_id'        => $pic['id'],
                'user_id'       => $pic['user_id'],
                'author_nick'   => $author->username,
                'created_at'    => $pic['created_at'],
                'filename'      => $pic['filename'],
                'url'           => $url,
                'comments'      => $comments,
                'likes'         => $likes,  // used to render number of likes
                'liked'         => $liked,  // used to render the heart filled
            ];
            array_push($data['posts'], $tmp);
        }
        $this->render('posts/index', $data);
    }
    // Add function to like a post
    public function like()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like']))
        {
            $user_id = $_SESSION['user_id'];
            $pic_id = $_POST['like'];
            $this->likeModel->new($user_id, $pic_id);
            // Send mail if user has push notif. enabled
        }
    }
    // Add function to edit a post (comment mb?)
}