<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Megafilms</title>

    <link rel="stylesheet" href="/styles/style.css">
    <link rel="icon" href="/assets/icons/logo.svg" type="image/x-icon">

    <script src="/scripts/register-login.js" defer></script>
</head>

<body>
<div class="header">
    <div class="header__inner">
        <a class="header__logo logo" href="../../index.php">
            <img src="../../assets/icons/logo.svg" alt="" class="logo__img">
            <span class="logo__title">MEGAFILMS || АККАУНТ </span>
        </a>
        <nav class="header__nav">
            <a href="../films/index.php" class="header__nav-link">Фильмы</a>
            <a href="../series/index.php" class="header__nav-link">Сериалы</a>
            <a href="../music/index.php" class="header__nav-link">Клипы</a>
            <a href="../sport/index.php" class="header__nav-link">Спорт</a>
        </nav>
        <div class="header__menu">
            <form action="/controllers/UserController.php" method="post">
                <button type="submit" class="header__accaunt-btn" name="exit" value="1">
                    <img src="../../assets/icons/Exit.svg" alt="" class="header__accaunt-img" id="accauntBtn">
                </button>
            </form>
        </div>
    </div>
</div>