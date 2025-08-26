<?php
require_once '../model/Model.php';

session_start();

class filmController
{
    public function addFilm()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен. Только администраторы могут добавлять фильмы.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['film_form'])) {
            $coverDir = __DIR__ . '/../assets/image/';
            $videoDir = __DIR__ . '/../assets/video/';

            $coverFile = uniqid() . '_' . basename($_FILES['cover']['name']);
            $trailerFile = uniqid() . '_' . basename($_FILES['trailer']['name']);
            $videoFile = uniqid() . '_' . basename($_FILES['video']['name']);

            move_uploaded_file($_FILES['cover']['tmp_name'], $coverDir . $coverFile);
            move_uploaded_file($_FILES['trailer']['tmp_name'], $videoDir . $trailerFile);
            move_uploaded_file($_FILES['video']['tmp_name'], $videoDir . $videoFile);

            $rating = (float)$_POST['rating'];
            if ($rating < 0 || $rating > 10) {
                die("Ошибка: Рейтинг должен быть от 0 до 10");
            }

            $filmId = $m->addFilm(
                $_POST['country'],
                "assets/image/$coverFile",
                $_POST['description'],
                $rating,
                $_POST['title'],
                "assets/video/$trailerFile",
                "assets/video/$videoFile",
                $_POST['year']
            );

            $getGenres = $_POST['adminFilmGenres'] ?? [];
            foreach ($getGenres as $g) {
                $m->addFilmGenres($filmId, $g);
            }

            $_SESSION['last_film_id'] = $filmId;
            header('Location: ?success=1');
            exit;
        }
    }

    public function addSport()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен. Только администраторы могут добавлять спортивные события.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sport_form'])) {
            $coverDir = __DIR__ . '/../assets/image/';
            $videoDir = __DIR__ . '/../assets/video/';

            $coverFile = uniqid() . '_' . basename($_FILES['sport_cover']['name']);
            $trailerFile = uniqid() . '_' . basename($_FILES['sport_trailer']['name']);
            $videoFile = uniqid() . '_' . basename($_FILES['sport_video']['name']);

            move_uploaded_file($_FILES['sport_cover']['tmp_name'], $coverDir . $coverFile);
            move_uploaded_file($_FILES['sport_trailer']['tmp_name'], $videoDir . $trailerFile);
            move_uploaded_file($_FILES['sport_video']['tmp_name'], $videoDir . $videoFile);

            $rating = (float)$_POST['sport_rating'];
            if ($rating < 0 || $rating > 10) {
                die("Ошибка: Рейтинг должен быть от 0 до 10");
            }

            $sportId = $m->addSport(
                $_POST['sport_title'],
                $_POST['sport_description'],
                "assets/video/$trailerFile",
                "assets/video/$videoFile",
                $rating,
                $_POST['sport_year'],
                $_POST['sport_country'],
                "assets/image/$coverFile"
            );

            $getTypes = $_POST['sportTypes'] ?? [];
            foreach ($getTypes as $typeId) {
                $m->addSportTypesRelation($sportId, $typeId);
            }

            $_SESSION['last_sport_id'] = $sportId;
            header('Location: ?sport_success=1');
            exit;
        }
    }

    public function addClip()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен. Только администраторы могут добавлять клипы.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clip_form'])) {
            $coverDir = __DIR__ . '/../assets/image/';
            $videoDir = __DIR__ . '/../assets/video/';

            $coverFile = uniqid() . '_' . basename($_FILES['clip_cover']['name']);
            $videoFile = uniqid() . '_' . basename($_FILES['clip_video']['name']);

            move_uploaded_file($_FILES['clip_cover']['tmp_name'], $coverDir . $coverFile);
            move_uploaded_file($_FILES['clip_video']['tmp_name'], $videoDir . $videoFile);

            $rating = (float)$_POST['clip_rating'];
            if ($rating < 0 || $rating > 10) {
                die("Ошибка: Рейтинг должен быть от 0 до 10");
            }

            $clipId = $m->addClip(
                $_POST['clip_title'],
                "assets/image/$coverFile",
                $rating,
                "assets/video/$videoFile",
                $_POST['clip_country'],
                $_POST['clip_year']
            );

            $getGenres = $_POST['clipGenres'] ?? [];
            foreach ($getGenres as $genreId) {
                $m->addClipGenresRelation($clipId, $genreId);
            }

            $_SESSION['last_clip_id'] = $clipId;
            header('Location: ?clip_success=1');
            exit;
        }
    }

    public function addSeries()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен. Только администраторы могут добавлять сериалы.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['series_form'])) {
            $coverDir = __DIR__ . '/../assets/image/';
            $videoDir = __DIR__ . '/../assets/video/';

            $coverFile = uniqid() . '_' . basename($_FILES['series_cover']['name']);
            $trailerFile = uniqid() . '_' . basename($_FILES['series_trailer']['name']);

            move_uploaded_file($_FILES['series_cover']['tmp_name'], $coverDir . $coverFile);
            move_uploaded_file($_FILES['series_trailer']['tmp_name'], $videoDir . $trailerFile);

            $seriesId = $m->addSeries(
                $_POST['series_title'],
                $_POST['series_description'],
                $_POST['series_country'],
                $_POST['series_year'],
                "assets/video/$trailerFile",
                "assets/image/$coverFile"
            );

            $getGenres = $_POST['seriesGenres'] ?? [];
            foreach ($getGenres as $genreId) {
                $m->addSeriesGenresRelation($seriesId, $genreId);
            }

            $_SESSION['last_series_id'] = $seriesId;
            header('Location: ?series_success=1');
            exit;
        }
    }

    public function addSeriesEpisode()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен. Только администраторы могут добавлять эпизоды.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['episode_form'])) {
            $videoDir = __DIR__ . '/../assets/video/';
            $videoFile = uniqid() . '_' . basename($_FILES['episode_video']['name']);

            move_uploaded_file($_FILES['episode_video']['tmp_name'], $videoDir . $videoFile);

            $m->addSeriesEpisode(
                $_POST['series_id'],
                $_POST['season'],
                $_POST['episode_number'],
                "assets/video/$videoFile"
            );

            $_SESSION['last_series_id'] = $_POST['series_id'];
            header('Location: ?episode_success=1');
            exit;
        }
    }

    public function addMovieGenre()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movie_genre_form'])) {
            $name = $_POST['genre_name'];
            $m->addMovieGenre($name);
            header('Location: ?movie_genre_success=1');
            exit;
        }
    }

    public function addSeriesGenre()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['series_genre_form'])) {
            $name = $_POST['genre_name'];
            $m->addSeriesGenre($name);
            header('Location: ?series_genre_success=1');
            exit;
        }
    }

    public function addClipGenre()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clip_genre_form'])) {
            $name = $_POST['genre_name'];
            $m->addClipGenre($name);
            header('Location: ?clip_genre_success=1');
            exit;
        }
    }

    public function addSportType()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sport_type_form'])) {
            $name = $_POST['type_name'];
            $m->addSportType($name);
            header('Location: ?sport_type_success=1');
            exit;
        }
    }

    public function addNewCollection()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['collection_form'])) {
            $title = $_POST['collection_title'];
            $collectionId = $m->addCollection($title);
            $_SESSION['last_collection_id'] = $collectionId;
            header('Location: ?collection_success=1');
            exit;
        }
    }

    public function addContentToCollection()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['collection_content_form'])) {
            $collectionId = $_POST['collection_id'];
            $contentType = $_POST['content_type'];
            $contentId = $_POST['content_id'];

            switch ($contentType) {
                case 'film':
                    $m->addFilmToCollection($collectionId, $contentId);
                    break;
                case 'series':
                    $m->addSeriesToCollection($collectionId, $contentId);
                    break;
                case 'sport':
                    $m->addSportToCollection($collectionId, $contentId);
                    break;
                case 'clip':
                    $m->addClipToCollection($collectionId, $contentId);
                    break;
            }

            header('Location: ?collection_content_success=1');
            exit;
        }
    }

    public function deleteFilm()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_film'])) {
            $filmId = $_POST['film_id'];
            if ($m->deleteFilm($filmId)) {
                $_SESSION['message'] = "Фильм успешно удален!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении фильма!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function deleteSeries()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_series'])) {
            $seriesId = $_POST['series_id'];
            if ($m->deleteSeries($seriesId)) {
                $_SESSION['message'] = "Сериал успешно удален!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении сериала!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function deleteEpisode()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_episode'])) {
            $episodeId = $_POST['episode_id'];
            if ($m->deleteEpisode($episodeId)) {
                $_SESSION['message'] = "Эпизод успешно удален!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении эпизода!";
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function deleteSport()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_sport'])) {
            $sportId = $_POST['sport_id'];
            if ($m->deleteSport($sportId)) {
                $_SESSION['message'] = "Спортивное событие успешно удалено!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении спортивного события!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function deleteClip()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_clip'])) {
            $clipId = $_POST['clip_id'];
            if ($m->deleteClip($clipId)) {
                $_SESSION['message'] = "Клип успешно удален!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении клипа!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function deleteMovieGenre()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_movie_genre'])) {
            $genreId = $_POST['genre_id'];
            if ($m->deleteMovieGenre($genreId)) {
                $_SESSION['message'] = "Жанр фильма удален!";
            } else {
                $_SESSION['error'] = "Жанр используется в фильмах!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function deleteSeriesGenre()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_series_genre'])) {
            $genreId = $_POST['genre_id'];
            if ($m->deleteSeriesGenre($genreId)) {
                $_SESSION['message'] = "Жанр сериала удален!";
            } else {
                $_SESSION['error'] = "Жанр используется в сериалах!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function deleteClipGenre()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_clip_genre'])) {
            $genreId = $_POST['genre_id'];
            if ($m->deleteClipGenre($genreId)) {
                $_SESSION['message'] = "Жанр клипа удален!";
            } else {
                $_SESSION['error'] = "Жанр используется в клипах!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function deleteSportType()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_sport_type'])) {
            $typeId = $_POST['type_id'];
            if ($m->deleteSportType($typeId)) {
                $_SESSION['message'] = "Тип спорта удален!";
            } else {
                $_SESSION['error'] = "Тип используется в спортивных событиях!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function deleteCollection()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_collection'])) {
            $collectionId = $_POST['collection_id'];
            if ($m->deleteCollection($collectionId)) {
                $_SESSION['message'] = "Подборка удалена!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении подборки!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function removeFromCollection()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_collection'])) {
            $collectionId = $_POST['collection_id'];
            $contentType = $_POST['content_type'];
            $contentId = $_POST['content_id'];

            if ($m->removeFromCollection($collectionId, $contentType, $contentId)) {
                $_SESSION['message'] = "Контент удален из подборки!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении контента!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function addSubscription() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subscription'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            $subscriptionId = $m->addSubscription($title, $description, $price);

            if ($subscriptionId) {
                $_SESSION['message'] = "Подписка успешно создана! ID: $subscriptionId";
            } else {
                $_SESSION['error'] = "Ошибка при создании подписки!";
            }
            header('Location: ?');
            exit;
        }
    }

    public function deleteSubscription() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subscription'])) {
            $subscriptionId = $_POST['subscription_id'];

            if ($m->deleteSubscription($subscriptionId)) {
                $_SESSION['message'] = "Подписка удалена! Деньги возвращены пользователям.";
            } else {
                $_SESSION['error'] = "Ошибка при удалении подписки!";
            }
            header('Location: ?');
            exit;
        }
    }

    // Добавление контента в подписку
    public function addContentToSubscription() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_content_to_subscription'])) {
            $subscriptionId = $_POST['subscription_id'];
            $contentType = $_POST['content_type'];
            $contentId = $_POST['content_id'];

            if ($m->addContentToSubscription($subscriptionId, $contentType, $contentId)) {
                $_SESSION['message'] = "Контент добавлен в подписку!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении контента!";
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    // Удаление контента из подписки
    public function removeContentFromSubscription() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_content_from_subscription'])) {
            $subscriptionId = $_POST['subscription_id'];
            $contentType = $_POST['content_type'];
            $contentId = $_POST['content_id'];

            if ($m->removeContentFromSubscription($subscriptionId, $contentType, $contentId)) {
                $_SESSION['message'] = "Контент удален из подписки!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении контента!";
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function addSlide() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_slide'])) {
            // Обработка данных
            $title = $_POST['title'];
            $text = $_POST['text'];
            $linkType = $_POST['link_type'];
            $content_id = 0;
            $link = 'none';

            // Обработка ссылки
            if ($linkType !== 'none') {
                $link = $linkType;

                // Если это не подписки, получаем content_id
                if ($linkType !== 'subscriptions.php' && isset($_POST['content_id'])) {
                    $content_id = (int)$_POST['content_id'];
                }
            }

            // Обработка видео
            $videoPath = 'none';
            if (isset($_FILES['video']) && $_FILES['video']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/video/';
                $fileName = uniqid('slide_') . '_' . basename($_FILES['video']['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadFile)) {
                    $videoPath = 'assets/video/' . $fileName;
                }
            }

            // Подготовка данных
            $slideData = [
                'title' => $title,
                'text' => $text,
                'video' => $videoPath,
                'content_id' => $content_id,
                'link' => $link
            ];

            // Добавление слайда
            if ($m->addSlide($slideData)) {
                $_SESSION['message'] = "Слайд успешно добавлен!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении слайда!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function deleteSlide() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_slide'])) {
            $slideId = $_POST['slide_id'];
            if ($m->deleteSlide($slideId)) {
                $_SESSION['message'] = "Слайд успешно удален!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении слайда!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function addBackgroundVideo() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_background_video'])) {
            $uploadDir = __DIR__ . '/../assets/video/';
            $fileName = uniqid('bg_') . '_' . basename($_FILES['background_video']['name']);
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['background_video']['tmp_name'], $uploadFile)) {
                $videoPath = 'assets/video/' . $fileName;

                if ($m->addBackgroundVideo($videoPath)) {
                    $_SESSION['message'] = "Фоновое видео успешно добавлено!";
                } else {
                    $_SESSION['error'] = "Ошибка при добавлении видео!";
                }
            } else {
                $_SESSION['error'] = "Ошибка при загрузке видео!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function setActiveBackgroundVideo() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_active_background'])) {
            $videoId = $_POST['video_id'];
            if ($m->setActiveBackgroundVideo($videoId)) {
                $_SESSION['message'] = "Активное видео успешно изменено!";
            } else {
                $_SESSION['error'] = "Ошибка при изменении активного видео!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function deleteBackgroundVideo() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_background_video'])) {
            $videoId = $_POST['video_id'];
            if ($m->deleteBackgroundVideo($videoId)) {
                $_SESSION['message'] = "Видео успешно удалено!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении видео!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function addHit() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_hit'])) {
            // Обработка загрузки основного фона
            $backgroundPath = '';
            if (isset($_FILES['background']) && $_FILES['background']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/image/';
                $fileName = uniqid('hit_bg_') . '_' . basename($_FILES['background']['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['background']['tmp_name'], $uploadFile)) {
                    $backgroundPath = 'assets/image/' . $fileName;
                }
            }

            // Обработка загрузки мобильного фона
            $mobileBackgroundPath = '';
            if (isset($_FILES['mobile_background']) && $_FILES['mobile_background']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/image/';
                $fileName = uniqid('hit_mobile_') . '_' . basename($_FILES['mobile_background']['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['mobile_background']['tmp_name'], $uploadFile)) {
                    $mobileBackgroundPath = 'assets/image/' . $fileName;
                }
            }

            // Подготовка данных
            $hitData = [
                'background' => $backgroundPath,
                'mobile_background' => $mobileBackgroundPath,
                'title' => $_POST['title'],
                'text' => $_POST['text'],
                'collection_id' => (int)$_POST['collection_id']
            ];

            // Добавление хита
            if ($m->addHit($hitData)) {
                $_SESSION['message'] = "Хит успешно добавлен!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении хита!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function deleteHit() {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_hit'])) {
            $hitId = $_POST['hit_id'];
            if ($m->deleteHit($hitId)) {
                $_SESSION['message'] = "Хит успешно удален!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении хита!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }


    }

    public function addActor()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_actor'])) {
            $name = $_POST['actor_name'];
            $photo = '';

            // Загрузка фото актера
            if (isset($_FILES['actor_photo']) && $_FILES['actor_photo']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/image/';
                $fileName = uniqid('actor_') . '_' . basename($_FILES['actor_photo']['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['actor_photo']['tmp_name'], $uploadFile)) {
                    $photo = 'assets/image/' . $fileName;
                }
            }

            if ($m->addActor($name, $photo)) {
                $_SESSION['message'] = "Актер успешно добавлен!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении актера!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function addSportTeam()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_sport_team'])) {
            $name = $_POST['team_name'];
            $photo = '';

            // Загрузка фото команды
            if (isset($_FILES['team_photo']) && $_FILES['team_photo']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/image/';
                $fileName = uniqid('team_') . '_' . basename($_FILES['team_photo']['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['team_photo']['tmp_name'], $uploadFile)) {
                    $photo = 'assets/image/' . $fileName;
                }
            }

            if ($m->addSportTeam($name, $photo)) {
                $_SESSION['message'] = "Команда успешно добавлена!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении команды!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function addMusicGroup()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_music_group'])) {
            $name = $_POST['group_name'];
            $photo = '';

            // Загрузка фото группы
            if (isset($_FILES['group_photo']) && $_FILES['group_photo']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/image/';
                $fileName = uniqid('group_') . '_' . basename($_FILES['group_photo']['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['group_photo']['tmp_name'], $uploadFile)) {
                    $photo = 'assets/image/' . $fileName;
                }
            }

            if ($m->addMusicGroup($name, $photo)) {
                $_SESSION['message'] = "Группа успешно добавлена!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении группы!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    // Методы для добавления связей
    public function addFilmActor()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_film_actor'])) {
            $filmId = $_POST['film_id'];
            $actorId = $_POST['actor_id'];

            if ($m->addFilmActor($filmId, $actorId)) {
                $_SESSION['message'] = "Актер успешно добавлен к фильму!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении актера к фильму!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function addSeriesActor()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_series_actor'])) {
            $seriesId = $_POST['series_id'];
            $actorId = $_POST['actor_id'];

            if ($m->addSeriesActor($seriesId, $actorId)) {
                $_SESSION['message'] = "Актер успешно добавлен к сериалу!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении актера к сериалу!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function addSportTeamRelation()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_sport_team_relation'])) {
            $sportId = $_POST['sport_id'];
            $teamId = $_POST['team_id'];

            if ($m->addSportTeamRelation($sportId, $teamId)) {
                $_SESSION['message'] = "Команда успешно добавлена к спортивному событию!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении команды к спортивному событию!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function addClipGroup()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_clip_group'])) {
            $clipId = $_POST['clip_id'];
            $groupId = $_POST['group_id'];

            if ($m->addClipGroup($clipId, $groupId)) {
                $_SESSION['message'] = "Группа успешно добавлена к клипу!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении группы к клипу!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    // Методы для удаления
    public function deleteActor()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_actor'])) {
            $actorId = $_POST['actor_id'];
            if ($m->deleteActor($actorId)) {
                $_SESSION['message'] = "Актер успешно удален!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении актера!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function deleteSportTeam()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_sport_team'])) {
            $teamId = $_POST['team_id'];
            if ($m->deleteSportTeam($teamId)) {
                $_SESSION['message'] = "Команда успешно удалена!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении команды!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function deleteMusicGroup()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_music_group'])) {
            $groupId = $_POST['group_id'];
            if ($m->deleteMusicGroup($groupId)) {
                $_SESSION['message'] = "Группа успешно удалена!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении группы!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    // Методы для удаления связей
    public function removeFilmActor()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_film_actor'])) {
            $filmId = $_POST['film_id'];
            $actorId = $_POST['actor_id'];

            if ($m->removeFilmActor($filmId, $actorId)) {
                $_SESSION['message'] = "Актер успешно удален из фильма!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении актера из фильма!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function removeSeriesActor()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_series_actor'])) {
            $seriesId = $_POST['series_id'];
            $actorId = $_POST['actor_id'];

            if ($m->removeSeriesActor($seriesId, $actorId)) {
                $_SESSION['message'] = "Актер успешно удален из сериала!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении актера из сериала!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function removeSportTeamRelation()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_sport_team_relation'])) {
            $sportId = $_POST['sport_id'];
            $teamId = $_POST['team_id'];

            if ($m->removeSportTeamRelation($sportId, $teamId)) {
                $_SESSION['message'] = "Команда успешно удалена из спортивного события!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении команды из спортивного события!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function removeClipGroup()
    {
        $m = new Model();
        $user = $m->getUser($_SESSION['userId']);

        if ($user['role'] != 'admin') {
            die('Доступ запрещен.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_clip_group'])) {
            $clipId = $_POST['clip_id'];
            $groupId = $_POST['group_id'];

            if ($m->removeClipGroup($clipId, $groupId)) {
                $_SESSION['message'] = "Группа успешно удалена из клипа!";
            } else {
                $_SESSION['error'] = "Ошибка при удалении группы из клипа!";
            }
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }





    public function index()
    {
        $this->addSubscription();
        $this->deleteSubscription();
        $this->addContentToSubscription();
        $this->removeContentFromSubscription();

        $this->addFilm();
        $this->addSport();
        $this->addClip();
        $this->addSeries();
        $this->addSeriesEpisode();

        $m = new Model();
        $sportTypes = $m->getSportTypes();
        $clipGenres = $m->getClipGenres();
        $movieGenres = $m->getMovieGenres();
        $seriesGenres = $m->getSeriesGenres();
        $allSeries = $m->getAllSeries();

        $this->deleteFilm();
        $this->deleteSeries();
        $this->deleteEpisode();
        $this->deleteSport();
        $this->deleteClip();
        $this->deleteMovieGenre();
        $this->deleteSeriesGenre();
        $this->deleteClipGenre();
        $this->deleteSportType();
        $this->deleteCollection();
        $this->removeFromCollection();

        $this->addMovieGenre();
        $this->addSeriesGenre();
        $this->addClipGenre();
        $this->addSportType();
        $this->addNewCollection();
        $this->addContentToCollection();

        $films = $m->getAllFilms();
        $allSeries = $m->getAllSeries();
        $sports = $m->getAllSports();
        $clips = $m->getAllClips();
        $collections = $m->getAllCollections();

        $subscriptions = $m->getAllSubscriptions();
        $films = $m->getAllFilms();
        $allSeries = $m->getAllSeries();
        $sports = $m->getAllSports();
        $clips = $m->getAllClips();

        $this->addHit();
        $this->deleteHit();

        $hits = $m->getAllHits();
        $collections = $m->getAllCollections();

        $this->addSlide();
        $this->deleteSlide();

        $this->addBackgroundVideo();
        $this->setActiveBackgroundVideo();
        $this->deleteBackgroundVideo();

        $this->addActor();
        $this->addSportTeam();
        $this->addMusicGroup();
        $this->addFilmActor();
        $this->addSeriesActor();
        $this->addSportTeamRelation();
        $this->addClipGroup();
        $this->deleteActor();
        $this->deleteSportTeam();
        $this->deleteMusicGroup();
        $this->removeFilmActor();
        $this->removeSeriesActor();
        $this->removeSportTeamRelation();
        $this->removeClipGroup();

        $actors = $m->getActors();
        $sportTeams = $m->getSportTeams();
        $musicGroups = $m->getMusicGroups();



        $subscriptionContent = [];
        $subscriptionContent = $m->getSubscriptionContentForAll();

        foreach ($subscriptions as $subscription) {
            $subscriptionContent[$subscription['id']] = $m->getSubscriptionContent($subscription['id']);
        }

        $slides = $m->getAllSlides();

        $backgroundVideos = $m->getAllBackgroundVideos();

        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Админ панель</title>
            <link rel="stylesheet" href="../styles/style.css">
            <link rel="icon" href="../assets/icons/logo.svg" type="image/x-icon">
        </head>
        <body>
        <h1>Добро пожаловать в админ панель!!!</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Форма для фильмов -->
        <div class="form-section">
            <h2>Добавление фильма</h2>
            <?php if (isset($_GET['success'])): ?>
                <div class="success">
                    Фильм успешно добавлен! ID: <?= $_SESSION['last_film_id'] ?? '' ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="film_form" value="1">
                <div class="form-group">
                    <label>Название фильма:*
                        <input type="text" name="title" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Страна:
                        <input type="text" name="country">
                    </label>
                </div>
                <div class="form-group">
                    <label>Описание:
                        <textarea name="description" rows="3"></textarea>
                    </label>
                </div>
                <div class="form-group">
                    <label>Рейтинг (0-10):
                        <input type="number" name="rating" step="0.1" min="0" max="10">
                    </label>
                </div>
                <div class="form-group">
                    <label>Год выпуска:*
                        <input type="number" name="year" min="1900" max="<?= date('Y') + 5 ?>" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Обложка (изображение):*
                        <input type="file" name="cover" accept="image/*" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Трейлер (видео):*
                        <input type="file" name="trailer" accept="video/*" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Фильм (видео):*
                        <input type="file" name="video" accept="video/*" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Жанры фильма*
                        <select multiple style="background-color: black" name="adminFilmGenres[]">
                            <?php foreach ($movieGenres as $g): ?>
                                <option value="<?= $g['id'] ?>"> <?= $g['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <button type="submit" class="button">Добавить фильм</button>
            </form>
        </div>

        <!-- Форма для спорта -->
        <div class="form-section">
            <h2>Добавление спортивного события</h2>
            <?php if (isset($_GET['sport_success'])): ?>
                <div class="success">
                    Спортивное событие успешно добавлено! ID: <?= $_SESSION['last_sport_id'] ?? '' ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="sport_form" value="1">
                <div class="form-group">
                    <label>Название события:*
                        <input type="text" name="sport_title" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Описание:
                        <textarea name="sport_description" rows="3"></textarea>
                    </label>
                </div>
                <div class="form-group">
                    <label>Страна:
                        <input type="text" name="sport_country">
                    </label>
                </div>
                <div class="form-group">
                    <label>Рейтинг (0-10):
                        <input type="number" name="sport_rating" step="0.1" min="0" max="10">
                    </label>
                </div>
                <div class="form-group">
                    <label>Год проведения:*
                        <input type="number" name="sport_year" min="1900" max="<?= date('Y') + 5 ?>" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Обложка (изображение):*
                        <input type="file" name="sport_cover" accept="image/*" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Трейлер (видео):*
                        <input type="file" name="sport_trailer" accept="video/*" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Видео события:*
                        <input type="file" name="sport_video" accept="video/*" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Типы спорта*
                        <select multiple style="background-color: black" name="sportTypes[]">
                            <?php foreach ($sportTypes as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <button type="submit" class="button">Добавить событие</button>
            </form>
        </div>

        <!-- Форма для клипов -->
        <div class="form-section">
            <h2>Добавление клипа</h2>
            <?php if (isset($_GET['clip_success'])): ?>
                <div class="success">
                    Клип успешно добавлен! ID: <?= $_SESSION['last_clip_id'] ?? '' ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="clip_form" value="1">
                <div class="form-group">
                    <label>Название клипа:*
                        <input type="text" name="clip_title" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Страна:
                        <input type="text" name="clip_country">
                    </label>
                </div>
                <div class="form-group">
                    <label>Рейтинг (0-10):
                        <input type="number" name="clip_rating" step="0.1" min="0" max="10">
                    </label>
                </div>
                <div class="form-group">
                    <label>Год выпуска:*
                        <input type="number" name="clip_year" min="1900" max="<?= date('Y') + 5 ?>" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Обложка (изображение):*
                        <input type="file" name="clip_cover" accept="image/*" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Видео клипа:*
                        <input type="file" name="clip_video" accept="video/*" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Жанры клипа*
                        <select multiple style="background-color: black" name="clipGenres[]">
                            <?php foreach ($clipGenres as $genre): ?>
                                <option value="<?= $genre['id'] ?>"><?= $genre['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <button type="submit" class="button">Добавить клип</button>
            </form>
        </div>

        <!-- Форма для сериалов -->
        <div class="form-section">
            <h2>Добавление сериала</h2>
            <?php if (isset($_GET['series_success'])): ?>
                <div class="success">
                    Сериал успешно добавлен! ID: <?= $_SESSION['last_series_id'] ?? '' ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="series_form" value="1">
                <div class="form-group">
                    <label>Название сериала:*
                        <input type="text" name="series_title" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Страна:
                        <input type="text" name="series_country">
                    </label>
                </div>
                <div class="form-group">
                    <label>Описание:
                        <textarea name="series_description" rows="3"></textarea>
                    </label>
                </div>
                <div class="form-group">
                    <label>Год выпуска:*
                        <input type="number" name="series_year" min="1900" max="<?= date('Y') + 5 ?>" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Обложка (изображение):*
                        <input type="file" name="series_cover" accept="image/*" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Трейлер (видео):*
                        <input type="file" name="series_trailer" accept="video/*" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Жанры сериала*
                        <select multiple style="background-color: black" name="seriesGenres[]">
                            <?php foreach ($seriesGenres as $genre): ?>
                                <option value="<?= $genre['id'] ?>"><?= $genre['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <button type="submit" class="button">Добавить сериал</button>
            </form>
        </div>

        <!-- Форма для добавления эпизодов -->
        <div class="form-section">
            <h2>Добавление эпизода сериала</h2>
            <?php if (isset($_GET['episode_success'])): ?>
                <div class="success">
                    Эпизод успешно добавлен к сериалу ID: <?= $_SESSION['last_series_id'] ?? '' ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="episode_form" value="1">
                <div class="form-row">
                    <div class="form-group">
                        <label>Сериал:*
                            <select name="series_id" required>
                                <option value="">-- Выберите сериал --</option>
                                <?php foreach ($allSeries as $series): ?>
                                    <option value="<?= $series['id'] ?>"><?= $series['title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Сезон:*
                            <input type="number" name="season" min="1" max="100" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Номер эпизода:*
                            <input type="number" name="episode_number" min="1" max="100" required>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Видео эпизода:*
                        <input type="file" name="episode_video" accept="video/*" required>
                    </label>
                </div>
                <button type="submit" class="button">Добавить эпизод</button>
            </form>
        </div>
        <div class="form-section compact-form">
            <h2>Добавление жанров/типов</h2>

            <div class="form-row">
                <!-- Жанры фильмов -->
                <div class="form-group">
                    <h3>Добавить жанр фильма</h3>
                    <?php if (isset($_GET['movie_genre_success'])): ?>
                        <div class="success">Жанр успешно добавлен!</div>
                    <?php endif; ?>
                    <form method="POST">
                        <input type="hidden" name="movie_genre_form" value="1">
                        <div class="form-group">
                            <label>Название жанра:*
                                <input type="text" name="genre_name" required>
                            </label>
                        </div>
                        <button type="submit" class="button">Добавить</button>
                    </form>
                </div>

                <!-- Жанры сериалов -->
                <div class="form-group">
                    <h3>Добавить жанр сериала</h3>
                    <?php if (isset($_GET['series_genre_success'])): ?>
                        <div class="success">Жанр успешно добавлен!</div>
                    <?php endif; ?>
                    <form method="POST">
                        <input type="hidden" name="series_genre_form" value="1">
                        <div class="form-group">
                            <label>Название жанра:*
                                <input type="text" name="genre_name" required>
                            </label>
                        </div>
                        <button type="submit" class="button">Добавить</button>
                    </form>
                </div>
            </div>

            <div class="form-row">
                <!-- Жанры клипов -->
                <div class="form-group">
                    <h3>Добавить жанр клипа</h3>
                    <?php if (isset($_GET['clip_genre_success'])): ?>
                        <div class="success">Жанр успешно добавлен!</div>
                    <?php endif; ?>
                    <form method="POST">
                        <input type="hidden" name="clip_genre_form" value="1">
                        <div class="form-group">
                            <label>Название жанра:*
                                <input type="text" name="genre_name" required>
                            </label>
                        </div>
                        <button type="submit" class="button">Добавить</button>
                    </form>
                </div>

                <!-- Типы спорта -->
                <div class="form-group">
                    <h3>Добавить тип спорта</h3>
                    <?php if (isset($_GET['sport_type_success'])): ?>
                        <div class="success">Тип успешно добавлен!</div>
                    <?php endif; ?>
                    <form method="POST">
                        <input type="hidden" name="sport_type_form" value="1">
                        <div class="form-group">
                            <label>Название типа:*
                                <input type="text" name="type_name" required>
                            </label>
                        </div>
                        <button type="submit" class="button">Добавить</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Формы управления подборками -->
        <div class="form-section">
            <h2>Управление подборками</h2>

            <!-- Создание подборки -->
            <div class="form-group">
                <h3>Создать новую подборку</h3>
                <?php if (isset($_GET['collection_success'])): ?>
                    <div class="success">Подборка успешно создана! ID: <?= $_SESSION['last_collection_id'] ?? '' ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="collection_form" value="1">
                    <div class="form-group">
                        <label>Название подборки:*
                            <input type="text" name="collection_title" required>
                        </label>
                    </div>
                    <button type="submit" class="button">Создать</button>
                </form>
            </div>

            <!-- Добавление контента в подборку -->
            <div class="form-group">
                <h3>Добавить контент в подборку</h3>
                <?php if (isset($_GET['collection_content_success'])): ?>
                    <div class="success">Контент успешно добавлен в подборку!</div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="collection_content_form" value="1">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Подборка:*
                                <select name="collection_id" required>
                                    <?php foreach ($collections as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= $c['title'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>Тип контента:*
                                <select name="content_type" id="content_type" required>
                                    <option value="film">Фильм</option>
                                    <option value="series">Сериал</option>
                                    <option value="sport">Спорт</option>
                                    <option value="clip">Клип</option>
                                </select>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>Контент:*
                                <select name="content_id" id="content_selector" required>
                                    <!-- Заполнится динамически -->
                                </select>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="button">Добавить в подборку</button>
                </form>
            </div>
        </div>

        <div class="delete-section">
            <h2>Управление фильмами</h2>
            <table class="delete-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($films as $film): ?>
                    <tr>
                        <td><?= $film['id'] ?></td>
                        <td><?= $film['title'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete_film" value="1">
                                <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                                <button type="submit" class="delete-btn">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="delete-section">
            <h2>Управление сериалами</h2>
            <table class="delete-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($allSeries as $series): ?>
                    <tr>
                        <td><?= $series['id'] ?></td>
                        <td><?= $series['title'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete_series" value="1">
                                <input type="hidden" name="series_id" value="<?= $series['id'] ?>">
                                <button type="submit" class="delete-btn">Удалить сериал</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Эпизоды сериала -->
                    <tr>
                        <td colspan="3">
                            <h3>Эпизоды:</h3>
                            <?php
                            $episodes = $m->getSeriesEpisodes($series['id']);
                            if (!empty($episodes)): ?>
                                <div class="episodes-list">
                                    <?php foreach ($episodes as $episode): ?>
                                        <div class="episode-item">
                                            <div>
                                                Сезон <?= $episode['season'] ?>
                                                Эпизод <?= $episode['number'] ?>
                                            </div>
                                            <form method="POST">
                                                <input type="hidden" name="delete_episode" value="1">
                                                <input type="hidden" name="episode_id" value="<?= $episode['id'] ?>">
                                                <button type="submit" class="delete-btn">Удалить</button>
                                            </form>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p>Нет эпизодов</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="delete-section">
            <h2>Управление спортивными событиями</h2>
            <table class="delete-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($sports as $sport): ?>
                    <tr>
                        <td><?= $sport['id'] ?></td>
                        <td><?= $sport['title'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete_sport" value="1">
                                <input type="hidden" name="sport_id" value="<?= $sport['id'] ?>">
                                <button type="submit" class="delete-btn">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="delete-section">
            <h2>Управление клипами</h2>
            <table class="delete-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($clips as $clip): ?>
                    <tr>
                        <td><?= $clip['id'] ?></td>
                        <td><?= $clip['title'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete_clip" value="1">
                                <input type="hidden" name="clip_id" value="<?= $clip['id'] ?>">
                                <button type="submit" class="delete-btn">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="delete-section">
            <h2>Управление жанрами</h2>

            <h3>Жанры фильмов</h3>
            <table class="delete-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($movieGenres as $genre): ?>
                    <tr>
                        <td><?= $genre['id'] ?></td>
                        <td><?= $genre['name'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete_movie_genre" value="1">
                                <input type="hidden" name="genre_id" value="<?= $genre['id'] ?>">
                                <button type="submit" class="delete-btn">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Жанры сериалов</h3>
            <table class="delete-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($seriesGenres as $genre): ?>
                    <tr>
                        <td><?= $genre['id'] ?></td>
                        <td><?= $genre['name'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete_series_genre" value="1">
                                <input type="hidden" name="genre_id" value="<?= $genre['id'] ?>">
                                <button type="submit" class="delete-btn">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Жанры клипов</h3>
            <table class="delete-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($clipGenres as $genre): ?>
                    <tr>
                        <td><?= $genre['id'] ?></td>
                        <td><?= $genre['name'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete_clip_genre" value="1">
                                <input type="hidden" name="genre_id" value="<?= $genre['id'] ?>">
                                <button type="submit" class="delete-btn">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Типы спорта</h3>
            <table class="delete-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($sportTypes as $type): ?>
                    <tr>
                        <td><?= $type['id'] ?></td>
                        <td><?= $type['name'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete_sport_type" value="1">
                                <input type="hidden" name="type_id" value="<?= $type['id'] ?>">
                                <button type="submit" class="delete-btn">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="delete-section">
            <h2>Управление подборками</h2>
            <table class="delete-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($collections as $collection):
                    $filmsInCollection = $m->getFilmOnCollection($collection['id']);
                    $seriesInCollection = $m->getSeriesOnCollection($collection['id']);
                    $sportsInCollection = $m->getSportOnCollection($collection['id']);
                    $clipsInCollection = $m->getClipOnCollection($collection['id']);
                    ?>
                    <tr>
                        <td><?= $collection['id'] ?></td>
                        <td><?= $collection['title'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete_collection" value="1">
                                <input type="hidden" name="collection_id" value="<?= $collection['id'] ?>">
                                <button type="submit" class="delete-btn">Удалить подборку</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Контент в подборке -->
                    <tr>
                        <td colspan="3">
                            <h3>Контент в подборке:</h3>

                            <?php if (empty($filmsInCollection) && empty($seriesInCollection) &&
                                empty($sportsInCollection) && empty($clipsInCollection)): ?>
                                <p>Подборка пуста</p>
                            <?php endif; ?>

                            <?php if (!empty($filmsInCollection)): ?>
                                <h4>Фильмы:</h4>
                                <ul>
                                    <?php foreach ($filmsInCollection as $film): ?>
                                        <li>
                                            <?= $film['title'] ?>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="remove_from_collection" value="1">
                                                <input type="hidden" name="collection_id" value="<?= $collection['id'] ?>">
                                                <input type="hidden" name="content_type" value="film">
                                                <input type="hidden" name="content_id" value="<?= $film['id'] ?>">
                                                <button type="submit" class="delete-btn">Удалить</button>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (!empty($seriesInCollection)): ?>
                                <h4>Сериалы:</h4>
                                <ul>
                                    <?php foreach ($seriesInCollection as $series): ?>
                                        <li>
                                            <?= $series['title'] ?>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="remove_from_collection" value="1">
                                                <input type="hidden" name="collection_id" value="<?= $collection['id'] ?>">
                                                <input type="hidden" name="content_type" value="series">
                                                <input type="hidden" name="content_id" value="<?= $series['id'] ?>">
                                                <button type="submit" class="delete-btn">Удалить</button>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (!empty($sportsInCollection)): ?>
                                <h4>Спортивные события:</h4>
                                <ul>
                                    <?php foreach ($sportsInCollection as $sport): ?>
                                        <li>
                                            <?= $sport['title'] ?>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="remove_from_collection" value="1">
                                                <input type="hidden" name="collection_id" value="<?= $collection['id'] ?>">
                                                <input type="hidden" name="content_type" value="sport">
                                                <input type="hidden" name="content_id" value="<?= $sport['id'] ?>">
                                                <button type="submit" class="delete-btn">Удалить</button>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (!empty($clipsInCollection)): ?>
                                <h4>Клипы:</h4>
                                <ul>
                                    <?php foreach ($clipsInCollection as $clip): ?>
                                        <li>
                                            <?= $clip['title'] ?>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="remove_from_collection" value="1">
                                                <input type="hidden" name="collection_id" value="<?= $collection['id'] ?>">
                                                <input type="hidden" name="content_type" value="clip">
                                                <input type="hidden" name="content_id" value="<?= $clip['id'] ?>">
                                                <button type="submit" class="delete-btn">Удалить</button>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="subscription-section">
            <h2>Управление подписками</h2>

            <!-- Форма создания подписки -->
            <div class="form-section">
                <h3>Создать новую подписку</h3>
                <form method="POST">
                    <input type="hidden" name="add_subscription" value="1">
                    <div class="form-group">
                        <label>Название:*
                            <input type="text" name="title" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Описание:
                            <textarea name="description" rows="3"></textarea>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Цена:*
                            <input type="number" name="price" step="0.01" min="0" required>
                        </label>
                    </div>
                    <button type="submit" class="button">Создать подписку</button>
                </form>
            </div>

            <!-- Список существующих подписок -->
            <div class="delete-section">
                <h3>Список подписок</h3>
                <table class="delete-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($subscriptions as $subscription): ?>
                        <?php
                        $content = $subscriptionContent[$subscription['id']] ?? [];
                        ?>
                        <tr>
                            <td><?= $subscription['id'] ?></td>
                            <td><?= $subscription['title'] ?></td>
                            <td><?= $subscription['price'] ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="delete_subscription" value="1">
                                    <input type="hidden" name="subscription_id" value="<?= $subscription['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Контент подписки -->
                        <tr>
                            <td colspan="4" class="subscription-content">
                                <h4>Контент в подписке:</h4>

                                <?php if (empty($content)): ?>
                                    <p>Подписка не содержит контента</p>
                                <?php else: ?>
                                    <!-- Фильмы -->
                                    <?php if (!empty($content['films'])): ?>
                                        <h5>Фильмы:</h5>
                                        <ul>
                                            <?php foreach ($content['films'] as $film): ?>
                                                <li>
                                                    <?= $film['title'] ?>
                                                    <form method="POST" style="display:inline-block;margin-left:10px;">
                                                        <input type="hidden" name="remove_content_from_subscription" value="1">
                                                        <input type="hidden" name="subscription_id" value="<?= $subscription['id'] ?>">
                                                        <input type="hidden" name="content_type" value="film">
                                                        <input type="hidden" name="content_id" value="<?= $film['id'] ?>">
                                                        <button type="submit" class="delete-btn">Удалить</button>
                                                    </form>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <!-- Сериалы -->
                                    <?php if (!empty($content['series'])): ?>
                                        <h5>Сериалы:</h5>
                                        <ul>
                                            <?php foreach ($content['series'] as $series): ?>
                                                <li>
                                                    <?= $series['title'] ?>
                                                    <form method="POST" style="display:inline-block;margin-left:10px;">
                                                        <input type="hidden" name="remove_content_from_subscription" value="1">
                                                        <input type="hidden" name="subscription_id" value="<?= $subscription['id'] ?>">
                                                        <input type="hidden" name="content_type" value="series">
                                                        <input type="hidden" name="content_id" value="<?= $series['id'] ?>">
                                                        <button type="submit" class="delete-btn">Удалить</button>
                                                    </form>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <!-- Спортивные события -->
                                    <?php if (!empty($content['sports'])): ?>
                                        <h5>Спортивные события:</h5>
                                        <ul>
                                            <?php foreach ($content['sports'] as $sport): ?>
                                                <li>
                                                    <?= $sport['title'] ?>
                                                    <form method="POST" style="display:inline-block;margin-left:10px;">
                                                        <input type="hidden" name="remove_content_from_subscription" value="1">
                                                        <input type="hidden" name="subscription_id" value="<?= $subscription['id'] ?>">
                                                        <input type="hidden" name="content_type" value="sport">
                                                        <input type="hidden" name="content_id" value="<?= $sport['id'] ?>">
                                                        <button type="submit" class="delete-btn">Удалить</button>
                                                    </form>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <!-- Клипы -->
                                    <?php if (!empty($content['clips'])): ?>
                                        <h5>Клипы:</h5>
                                        <ul>
                                            <?php foreach ($content['clips'] as $clip): ?>
                                                <li>
                                                    <?= $clip['title'] ?>
                                                    <form method="POST" style="display:inline-block;margin-left:10px;">
                                                        <input type="hidden" name="remove_content_from_subscription" value="1">
                                                        <input type="hidden" name="subscription_id" value="<?= $subscription['id'] ?>">
                                                        <input type="hidden" name="content_type" value="clip">
                                                        <input type="hidden" name="content_id" value="<?= $clip['id'] ?>">
                                                        <button type="submit" class="delete-btn">Удалить</button>
                                                    </form>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- Форма добавления контента -->
                                <div class="form-section">
                                    <h5>Добавить контент в подписку</h5>
                                    <form method="POST">
                                        <input type="hidden" name="add_content_to_subscription" value="1">
                                        <input type="hidden" name="subscription_id" value="<?= $subscription['id'] ?>">

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>Тип контента:
                                                    <select name="content_type">
                                                        <option value="film">Фильм</option>
                                                        <option value="series">Сериал</option>
                                                        <option value="sport">Спорт</option>
                                                        <option value="clip">Клип</option>
                                                    </select>
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label>Контент:
                                                    <select name="content_id">
                                                        <option value="">-- Выберите контент --</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>

                                        <button type="submit" class="button">Добавить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-section">
            <h2>Управление слайдами</h2>

            <!-- Форма добавления слайда -->
            <div class="form-group">
                <h3>Добавить новый слайд</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="add_slide" value="1">
                    <div class="form-group">
                        <label>Заголовок:*
                            <input type="text" name="title" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Текст:*
                            <textarea name="text" required></textarea>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Видео (опционально):
                            <input type="file" name="video" accept="video/mp4">
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Тип ссылки:*
                            <select name="link_type" id="slide_link_type" required>
                                <option value="none">Без ссылки</option>
                                <option value="subscriptions.php">Подписки</option>
                                <option value="clip.php">Клип</option>
                                <option value="film.php">Фильм</option>
                                <option value="sport-video.php">Спорт</option>
                                <option value="series-video.php">Сериал</option>
                            </select>
                        </label>
                    </div>
                    <div class="form-group" id="slide_content_group" style="display:none;">
                        <label>Контент:*
                            <select name="content_id" id="slide_content_select"></select>
                        </label>
                    </div>
                    <button type="submit" class="button">Добавить слайд</button>
                </form>
            </div>

            <!-- Список слайдов -->
            <div class="delete-section">
                <h3>Список слайдов</h3>
                <table class="delete-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Превью</th>
                        <th>Ссылка</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($slides as $slide): ?>
                        <tr>
                            <td><?= $slide['id'] ?></td>
                            <td><?= $slide['title'] ?></td>
                            <td>
                                <?php if ($slide['video'] !== 'none'): ?>
                                    <video style="max-width: 200px; max-height: 100px;" controls muted>
                                        <source src="../<?= $slide['video'] ?>" type="video/mp4">
                                    </video>
                                <?php else: ?>
                                    Нет видео
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $slide['link'] ?>
                                <?= $slide['content_id'] > 0 ? "(ID: {$slide['content_id']})" : '' ?>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="delete_slide" value="1">
                                    <input type="hidden" name="slide_id" value="<?= $slide['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-section">
            <h2>Управление фоновыми видео</h2>

            <!-- Форма добавления видео -->
            <div class="form-group">
                <h3>Добавить новое фоновое видео</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="add_background_video" value="1">
                    <div class="form-group">
                        <label>Видео (MP4):*
                            <input type="file" name="background_video" accept="video/mp4" required>
                        </label>
                    </div>
                    <button type="submit" class="button">Добавить видео</button>
                </form>
            </div>

            <!-- Список видео -->
            <div class="delete-section">
                <h3>Доступные видео</h3>
                <table class="delete-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Видео</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($backgroundVideos as $video): ?>
                        <tr>
                            <td><?= $video['id'] ?></td>
                            <td>
                                <video style="max-width: 200px; max-height: 100px;" controls muted>
                                    <source src="../<?= $video['video_path'] ?>" type="video/mp4">
                                </video>
                                <div><?= basename($video['video_path']) ?></div>
                            </td>
                            <td><?= $video['is_active'] ? 'Активно' : 'Не активно' ?></td>
                            <td>
                                <?php if (!$video['is_active']): ?>
                                    <form method="POST" style="display:inline-block;">
                                        <input type="hidden" name="set_active_background" value="1">
                                        <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                                        <button type="submit" class="button">Сделать активным</button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="delete_background_video" value="1">
                                    <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-section">
            <h2>Управление хитами</h2>

            <!-- Форма добавления хита -->
            <div class="form-group">
                <h3>Добавить новый хит</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="add_hit" value="1">
                    <div class="form-group">
                        <label>Заголовок:*
                            <input type="text" name="title" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Текст:*
                            <textarea name="text" required></textarea>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Коллекция:*
                            <select name="collection_id" required>
                                <option value="">-- Выберите коллекцию --</option>
                                <?php foreach ($collections as $collection): ?>
                                    <option value="<?= $collection['id'] ?>"><?= $collection['title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Фон (десктоп):*
                            <input type="file" name="background" accept="image/*" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Фон (мобильный):*
                            <input type="file" name="mobile_background" accept="image/*" required>
                        </label>
                    </div>
                    <button type="submit" class="button">Добавить хит</button>
                </form>
            </div>

            <!-- Список хитов -->
            <div class="delete-section">
                <h3>Список хитов</h3>
                <table class="delete-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Текст</th>
                        <th>Коллекция</th>
                        <th>Фон (десктоп)</th>
                        <th>Фон (мобильный)</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($hits as $hit): ?>
                        <tr>
                            <td><?= $hit['id'] ?></td>
                            <td><?= $hit['title'] ?></td>
                            <td><?= $hit['text'] ?></td>
                            <td><?= $hit['collection_title'] ?? 'Не указана' ?></td>
                            <td>
                                <?php if (!empty($hit['background'])): ?>
                                    <img src="../<?= $hit['background'] ?>" style="max-width: 100px; max-height: 100px;">
                                <?php else: ?>
                                    Нет изображения
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($hit['mobile_background'])): ?>
                                    <img src="../<?= $hit['mobile_background'] ?>" style="max-width: 100px; max-height: 100px;">
                                <?php else: ?>
                                    Нет изображения
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="delete_hit" value="1">
                                    <input type="hidden" name="hit_id" value="<?= $hit['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Управление актерами -->
        <div class="form-section">
            <h2>Управление актерами</h2>

            <!-- Форма добавления актера -->
            <div class="form-group">
                <h3>Добавить актера</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="add_actor" value="1">
                    <div class="form-group">
                        <label>Имя актера:*
                            <input type="text" name="actor_name" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Фото:
                            <input type="file" name="actor_photo" accept="image/*">
                        </label>
                    </div>
                    <button type="submit" class="button">Добавить актера</button>
                </form>
            </div>

            <!-- Форма привязки актера к фильму -->
            <div class="form-group">
                <h3>Привязать актера к фильму</h3>
                <form method="POST">
                    <input type="hidden" name="add_film_actor" value="1">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Фильм:*
                                <select name="film_id" required>
                                    <?php foreach ($films as $film): ?>
                                        <option value="<?= $film['id'] ?>"><?= $film['title'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Актер:*
                                <select name="actor_id" required>
                                    <?php foreach ($actors as $actor): ?>
                                        <option value="<?= $actor['id'] ?>"><?= $actor['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="button">Привязать</button>
                </form>
            </div>

            <!-- Форма привязки актера к сериалу -->
            <div class="form-group">
                <h3>Привязать актера к сериалу</h3>
                <form method="POST">
                    <input type="hidden" name="add_series_actor" value="1">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Сериал:*
                                <select name="series_id" required>
                                    <?php foreach ($allSeries as $series): ?>
                                        <option value="<?= $series['id'] ?>"><?= $series['title'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Актер:*
                                <select name="actor_id" required>
                                    <?php foreach ($actors as $actor): ?>
                                        <option value="<?= $actor['id'] ?>"><?= $actor['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="button">Привязать</button>
                </form>
            </div>

            <!-- Список актеров -->
            <div class="delete-section">
                <h3>Список актеров</h3>
                <table class="delete-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Фото</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($actors as $actor): ?>
                        <tr>
                            <td><?= $actor['id'] ?></td>
                            <td><?= $actor['name'] ?></td>
                            <td>
                                <?php if (!empty($actor['photo'])): ?>
                                    <img src="../<?= $actor['photo'] ?>" style="max-width: 100px; max-height: 100px;">
                                <?php else: ?>
                                    Нет фото
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="delete_actor" value="1">
                                    <input type="hidden" name="actor_id" value="<?= $actor['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Список актеров по фильмам -->
            <div class="delete-section">
                <h3>Актеры по фильмам</h3>
                <?php foreach ($films as $film):
                    $filmActors = $m->getActorsByFilm($film['id']);
                    ?>
                    <h4><?= $film['title'] ?></h4>
                    <?php if (!empty($filmActors)): ?>
                    <ul>
                        <?php foreach ($filmActors as $actor): ?>
                            <li>
                                <?= $actor['name'] ?>
                                <form method="POST" style="display:inline-block;margin-left:10px;">
                                    <input type="hidden" name="remove_film_actor" value="1">
                                    <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                                    <input type="hidden" name="actor_id" value="<?= $actor['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Нет привязанных актеров</p>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Список актеров по сериалам -->
            <div class="delete-section">
                <h3>Актеры по сериалам</h3>
                <?php foreach ($allSeries as $series):
                    $seriesActors = $m->getActorsBySeries($series['id']);
                    ?>
                    <h4><?= $series['title'] ?></h4>
                    <?php if (!empty($seriesActors)): ?>
                    <ul>
                        <?php foreach ($seriesActors as $actor): ?>
                            <li>
                                <?= $actor['name'] ?>
                                <form method="POST" style="display:inline-block;margin-left:10px;">
                                    <input type="hidden" name="remove_series_actor" value="1">
                                    <input type="hidden" name="series_id" value="<?= $series['id'] ?>">
                                    <input type="hidden" name="actor_id" value="<?= $actor['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Нет привязанных актеров</p>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Управление спортивными командами -->
        <div class="form-section">
            <h2>Управление спортивными командами</h2>

            <!-- Форма добавления команды -->
            <div class="form-group">
                <h3>Добавить спортивную команду</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="add_sport_team" value="1">
                    <div class="form-group">
                        <label>Название команды:*
                            <input type="text" name="team_name" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Фото:
                            <input type="file" name="team_photo" accept="image/*">
                        </label>
                    </div>
                    <button type="submit" class="button">Добавить команду</button>
                </form>
            </div>

            <!-- Форма привязки команды к спортивному событию -->
            <div class="form-group">
                <h3>Привязать команду к спортивному событию</h3>
                <form method="POST">
                    <input type="hidden" name="add_sport_team_relation" value="1">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Спортивное событие:*
                                <select name="sport_id" required>
                                    <?php foreach ($sports as $sport): ?>
                                        <option value="<?= $sport['id'] ?>"><?= $sport['title'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Команда:*
                                <select name="team_id" required>
                                    <?php foreach ($sportTeams as $team): ?>
                                        <option value="<?= $team['id'] ?>"><?= $team['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="button">Привязать</button>
                </form>
            </div>

            <!-- Список команд -->
            <div class="delete-section">
                <h3>Список команд</h3>
                <table class="delete-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Фото</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($sportTeams as $team): ?>
                        <tr>
                            <td><?= $team['id'] ?></td>
                            <td><?= $team['name'] ?></td>
                            <td>
                                <?php if (!empty($team['photo'])): ?>
                                    <img src="../<?= $team['photo'] ?>" style="max-width: 100px; max-height: 100px;">
                                <?php else: ?>
                                    Нет фото
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="delete_sport_team" value="1">
                                    <input type="hidden" name="team_id" value="<?= $team['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Список команд по спортивным событиям -->
            <div class="delete-section">
                <h3>Команды по спортивным событиям</h3>
                <?php foreach ($sports as $sport):
                    $sportTeams = $m->getTeamsBySport($sport['id']);
                    ?>
                    <h4><?= $sport['title'] ?></h4>
                    <?php if (!empty($sportTeams)): ?>
                    <ul>
                        <?php foreach ($sportTeams as $team): ?>
                            <li>
                                <?= $team['name'] ?>
                                <form method="POST" style="display:inline-block;margin-left:10px;">
                                    <input type="hidden" name="remove_sport_team_relation" value="1">
                                    <input type="hidden" name="sport_id" value="<?= $sport['id'] ?>">
                                    <input type="hidden" name="team_id" value="<?= $team['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Нет привязанных команд</p>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Управление музыкальными группами -->
        <div class="form-section">
            <h2>Управление музыкальными группами</h2>

            <!-- Форма добавления группы -->
            <div class="form-group">
                <h3>Добавить музыкальную группу</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="add_music_group" value="1">
                    <div class="form-group">
                        <label>Название группы:*
                            <input type="text" name="group_name" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Фото:
                            <input type="file" name="group_photo" accept="image/*">
                        </label>
                    </div>
                    <button type="submit" class="button">Добавить группу</button>
                </form>
            </div>

            <!-- Форма привязки группы к клипу -->
            <div class="form-group">
                <h3>Привязать группу к клипу</h3>
                <form method="POST">
                    <input type="hidden" name="add_clip_group" value="1">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Клип:*
                                <select name="clip_id" required>
                                    <?php foreach ($clips as $clip): ?>
                                        <option value="<?= $clip['id'] ?>"><?= $clip['title'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Группа:*
                                <select name="group_id" required>
                                    <?php foreach ($musicGroups as $group): ?>
                                        <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="button">Привязать</button>
                </form>
            </div>

            <!-- Список групп -->
            <div class="delete-section">
                <h3>Список групп</h3>
                <table class="delete-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Фото</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($musicGroups as $group): ?>
                        <tr>
                            <td><?= $group['id'] ?></td>
                            <td><?= $group['name'] ?></td>
                            <td>
                                <?php if (!empty($group['photo'])): ?>
                                    <img src="../<?= $group['photo'] ?>" style="max-width: 100px; max-height: 100px;">
                                <?php else: ?>
                                    Нет фото
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="delete_music_group" value="1">
                                    <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Список групп по клипам -->
            <div class="delete-section">
                <h3>Группы по клипам</h3>
                <?php foreach ($clips as $clip):
                    $clipGroups = $m->getGroupsByClip($clip['id']);
                    ?>
                    <h4><?= $clip['title'] ?></h4>
                    <?php if (!empty($clipGroups)): ?>
                    <ul>
                        <?php foreach ($clipGroups as $group): ?>
                            <li>
                                <?= $group['name'] ?>
                                <form method="POST" style="display:inline-block;margin-left:10px;">
                                    <input type="hidden" name="remove_clip_group" value="1">
                                    <input type="hidden" name="clip_id" value="<?= $clip['id'] ?>">
                                    <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                                    <button type="submit" class="delete-btn">Удалить</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Нет привязанных групп</p>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>


        <a href="/" class="button">Вернуться на сайт</a>

        <script>
            // Общие данные контента для всех форм
            const contentData = {
                film: <?= json_encode($films) ?>,
                series: <?= json_encode($allSeries) ?>,
                sport: <?= json_encode($sports) ?>,
                clip: <?= json_encode($clips) ?>
            };

            // Функция для обновления опций в селекте контента
            function updateContentOptions(contentTypeSelect, contentSelect) {
                const type = contentTypeSelect.value;
                const items = contentData[type] || [];

                // Очищаем и заполняем список
                contentSelect.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '-- Выберите контент --';
                contentSelect.appendChild(defaultOption);

                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.title;
                    contentSelect.appendChild(option);
                });
            }

            // Инициализация при загрузке страницы
            document.addEventListener('DOMContentLoaded', function() {
                // Для формы добавления в подборку
                const collectionTypeSelect = document.getElementById('content_type');
                const collectionContentSelect = document.getElementById('content_selector');
                if (collectionTypeSelect && collectionContentSelect) {
                    updateContentOptions(collectionTypeSelect, collectionContentSelect);
                    collectionTypeSelect.addEventListener('change', function() {
                        updateContentOptions(collectionTypeSelect, collectionContentSelect);
                    });
                }

                // Для всех форм подписок
                document.querySelectorAll('.subscription-content').forEach(container => {
                    const contentTypeSelect = container.querySelector('select[name="content_type"]');
                    const contentSelect = container.querySelector('select[name="content_id"]');

                    if (contentTypeSelect && contentSelect) {
                        // Инициализация при загрузке
                        updateContentOptions(contentTypeSelect, contentSelect);

                        // Обработчик изменения типа контента
                        contentTypeSelect.addEventListener('change', function() {
                            updateContentOptions(contentTypeSelect, contentSelect);
                        });
                    }
                });
            });
        </script>
        <script>
            // Обновление контента для слайдов
            document.getElementById('slide_link_type').addEventListener('change', function() {
                const linkType = this.value;
                const contentGroup = document.getElementById('slide_content_group');
                const contentSelect = document.getElementById('slide_content_select');

                // Показываем/скрываем выбор контента
                if (linkType !== 'none' && linkType !== 'subscriptions.php') {
                    contentGroup.style.display = 'block';

                    // Определяем тип контента
                    let contentType = '';
                    switch (linkType) {
                        case 'index.php': contentType = 'clip'; break;
                        case 'index.php': contentType = 'film'; break;
                        case 'index.php': contentType = 'sport'; break;
                        case 'index.php': contentType = 'series'; break;
                    }

                    // Загружаем контент
                    if (contentType) {
                        const items = window.contentData[contentType] || [];

                        // Очищаем и заполняем список
                        contentSelect.innerHTML = '';
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = '-- Выберите контент --';
                        contentSelect.appendChild(defaultOption);

                        items.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.title;
                            contentSelect.appendChild(option);
                        });
                    }
                } else {
                    contentGroup.style.display = 'none';
                }
            });

            // Инициализация данных контента
            window.contentData = {
                film: <?= json_encode($films) ?>,
                series: <?= json_encode($allSeries) ?>,
                sport: <?= json_encode($sports) ?>,
                clip: <?= json_encode($clips) ?>
            };
        </script>

        </body>
        </html>
        <?php
    }
}

$fc = new filmController;
$fc->index();
?>