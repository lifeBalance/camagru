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
        $sql = 'SELECT * FROM likes
                WHERE pic_id = :pic_id AND user_id = :user_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':pic_id', $pic_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }

    public function toggle($user_id, $pic_id)
    {
        if ($this->userLikedPic($user_id, $pic_id)) {
            $this->delete($user_id, $pic_id);
            return false;
        } else {
            $this->new($user_id, $pic_id);
            return true;
        }
    }

    public function delete($user_id, $pic_id)
    {
        $db = static::getDB();
        $sql = 'DELETE FROM likes
                WHERE user_id = :user_id AND pic_id = :pic_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':pic_id', $pic_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteById($post_id)
    {
        $db = static::getDB();
        $sql = 'DELETE FROM likes
                WHERE pic_id = :post_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}