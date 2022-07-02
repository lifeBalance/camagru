<?php

class Like extends Model
{
    public          $errors = [];

    public function __construct() {}

    public function new($user_id, $pic_id)
    {
        $db = static::getDB();

        $sql = 'INSERT INTO likes (user_id, pic_id)
                VALUES (:user_id, :pic_id)';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':pic_id', $pic_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getPicLikes($pic_id)
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM likes WHERE pic_id = :pic_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':pic_id', $pic_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function userLikedPic($user_id, $pic_id)
    {
        $db = static::getDB();
        $sql = 'SELECT id FROM likes
                WHERE pic_id = :pic_id AND user_id = :user_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':pic_id', $pic_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return ($stmt->fetchColumn());
    }

    public function toggle($user_id, $pic_id)
    {
        $liked = $this->userLikedPic($user_id, $pic_id);
        error_log($liked);
        if ($liked) {
            $this->delete($liked);
            return 'nope';
        } else {
            $this->new($user_id, $pic_id);
            return 'yep';
        }
    }

    public function delete($id)
    {
        $db = static::getDB();
        $sql = 'DELETE FROM likes WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}