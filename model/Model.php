<?php

class Model
{

    // Подключение PDO
    public $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=mymainproject", 'root', 'root');
        } catch (PDOException $e) {
            echo 'Ошибка подключения' . $e->getMessage();
        };
    }

    // Запросы на получение данных

    public function getContentOnCollection($collectionId)
    {

        $query = 'SELECT c.* FROM content c
                  JOIN collections_content cc ON c.id = cc.content_id
                  WHERE cc.Colections_id = ? ;';
        $result = $this->pdo->prepare($query);
        $result->execute([$collectionId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getCollection($collectionId)
    {

        $query = 'select * from collections where id = ? LIMIT 1';
        $result = $this->pdo->prepare($query);
        $result->execute([$collectionId]);
        return $result->fetch(PDO::FETCH_ASSOC);

    }

    public function getUser($userId)
    {

        $query = "select * from users where id = ? limit 1";
        $result = $this->pdo->prepare($query);
        $result->execute([$userId]);
        return $result->fetch(PDO::FETCH_ASSOC);

    }

    public function getAllSubscriptions()
    {

        $query = 'SELECT * FROM subscriptions;';
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

}