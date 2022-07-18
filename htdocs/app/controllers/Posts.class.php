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
        // Redirect to login page if user is not logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login/new');
            die();
        }
        $data = [
            'title' => '<p><i class="fa-solid fa-camera-retro"></i> shy, huh? &#128527;</p>',
            'scripts' => [
                'main.js',
                'upload.js',
                'stickers.js',
                'dragQueen.js',
                'gallery_modal.js',
            ],
        ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['img']) {
                $comment = filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                // Check in case someone tampered front-end validation
                if (strlen($comment) <= 0) {
                    Flash::addFlashes([
                        'Invalid post' => 'danger'
                    ]);
                    return;
                }
                // Truncate the comment if it's too long
                if (strlen($comment) > 255) {
                    $comment = substr($comment, 0, 255);
                }
                // Generate unique filename
                $name = uniqid();

                // Write base image to a file with unique name and '.png' extension
                file_put_contents(UPLOADS_DIR . "/$name.png", file_get_contents($_POST['img']));

                // Merge the user's image with the stickers
                Img::merge(UPLOADS_DIR . "/$name.png", json_decode($_POST['stickers']));

                // Pic intel
                $data = [
                    'comment'       => $comment,
                    'filename'      => $name,
                ];
                $this->write_post_data($data);

                Flash::addFlashes([
                    'Pic uploaded' => 'success'
                ]);
            }
        } else {
            Flash::addFlashes([
                'Select a sticker please' => 'warning'
            ]);
            // Send down the user's gallery
            if (isset($_SESSION['user_id'])) {
                $data['user_pics'] = $this->picModel->getAllFrom($_SESSION['user_id']);
                $this->render('posts/upload', $data);
            }
        }
    }

    public function camera()
    {
        // Redirect to login page if user is not logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login/new');
            die();
        }
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
                $comment = filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                // Check in case someone tampered front-end validation
                if (strlen($comment) <= 0) {
                    Flash::addFlashes([
                        'Invalid post' => 'danger'
                    ]);
                    return;
                }
                // Truncate the comment if it's too long
                if (strlen($comment) > 255) {
                    $comment = substr($comment, 0, 255);
                }
                // Generate unique filename
                $name = uniqid();

                // Write decoded content to a file with unique name and '.png' extension
                file_put_contents(UPLOADS_DIR . "/$name.png", file_get_contents($_POST['img']));

                // Merge the user's image with the stickers
                Img::merge(UPLOADS_DIR . "/$name.png", json_decode($_POST['stickers']));

                // Pic intel
                $data = [
                    'comment'       => $comment,
                    'filename'      => $name,
                    'stickers'      => $_POST['stickers']
                ];
                $this->write_post_data($data);
                // Send response back as JSON string (check everything is OK)
                // header('Content-type: application/json');
                // echo json_encode($data);
                // exit();
                Flash::addFlashes([
                    'Pic uploaded' => 'success'
                ]);
            }
        } else {
            Flash::addFlashes([
                'Select a sticker please' => 'warning'
            ]);
            // Send down the user's gallery
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

    public function page($args)
    {
        if (isset($args[0]) && is_numeric($args[0]))
        {
            $postsPerPage = 5;
            $page = (int)$args[0];
            $pages = ceil($this->picModel->getAmountPics() / $postsPerPage);
            if ($page > $pages || $page < 0)
            {
                Router::notfound(); // Render 404
            }
        }
        else
        {
            Router::notfound(); // Render 404
        }
        $data = [
            'scripts' => [
                'main.js',
                'comments_modal.js',
                'pagination.js',
            ],
            'page'  => $page,
            'pages'  => $pages,
            'posts' => [],
        ];
        if (isset($_SESSION['user_id']))
        {
            array_push($data['scripts'], 'likes.js');
        }
        // Get pics to fill page
        $pagePics = $this->picModel->getPage($page, $postsPerPage);
        // Iterate over all pics
        foreach($pagePics as $pic) {
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
                'comments_qty'  => count($allComments),
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
                // Truncate the comment to the maximum length the db takes
                $truncated = substr(filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS), 0, 255);
                // Exit if it's an empty comment (not to store empty comments in db)
                if (strlen($truncated) == 0)
                    return;
                $data = [
                    'comment'       =>  $truncated,
                    'user_id'       => $_SESSION['user_id'],
                    'pic_id'        => $_POST['pic_id'],
                    'created_at'    => date('Y-m-d H:i:s')
                ];
                $this->commentModel->new($data);

                // Get other intel to notify by email
                $post_owner_id = $this->picModel->getAuthorId($_POST['pic_id']);
                $post_owner = $this->usrModel->findById($post_owner_id);
                // Send mail to OP if she has push notif. enabled
                if ($post_owner->push_notif && $post_owner_id != $_SESSION['user_id']) {
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
                // Send response back as JSON string
                header('Content-type: application/json');
                echo json_encode($data);
                exit();
            }
        }
    }

    // Add function to delete a post (comment mb?)
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if ($this->picModel->getAuthorId($_POST['post_id']) == $_SESSION['user_id'])
            {
                // Delete pic, comments and likes
                $this->likeModel->deleteById($_POST['post_id']);
                $this->commentModel->deleteById($_POST['post_id']);
                // Delete the picture file before!!!
                $filename = $this->picModel->getFilenameById($_POST['post_id']);
                // Remove filename
                unlink(PUBLIC_DIR . "/uploads/$filename.png");
                // Remove db entry
                $this->picModel->deleteById($_POST['post_id']);

                // Send response back as JSON string
                if(!empty($_POST)) {
                    header('Content-type: application/json');
                    // error_log(json_encode($_POST));
                    echo json_encode($_POST);
                    exit();
                }
            }
        }
    }
}