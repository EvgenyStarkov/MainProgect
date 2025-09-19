<?php

/* @var $user */
/* @var $subscriptions */
/* @var $suggestedSubscriptions */
/* @var $path */

if ( $user['id'] != 0) {

    require_once 'header.php'

    ?>

    <section class="accaunt">
        <div class="accaunt__header">
            <div class="accaunt__main-info">
                <?php
                $avatar = "/assets/icons/Test Account.svg";
                if ($user['avatar'] != null && $user['avatar'] != '') {
                    $avatar =  $user['avatar'];
                }
                ?>
                <img src="<?= $avatar ?>" alt="" class="accaunt__avatar">
                <h1 class="accaunt_name"> <?= $user['username'] ?></h1>
            </div>
            <div class="accaunt__info">
                <p class="accaunt__tel"> Номер телефона: <?= $user['tel'] ?></p>
                <p class="accaunt__email"> Электроная почта: <?= $user['email'] ?></p>
                <p class="accaunt__name"> ФИО: <?= $user['name'] ?></p>
                <p class="accaunt__tel"> Счет: <?= $user['cash'] ?></p>
            </div>
            <div class="accaunt__info">
                <button class="button accaunt__info-btn">Изменить данные</button>
                <form action="/views/home/?user=1" method="post">
                    <button type="submit" name="deId" value="<?= $_SESSION['userId'] ?>" class="register__submit button">
                        удалить аккаунт
                    </button>
                </form>
                <button class="button accaunt__cash-btn">Пополнить счет</button>
                <button class="button accaunt__cash-btn" id="themeToggle">Сменить тему</button>
            </div>
        </div>
    </section>

    <div class="subscriptions">
    <div class="subscriptions__inner">

    <?php if (count($subscriptions) > 0) { ?>

        <h2 class="subscriptions__title h1">Ваши подписки</h2>
        <ul class="subcriptions__list">
            <?php foreach ($subscriptions as $sb) {

                ?>

                <li class="subscriptions__item ">
                    <h2 class="h1"><?= $sb['title'] ?></h2>
                    Цена: <?= $sb['price'] ?>р
                    <br><br>
                    Дата оканчания: <?= $sb['date'] ?>
                    <form method="post" action="<?= $path ?>?user=1">
                        <button type="submit" class="button subscriptions__item-btn" name="subDelete"
                                value="<?= $sb['id'] ?>">Отключить
                        </button>
                    </form>
                </li>

            <?php } ?>
        </ul>

    <?php }

    if (isset($suggestedSubscriptions)) {

        ?>

        <div class="subscriptions__shop">
            <h2 class="subscriptions__title h1">Наши подписки</h2>
            <ul class="subscriptions__shop-list">
                <?php foreach ($suggestedSubscriptions as $sg) { ?>

                    <li class="subscriptions__shop-item">
                        <div class="subscriptions__shop-item-content">
                            <h2 class="subscriptions__shop-item-title h1"><?= $sg['title'] ?></h2>
                            <p><?= $sg['description'] ?></p>
                            <div class="subscriptions__shop-price"><?= $sg['price'] ?>р</div>
                        </div>
                        <form method="post" action="<?= $path ?>?user=1">
                            <button class="button subscriptions__item-btn" name="subBuy" value="<?= $sg['id'] ?>">
                                Подключить
                            </button>
                        </form>
                    </li>

                <?php } ?>
            </ul>
        </div>



 <?php } ?>

    </div>
    </div>

    <?php
    require_once 'footer.php';

} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/views/404/index.php'; // Добавлено подключение  страницы ошибки если ользоваьель является гостем

}