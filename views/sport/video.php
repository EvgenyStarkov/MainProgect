<?php
if (isset($_POST['filmFakeLink']) || isset($_POST['comFilmId'])) {
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/model/Model.php";
$m = new  Model;
if(isset($_POST['comFilmId'])){
    $sport = $m->getSport($_POST['comFilmId']);
} else {$sport = $m->getSport($_POST['filmFakeLink']);}
$sports = $m->getSportCastomColection($sport['id']);
$genres = $m->getGenresForSport($sport['id']);
if (isset($_SESSION['userId']) && isset($_POST['comContent']) && isset($_POST['comFilmId'])) {
    $m->addSportComment($_SESSION['userId'], $_POST['comFilmId'], $_POST['comContent']);
}
$filmSubscriptions = $m->getSubscriptionsForSport($sport['id']);
$isFilmInSubscription = !empty($filmSubscriptions);
$hasAccess = false;
$userId = $_SESSION['userId'] ?? null;
if ($userId) {
    $userSubscriptions = $m->getUserSubscriptions($userId);
    $userSubscriptionIds = array_column($userSubscriptions, 'id');
    foreach ($filmSubscriptions as $sub) {
        if (in_array($sub['id'], $userSubscriptionIds)) {
            $hasAccess = true;
            break;
        }
    }
}
$views = $sport['views'] + 1;
$m->updateSportViews($sport['id'],$views);

$comments = $m->getSportComments($sport['id']);
$video = $sport;

    $user = [];

    if (isset($_SESSION['userId'])) {
        $user = $m->getUser($_SESSION['userId']);
    }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=  'MEGAFILMS || ' . mb_strtoupper($sport['title']) ?></title>

    <link rel="stylesheet" href="/styles/style.css">
    <link rel="icon" href="/assets/icons/logo.svg" type="image/x-icon">

    <script src="/scripts/register-login.js" defer></script>
    <script src="/scripts/telMask.js" defer></script>
    <script src="/scripts/more.js" defer> </script>
    <script src="/scripts/scroll.js" defer></script>
    <script src="/scripts/video.js" defer> </script>
</head>

<body>
    <section class="hero film">
        <div class="hero__header header">
            <div class="header__inner">
                <a class="header__logo logo" href="/index.php">
                    <img src="/assets/icons/logo.svg" alt="" class="logo__img">
                    <span class="logo__title">MEGAFILMS</span>
                </a>
                <nav class="header__nav">
                    <a href="../films/index.php" class="header__nav-link">Фильмы</a>
                    <a href="../series/index.php" class="header__nav-link">Сериалы</a>
                    <a href="../music/index.php" class="header__nav-link">Клипы</a>
                    <a href="index.php" class="header__nav-link">Спорт</a>
                </nav>
                <div class="header__menu">
                    <?php
                    if (isset($_SESSION['userId'])) {
                        if ($user['avatar'] != null && $user['avatar'] != '') {
                            $avatar =   $user['avatar'];
                        } else{  $avatar = '/assets/icons/Profile.svg';}?>
                        <form action="/views/accaunt/index.php">
                            <button type="submit" class="header__accaunt-btn"><img  alt=" " src="/<?= $avatar ?>"
                                                                                    class="header__accaunt-img "
                                                                                    id="accauntBtn"></button>
                        </form>
                    <?php } else { ?>
                        <button class="header__accaunt-btn"><img src="/assets/icons/Profile.svg" alt=""
                                                                 class="header__accaunt-img " id="accauntBtn"></button>
                    <?php } ?>
                </div>
            </div>
        </div>
        <video class="hero__film-background" autoplay muted loop playsinline>
            <source src="<?='/'.$sport['trailer'] ?>" type="video/mp4">
            <h1>Увы рекламное видео не загрузилось</h1>
        </video>
        <div class="hero__info">
            <div class="hero__info-inner">
                <h2 class="hero__info-title "><?= $sport['title']?></h2>
                <p class="hero__info-text"> <?= $sport['description']?></p>
                <?php if (!$isFilmInSubscription || $hasAccess){ ?>
                    <button class="button hero__info-link">Смотреть</button>
                <?php } else{ ?>
                    <div class="subscription-required">
                        <?php if (!$userId){ ?>
                            <p>Данный контент являетс платным, пожалуйста зарегистрируйтесь или авторизуйтесь</p>
                        <?php } else{ ?>
                            <p>  Данный контент являетс платным,для просмотра его требуется одна из подписок:</p>
                            <div class="subscription-list">
                                <?php foreach ($filmSubscriptions as $subscription){?>
                                    <div class="subscription-item">
                                        <p><?= htmlspecialchars($subscription['title']) ?></p>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="video-player">
            <div class="video-player__inner">
                <video class="video-player__video">
                    <source src="<?='/'.$sport['video'] ?>" type="video/mp4">
                    <h1>Увы рекламное видео не загрузилось</h1>
                </video>
                <div class="video-player__controls">
                    <div class="video-player__controls-item play">
                        <img src="../../assets/icons/Play.svg" alt="" class="video-player__controls-img">
                    </div>
                    <div class="video-player__controls-item progress">
                        <input type="range" class="video-player__controls-input">
                    </div>
                    <div class="video-player__controls-item time">00:00</div>
                    <div class="video-player__controls-item audio">
                        <img src="../../assets/icons/Sound.svg" alt="" class="video-player__controls-img">
                        <input type="range" class="video-player__controls-input">
                    </div>
                    <div class="video-player__controls-item full-scrin">
                        <img src="../../assets/icons/Full%20Screen.svg" alt="" class="video-player__controls-img">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php

if (isset($_SESSION['userId'])) {
if ($user['role'] == 'admin') { ?>
    <br><br><br>
    <h1>Добро пожаловать в админ панель!!! </h1>
    <a href="/controllers/AdminController.php" class="button"> Перейти в редактор </a>
    <br><br><br>
<?php }
} ?>

    <section class="film-stats">
        <div class="film-stats__item"><?= $sport['views']?></div>
        <div class="film-stats__item"> <?= $sport['rating']?></div>
        <div class="film-stats__item"> Год: <form method="post" action="index.php"> <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;" value="<?= $sport['year'] ?>" name="year"> <?= $sport['year'] ?> г </button></form></div>
        <div class="film-stats__item">Страна: <form method="post" action="index.php"> <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;" value="<?= $sport['country'] ?>" name="country"><?=  $sport['country'] ?></button></form></div>
        <div class="film-stats__item"> Жанры: <?php foreach ($genres as $g){ ?> <form method="post" action="index.php" style="margin-left: 7px"> <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;" value="<?= $g['id'] ?>" name="sportType"></button></form><?php } ?></div>
    </section>
    <section class="film-actors">
        <h1 class="film-actors__title">Актеры:</h1>
        <div class="film-actors__inner">
            <?php
            $actors = $m->getTeamsBySport($sport['id']);
            if (!empty($actors)): ?>
                <?php foreach ($actors as $actor): ?>
                    <div class="film-actors__item">
                        <img src="../<?= htmlspecialchars($actor['photo'] ?? 'assets/image/default-actor.jpg') ?>"
                             alt="<?= htmlspecialchars($actor['name']) ?>"
                             class="film-actors__img">
                        <h3 class="film-actors__name"><?= htmlspecialchars($actor['name']) ?></h3>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Информация об актерах отсутствует</p>
            <?php endif; ?>
        </div>
    </section>
    <?php  $count = 1; if(count($sports) > 0){ ?>
        <section class="collections">
            <div class="collections__item">
                <h2 class="collections__item-title h1">Смотрите так же</h2>
                <div class="collections__item-body">

                    <?php foreach ($sports as $s) { ?>

                        <form class="collections__item-link" action="video.php?<?= $s['title']?>" method="post" >
                            <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;" value="<?= $s['id'] ?>" name="filmFakeLink">
                                <img src="<?= './' . $s['cover'] ?>" class="collections__item-link-img">
                            </button>
                        </form>

                        <?php
                        if($count >= 20){break;}
                        $count++;
                    } ?>
                </div>
            </div>
        </section>
    <?php }

    require_once $_SERVER['DOCUMENT_ROOT'] . '/comments.php';

    require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';

} else {echo "Ошибка перехода";}?>