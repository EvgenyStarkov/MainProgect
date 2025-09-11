<?php

namespace controllers;

class CollectionController extends Controller
{

    public  function index($path ,$params)
    {
        $data = $this->getData($params);
        $this->render($path,$data);
    }

    public function getData($params)
    {
        $m = new \Model;

        $user = [
            'id' => null,
            'role' => 'guest'
        ];

        if (isset($_SESSION['userId'])) {
            $user = $m->getUser($_SESSION['userId']);
        }

        $collection = $m->getCollection($params['id']);
        $content = $m->getContentOnCollection($params['id']);
        $pageTitle = $collection['title'];

        return [
            'user' => $user,
            'collection' => $collection,
            'pageTitle' => $pageTitle,
            'content' => $content
        ];

    }

}