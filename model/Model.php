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

    public function getAllContent()
    {

        $query = 'select * from content';
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAllContentOnType($contentType)
    {

        $query = 'select * from content where type = ?';
        $result = $this->pdo->prepare($query);
        $result->execute([$contentType]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getContent($contentId)
    {

        $query = 'select * from content where id = ? LIMIT 1';
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId]);
        return $result->fetch(PDO::FETCH_ASSOC);

    }

    public function getContentOnCollection($collectionId)
    {

        $query = 'SELECT c.* FROM content c
                  JOIN collections_content cc ON c.id = cc.content_id
                  WHERE cc.Colections_id = ? ;';
        $result = $this->pdo->prepare($query);
        $result->execute([$collectionId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAllCollections()
    {

        $query = 'select * from collections';
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getCollection($collectionId)
    {

        $query = 'select * from collections where id = ? LIMIT 1';
        $result = $this->pdo->prepare($query);
        $result->execute([$collectionId]);
        return $result->fetch(PDO::FETCH_ASSOC);

    }

    public function getAllCollectionsOnType($contentType)
    {

        $query = "SELECT col.id, col.title FROM collections col
                  WHERE NOT EXISTS (
                  SELECT 1 FROM collections_content cc
                  JOIN content c ON cc.content_id = c.id
                  WHERE cc.Colections_id = col.id AND c.type <> ?
                  ) AND EXISTS (
                  SELECT 1 FROM collections_content cc
                  WHERE cc.Colections_id = col.id
);";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentType]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAllCountrysOnType($contentType)
    {

        $query = "SELECT DISTINCT country FROM content 
                  WHERE type = ? AND country IS NOT NULL AND country != '' 
                  ORDER BY country;";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentType]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAllYearsOnType($contentType)
    {

        $query = "SELECT DISTINCT year FROM content 
                  WHERE type = ? AND year IS NOT NULL AND year != '' 
                  ORDER BY year;";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentType]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAllGenresOnType($contentType)
    {
        $query = "SELECT DISTINCT genres_table.genre_value AS genre
              FROM content
              CROSS JOIN JSON_TABLE(
                genres,
                '$[*]' COLUMNS (genre_value VARCHAR(50) PATH '$')
              ) AS genres_table
              WHERE type = ? 
                AND genres IS NOT NULL 
                AND genres != 'null'
                AND genres != '[]'
                AND genre_value IS NOT NULL 
                AND genre_value != ''
              ORDER BY genre_value;";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentType]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
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

    public function getContentSubscriptions($contentId)
    {

        $query = "SELECT s.* FROM subscriptions s
                  JOIN subscription_content sc ON s.id = sc.subscription_id
                  WHERE sc.film_id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getUserSubscriptions($userId)
    {

        $query = "SELECT s.*, us.valid_until FROM subscriptions s
                  JOIN user_subscriptions us ON s.id = us.subscription_id
                  WHERE us.user_id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$userId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getUserContentSubscriptions($userId, $contentId)
    {

        $query = "SELECT s.*, us.valid_until FROM subscriptions s
                  JOIN subscription_content sc ON s.id = sc.subscription_id
                  JOIN user_subscriptions us ON s.id = us.subscription_id
                  WHERE us.user_id = ? AND sc.film_id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$userId, $contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getContentEpisodes($contentId)
{
    $query = "SELECT * FROM `episode` WHERE content_id = ?";
    $result = $this->pdo->prepare($query);
    $result->execute([$contentId]);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

    public function getMembersByContent($contentId)
    {
        $query = "SELECT * FROM `content_member` WHERE content_id = ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContentRecommendations($contentId)
    {
        $query = "SELECT c.* FROM content c
                  JOIN collections_content cc ON c.id = cc.content_id
                  WHERE cc.Colections_id IN (
                  SELECT Colections_id FROM collections_content WHERE content_id = ?
                  ) AND c.id != ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId,$contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContentComments($contentId)
    {
        $query = "SELECT * FROM `comments` WHERE content_id = ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSlides()
    {
        $query = "SELECT * FROM `hero_slide` ";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllHits()
    {
        $query = "SELECT * FROM `collections_advertising` ";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Функция для фильтрации

    public function getFilteredContent($genre, $country, $year, $orderBy, $contentType)
    {
        // Базовый запрос
        $query = "SELECT * FROM content WHERE type = :contentType";

        // Добавляем условия фильтрации
        $params = [':contentType' => $contentType];

        if ($genre !== null && $genre !== '') {
            // Используем JSON_SEARCH для поиска жанра в JSON-массиве
            $query .= " AND JSON_SEARCH(genres, 'one', :genre) IS NOT NULL";
            $params[':genre'] = $genre;
        }

        if ($country !== null && $country !== '') {
            $query .= " AND country = :country";
            $params[':country'] = $country;
        }

        if ($year !== null && $year !== '') {
            $query .= " AND year = :year";
            $params[':year'] = $year;
        }

        // Добавляем сортировку
        switch ($orderBy) {
            case 'year_desc':
                $query .= " ORDER BY year DESC";
                break;
            case 'views_desc':
                $query .= " ORDER BY views DESC";
                break;
            case 'rating_desc':
                $query .= " ORDER BY rating DESC";
                break;
            default:
                $query .= " ORDER BY id DESC"; // Сортировка по умолчанию
        }

        // Выполняем запрос
        $result = $this->pdo->prepare($query);
        $result->execute($params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

}