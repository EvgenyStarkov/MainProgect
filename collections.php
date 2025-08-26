
<section class="collections">
    <?php

    /* @var $collections */

    foreach ($collections as $c) {?>

        <div class="collections__item">
            <h2 class="collections__item-title h1"> <?= $c['title'] ?></h2>
            <div class="collections__item-body">

                <?php

                /* @var $m */

                $films = $m->getFilmOnCollection($c['id']);
                $series = $m->getSeriesOnCollection($c['id']);
                $clips = $m->getClipOnCollection($c['id']);
                $sport = $m->getSportOnCollection($c['id']);
                $count = 1;

                foreach ($films as $cl) {
                    ?>

                    <form class="collections__item-link" action="/views/films/video.php?<?= $cl['title']?>" method="post">
                        <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;"
                                value="<?= $cl['id'] ?>" name="filmFakeLink">
                            <img src="<?= '/' . $cl['cover'] ?>" class="collections__item-link-img" alt="<?= $cl['title'] ?>">
                        </button>
                    </form>

                    <?php if ($count >= 10) {
                        break;
                    }
                    $count++;
                }
                foreach ($series as $cl) {
                    ?>

                    <form class="collections__item-link" action="/views/series/video.php?<?= $cl['title']?>" method="post">
                        <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;"
                                value="<?= $cl['id'] ?>" name="filmFakeLink">
                            <img src="<?= '/' . $cl['cover'] ?>" class="collections__item-link-img" alt="<?= $cl['title'] ?>">
                        </button>
                    </form>

                    <?php if ($count >= 10) {
                        break;
                    }
                    $count++;
                }

                foreach ($clips as $cl) {
                    ?>

                    <form class="collections__item-link" action="/views/music/video.php?<?= $cl['title']?>" method="post">
                        <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;" value="<?= $cl['id'] ?>" name="filmFakeLink">
                            <img src="<?= '/' . $cl['cover'] ?>"   class="collections__item-link-img" alt="<?= $cl['title'] ?>">
                        </button
                    </form>

                    <?php if ($count >= 10) {
                        break;
                    }
                    $count++;
                }

                foreach ($sport as $cl) {
                    ?>

                    <form class="collections__item-link" action="/views/sport/video.php?<?= $cl['title']?>" method="post">
                        <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;" value="<?= $cl['id'] ?>" name="filmFakeLink">
                            <img src="<?= '/' . $cl['cover'] ?>"   class="collections__item-link-img" alt="<?= $cl['title'] ?>">
                        </button>
                    </form>

                    <?php if ($count >= 10) {
                        break;
                    }
                    $count++;
                }
                if (count($films) + count($series) + count($clips) + count($sport) > 10){
                ?>
                <form action="/views/collection/index.php" method="post">
                    <button type="submit" class="collections__item-btn" name="collection" value="<?= $c['id'] ?>">
                        <img src="assets/icons/next.svg" alt="" class="collections__item-btn-img">СМОТРЕТЬ БОЛЬШЕ
                    </button> <?php
                    } ?>
            </div>
        </div>
    <?php } ?>
</section>
