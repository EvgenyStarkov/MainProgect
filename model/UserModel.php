<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/model/Model.php';

class UserModel extends Model
{
    public function getUserForEmail($email, $password)
    {
        $query = "select * from users where email= ? && password = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$email, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUser()
    {
        $query = "select * from users";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $data)
    {
        $allowedFields = ['name', 'username', 'email', 'password', 'tel', 'avatar', 'сonsent_to_mailing'];
        $setParts = [];
        $params = [];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) { // учитываем null/пустые значения
                $value = $data[$field];

                if ($field === 'сonsent_to_mailing') {
                    $value = $value ? 1 : 0;
                }

                $setParts[] = "`$field` = ?";
                $params[] = $value;
            }
        }

        $setClause = implode(', ', $setParts);
        $query = "UPDATE `users` SET $setClause WHERE `id` = ?";
        $params[] = $userId; // id — последний параметр

        $stmt = $this->pdo->prepare($query);
        $ok = $stmt->execute($params); // передаём массив параметров

    }

    public function deleteUser($id)
    {
        $query = "delete from users where id = ?;              
                  delete from comments where user_id = ?                     
                  delete from user_subscription where user_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id, $id, $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);}

    public function addUser($name, $username, $email, $tel, $password, $consent){
        $query = "INSERT INTO `users` (`name`,`username`, `email`, `password`, `tel`,  `сonsent_to_mailing`) VALUES (?,?,?,?,?,?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$name, $username, $email, $password, $tel, $consent]);
        return $stmt->fetch(PDO::FETCH_ASSOC);}

    public function getSubscriptionById($subscriptionId)
    {
        $query = "SELECT * FROM subscriptions WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$subscriptionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserSubscriptionExpirationDate($userId, $subscriptionId)
    {
        $query = "SELECT valid_until FROM user_subscriptions 
              WHERE user_id = ? AND subscription_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId, $subscriptionId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['valid_until'] : null;
    }

    public function buySubscription($userId, $subId, $date)
    {
        $query = "insert into user_subscriptions(user_id,subscription_id,valid_until) values(?, ?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId, $subId, $date]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUserSubscriptionDate($userId, $subscriptionId, $newDate)
    {
        $query = "UPDATE user_subscriptions 
              SET valid_until = ?
              WHERE user_id = ? AND subscription_id = ?";

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$newDate, $userId, $subscriptionId]);
    }

    public function updateUserCash($userId, $amount)
    {
        $query = "UPDATE users 
              SET cash = cash + ?
              WHERE id = ?";

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$amount, $userId]);
    }

    public function getDateAfter31Days()
    {
        $currentDate = new DateTime();

        $currentDate->add(new DateInterval('P31D'));

        return $currentDate->format('Y-m-d');
    }

    public function removeUserSubscription($userId, $subscriptionId)
    {
        $query = "DELETE FROM user_subscriptions 
              WHERE user_id = ? AND subscription_id = ?";

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$userId, $subscriptionId]);
    }

}