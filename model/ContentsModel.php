<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/model/Model.php';

class ContentsModel extends  Model
{
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

    public function getAllContentOnType($contentType)
    {

        $query = 'select * from content where type = ?';
        $result = $this->pdo->prepare($query);
        $result->execute([$contentType]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }
}