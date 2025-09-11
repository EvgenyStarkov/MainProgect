<?php

namespace controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
require_once  $_SERVER['DOCUMENT_ROOT'] . '/model/Model.php';


class HomeController extends controller
{
    public  function index($path ,$params)
    {
        $data = $this->getData();
        $this->render($path,$data);
    }

    public function getData()
    {
        $m = new \HomeModel;

        $user = [
            'id' => null,
            'role' => 'guest'
        ];

        if (isset($_SESSION['userId'])) {
            $user = $m->getUser($_SESSION['userId']);
        }

        $slides = $m->getAllSlides();

        $preHits = $m->getAllHits();
        $hits = [];
        foreach ($preHits as $h){
            $collection = $m->getCollection($h['collection_id']);
            $content = $m->getContentOnCollection($h['collection_id']);
            $hits[] = [
                'title' => $collection['title'],
                'text' => $h['text'],
                'background' => $h['background'],
                'mobile_background' => $h['mobile_background'],
                'content' => array_slice($content, 0, 7)
            ];

        }


        $preCollections = $m->getAllCollections();
        $collections = [];

        foreach ($preCollections as $c){
            $content =   $m->getContentOnCollection($c['id']);
            $collections[] = [
                'title' => $c['title'],
                'content' => array_slice($content, 0, 7)
            ];

        }


        return [
           'user' => $user,
            'slides' => array_slice($slides, 0, 7) ,
            'hits' => array_slice($hits,0, 3) ,
            'collections' => $collections
        ];

    }



}

 ?>

