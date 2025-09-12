<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/model/Model.php';

class HomeModel extends Model

{

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

    public function getAllCollections()
    {

        $query = 'select * from collections';
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

}