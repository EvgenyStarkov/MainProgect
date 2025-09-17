<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/ContentsModel.php';

class ContentsController extends Controller
{
    public function index($path, $params)
    {
        $data = $this->getData($params);
        $this->render($path, $data);
    }

    public function getData($params)
    {

        $m = new  \ContentsModel;

        $user = [
            'id' => 0,
            'role' => 'guest'
        ];

        if (isset($_SESSION['userId'])) {
            $user = $m->getUser($_SESSION['userId']);
        }

        if (isset($params['type'])) { // Изменена проверка на отсутствие $params['type'], чтобы не вызывало ошибку при неверном переходе

            $contentType = $params['type'];

            switch ($contentType) {
                case 'фильм':
                    $pageTitle = 'ФИЛЬМЫ';
                    break;
                case 'сериал':
                    $pageTitle = 'СЕРИАЛЫ';
                    break;
                case 'спортивное событие':
                    $pageTitle = 'СПОРТ';
                    break;
                case "музыкальный клип":
                    $pageTitle = 'КЛИПЫ';
                    break;
                default:
                    $pageTitle = 'НЕИЗВЕСТНЫЙ РАЗДЕЛ';
            }

            $genres = $m->getAllGenresOnType($contentType);
            $years = $m->getAllYearsOnType($contentType);
            $countries = $m->getAllCountrysOnType($contentType);

            $content = $m->getAllContentOnType($contentType);
            $allVideo = $m->getAllContentOnType($contentType);

            $preCollections = $m->getAllCollectionsOnType($contentType);
            $collections = [];

            foreach ($preCollections as $c) {
                $collectionsContent = $m->getContentOnCollection($c['id']);
                $collections[] = [
                    'title' => $c['title'],
                    'content' => array_slice($collectionsContent, 0, 7)
                ];

            }

            switch ($params['type']) {
                case 'фильм':
                    $allContentName = 'Все фильмы';
                    break;
                case 'сериал':
                    $allContentName = 'Все сериалы';
                    break;
                case 'спортивное событие':
                    $allContentName = 'Все спортивные события';
                    break;
                case "музыкальный клип":
                    $allContentName = 'Все клипы';
                    break;
                default:
                    $allContentName = 'Неопределенный контент';
            }

            $data = [
                'user' => $user,
                'contentType' => $contentType,
                'pageTitle' => $pageTitle,
                'genres' => $genres,
                'years' => $years,
                'countries' => $countries,
                'content' => $content,
                'allVideo' => $allVideo,
                'collections' => $collections,
                'allContentName' => $allContentName
            ];

            if (isset($params['genre']) || isset($params['country']) || isset($params['year'])) {
                $genre = empty($params['genre']) ? null : $params['genre'];
                $country = empty($params['country']) ? null : $params['country'];
                $year = empty($params['year']) ? null : (int)$params['year'];
                $orderBy = empty($params['orderBy']) ? 'year_desc' : $params['orderBy'];
                $filtered = $m->getFilteredContent($genre, $country, $year, $orderBy, $contentType);
                $filteredTitle = '';

                $filteredPost = [$genre, $year, $country];

                foreach ($filteredPost as $fp) {
                    if ($fp != null) {
                        if ($filteredTitle == '') {
                            $filteredTitle = $filteredTitle . $fp;
                        } else {
                            $filteredTitle = $filteredTitle . ' , ' . $fp;
                        }
                    }
                }

                if ($filtered == $content) {
                    $filteredTitle = $allContentName;
                }

                $data['filtered'] = $filtered;
                $data['genre'] = $genre;
                $data['year'] = $year;
                $data['country'] = $country;
                $data['orderBy'] = $orderBy;
                $data['filteredTitle'] = $filteredTitle;

            }

            return $data;
        } else {
            return [] ;
        }


    }
}