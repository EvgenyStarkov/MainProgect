<?php

require_once 'header.php';

/* @var $m */

$backgroundVideo = '';

?>

    <!-- hero -->

    <video class="hero__background" autoplay muted loop playsinline>
        <source src="/assets/video/HomeBackground.mp4" type="video/mp4">
        <h1>Увы рекламное видео не загрузилось</h1>
    </video>

<?php

/* @var $slides */

if (count($slides) > 0) {

    ?>
    <div class="hero__more">
        <button class="hero__more-btn button" id="moreBtn"> Подробнее</button>
    </div>
    <div class="hero__more-window">
        <button class="hero__more-window-close"><img src="/assets/icons/Close.svg" alt=""
                                                     class="hero__more-window-close-img"></button>
        <div class="hero__slider">
            <button class="hero__slider-left-btn"><img src="/assets/icons/next.svg"
                                                       alt="Преведущий слайд" class="hero__slider-btn-img"></button>


            <div class="hero__slides">
                <?php
                foreach ($slides as $sl) {
                    ?>

                    <div class="hero__slide">
                        <h2 class="hero__slide-title "><?= $sl['title'] ?></h2>
                        <?php if ($sl['video'] != 'none') { ?>
                            <video class="hero__slide-video" autoplay muted loop playsinline>
                                <source src="<?=  $sl['video'] ?>" type="video/mp4">
                                <h1>Увы рекламное видео не загрузилось</h1>
                            </video>
                        <?php } ?>
                        <p class="hero__slide-text"><?= $sl['text'] ?></p>
                        <?php if ($sl['content_id'] != 'none') { ?>
                            <a class="button hero__slide-link" href="/views/content/?id=<?= $sl['content_id']?>" >
                                    Перейти
                                </a>
                        <?php } ?>
                    </div>

                    <?php
                }
                ?>
            </div>

            <button class="hero__slider-right-btn">
                <img src="/assets/icons/next.svg" alt="Следующий слайд" class="hero__slider-btn-img">
            </button>

            <div class="hero__slider-pagination">
                <?php
                $pagNum = 1;

                foreach ($slides as $sl) {
                    if ($pagNum == 1) {
                        $pagActive = 'is-active';
                    } else {
                        $pagActive = '';
                    }
                    ?>
                    <div class="hero__slider-pagination-item <?= $pagActive ?>" data-set="<?= $pagNum ?>"></div>
                    <?php
                    $pagNum++;
                } ?>
            </div>
        </div>
    </div>

<?php } ?>

    </section>

<?php

/* @var $hits */

foreach ($hits as $h) {
    ?>
    <section class="fresh-hits"
             style='background-image: url("<?= './' . $h['background'] ?>"); @media (max-width: 800px) {
                     background-image: url("<?= './' . $h['mobile_background'] ?>")
                     }'>
        <div class="fresh-hits__inner">
            <div class="fresh-hits__content">
                <h1 class="fresh-hits__title"><?= $h['title'] ?></h1>
                <p class="fresh-hits__text"><?= $h['text'] ?></p>
            </div>
            <div class="fresh-hits__list">
                <?php
                foreach ($h['content'] as $cc) {
                    ?>
                    <a class="collections__item-link"
                       href="/views/content?id=<?= $cc['id'] ?>">
                        <img src="<?= $cc['cover'] ?>" alt="" class="fresh-hits__item">
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
<?php } ?>

    <nav class="menu">
        <a class="menu__item" href="/views/contents?type=фильм"><img src="/assets/icons/film.svg" alt="Фильмы"
                                                                     class="menu__item-img">
            <h2 class="menu__item-title h1">Фильмы</h2>
        </a>
        <a class="menu__item" href="/views/contents?type=сериал"><img src="/assets/icons/Serial.svg" alt="Сериалы"
                                                                      class="menu__item-img">
            <h2 class="menu__item-title h1">Сериалы</h2>
        </a>
        <a class="menu__item" href="/views/contents?type=музыкальный клип"><img src="/assets/icons/Music.svg"
                                                                                alt="Клипы" class="menu__item-img">
            <h2 class="menu__item-title h1">Клипы</h2>
        </a>
        <a class="menu__item" href="/views/contents?type=спортивное событие"><img src="/assets/icons/sport.svg"
                                                                                  alt="Спорт" class="menu__item-img">
            <h2 class="menu__item-title h1">Спорт</h2>
        </a>
    </nav>

<?php

require_once 'collections.php';
require_once 'footer.php';
