<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/model/Model.php";
$m = new Model;

$collections = $m->getCollections();
$backgroundVideo = $m->getActiveBackgroundVideo();
$user = [];

if (isset($_SESSION['errorRegister'])) {
    ?>
    <script>
        alert("Произошла ошибка регистрации, аккаунт с указанным номером телефона или указаной электронной почтой уже существует. Попробуйте зарегистрироватся заново или обратитесь в службу поддержки")
    </script>
    <?php
    $_SESSION['errorRegister'] = null;
}
if (isset($_SESSION['errorLogin'])) {
    ?>
    <script>
        alert("Неверно указан логин или пароль")
    </script> <?php
    $_SESSION['errorLogin'] = null;
}
if (isset($_SESSION['userId'])) {
    $user = $m->getUser($_SESSION['userId']);
    if ($user['role'] == 'admin') { ?>
        <h1>Добро пожаловать в админ панель!!! </h1>
        <a href="/controllers/AdminController.php" class="button"> Перейти в редактор </a>
    <?php }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEGAFILMS</title>

    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" href="assets/icons/logo.svg" type="image/x-icon">

    <script src="scripts/register-login.js" defer></script>
    <script src="scripts/telMask.js" defer></script>
    <script src="scripts/more.js" defer></script>
    <script src="scripts/scroll.js" defer></script>
    <script src="scripts/header.js" defer></script>
</head>

<body>
<section class="hero">
    <div class="hero__header header">
        <div class="header__inner">
            <a class="header__logo logo" href="/">
                <img src="assets/icons/logo.svg" alt="" class="logo__img">
                <span class="logo__title">MEGAFILM </span>
            </a>
            <nav class="header__nav">
                <a href="views/films/index.php" class="header__nav-link">Фильмы</a>
                <a href="views/series/index.php" class="header__nav-link">Сериалы</a>
                <a href="views/music/index.php" class="header__nav-link">Клипы</a>
                <a href="views/sport/index.php" class="header__nav-link">Спорт</a>
            </nav>
            <div class="header__menu">
                <?php
                if (isset($_SESSION['userId'])) {
                    if ($user['avatar'] != null && $user['avatar'] != '') {
                        $avatar =   $user['avatar'];
                    } else{  $avatar = '/assets/icons/Profile.svg';}?>
                    <form action="views/accaunt/index.php">
                        <button type="submit" class="header__accaunt-btn"><img  alt=" " src="<?= $avatar ?>"
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
    <video class="hero__background" autoplay muted loop playsinline>
        <source src="<?= $_SERVER['DOCUMENT_ROOT'].'/' . $backgroundVideo ?>" type="video/mp4">
        <h1>Увы рекламное видео не загрузилось</h1>
    </video>
    <div class="hero__more">
        <button class="hero__more-btn button" id="moreBtn"> Подробнее</button>
    </div>
    <div class="hero__more-window">
        <button class="hero__more-window-close"><img src="assets/icons/Close.svg" alt=""
                                                     class="hero__more-window-close-img"></button>
        <div class="hero__slider">
            <button class="hero__slider-left-btn"><img src="assets/icons/next.svg"
                                                       alt="Преведущий слайд" class="hero__slider-btn-img"></button>
            <?php $slides = $m->getAllSlides(); ?>
            <div class="hero__slides">
                <?php
                $slideCountPhp = 0;
                foreach ($slides as $sl) {
                    ?>
                    <div class="hero__slide">
                        <h2 class="hero__slide-title "><?= $sl['title'] ?></h2>
                        <?php if ($sl['video'] != 'none') { ?>
                            <video class="hero__slide-video" autoplay muted loop playsinline>
                                <source src="<?= './' . $sl['video'] ?>" type="video/mp4">
                                <h1>Увы рекламное видео не загрузилось</h1>
                            </video>
                        <?php } ?>
                        <p class="hero__slide-text"><?= $sl['text'] ?></p>
                        <?php if ($sl['link'] != 'none') { ?>
                            <form method="post" action="<?= $sl['link'] ?>">
                                <button name="filmFakeLink" type="submit" class="button hero__slide-link"
                                        value="<?= $sl['content_id'] ?>">
                                    Перейти
                                </button>
                            </form>
                        <?php } ?>
                    </div>
                    <?php
                    $slideCountPhp++;
                    if ($slideCountPhp >= 7) {
                        break;
                    }
                }
                ?>
            </div>
            <button class="hero__slider-right-btn"><img src="assets/icons/next.svg"
                                                        alt="Следующий слайд" class="hero__slider-btn-img"></button>
            <div class="hero__slider-pagination">
                <?php
                $pagNum = 1;
                foreach ($slides as $sl) {
                    if ($pagNum == 1) {
                        $pagActive = 'is-active';
                    } else {
                        $pagActive = '';
                    }
                    ?>
                    <div class="hero__slider-pagination-item <?= $pagActive ?>" data-set="<?= $pagNum ?>"></div>
                    <?php
                    $pagNum++;
                } ?>
            </div>
        </div>
    </div>
</section>
<?php
$hits = $m->getAllHits();
foreach ($hits as $h) {
    ?>
    <section class="fresh-hits" style='background-image: url("<?= './'.$h['background'] ?>"); @media (max-width: 800px) {
            background-image: url("<?= './'.$h['mobile_background'] ?>")
            }'>
        <div class="fresh-hits__inner">
            <div class="fresh-hits__content">
                <h1 class="fresh-hits__title"><?= $h['title'] ?></h1>
                <p class="fresh-hits__text"><?= $h['text'] ?></p>
            </div>
            <div class="fresh-hits__list">
                <?php
                $hitCollection = $m->getCollection($h['collection_id']);
                $films = $m->getFilmOnCollection($hitCollection['id']);
                $series = $m->getSeriesOnCollection($hitCollection['id']);
                $clips = $m->getClipOnCollection($hitCollection['id']);
                $sport = $m->getSportOnCollection($hitCollection['id']);
                $count = 1;
                foreach ($films as $f) {
                    ?>
                    <form action="views/films/video.php" method="post" class="fresh-hits__link">
                        <button type="submit" style="background-color: transparent; width: fit-content; height: fit-content; border: none;"
                                value="<?= $f['id'] ?>" name="filmFakeLink">
                            <img src="<?=  $f['cover'] ?>" alt="" class="fresh-hits__item">
                        </button>
                    </form>
                <?php
                    if ($count >= 7) {
                        break;
                    }
                    $count++;
                }
                foreach ($series as $f) {
                    if ($count >= 7) {
                        break;
                    }
                    $count++;
                ?>
                <form action="views/series/video.php" class="fresh-hits__link" method="post">
                    <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;"
                            type="submit" value="<?= $f['id'] ?>" name="filmFakeLink">
                        <img src="<?=  $f['cover'] ?>" alt="" class="fresh-hits__item">
                    </button>
                </form>
                <?php }
                foreach ($sport as $f) {
                    if ($count >= 7) {
                        break;
                    }
                    $count++;
                ?>
                <form action="views/sport/video.php" method="post" class="fresh-hits__link">
                    <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;"
                            type="submit" value="<?= $f['id'] ?>" name="filmFakeLink">
                        <img src="<?=  $f['cover'] ?>" alt="" class="fresh-hits__item">
                    </button>
                </form>
                <?php }
                foreach ($clips as $cl) {
                    if ($count >= 7) {
                        break;
                    }
                    $count++;
                    ?>
                    <form action="views/music/video.php" class="fresh-hits__link" method="post">
                        <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;"
                                type="submit" value="<?= $cl['id'] ?>" name="filmFakeLink">
                            <img src="<?=  $cl['cover'] ?>" alt="" class="fresh-hits__item">
                        </button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>
<nav class="menu">
    <a class="menu__item" href="views/films/index.php"><img src="assets/icons/film.svg" alt="Фильмы"
                                                 class="menu__item-img">
        <h2 class="menu__item-title h1">Фильмы</h2>
    </a>
    <a class="menu__item" href="views/series/index.php"><img src="assets/icons/Serial.svg" alt="Сериалы"
                                                   class="menu__item-img">
        <h2 class="menu__item-title h1">Сериалы</h2>
    </a>
    <a class="menu__item" href="views/music/index.php"><img src="assets/icons/Music.svg" alt="Клипы" class="menu__item-img">
        <h2 class="menu__item-title h1">Клипы</h2>
    </a>
    <a class="menu__item" href="views/sport/index.php"><img src="assets/icons/sport.svg" alt="Спорт" class="menu__item-img">
        <h2 class="menu__item-title h1">Спорт</h2>
    </a>
</nav>
<?php
require_once './collections.php';
require_once './footer.php';
