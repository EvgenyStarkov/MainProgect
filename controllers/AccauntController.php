<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/AccauntModel.php';

class AccauntController extends Controller
{

    public function index($path, $params)
    {
        $data = $this->getData($params);
        $this->render($path, $data);
    }

    public function getData($params)
    {
        $m = new \AccauntModel;

        $user = [
            'id' => null,
            'role' => 'guest'
        ];

        if (isset($_SESSION['userId'])) {
            $user = $m->getUser($_SESSION['userId']);
        }

        $preSubscriptions = $m->getUserSubscriptions($user['id']);
        $subscriptions = [];



        foreach ($preSubscriptions as $sb) {
            $date = $m->getUserSubscriptionExpirationDate($user['id'], $sb['id']);

            $sb['date'] = $date;

        }

        $suggestedSubscriptions = $m->getAvailableSubscriptions($user['id']);

        return [
            'user' => $user,
            'subscriptions' => $subscriptions,
            'suggestedSubscriptions ' => $suggestedSubscriptions
        ];

    }

}