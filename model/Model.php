<?php

class Model
{
    public $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=mymainproject", 'root', 'root');
        } catch (PDOException $e) {
            echo 'Ошибка подключения' . $e->getMessage();
        };
    }

    public function getAllSlides()
    {
        $query = "select * from hero_slide";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCollections()
    {
        $query = "SELECT * FROM colections WHERE id IN ( SELECT DISTINCT colections_id FROM colectionsfilm ) or id IN ( SELECT DISTINCT colection_id FROM colectionsclip ) or id IN ( SELECT DISTINCT colections_id FROM colectionssport ) or id IN ( SELECT DISTINCT colections_id FROM colectionsseries )";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCollections()
    {
        $query = "SELECT * FROM colections;";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCollection($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "select * from colections where id= ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getFilmCastomColection($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "SELECT DISTINCT f2.*  FROM colectionsfilm cf1 JOIN colectionsfilm cf2 ON cf1.Colections_id = cf2.Colections_id JOIN film f2 ON cf2.Film_id = f2.id  WHERE cf1.Film_id = ?  AND cf2.Film_id != ?";
        $result = $this->pdo->prepare($query);
        $result->execute([$id, $id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSeriesCastomColection($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "SELECT DISTINCT s2.*  
              FROM colectionsseries cs1 
              JOIN colectionsseries cs2 ON cs1.colections_id = cs2.colections_id 
              JOIN series s2 ON cs2.series_id = s2.id  
              WHERE cs1.series_id = ?  
              AND cs2.series_id != ?";

        $result = $this->pdo->prepare($query);
        $result->execute([$id, $id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSportCastomColection($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "SELECT DISTINCT sp2.*  
              FROM colectionssport csp1 
              JOIN colectionssport csp2 ON csp1.colections_id = csp2.colections_id 
              JOIN sport sp2 ON csp2.sport_id = sp2.id  
              WHERE csp1.sport_id = ?  
              AND csp2.sport_id != ?";

        $result = $this->pdo->prepare($query);
        $result->execute([$id, $id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClipCastomColection($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "SELECT DISTINCT c2.*  
              FROM colectionsclip cc1 
              JOIN colectionsclip cc2 ON cc1.colection_id = cc2.colection_id 
              JOIN clip c2 ON cc2.clip_id = c2.id  
              WHERE cc1.clip_id = ?  
              AND cc2.clip_id != ?";

        $result = $this->pdo->prepare($query);
        $result->execute([$id, $id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilm($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "select * from film where id= ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function addFilm($country, $cover, $description, $rating, $title, $trailer, $video, $year)
    {
        $query = "INSERT INTO film(country, cover, description, rating, title, trailer, video, year) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$country, $cover, $description, $rating, $title, $trailer, $video, $year]);

        return $this->pdo->lastInsertId();
    }

    public function getClip($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "select * from clip where id= ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getSport($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "select * from sport where id= ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getSeries($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "select * from series where id= ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function updateFilmViews($id, $views)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "UPDATE film SET views = ? WHERE id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$views, $id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function updateClipViews($id, $views)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "UPDATE clip SET views = ? WHERE id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$views, $id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSportViews($id, $views)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "UPDATE sport SET views = ? WHERE id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$views, $id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSeriesViews($id, $views)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "UPDATE series SET views = ? WHERE id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$views, $id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    public function getFilmOnCollection($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "SELECT *FROM film INNER JOIN colectionsfilm ON film.id = colectionsfilm.Film_id WHERE colectionsfilm.Colections_id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClipOnCollection($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "SELECT *FROM clip INNER JOIN colectionsclip ON clip.id = colectionsclip.clip_id WHERE colectionsclip.colection_id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSportOnCollection($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "SELECT *FROM sport INNER JOIN colectionssport ON sport.id = colectionssport.sport_id WHERE colectionssport.Colections_id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSeriesOnCollection($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $query = "SELECT *FROM series INNER JOIN colectionsseries ON series.id = colectionsseries.series_id WHERE colectionsseries.Colections_id = ?;";
        $result = $this->pdo->prepare($query);
        $result->execute([$id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCollectionsWithMovies()
    {
        $query = "SELECT DISTINCT c.id, c.title 
            FROM colections c
            JOIN colectionsfilm cf ON c.id = cf.Colections_id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCollectionsWithSeries()
    {
        $query = "SELECT DISTINCT c.id, c.title 
            FROM colections c
            JOIN colectionsseries cs ON c.id = cs.colections_id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCollectionsWithSport()
    {
        $query = "SELECT DISTINCT c.id, c.title 
            FROM colections c
            JOIN colectionssport csp ON c.id = csp.colections_id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCollectionsWithClips()
    {
        $query = "SELECT DISTINCT c.id, c.title 
            FROM colections c
            JOIN colectionsclip cc ON c.id = cc.colection_id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieOnlyCollections()
    {
        $query = "SELECT c.id, c.title 
            FROM colections c
            WHERE 
                EXISTS (SELECT 1 FROM colectionsfilm cf WHERE cf.Colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionsseries cs WHERE cs.colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionssport csp WHERE csp.colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionsclip cc WHERE cc.colection_id = c.id)";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSeriesOnlyCollections()
    {
        $query = "SELECT c.id, c.title 
            FROM colections c
            WHERE 
                EXISTS (SELECT 1 FROM colectionsseries cs WHERE cs.colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionsfilm cf WHERE cf.Colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionssport csp WHERE csp.colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionsclip cc WHERE cc.colection_id = c.id)";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSportOnlyCollections()
    {
        $query = "SELECT c.id, c.title 
            FROM colections c
            WHERE 
                EXISTS (SELECT 1 FROM colectionssport csp WHERE csp.colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionsfilm cf WHERE cf.Colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionsseries cs WHERE cs.colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionsclip cc WHERE cc.colection_id = c.id)";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClipOnlyCollections()
    {
        $query = "SELECT c.id, c.title 
            FROM colections c
            WHERE 
                EXISTS (SELECT 1 FROM colectionsclip cc WHERE cc.colection_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionsfilm cf WHERE cf.Colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionsseries cs WHERE cs.colections_id = c.id)
                AND NOT EXISTS (SELECT 1 FROM colectionssport csp WHERE csp.colections_id = c.id)";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSeriesEpisodes($seriesId)
    {
        $query = "SELECT * FROM series_episode WHERE series_id = ? ORDER BY season, number";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$seriesId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieGenres()
    {
        $query = "SELECT * FROM genres ORDER BY name";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGenresForMovie($filmId)
    {
        $query = "SELECT g.* 
                  FROM genres g
                  JOIN film_genres fg ON g.id = fg.genre_id
                  WHERE fg.film_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$filmId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieCountries()
    {
        $query = "SELECT DISTINCT country FROM film 
                  WHERE country IS NOT NULL AND country != '' 
                  ORDER BY country";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getMovieYears()
    {
        $query = "SELECT DISTINCT year FROM film 
                  WHERE year IS NOT NULL 
                  ORDER BY year DESC";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function addFilmGenres($filmId, $genreId)
    {
        $query = "insert into film_genres(film_id,genre_id) values(?,?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$filmId, $genreId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getFilteredMovies($genreId = null, $country = null, $year = null, $sortBy = 'id_desc')
    {
        $query = "SELECT f.* 
                FROM film f";

        $params = [];
        $conditions = [];

        if ($genreId !== null) {
            $query .= " INNER JOIN film_genres fg ON f.id = fg.film_id";
            $conditions[] = "fg.genre_id = ?";
            $params[] = $genreId;
        }

        if ($country !== null) {
            $conditions[] = "f.country = ?";
            $params[] = $country;
        }

        if ($year !== null) {
            $conditions[] = "f.year = ?";
            $params[] = $year;
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $sortOptions = [
            'rating_desc' => 'f.rating DESC, f.id DESC',
            'views_desc' => 'f.views DESC, f.id DESC',
            'year_desc' => 'f.year DESC, f.id DESC',
            'id_desc' => 'f.id DESC'
        ];

        $order = $sortOptions[$sortBy] ?? 'f.id DESC';
        $query .= " ORDER BY $order";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSeriesGenres()
    {
        $query = "SELECT * FROM series_genres ORDER BY name";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSeriesCountries()
    {
        $query = "SELECT DISTINCT country FROM series 
                  WHERE country IS NOT NULL AND country != '' 
                  ORDER BY country";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getSeriesYears()
    {
        $query = "SELECT DISTINCT year FROM series 
                  WHERE year IS NOT NULL 
                  ORDER BY year DESC";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getFilteredSeries($genreId = null, $country = null, $year = null, $sortBy = 'id_desc')
    {
        $query = "SELECT s.* 
                FROM series s";

        $params = [];
        $conditions = [];

        if ($genreId !== null) {
            $query .= " INNER JOIN series_genres_relation sgr ON s.id = sgr.series_id";
            $conditions[] = "sgr.genre_id = ?";
            $params[] = $genreId;
        }

        if ($country !== null) {
            $conditions[] = "s.country = ?";
            $params[] = $country;
        }

        if ($year !== null) {
            $conditions[] = "s.year = ?";
            $params[] = $year;
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $sortOptions = [
            'raiting_desc' => 's.raiting DESC, s.id DESC',
            'views_desc' => 's.views DESC, s.id DESC',
            'year_desc' => 's.year DESC, s.id DESC',
            'id_desc' => 's.id DESC'
        ];

        $order = $sortOptions[$sortBy] ?? 's.id DESC';
        $query .= " ORDER BY $order";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClipGenres()
    {
        $query = "SELECT * FROM clip_genres ORDER BY name";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClipCountries()
    {
        $query = "SELECT DISTINCT country FROM clip 
                  WHERE country IS NOT NULL AND country != '' 
                  ORDER BY country";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getClipYears()
    {
        $query = "SELECT DISTINCT year FROM clip 
                  WHERE year IS NOT NULL 
                  ORDER BY year DESC";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getFilteredClips($genreId = null, $country = null, $year = null, $sortBy = 'id_desc')
    {
        $query = "SELECT c.* 
                FROM clip c";

        $params = [];
        $conditions = [];

        if ($genreId !== null) {
            $query .= " INNER JOIN clip_genres_relation cgr ON c.id = cgr.clip_id";
            $conditions[] = "cgr.genre_id = ?";
            $params[] = $genreId;
        }

        if ($country !== null) {
            $conditions[] = "c.country = ?";
            $params[] = $country;
        }

        if ($year !== null) {
            $conditions[] = "c.year = ?";
            $params[] = $year;
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $sortOptions = [
            'raiting_desc' => 'c.raiting DESC, c.id DESC',
            'views_desc' => 'c.views DESC, c.id DESC',
            'year_desc' => 'c.year DESC, c.id DESC',
            'id_desc' => 'c.id DESC'
        ];

        $order = $sortOptions[$sortBy] ?? 'c.id DESC';
        $query .= " ORDER BY $order";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Фильтрация для спорта
    public function getSportTypes()
    {
        $query = "SELECT id, name FROM sport_types ORDER BY name";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSportCountries()
    {
        $query = "SELECT DISTINCT country FROM sport 
                  WHERE country IS NOT NULL AND country != '' 
                  ORDER BY country";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getSportYears()
    {
        $query = "SELECT DISTINCT year FROM sport 
                  WHERE year IS NOT NULL 
                  ORDER BY year DESC";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getFilteredSports($sportTypeId = null, $country = null, $year = null, $sortBy = 'id_desc')
    {
        $query = "SELECT s.* 
                FROM sport s";

        $params = [];
        $conditions = [];

        if ($sportTypeId !== null) {
            $query .= " INNER JOIN sport_types_relation str ON s.id = str.sport_id";
            $conditions[] = "str.type_id = ?";
            $params[] = $sportTypeId;
        }

        if ($country !== null) {
            $conditions[] = "s.country = ?";
            $params[] = $country;
        }

        if ($year !== null) {
            $conditions[] = "s.year = ?";
            $params[] = $year;
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $sortOptions = [
            'rating_desc' => 's.rating DESC, s.id DESC',
            'views_desc' => 's.views DESC, s.id DESC',
            'year_desc' => 's.year DESC, s.id DESC',
            'id_desc' => 's.id DESC'
        ];

        $order = $sortOptions[$sortBy] ?? 's.id DESC';
        $query .= " ORDER BY $order";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSportTypesForEvent($sportId)
    {
        $query = "SELECT st.id, st.name 
                  FROM sport_types st
                  JOIN sport_types_relation str ON st.id = str.type_id
                  WHERE str.sport_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$sportId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGenresForSeries($seriesId)
    {
        // Проверка существования таблиц
        $tableExists = $this->pdo->query("SHOW TABLES LIKE 'series_genres'")->rowCount() > 0;
        $relationExists = $this->pdo->query("SHOW TABLES LIKE 'series_genres_relation'")->rowCount() > 0;

        if (!$tableExists || !$relationExists) {
            return [];
        }

        $query = "SELECT sg.id, sg.name 
              FROM series_genres sg
              JOIN series_genres_relation sgr ON sg.id = sgr.genre_id
              WHERE sgr.series_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$seriesId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGenresForClip($clipId)
    {
        $query = "SELECT cg.id, cg.name 
              FROM clip_genres cg
              JOIN clip_genres_relation cgr ON cg.id = cgr.genre_id
              WHERE cgr.clip_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$clipId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGenresForSport($sportId)
    {

        $query = "SELECT st.id, st.name 
              FROM sport_types st
              JOIN sport_types_relation str ON st.id = str.type_id
              WHERE str.sport_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$sportId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser($name, $username, $email, $tel, $password, $consent)
    {
        $query = "INSERT INTO `users` (`name`,`username`, `email`, `password`, `tel`,  `сonsent_to_mailing`) VALUES (?,?,?,?,?,?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$name, $username, $email, $password, $tel, $consent]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUser($id)
    {
        $query = "select * from users where id= ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUser()
    {
        $query = "select * from users";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserForEmail($email, $password)
    {
        $query = "select * from users where email= ? && password = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$email, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $data)
    {
        // Проверяем обязательное наличие ID пользователя
        if (empty($userId)) {
            return false;
        }

        // Список всех возможных полей для обновления
        $allowedFields = [
            'name',
            'username',
            'email',
            'password',
            'tel',
            'avatar',
            'сonsent_to_mailing'
        ];

        // Подготавливаем данные для обновления
        $setParts = [];
        $params = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $value = $data[$field];

                // Специальная обработка для согласия на рассылку
                if ($field === 'сonsent_to_mailing') {
                    $value = $value ? 1 : 0;
                }

                $setParts[] = "`$field` = ?";
                $params[] = $value;
            }
        }

        // Если нет полей для обновления
        if (empty($setParts)) {
            return false;
        }

        // Добавляем ID пользователя в параметры
        $params[] = $userId;

        // Формируем SQL запрос
        $setClause = implode(', ', $setParts);
        $query = "UPDATE `users` SET $setClause WHERE `id` = ?";

        // Выполняем запрос
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        // Возвращаем true при успешном обновлении
        return $stmt->rowCount() > 0;
    }

    public function deleteUser($id)
    {
        $query = "delete from users where id = ?;
                  delete from commentsfilm where user_id = ?
                  delete from commentsclip where user_id = ?
                  delete from commentsseries where user_id = ?
                  delete from commentssport where user_id = ?
                  delete from user_subscription where user_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id, $id, $id, $id, $id, $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addFilmComment($userId, $filmId, $content)
    {

        $query = "insert into commentsfilm(user_id, film_id, content) values (?,?,?) ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId, $filmId, $content]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function addSportComment($userId, $filmId, $content)
    {

        $query = "insert into commentssport(user_id, sport_id, content) values (?,?,?) ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId, $filmId, $content]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function addSeriesComment($userId, $filmId, $content)
    {

        $query = "insert into commentsseries(user_id, series_id, content) values (?,?,?) ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId, $filmId, $content]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function addClipComment($userId, $filmId, $content)
    {

        $query = "insert into commentsclip(user_id, clip_id, content) values (?,?,?) ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId, $filmId, $content]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function getFilmComments($filmId)
    {
        $query = "select * from commentsfilm where film_id = ? ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$filmId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSportComments($filmId)
    {
        $query = "select * from commentssport where sport_id = ? ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$filmId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSeriesComments($filmId)
    {
        $query = "select * from commentsseries where series_id = ? ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$filmId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClipComments($filmId)
    {
        $query = "select * from commentsclip where clip_id = ? ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$filmId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserSubscriptions($userId)
    {
        $query = "SELECT s.* 
              FROM subscriptions s
              JOIN user_subscriptions us ON s.id = us.subscription_id
              WHERE us.user_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableSubscriptions($userId)
    {
        $query = "SELECT * 
              FROM subscriptions 
              WHERE id NOT IN (
                  SELECT subscription_id 
                  FROM user_subscriptions 
                  WHERE user_id = ?
              )";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function getSubscriptionExpirationDate($userId, $subscriptionId)
    {
        $query = "SELECT valid_until FROM user_subscriptions 
              WHERE user_id = ? AND subscription_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId, $subscriptionId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['valid_until'] : null;
    }

    public function getSubscriptionsForFilm($filmId)
    {
        $query = "SELECT s.* 
              FROM subscriptions s
              JOIN subscription_film sf ON s.id = sf.subscription_id
              WHERE sf.film_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$filmId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubscriptionsForClip($clipId)
    {
        $query = "SELECT s.* 
              FROM subscriptions s
              JOIN subscription_clip sc ON s.id = sc.subscription_id
              WHERE sc.clip_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$clipId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubscriptionsForSport($clipId)
    {
        $query = "SELECT s.* 
              FROM subscriptions s
              JOIN subscription_sport sc ON s.id = sc.subscription_id
              WHERE sc.sport_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$clipId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubscriptionsForSeries($clipId)
    {
        $query = "SELECT s.* 
              FROM subscriptions s
              JOIN subscription_series sc ON s.id = sc.subscription_id
              WHERE sc.series_id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$clipId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addSport($title, $description, $trailer, $video, $rating, $year, $country, $cover)
    {
        $query = "INSERT INTO sport(title, description, trailer, video, rating, year, country, cover) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$title, $description, $trailer, $video, $rating, $year, $country, $cover]);
        return $this->pdo->lastInsertId();
    }

    // Добавление клипа
    public function addClip($title, $cover, $raiting, $video, $country, $year)
    {
        $query = "INSERT INTO clip(title, cover, raiting, video, country, year) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$title, $cover, $raiting, $video, $country, $year]);
        return $this->pdo->lastInsertId();
    }

    // Добавление связи "спорт-тип"
    public function addSportTypesRelation($sportId, $typeId)
    {
        $query = "INSERT INTO sport_types_relation(sport_id, type_id) VALUES(?,?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$sportId, $typeId]);
        return $stmt->rowCount();
    }

    // Добавление связи "клип-жанр"
    public function addClipGenresRelation($clipId, $genreId)
    {
        $query = "INSERT INTO clip_genres_relation(clip_id, genre_id) VALUES(?,?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$clipId, $genreId]);
        return $stmt->rowCount();
    }

    public function addSeries($title, $description, $country, $year, $trailer, $cover)
    {
        $query = "INSERT INTO series(title, description, country, year, trailer, cover) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$title, $description, $country, $year, $trailer, $cover]);
        return $this->pdo->lastInsertId();
    }

    // Добавление эпизода сериала
    public function addSeriesEpisode($seriesId, $season, $number, $video)
    {
        $query = "INSERT INTO series_episode(series_id, season, number, video) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$seriesId, $season, $number, $video]);
        return $stmt->rowCount();
    }

    // Добавление связи "сериал-жанр"
    public function addSeriesGenresRelation($seriesId, $genreId)
    {
        $query = "INSERT INTO series_genres_relation(series_id, genre_id) VALUES(?,?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$seriesId, $genreId]);
        return $stmt->rowCount();
    }

    // Получение всех сериалов
    public function getAllSeries()
    {
        $query = "SELECT id, title, cover FROM series ORDER BY title";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMovieGenre($name)
    {
        $query = "INSERT INTO genres (name) VALUES (?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$name]);
        return $this->pdo->lastInsertId();
    }

    public function addSeriesGenre($name)
    {
        $query = "INSERT INTO series_genres (name) VALUES (?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$name]);
        return $this->pdo->lastInsertId();
    }

    public function addClipGenre($name)
    {
        $query = "INSERT INTO clip_genres (name) VALUES (?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$name]);
        return $this->pdo->lastInsertId();
    }

    public function addSportType($name)
    {
        $query = "INSERT INTO sport_types (name) VALUES (?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$name]);
        return $this->pdo->lastInsertId();
    }

    // Подборки
    public function addCollection($title)
    {
        $query = "INSERT INTO colections (title) VALUES (?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$title]);
        return $this->pdo->lastInsertId();
    }

    public function addFilmToCollection($collectionId, $filmId)
    {
        $query = "INSERT INTO colectionsfilm (Colections_id, Film_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$collectionId, $filmId]);
        return $stmt->rowCount();
    }

    public function addSeriesToCollection($collectionId, $seriesId)
    {
        $query = "INSERT INTO colectionsseries (colections_id, series_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$collectionId, $seriesId]);
        return $stmt->rowCount();
    }

    public function addSportToCollection($collectionId, $sportId)
    {
        $query = "INSERT INTO colectionssport (colections_id, sport_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$collectionId, $sportId]);
        return $stmt->rowCount();
    }

    public function addClipToCollection($collectionId, $clipId)
    {
        $query = "INSERT INTO colectionsclip (colection_id, clip_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$collectionId, $clipId]);
        return $stmt->rowCount();
    }

    // Получение данных
    public function getAllFilms()
    {
        $query = "SELECT id, title, cover FROM film";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSports()
    {
        $query = "SELECT id, title FROM sport";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllClips()
    {
        $query = "SELECT id, title, cover FROM clip";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteFilm($filmId)
    {
        $this->pdo->beginTransaction();
        try {

            $stmt = $this->pdo->prepare("DELETE FROM film_genres WHERE film_id = ?");
            $stmt->execute([$filmId]);

            $stmt = $this->pdo->prepare("DELETE FROM colectionsfilm WHERE Film_id = ?");
            $stmt->execute([$filmId]);

            $stmt = $this->pdo->prepare("DELETE FROM commentsfilm WHERE film_id = ?");
            $stmt->execute([$filmId]);

            $stmt = $this->pdo->prepare("DELETE FROM film WHERE id = ?");
            $stmt->execute([$filmId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function deleteSeries($seriesId)
    {
        $this->pdo->beginTransaction();
        try {

            $stmt = $this->pdo->prepare("DELETE FROM series_episode WHERE series_id = ?");
            $stmt->execute([$seriesId]);

            $stmt = $this->pdo->prepare("DELETE FROM series_genres_relation WHERE series_id = ?");
            $stmt->execute([$seriesId]);

            $stmt = $this->pdo->prepare("DELETE FROM colectionsseries WHERE series_id = ?");
            $stmt->execute([$seriesId]);

            $stmt = $this->pdo->prepare("DELETE FROM commentsseries WHERE series_id = ?");
            $stmt->execute([$seriesId]);

            $stmt = $this->pdo->prepare("DELETE FROM series WHERE id = ?");
            $stmt->execute([$seriesId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function deleteEpisode($episodeId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM series_episode WHERE id = ?");
        return $stmt->execute([$episodeId]);
    }

    public function deleteSport($sportId)
    {
        $this->pdo->beginTransaction();
        try {
            // Удаление связей типов
            $stmt = $this->pdo->prepare("DELETE FROM sport_types_relation WHERE sport_id = ?");
            $stmt->execute([$sportId]);

            // Удаление из подборок
            $stmt = $this->pdo->prepare("DELETE FROM colectionssport WHERE sport_id = ?");
            $stmt->execute([$sportId]);

            // Удаление комментариев
            $stmt = $this->pdo->prepare("DELETE FROM commentssport WHERE sport_id = ?");
            $stmt->execute([$sportId]);

            // Удаление самого события
            $stmt = $this->pdo->prepare("DELETE FROM sport WHERE id = ?");
            $stmt->execute([$sportId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function deleteClip($clipId)
    {
        $this->pdo->beginTransaction();
        try {
            // Удаление связей жанров
            $stmt = $this->pdo->prepare("DELETE FROM clip_genres_relation WHERE clip_id = ?");
            $stmt->execute([$clipId]);

            // Удаление из подборок
            $stmt = $this->pdo->prepare("DELETE FROM colectionsclip WHERE clip_id = ?");
            $stmt->execute([$clipId]);

            // Удаление комментариев
            $stmt = $this->pdo->prepare("DELETE FROM commentsclip WHERE clip_id = ?");
            $stmt->execute([$clipId]);

            // Удаление самого клипа
            $stmt = $this->pdo->prepare("DELETE FROM clip WHERE id = ?");
            $stmt->execute([$clipId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function deleteMovieGenre($genreId)
    {

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM film_genres WHERE genre_id = ?");
        $stmt->execute([$genreId]);

        if ($stmt->fetchColumn() > 0) {
            return false; // Жанр используется
        }

        $stmt = $this->pdo->prepare("DELETE FROM genres WHERE id = ?");
        return $stmt->execute([$genreId]);
    }

    public function deleteSeriesGenre($genreId)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM series_genres_relation WHERE genre_id = ?");
        $stmt->execute([$genreId]);

        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM series_genres WHERE id = ?");
        return $stmt->execute([$genreId]);
    }

    public function deleteClipGenre($genreId)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM clip_genres_relation WHERE genre_id = ?");
        $stmt->execute([$genreId]);

        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM clip_genres WHERE id = ?");
        return $stmt->execute([$genreId]);
    }

    public function deleteSportType($typeId)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM sport_types_relation WHERE type_id = ?");
        $stmt->execute([$typeId]);

        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM sport_types WHERE id = ?");
        return $stmt->execute([$typeId]);
    }

    public function deleteCollection($collectionId)
    {
        $this->pdo->beginTransaction();
        try {

            $stmt = $this->pdo->prepare("DELETE FROM colectionsfilm WHERE Colections_id = ?");
            $stmt->execute([$collectionId]);

            $stmt = $this->pdo->prepare("DELETE FROM colectionsseries WHERE colections_id = ?");
            $stmt->execute([$collectionId]);

            $stmt = $this->pdo->prepare("DELETE FROM colectionssport WHERE colections_id = ?");
            $stmt->execute([$collectionId]);

            $stmt = $this->pdo->prepare("DELETE FROM colectionsclip WHERE colection_id = ?");
            $stmt->execute([$collectionId]);

            // Удаление самой подборки
            $stmt = $this->pdo->prepare("DELETE FROM colections WHERE id = ?");
            $stmt->execute([$collectionId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function removeFromCollection($collectionId, $contentType, $contentId)
    {
        switch ($contentType) {
            case 'film':
                $stmt = $this->pdo->prepare("DELETE FROM colectionsfilm WHERE Colections_id = ? AND Film_id = ?");
                break;
            case 'series':
                $stmt = $this->pdo->prepare("DELETE FROM colectionsseries WHERE colections_id = ? AND series_id = ?");
                break;
            case 'sport':
                $stmt = $this->pdo->prepare("DELETE FROM colectionssport WHERE colections_id = ? AND sport_id = ?");
                break;
            case 'clip':
                $stmt = $this->pdo->prepare("DELETE FROM colectionsclip WHERE colection_id = ? AND clip_id = ?");
                break;
            default:
                return false;
        }

        return $stmt->execute([$collectionId, $contentId]);
    }

    public function addSubscription($title, $description, $price)
    {
        $query = "INSERT INTO subscriptions (title, description, price) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$title, $description, $price]);
        return $this->pdo->lastInsertId();
    }

    // Удаление подписки с возвратом денег
    public function deleteSubscription($subscriptionId)
    {
        $this->pdo->beginTransaction();
        try {
            // 1. Получаем стоимость подписки
            $subscription = $this->getSubscriptionById($subscriptionId);
            if (!$subscription) return false;
            $price = $subscription['price'];

            // 2. Получаем пользователей с этой подпиской
            $users = $this->getUsersBySubscription($subscriptionId);

            // 3. Возвращаем деньги
            foreach ($users as $user) {
                $this->updateUserCash($user['id'], $price);
            }

            // 4. Удаляем связи подписки с контентом
            $this->pdo->prepare("DELETE FROM subscription_film WHERE subscription_id = ?")->execute([$subscriptionId]);
            $this->pdo->prepare("DELETE FROM subscription_series WHERE subscription_id = ?")->execute([$subscriptionId]);
            $this->pdo->prepare("DELETE FROM subscription_sport WHERE subscription_id = ?")->execute([$subscriptionId]);
            $this->pdo->prepare("DELETE FROM subscription_clip WHERE subscription_id = ?")->execute([$subscriptionId]);

            // 5. Удаляем связи подписки с пользователями
            $this->pdo->prepare("DELETE FROM user_subscriptions WHERE subscription_id = ?")->execute([$subscriptionId]);

            // 6. Удаляем саму подписку
            $this->pdo->prepare("DELETE FROM subscriptions WHERE id = ?")->execute([$subscriptionId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // Добавление контента в подписку
    public function addContentToSubscription($subscriptionId, $contentType, $contentId)
    {
        switch ($contentType) {
            case 'film':
                return $this->addFilmToSubscription($subscriptionId, $contentId);
            case 'series':
                return $this->addSeriesToSubscription($subscriptionId, $contentId);
            case 'sport':
                return $this->addSportToSubscription($subscriptionId, $contentId);
            case 'clip':
                return $this->addClipToSubscription($subscriptionId, $contentId);
            default:
                return false;
        }
    }

    // Удаление контента из подписки
    public function removeContentFromSubscription($subscriptionId, $contentType, $contentId)
    {
        switch ($contentType) {
            case 'film':
                return $this->removeFilmFromSubscription($subscriptionId, $contentId);
            case 'series':
                return $this->removeSeriesFromSubscription($subscriptionId, $contentId);
            case 'sport':
                return $this->removeSportFromSubscription($subscriptionId, $contentId);
            case 'clip':
                return $this->removeClipFromSubscription($subscriptionId, $contentId);
            default:
                return false;
        }
    }

    public function getUsersBySubscription($subscriptionId)
    {
        $query = "SELECT user_id as id FROM user_subscriptions WHERE subscription_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$subscriptionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addFilmToSubscription($subscriptionId, $filmId)
    {
        $query = "INSERT INTO subscription_film (subscription_id, film_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$subscriptionId, $filmId]);
    }

    public function removeFilmFromSubscription($subscriptionId, $filmId)
    {
        $query = "DELETE FROM subscription_film WHERE subscription_id = ? AND film_id = ?";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$subscriptionId, $filmId]);
    }

    // Аналогичные методы для series, sport, clip
    public function addSeriesToSubscription($subscriptionId, $seriesId)
    {
        $query = "INSERT INTO subscription_series (subscription_id, series_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$subscriptionId, $seriesId]);
    }

    public function removeSeriesFromSubscription($subscriptionId, $seriesId)
    {
        $query = "DELETE FROM subscription_series WHERE subscription_id = ? AND series_id = ?";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$subscriptionId, $seriesId]);
    }

    public function addSportToSubscription($subscriptionId, $sportId)
    {
        $query = "INSERT INTO subscription_sport (subscription_id, sport_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$subscriptionId, $sportId]);
    }

    public function removeSportFromSubscription($subscriptionId, $sportId)
    {
        $query = "DELETE FROM subscription_sport WHERE subscription_id = ? AND sport_id = ?";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$subscriptionId, $sportId]);
    }

    public function addClipToSubscription($subscriptionId, $clipId)
    {
        $query = "INSERT INTO subscription_clip (subscription_id, clip_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$subscriptionId, $clipId]);
    }

    public function removeClipFromSubscription($subscriptionId, $clipId)
    {
        $query = "DELETE FROM subscription_clip WHERE subscription_id = ? AND clip_id = ?";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$subscriptionId, $clipId]);
    }

    // Получение контента подписки
    public function getSubscriptionContent($subscriptionId)
    {
        $content = [];

        // Фильмы
        $query = "SELECT f.id, f.title, 'film' as type 
                  FROM film f
                  JOIN subscription_film sf ON f.id = sf.film_id
                  WHERE sf.subscription_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$subscriptionId]);
        $content['films'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Сериалы
        $query = "SELECT s.id, s.title, 'series' as type 
                  FROM series s
                  JOIN subscription_series ss ON s.id = ss.series_id
                  WHERE ss.subscription_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$subscriptionId]);
        $content['series'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Спорт
        $query = "SELECT sp.id, sp.title, 'sport' as type 
                  FROM sport sp
                  JOIN subscription_sport ss ON sp.id = ss.sport_id
                  WHERE ss.subscription_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$subscriptionId]);
        $content['sports'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Клипы
        $query = "SELECT c.id, c.title, 'clip' as type 
                  FROM clip c
                  JOIN subscription_clip sc ON c.id = sc.clip_id
                  WHERE sc.subscription_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$subscriptionId]);
        $content['clips'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $content;
    }

    public function getAllSubscriptions()
    {
        $query = "SELECT * FROM subscriptions";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubscriptionContentForAll()
    {
        $query = "SELECT 
                sf.subscription_id, f.id, f.title, 'film' as type 
              FROM subscription_film sf
              JOIN film f ON sf.film_id = f.id
              
              UNION ALL
              
              SELECT 
                ss.subscription_id, s.id, s.title, 'series' as type 
              FROM subscription_series ss
              JOIN series s ON ss.series_id = s.id
              
              UNION ALL
              
              SELECT 
                ss.subscription_id, sp.id, sp.title, 'sport' as type 
              FROM subscription_sport ss
              JOIN sport sp ON ss.sport_id = sp.id
              
              UNION ALL
              
              SELECT 
                sc.subscription_id, c.id, c.title, 'clip' as type 
              FROM subscription_clip sc
              JOIN clip c ON sc.clip_id = c.id";

        $stmt = $this->pdo->query($query);
        $allContent = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Группируем по subscription_id
        $result = [];
        foreach ($allContent as $item) {
            $subId = $item['subscription_id'];
            unset($item['subscription_id']);

            if (!isset($result[$subId])) {
                $result[$subId] = [
                    'films' => [],
                    'series' => [],
                    'sports' => [],
                    'clips' => []
                ];
            }

            switch ($item['type']) {
                case 'film':
                    $result[$subId]['films'][] = $item;
                    break;
                case 'series':
                    $result[$subId]['series'][] = $item;
                    break;
                case 'sport':
                    $result[$subId]['sports'][] = $item;
                    break;
                case 'clip':
                    $result[$subId]['clips'][] = $item;
                    break;
            }
        }

        return $result;
    }

    public function addSlide($data)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO hero_slide (title, text, video, content_id, link) 
                                        VALUES (:title, :text, :video, :content_id, :link)");

            $params = [
                ':title' => $data['title'],
                ':text' => $data['text'],
                ':video' => $data['video'],
                ':content_id' => $data['content_id'] ?? 0,
                ':link' => $data['link']
            ];

            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Slide add error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteSlide($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM hero_slide WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Slide delete error: " . $e->getMessage());
            return false;
        }
    }

    public function addBackgroundVideo($videoPath)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO background_videos (video_path) VALUES (:path)");
            $stmt->bindParam(':path', $videoPath);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Background video add error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteBackgroundVideo($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM background_videos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Background video delete error: " . $e->getMessage());
            return false;
        }
    }

    public function setActiveBackgroundVideo($id)
    {
        try {
            // Сначала сбросим все активные видео
            $reset = $this->pdo->prepare("UPDATE background_videos SET is_active = 0");
            $reset->execute();

            // Затем установим выбранное как активное
            $stmt = $this->pdo->prepare("UPDATE background_videos SET is_active = 1 WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Set active video error: " . $e->getMessage());
            return false;
        }
    }

    public function getActiveBackgroundVideo()
    {
        $stmt = $this->pdo->query("SELECT video_path FROM background_videos WHERE is_active = 1 LIMIT 1");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['video_path'] : 'assets/video/103503566_free.mp4';
    }

    public function getAllBackgroundVideos()
    {
        $stmt = $this->pdo->query("SELECT * FROM background_videos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllHits()
    {
        $stmt = $this->pdo->query("SELECT * FROM hits");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addHit($data) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO hits (background, mobile_background, title, text, collection_id) 
                                        VALUES (:background, :mobile_background, :title, :text, :collection_id)");

            $params = [
                ':background' => $data['background'],
                ':mobile_background' => $data['mobile_background'],
                ':title' => $data['title'],
                ':text' => $data['text'],
                ':collection_id' => $data['collection_id']
            ];

            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Hit add error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteHit($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM hits WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Hit delete error: " . $e->getMessage());
            return false;
        }
    }


    public function getHit($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM hits WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getActors() {
        $stmt = $this->pdo->query("SELECT * FROM actors");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addActor($name, $photo) {
        $stmt = $this->pdo->prepare("INSERT INTO actors (name, photo) VALUES (?, ?)");
        return $stmt->execute([$name, $photo]);
    }

    public function deleteActor($id) {
        $stmt = $this->pdo->prepare("DELETE FROM actors WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function addFilmActor($filmId, $actorId) {
        $stmt = $this->pdo->prepare("INSERT INTO film_actors (film_id, actor_id) VALUES (?, ?)");
        return $stmt->execute([$filmId, $actorId]);
    }

    public function getActorsByFilm($filmId) {
        $stmt = $this->pdo->prepare("SELECT a.* FROM actors a 
                                   JOIN film_actors fa ON a.id = fa.actor_id 
                                   WHERE fa.film_id = ?");
        $stmt->execute([$filmId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeFilmActor($filmId, $actorId) {
        $stmt = $this->pdo->prepare("DELETE FROM film_actors WHERE film_id = ? AND actor_id = ?");
        return $stmt->execute([$filmId, $actorId]);
    }

    public function addSeriesActor($seriesId, $actorId) {
        $stmt = $this->pdo->prepare("INSERT INTO series_actors (series_id, actor_id) VALUES (?, ?)");
        return $stmt->execute([$seriesId, $actorId]);
    }

    public function getActorsBySeries($seriesId) {
        $stmt = $this->pdo->prepare("SELECT a.* FROM actors a 
                                   JOIN series_actors sa ON a.id = sa.actor_id 
                                   WHERE sa.series_id = ?");
        $stmt->execute([$seriesId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeSeriesActor($seriesId, $actorId) {
        $stmt = $this->pdo->prepare("DELETE FROM series_actors WHERE series_id = ? AND actor_id = ?");
        return $stmt->execute([$seriesId, $actorId]);
    }

    // Методы для работы со спортивными командами
    public function getSportTeams() {
        $stmt = $this->pdo->query("SELECT * FROM sport_teams");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addSportTeam($name, $photo) {
        $stmt = $this->pdo->prepare("INSERT INTO sport_teams (name, photo) VALUES (?, ?)");
        return $stmt->execute([$name, $photo]);
    }

    public function deleteSportTeam($id) {
        $stmt = $this->pdo->prepare("DELETE FROM sport_teams WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function addSportTeamRelation($sportId, $teamId) {
        $stmt = $this->pdo->prepare("INSERT INTO sport_teams_relation (sport_id, team_id) VALUES (?, ?)");
        return $stmt->execute([$sportId, $teamId]);
    }

    public function getTeamsBySport($sportId) {
        $stmt = $this->pdo->prepare("SELECT st.* FROM sport_teams st 
                                   JOIN sport_teams_relation str ON st.id = str.team_id 
                                   WHERE str.sport_id = ?");
        $stmt->execute([$sportId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeSportTeamRelation($sportId, $teamId) {
        $stmt = $this->pdo->prepare("DELETE FROM sport_teams_relation WHERE sport_id = ? AND team_id = ?");
        return $stmt->execute([$sportId, $teamId]);
    }

    // Методы для работы с музыкальными группами
    public function getMusicGroups() {
        $stmt = $this->pdo->query("SELECT * FROM music_groups");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMusicGroup($name, $photo) {
        $stmt = $this->pdo->prepare("INSERT INTO music_groups (name, photo) VALUES (?, ?)");
        return $stmt->execute([$name, $photo]);
    }

    public function deleteMusicGroup($id) {
        $stmt = $this->pdo->prepare("DELETE FROM music_groups WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function addClipGroup($clipId, $groupId) {
        $stmt = $this->pdo->prepare("INSERT INTO clip_groups (clip_id, group_id) VALUES (?, ?)");
        return $stmt->execute([$clipId, $groupId]);
    }

    public function getGroupsByClip($clipId) {
        $stmt = $this->pdo->prepare("SELECT mg.* FROM music_groups mg 
                                   JOIN clip_groups cg ON mg.id = cg.group_id 
                                   WHERE cg.clip_id = ?");
        $stmt->execute([$clipId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeClipGroup($clipId, $groupId) {
        $stmt = $this->pdo->prepare("DELETE FROM clip_groups WHERE clip_id = ? AND group_id = ?");
        return $stmt->execute([$clipId, $groupId]);
    }


}