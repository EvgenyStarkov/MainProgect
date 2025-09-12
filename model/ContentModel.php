<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/model/Model.php';

class ContentModel extends Model
{
    public function getContentSubscriptions($contentId)
    {

        $query = "SELECT s.* FROM subscriptions s
                  JOIN subscription_content sc ON s.id = sc.subscription_id
                  WHERE sc.film_id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getUserContentSubscriptions($userId, $contentId)
    {

        $query = "SELECT s.*, us.valid_until FROM subscriptions s
                  JOIN subscription_content sc ON s.id = sc.subscription_id
                  JOIN user_subscriptions us ON s.id = us.subscription_id
                  WHERE us.user_id = ? AND sc.film_id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$userId, $contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getContentEpisodes($contentId)
    {
        $query = "SELECT * FROM `episode` WHERE content_id = ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMembersByContent($contentId)
    {
        $query = "SELECT * FROM `content_member` WHERE content_id = ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContentRecommendations($contentId)
    {
        $query = "SELECT c.* FROM content c
                  JOIN collections_content cc ON c.id = cc.content_id
                  WHERE cc.Colections_id IN (
                  SELECT Colections_id FROM collections_content WHERE content_id = ?
                  ) AND c.id != ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId,$contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComment($userId, $contentId, $content)
    {

        $query = "insert into comments(user_id, content_id, content) values (?,?,?) ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId, $contentId, $content]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function updateViews($contentId)
    {

        $query = "UPDATE content SET views = (views + 1) WHERE id = ?; ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([ $contentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function getContentComments($contentId)
    {
        $query = "SELECT * FROM `comments` WHERE content_id = ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContent($contentId)
    {

        $query = 'select * from content where id = ? LIMIT 1';
        $result = $this->pdo->prepare($query);
        $result->execute([$contentId]);
        return $result->fetch(PDO::FETCH_ASSOC);

    }

}