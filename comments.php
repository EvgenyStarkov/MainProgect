<section class="coments">
    <?php

    /* @var $m        */
    /* @var $comments */
    /* @var $video    */

    if (count($comments) > 0) { ?>
        <h1 class="coments__title">Коментарии</h1>
        <div class="coments__body">
            <?php
            foreach ($comments as $cm) {
                $user = $m->getUser($cm['user_id']);
                $avatar = "/assets/icons/Test Account.svg";
                if ($user['avatar'] != null && $user['avatar'] != '') {
                    $avatar = '/' . $user['avatar'];
                }
                ?>
                <div class="coments__item">
                    <img src="<?= $avatar ?>" alt="" class="coments__item-img">
                    <h2 class="coments__item-title"><?= htmlspecialchars($user['username']) ?></h2>
                    <p class="coments__item-content"><?= htmlspecialchars($cm['content']) ?> </p>
                </div>
            <?php } ?>
        </div> <?php } else {
        echo "<h1>Коментариев у этого фильма нет. Оставте первый коминтарий!!!</h1>";
    } ?>
    <?php if (isset($_SESSION['userId'])) { ?>
        <form method="post" action="./video.php" >
            <input type="text" class="coments__input" name="comContent">
            <button type="submit" class="coments__button button" name="comFilmId" value="<?=$video['id'] ?>">
                Отправить коментарий
            </button>
        </form>
    <?php } else { ?> <h1> Мы всегда рады вашим коментарием! Зарегистрируйтесь на сайте или войдите в уже
        существующий аккаунт, что бы поделится своим мнением о данном фильме!!!</h1> <?php } ?>
</section>