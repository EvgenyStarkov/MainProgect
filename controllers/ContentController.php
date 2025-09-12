<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/ContentModel.php';

class ContentController extends Controller
{
    public function index($path, $params)
    {
        $data = $this->getData($params);
        $this->addToDB($params, $data);
        $data = $this->getData($params);
        $this->render($path, $data);
    }

    public function getData($params)
    {

        $m = new  \ContentModel;

        $user = [
            'id' => 10,
            'role' => 'guest'
        ];

        if (isset($_SESSION['userId'])) {
            $user = $m->getUser($_SESSION['userId']);
        }

        if (isset($params['id'])) { // Изменена проверка на отсутствие $params['id'], чтобы не вызывало ошибку при неверном переходе

            $video = $m->getContent($params['id']);
            $contentType = $video['type'];

            $pageTitle = 'MEGAFILMS || ' . mb_strtoupper($video['title']);

            if ($contentType == 'музыкальный клип') {
                $heroType = 'clip';
            } else {
                $heroType = 'film';
            }

            $userContentSubscriptions = $m->getUserContentSubscriptions($user['id'], $video['id']);
            $contentSubscriptions = $m->getContentSubscriptions($video['id']);

            $members = $m->getMembersByContent($video['id']);

            $recommendations = $m->getContentRecommendations($video['id']);

            $preComments = $m->getContentComments($video['id']);


            $comments = [];

            foreach ($preComments as $cm) {
                $commentsAuthor = $m->getUser($cm['user_id']);

                $comments[] = [
                    'commentsAuthor' => $commentsAuthor,
                    'content' => $cm['content']
                ];

            }

            $data = [
                'user' => $user,
                'contentType' => $contentType,
                'video' => $video,
                'pageTitle' => $pageTitle,
                'heroType' => $heroType,
                'userContentSubscriptions' => $userContentSubscriptions,
                'contentSubscriptions' => $contentSubscriptions,
                'members' => $members,
                'recommendations' => $recommendations,
                'comments' => $comments
            ];

            if ($contentType = 'сериал') {
                $episodes = $m->getContentEpisodes($video['id']);

                // Формируем сезоны с строковыми ключами
                $seasons = [];
                foreach ($episodes as $episode) {
                    $seasonKey = (string)$episode['season']; // Ключ как строка
                    if (!isset($seasons[$seasonKey])) {
                        $seasons[$seasonKey] = [];
                    }
                    $seasons[$seasonKey][] = $episode;

                    // Получаем первый эпизод
                    $firstEpisode = reset($episodes) ?: [
                        'video' => '',
                        'season' => '1', // Сезон как строка
                        'number' => 1,
                        'id' => 0
                    ];

                    $data['seasons'] = $seasons;
                    $data['episodes'] = $episodes;
                    $data['firstEpisode'] = $firstEpisode;

                }

            }

            return $data;

        } else return [];

    }

    public function addToDB($params, $data)
    {

        $m = new  \ContentModel;
        if (isset($params['comContent'])) {
            $m->addComment($data['user']['id'], $data['video']['id'], $params['comContent']);
        }

        $m->updateViews($data['video']['id']);

    }

}