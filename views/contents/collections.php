<?php

/*  @var $m */
/*  @var $contentType */
/* @var $content */
/* @var $collections */
/* @var $allVideo */
/* @var $allContentName */

?>

<section class="collections">

    <!-- Генерация всех загруженных подборок -->

    <?php

    foreach ($collections as $c) { ?>

        <div class="collections__item">
            <h2 class="collections__item-title h1"> <?= $c['title'] ?></h2>
            <div class="collections__item-body">

                <?php

                foreach ($c['content'] as $cc) {
                    ?>

                    <a class="collections__item-link" href="/views/content?id=<?= $cc['id'] ?>&&type=<?= $cc['type'] ?>">
                        <img src="<?= '/' . $cc['cover'] ?>" class="collections__item-link-img"
                             alt="<?= $cc['title'] ?>">
                    </a>
                    <?php
                }
                if (count($c['content']) > 10){
                ?>
                    <a  class="collections__item-btn" name="collection" href="/views/collection/?id=<?= $c['id'] ?>">
                        <img src="/assets/icons/next.svg" alt="" class="collections__item-btn-img">СМОТРЕТЬ БОЛЬШЕ
                    </a> <?php
                    } ?>
            </div>
        </div>

    <?php } ?>

    <!-- Подборка со всеми видео данного типа -->

    <div class="collections__item">
        <h2 class="collections__item-title h1"> <?= $allContentName ?> </h2>
        <div class="collections__item-body.grid">

            <?php foreach ($allVideo as $v) { ?>

                    <a class="collections__item-link" href="/views/content?id=<?= $v['id'] ?>&&type=<?= $v['type'] ?>">
                        <img src="<?= '/' . $v['cover'] ?>" class="collections__item-link-img" alt="<?= $v['title'] ?>">
                    </a>

            <?php } ?>

        </div>
    </div>
</section>
