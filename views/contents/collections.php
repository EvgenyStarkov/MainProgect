<?php

/*  @var $m */
/*  @var $contentType */

$content = $m->getAllContent();
$collections = $m->getAllCollectionsOnType($contentType);
$allVideo = $m->getAllContentOnType($contentType)

?>

<section class="collections">

    <!-- Генерация всех загруженных подборок -->

    <?php

    foreach ($collections as $c) { ?>

        <div class="collections__item">
            <h2 class="collections__item-title h1"> <?= $c['title'] ?></h2>
            <div class="collections__item-body">

                <?php

                $collectionContent = $m->getContentOnCollection($c['id']);

                foreach ($collectionContent as $cc) {
                    $count = 0;
                    ?>

                    <a class="collections__item-link" href="/views/content?id=<?= $cc['id'] ?>&&type=<?= $cc['type'] ?>">
                        <img src="<?= '/' . $cc['cover'] ?>" class="collections__item-link-img"
                             alt="<?= $cc['title'] ?>">
                    </a>
                    <?php if ($count >= 10) {
                        break;
                    }
                    $count++;
                }
                if (count($collectionContent) > 10){
                ?>
                <form action="/views/collection/index.php" method="post">
                    <button type="submit" class="collections__item-btn" name="collection" value="<?= $c['id'] ?>">
                        <img src="/assets/icons/next.svg" alt="" class="collections__item-btn-img">СМОТРЕТЬ БОЛЬШЕ
                    </button> <?php
                    } ?>
            </div>
        </div>

    <?php } ?>

    <!-- Подборка со всеми видео данного типа -->

    <?php

    switch ($_GET['type']) {
        case 'фильм':
            $allContentName = 'Все фильмы';
            break;
        case 'сериал':
            $allContentName = 'Все сериалы';
            break;
        case 'спортивное событие':
            $allContentName = 'Все спортивные события';
            break;
        case "музыкальный клип":
            $allContentName = 'Все клипы';
            break;
        default:
            $allContentName = '';
    }

    ?>

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
