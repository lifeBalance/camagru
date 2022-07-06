<?php

class Pic extends Model
{
    public          $errors = [];

    public function __construct() {}

    /**
     * Create a new pic in the 'pics' table:
     *      - Fill the 'errors' array in case no 'title' was supplied (in JS?).
     *
     * @param data  The data from the "register" form. 
     *
     * @return pic_id   Which is used at the controller to store the comment.
     */
    public function new($data)
    {
        $db = static::getDB();

        $sql = 'INSERT INTO pics (user_id, created_at, filename)
                VALUES (:user_id, :created_at, :filename)';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':created_at', $data['created_at'], PDO::PARAM_STR);
        $stmt->bindValue(':filename', $data['filename'], PDO::PARAM_STR);
        $stmt->execute();
        return $db->lastInsertId();   // Return the id of the pic
    }

    public function getAll()
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM pics ORDER BY created_at';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAuthorId($pic_id)
    {
        $db = static::getDB();
        $sql = 'SELECT user_id FROM pics
                WHERE id = :pic_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':pic_id', $pic_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getAllFrom($user_id)
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM pics
                WHERE user_id = :user_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById($id)
    {
        $db = static::getDB();
        $sql = 'DELETE FROM pics
                WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getFilenameById($post_id)
    {
        $db = static::getDB();
        $sql = 'SELECT filename FROM pics
                WHERE id = :post_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}