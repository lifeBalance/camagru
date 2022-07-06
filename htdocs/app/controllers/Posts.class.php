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
            'title' => '<p>It seems you\'re <i class="fa-solid fa-camera-retro"></i>-shy &#128527;</p>',
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

                // Write base image to a file with unique name and '.png' extension
                file_put_contents(UPLOADS_DIR . "/$name.png", file_get_contents($_POST['img']));

                // Merge the user's image with the stickers
                Img::merge(UPLOADS_DIR . "/$name.png", $_POST['stickers']);

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
            'title' => '<p>Say &#129472;!</p>',
            'scripts' => [
                'main.js',
                'camera.js',
                'stickers.js',
                'dragQueen.js',
                'gallery_modal.js',
            ],
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['img']) {
                // Generate unique filename
                $name = uniqid();

                // Write decoded content to a file with unique name and '.png' extension
                file_put_contents(UPLOADS_DIR . "/$name.png", file_get_contents($_POST['img']));

                // Merge the user's image with the stickers
                Img::merge(UPLOADS_DIR . "/$name.png", $_POST['stickers']);

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
            $data['user_pics'] = $this->picModel->getAllFrom($_SESSION['user_id']);
            $this->render('posts/camera', $data);
        }
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

    public function index()
    {
        // Get all pics
        $allPics = $this->picModel->getAll();
        $data = [
            'scripts' => [
                'main.js',
                'comments_modal.js',
            ],
            'posts' => [],
        ];
        if (isset($_SESSION['user_id']))
        {
            array_push($data['scripts'], 'likes.js');
        }
        // Iterate over all pics
        foreach($allPics as $pic) {
            // Get all comments for each pic_id
            $comments = $this->commentModel->getPicComments($pic['id']);
            $allComments = [];
            foreach ($comments as $comment) {
                $comment_author = $this->usrModel->findById($comment['user_id']);
                $tmp = [
                    'author'        => $comment_author->username,
                    'date'          => Time::ago($comment['created_at']),
                    'profile_pic'   => Img::url_profile_pic($comment['user_id']),
                    'content'       => $comment['comment']
                ];
                array_push($allComments, $tmp);
            }
            // Transform filenames into urls
            $url_pic = URLROOT . "/uploads/{$pic['filename']}.png";
            // Get number of likes for each pic_id
            $likes = $this->likeModel->getPicLikes($pic['id']);
            // Get if a pic has been liked by the logged in user
            if (isset($_SESSION['user_id']))
                $liked = $this->likeModel->userLikedPic($_SESSION['user_id'], $pic['id']);
            else
                $liked = false;
            $tmp = [
                'url_pic'       => $url_pic,
                'pic_id'        => $pic['id'],
                'comments'      => $allComments,
                'likes'         => $likes,
                'user_liked'    => $liked,
            ];
            array_push($data['posts'], $tmp);
        }
        $this->render('posts/index', $data);
    }

    // Add function to toggle like in a post
    public function like()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if ($_POST['pic_id'] && $_SESSION['user_id']) {
                $data = [];
                $data['liked'] = $this->likeModel->toggle($_SESSION['user_id'], $_POST['pic_id']);
                // Send response to browser
                header('Content-type: application/json');
                echo json_encode($data);
                exit();
            }
        }
    }

    public function comment()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if ($_POST['comment'] && $_SESSION['user_id']) {
                $data = [
                    'comment'       =>  filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                    'user_id'       => $_SESSION['user_id'],
                    'pic_id'        => $_POST['pic_id'],
                    'created_at'    => date('Y-m-d H:i:s')
                ];
                $this->commentModel->new($data);

                // Get other intel to notify by email
                $post_owner_id = $this->picModel->getAuthorId($_POST['pic_id']);
                $post_owner = $this->usrModel->findById($post_owner_id);
                // Send mail to OP if she has push notif. enabled
                if ($post_owner->push_notif) {
                    Mail::notify([
                        'address'   =>  $post_owner->email,
                        'username'  =>  $_SESSION['username'],
                        'subject'   => 'Your post got a comment!'
                    ]);
                }
                // Add some intel to the data for the response
                $data['author'] = $this->usrModel->findById($_SESSION['user_id'])->username;
                $data['ago'] = Time::ago($data['created_at']);
                $data['profile_pic'] = Img::url_profile_pic($_SESSION['user_id']);
                // send response back
                header('Content-type: application/json');
                echo json_encode($data);
                exit();
            }
        }
    }
    // Add function to edit a post (comment mb?)
}