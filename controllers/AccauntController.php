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
            'id' => 0,
            'role' => 'guest'
        ];

        if (isset($_SESSION['userId'])) {
            $user = $m->getUser($_SESSION['userId']);

            $preSubscriptions = $m->getUserSubscriptions($user['id']);
            $subscriptions = [];


            foreach ($preSubscriptions as $sb) {
                $date = $m->getUserSubscriptionExpirationDate($user['id'], $sb['id']);

                $subscriptions[] = [
                    'id' => $sb['id'],
                    'date' => $date,
                    'title' => $sb['title'],
                    'description' => $sb['description'],
                    'price' => $sb['price'],
                    'valid_until' => $sb['valid_until']
                ];

            }

            $suggestedSubscriptions = $m->getAvailableSubscriptions($user['id']);

            return [
                'user' => $user,
                'subscriptions' => $subscriptions,
                'suggestedSubscriptions' => $suggestedSubscriptions
            ];
        } else {
            return [
                'user' => $user
            ];
        }



    }

}