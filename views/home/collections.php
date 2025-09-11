<?php

/*  @var $m */

/* @var $collections */

if(count($collections) > 0){
?>

<section class="collections">

    <?php

    foreach ($collections as $c) { ?>

        <div class="collections__item">
            <h2 class="collections__item-title h1"> <?= $c['title'] ?></h2>
            <div class="collections__item-body">

                <?php

                foreach ($c['content'] as $cc) {
                    $count = 0;
                    ?>

                    <a class="collections__item-link" href="/views/content?id=<?= $cc['id'] ?>&&type=<?= $cc['type'] ?>">
                        <img src="<?= '/' . $cc['cover'] ?>" class="collections__item-link-img" alt="<?= $cc['title'] ?>">
                    </a>
                    <?php if ($count >= 10) {
                        break;
                    }
                    $count++;
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

</section>
<?php } ?>