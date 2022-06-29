<?php

class Like extends Model
{
    public          $errors = [];

    public function __construct() {}

    public function new($user_id, $pic_id)
    {
        $db = static::getDB();

        $sql = 'INSERT INTO likes
                WHERE user_id = :user_id AND pic_id = :pic_id';
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

    public function getPicLiked($user_id, $pic_id)
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM likes
                WHERE pic_id = :pic_id AND user_id = :user_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':pic_id', $pic_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}