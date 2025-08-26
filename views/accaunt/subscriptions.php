<?php

/*  @var $m */

$pageTitle = 'MEGFILMS || ПОДПИСКИ';

require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

if (isset($_SESSION['userId'])) {

    $user = $m->getUser($_SESSION['userId']);

    if (isset($_POST['subBuy'])) {

        $subscription = $m->getSubscriptionById($_POST['subBuy']);

        if ($user['cash'] - $subscription['price'] >= 0) {
            $date = $m->getDateAfter31Days();
            $m->updateUserCash($user['id'], (-$subscription['price']));
            $m->buySubscription($user['id'], $_POST['subBuy'], $date);

        } else { ?>

            <script> alert("Недостаточно средств для получения подписки") </script>

            <?php }
    }

    if(isset($_POST['subDelete'])){

        $m->removeUserSubscription($user['id'],$_POST['subDelete']);

    }

    $subscriptions = $m->getUserSubscriptions($user['id']);

    foreach ($subscriptions as $sb){

        $date = date('Y-m-d');
        $expirationDate = $m->getUserSubscriptionExpirationDate($user['id'], $sb['id']);

        if($date > $expirationDate){

            if($user['cash'] - $sb['price'] >= 0){

                $m->updateUserCash($user['id'],(- $sb['price']));
                $newDate = $m->getDateAfter31Days();
                $m->updateUserSubscriptionDate($user['id'], $sb['id'],$newDate);

            } else {
                $m->removeUserSubscription($user['id'],$sb['id']); ?>
            <script> alert("Вам не хватило средст для продления  подписки : '<?= $sb['title'] ?>' В следствии чего она была удалена из ваших подписок") </script>
            <?php }
        }
    }

    $subscriptions = $m->getUserSubscriptions($user['id']);
    $suggestedSubscriptions = $m->getAvailableSubscriptions($user['id']);
    ?>
    <div class="subscriptions">
        <div class="subscriptions__inner">

            <?php if (count($subscriptions) > 0) { ?>

                <h2 class="subscriptions__title h1">Ваши подписки</h2>
                <ul class="subcriptions__list">
                    <?php foreach ($subscriptions as $sb) {

                        $date = $m->getUserSubscriptionExpirationDate($user['id'], $sb['id']) ?>

                        <li class="subscriptions__item ">
                            <h2 class="h1"><?= $sb['title'] ?></h2>
                            Цена: <?= $sb['price'] ?>р
                            <br><br>
                            Дата оканчания: <?= $date ?>
                            <form method="post" action="subscriptions.php">
                            <button type="submit" class="button subscriptions__item-btn" name="subDelete" value="<?= $sb['id'] ?>">Отключить</button>
                            </form>
                        </li>

                    <?php } ?>
                </ul>

            <?php } ?>

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
                            <form method="post" action="subscriptions.php">
                                <button class="button subscriptions__item-btn" name="subBuy" value="<?= $sg['id'] ?>">
                                    Подключить
                                </button>
                            </form>
                        </li>

                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';

} else {
    echo "Ошибка подключения к акаунту, попробуйте перезойти в ваш аккаунт";
}