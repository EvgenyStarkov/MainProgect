<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/model/Model.php';
class AccauntModel extends  Model
{
    public function getUserSubscriptionExpirationDate($userId, $subscriptionId)
    {
        $query = "SELECT valid_until FROM user_subscriptions 
              WHERE user_id = ? AND subscription_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId, $subscriptionId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['valid_until'] : null;
    }

    public function getAvailableSubscriptions($userId)
    {
        $query = "SELECT * FROM subscriptions WHERE id NOT IN (
                  SELECT subscription_id 
                  FROM user_subscriptions 
                  WHERE user_id = ?
              )";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}