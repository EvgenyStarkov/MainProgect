<?php

if(isset($collection)){

    /*  @var $content */
    /*  @var $collection */

    require_once  'header.php';



?>


    <section class="collections">
            <div class="collections__item">
                <h2 class="collections__item-title h1"> <?= $collection['title'] ?></h2>
                <div class="collections__item-body grid">
    <?php
                    foreach ($content as $f) {
                        ?>

                        <a class="collections__item-link" href="/views/content/?id=<?= $f['id']?>"  >
                                <img src="<?= $f['cover'] ?>" class="collections__item-link-img" alt="<?= $f['title']?>">
                        </a>

                        <?php } ?>

                </div>
            </div>
    </section>

<?php

require_once  'footer.php';

} else{
    require_once $_SERVER['DOCUMENT_ROOT'] . '/views/404/index.php'; // Добавлено подключение  страницы ошибки если не указан "id" в URL
}
?>