<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/model/Model.php';

class AdminModel extends Model
{
    // content
    public function getAllContent()
    {
        $query = 'SELECT * FROM content;';
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addContent($title, $description, $trailer, $video, $rating, $year, $country, $cover, $genres, $type)
    {
        $query = 'INSERT INTO `content` 
                  ( `title`, `description`, `trailer`, `video`, `rating`, `year`, `country`, `cover`, `genres`, `type`)
                  VALUES (? ,?, ?, ?, ?, ?, ?, ?, ?, ?);';
        $result = $this->pdo->prepare($query);
        $result->execute([$title, $description, $trailer, $video, $rating, $year, $country, $cover, $genres, $type]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reContent($title, $description, $trailer, $video, $rating, $year, $country, $cover, $genres, $type, $id)
    {
        $query = "update  content set 
                  title = ?, description = ? ,  trailer = ?, video = ?,rating = ?,
                  year = ?, country = ?, cover = ?, genres =?, type = ?
                  where id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$title, $description, $trailer, $video, $rating, $year, $country, $cover, $genres, $type, $id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteContent($id)
    {
        $query = "delete from content where id = ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // episodes

    public function getAllEpisodes()
    {
        $query = 'SELECT * FROM episode order by content_id, season;';
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEpisode($episodeId)
    {
        $query = 'select * from episode where id = ?';
        $result = $this->pdo->prepare($query);
        $result->execute([$episodeId]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function addEpisode($number, $season, $video, $contentId)
    {
        $query = 'INSERT INTO `episode` 
                  ( `number`, `season`, `video`, `content_id`)
                  VALUES (? ,?, ?, ?);';
        $result = $this->pdo->prepare($query);
        $result->execute([$number, $season, $video, $contentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reEpisode($number, $season, $video, $contentId, $id)
    {
        $query = "update  episode set 
                  number = ?, season = ? ,  video = ?, content_id = ?
                  where id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$number, $season, $video, $contentId, $id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteEpisode($id)
    {
        $query = "delete from episode where id = ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // collections

    public function addCollection($title)
    {
        $query = 'INSERT INTO `collections` 
                  ( `title`) VALUES (?);';
        $result = $this->pdo->prepare($query);
        $result->execute([$title]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reCollection($title, $id)
    {
        $query = "update  collections set  title = ?  where id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$title, $id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteCollection($id)
    {
        $query = "delete from collections where id = ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addContentToCollection($collectionId, $ContentId)
    {
        $query = 'INSERT INTO `collections_content` 
                  ( `Colections_id` , `content_id`) VALUES (?, ?);';
        $result = $this->pdo->prepare($query);
        $result->execute([$collectionId, $ContentId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeContentFromCollection(int $collectionId, int $contentId): bool
    {
        $sql = "DELETE FROM collections_content WHERE Colections_id = ? AND content_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$collectionId, $contentId]);
    }

    public function isContentInCollection(int $collectionId, int $contentId): bool
    {
        $sql = "SELECT COUNT(*) as cnt FROM collections_content WHERE Colections_id = ? AND content_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$collectionId, $contentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['cnt'] ?? 0) > 0;
    }

    public function getAdvertising($id)
    {
        $query = "SELECT * FROM collections_advertising where id = ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function addAdvertising($background, $mobileBackground, $text, $collectionId)
    {
        $query = 'INSERT INTO collections_advertising (background, mobile_background,  text, collection_id) 
                  VALUES (?, ?, ?, ?);';
        $result = $this->pdo->prepare($query);
        $result->execute([$background, $mobileBackground, $text, $collectionId]);
        return $result->rowCount() > 0;
    }

    public function updateAdvertising($id, $background, $mobileBackground, $text, $collectionId)
    {
        $query = 'UPDATE collections_advertising SET 
                  background = ?, mobile_background = ?,  text = ?, collection_id = ?
                  WHERE id = ?;';
        $result = $this->pdo->prepare($query);
        $result->execute([$background, $mobileBackground, $text, $collectionId, $id]);
        return $result->rowCount() > 0;
    }

    public function deleteAdvertising($id)
    {
        $query = 'DELETE FROM collections_advertising WHERE id = ?;';
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->rowCount() > 0;
    }

    // Hero Slides

    public function getHeroSlide($id)
    {
        $query = "SELECT * FROM `hero_slide` where id = ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function addHeroSlide($title, $text, $video, $contentId)
    {
        $query = 'INSERT INTO hero_slide (title, text, video, content_id) VALUES (?, ?, ?, ?);';
        $result = $this->pdo->prepare($query);
        $result->execute([$title, $text, $video, $contentId]);
        return $result->rowCount() > 0;
    }

    public function updateHeroSlide($id, $title, $text, $video, $contentId)
    {
        $query = 'UPDATE hero_slide SET title = ?, text = ?, video = ?, content_id = ? WHERE id = ?;';
        $result = $this->pdo->prepare($query);
        $result->execute([$title, $text, $video, $contentId, $id]);
        return $result->rowCount() > 0;
    }

    public function deleteHeroSlide($id)
    {
        $query = 'DELETE FROM hero_slide WHERE id = ?;';
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->rowCount() > 0;
    }

    // Subscriptions
    public function getAllSubscriptions()
    {
        $query = 'SELECT * FROM subscriptions;';
        $result = $this->pdo->query($query);
        $subscriptions = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach ($subscriptions as &$subscription) {
            $content = $this->getContentForSubscription($subscription['id']);
            $subscription['content'] = implode(' ,', array_column($content, 'title'));
            $subscription['contentId'] = array_column($content, 'id');
        }

        return $subscriptions;
    }

    public function getContentForSubscription($subscriptionId)
    {
        $query = 'SELECT c.id, c.title 
                  FROM content c
                  JOIN subscription_content sc ON c.id = sc.film_id
                  WHERE sc.subscription_id = ?;';
        $result = $this->pdo->prepare($query);
        $result->execute([$subscriptionId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addSubscription($title, $description, $price)
    {
        $query = 'INSERT INTO subscriptions (title, description, price) VALUES (?, ?, ?);';
        $result = $this->pdo->prepare($query);
        $result->execute([$title, $description, $price]);
        return $result->rowCount() > 0;
    }

    public function updateSubscription($id, $title, $description, $price)
    {
        $query = 'UPDATE subscriptions SET title = ?, description = ?, price = ? WHERE id = ?;';
        $result = $this->pdo->prepare($query);
        $result->execute([$title, $description, $price, $id]);
        return $result->rowCount() > 0;
    }

    public function deleteSubscription($id)
    {
        // First delete related content
        $query = 'DELETE FROM subscription_content WHERE subscription_id = ?;';
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);

        // Then delete subscription
        $query = 'DELETE FROM subscriptions WHERE id = ?;';
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->rowCount() > 0;
    }

    public function addContentToSubscription($subscriptionId, $contentId)
    {
        $query = 'INSERT INTO subscription_content (subscription_id, film_id) VALUES (?, ?);';
        $result = $this->pdo->prepare($query);
        $result->execute([$subscriptionId, $contentId]);
        return $result->rowCount() > 0;
    }

    public function removeContentFromSubscription($subscriptionId, $contentId)
    {
        $query = 'DELETE FROM subscription_content WHERE subscription_id = ? AND film_id = ?;';
        $result = $this->pdo->prepare($query);
        $result->execute([$subscriptionId, $contentId]);
        return $result->rowCount() > 0;
    }

    public function isContentInSubscription($subscriptionId, $contentId)
    {
        $query = 'SELECT COUNT(*) FROM subscription_content WHERE subscription_id = ? AND film_id = ?;';
        $result = $this->pdo->prepare($query);
        $result->execute([$subscriptionId, $contentId]);
        return $result->fetchColumn() > 0;
    }


}