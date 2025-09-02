<?php

/* @var $filtered */
/* @var $link */
/* @var $genre */
/* @var $year */
/* @var $country */

if(count($filtered) > 0){

    $filteredTitle = '';

    $filteredPost = [$genre , $year, $country];

    foreach ($filteredPost as $fp){
        if($fp != null) {
            if ($filteredTitle == '') {
                $filteredTitle = $filteredTitle . $fp;
            } else {
                $filteredTitle = $filteredTitle . ' , ' . $fp;
            }
        }
    }

?>

<section class="collections">

    <!-- Подборка  со всеми видео, подходящими фильтрации -->

    <div class="collections__item">
        <h2 class="collections__item-title h1"><?= $filteredTitle ?> </h2>
        <div class="collections__item-body.grid">
            <?php foreach ($filtered as $fr){?>

                <a class="collections__item-link" href="/views/content?id=<?= $fr['id'] ?>&&type=<?= $fr['type'] ?>">
                    <img src="<?= '/' . $fr['cover'] ?>" class="collections__item-link-img" alt="<?= $fr['title'] ?>">
                </a>

        <?php } ?>
        </div>
    </div>
</section>

    <!-- / Подборка  со всеми видео, подходящими фильтрации -->

<?php
} else {
    echo '<h1> По вашему запросу ничего не нашлось</h1>';
}
?>