<?php

/*  @var $m */

$pageTitle = 'MEGFILMS || ПОДПИСКИ';

require_once 'header.php';

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

<?php

require_once 'footer.php';

} else {
    echo "Ошибка подключения к акаунту, попробуйте перезойти в ваш аккаунт";
}