<?php

session_start();

if (isset($_SESSION['userId'])) {

    require_once $_SERVER['DOCUMENT_ROOT']."/model/Model.php";

    $m = new Model;
    $user = $m->getUser($_SESSION['userId']);

    if (isset($_SESSION['errorRefactor'])) {
        ?>
        <script>
            alert("Номер телефона или адрес электронной почты на который вы указали уже существует у дргого пользователя")
        </script>
        <?php
        $_SESSION['errorRefactor'] = null;
    }

    $subscriptions = $m->getUserSubscriptions($user['id']);

    $pageTitle = 'MEGFILMS || АКАУНТ';

    require_once './header.php'
    ?>

    <section class="accaunt">
        <div class="accaunt__header">
            <div class="accaunt__main-info">
                <?php
                $avatar = "/assets/icons/Test Account.svg";
                if ($user['avatar'] != null && $user['avatar'] != '') {
                    $avatar = '/' . $user['avatar'];
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
                <button class="button accaunt__info-btn">Изменить данные</button>
                <button class="button accaunt__cash-btn">Пополнить счет</button>
            </div>
        </div>

        <div class="accaunt__subscriptions">
            <h2 class="accaunt__subcriptions-title h1">Ваши подписки</h2>
            <ul class="accaunt__subcriptions-list">
                <?php foreach ($subscriptions as $sb){ ?>
                <li class="accaunt__subcriptions-item">
                    <?= $sb['title'] ?>
                </li>
                <?php } ?>
            </ul>
            <form class="accaunt__subcriptions-link" action="subscriptions.php">
                <button class="button accaunt__subcriptions-btn">Изменить</button>
            </form>
        </div>
    </section>

<?php

require_once './footer.php';

} else {
    echo "<h1> Ошибка подключения к акаунту, попробуйте перезойти в ваш аккаунт</h1>";
}