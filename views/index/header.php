<?php

session_start();

require_once  "../../model/Model.php";
$m = new Model;

$user = [
    'id' => 'none'
];

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

    <link rel="stylesheet" href="/assets/styles/style.css">
    <link rel="icon" href="/assets/icons/logo.svg" type="image/x-icon">

    <script src="/assets/scripts/register-login.js" defer></script>
    <script src="/assets/scripts/telMask.js" defer></script>
    <script src="/assets/scripts/more.js" defer></script>
    <script src="/assets/scripts/scroll.js" defer></script>
    <script src="/assets/scripts/header.js" defer></script>
</head>

<body>
<section class="hero">
    <div class="hero__header header">
        <div class="header__inner">
            <a class="header__logo logo" href="/">
                <img src="/assets/icons/logo.svg" alt="" class="logo__img">
                <span class="logo__title">MEGAFILM </span>
            </a>
            <nav class="header__nav">
                <a href="/views/contents?type=фильм" class="header__nav-link">Фильмы</a>
                <a href="/views/contents?type=сериал" class="header__nav-link">Сериалы</a>
                <a href="/views/contents?type=музыкальный клип" class="header__nav-link">Клипы</a>
                <a href="/views/contents?type=спортивное событие"class="header__nav-link">Спорт</a>
            </nav>
            <div class="header__menu">
                <?php
                if (isset($_SESSION['userId'])) {
                    if ($user['avatar'] != null && $user['avatar'] != '') {
                        $avatar = $user['avatar'];
                    } else {
                        $avatar = '/assets/icons/Profile.svg';
                    } ?>
                    <form action="/views/accaunt/index.php">
                        <button type="submit" class="header__accaunt-btn"><img alt=" " src="<?='/'. $avatar ?>"
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
