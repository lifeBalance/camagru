<?php

class Comment extends Model
{
    public          $errors = [];

    public function __construct() {}

    /**
     * Create a new comment in the 'comments' table:
     *
     * @param data  The data from the "register" form. 
     *
     * @return ???
     */
    public function new($data)
    {
        $db = static::getDB();

        $sql = 'INSERT INTO comments (user_id, pic_id, created_at, comment)
                VALUES (:user_id, :pic_id, :created_at, :comment)';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':pic_id', $data['pic_id'], PDO::PARAM_INT);
        $stmt->bindValue(':created_at', $data['created_at'], PDO::PARAM_STR);
        $stmt->bindValue(':comment', $data['comment'], PDO::PARAM_STR);
        $stmt->execute();
    }
}