<?php

/* @var $contentType */
/* @var $genres */
/* @var $countries */
/* @var $years */

if (isset($contentType)) {

    require_once 'header.php';


    ?>

    <!-- Форма фильтрации -->

    <form class="filter" action="/views/contents/?type=<?= $contentType ?>" method="post">
        <label class="filter__select-label">
            Сортировать по :
            <select name="orderBy" class="filter__select">
                <option class="filter__option" value="year_desc">Дате добавления</option>
                <option class="filter__option" value="views_desc">Просмотрам</option>
                <option class="filter__option" value="rating_desc">Оценкам</option>
            </select>
        </label>
        <label class="filter__select-label">
            Жанр :
            <select name="genre" class="filter__select">
                <option class="filter__option" value="" selected>Все жанры</option>
                <?php
                foreach ($genres as $g) {?>

                    <option class="filter__option" value="<?= $g['genre'] ?>"><?= $g['genre']  ?> </option>

                <?php } ?>
            </select>
        </label>
        <label class="filter__select-label">
            Год выпуска :
            <select name="year" class="filter__select">
                <option class="filter__option" selected value="">Все года</option>
                <?php
                foreach ($years as $y) { ?>
                    <option class="filter__option" value="<?= $y['year'] ?>"><?= $y['year'] ?></option>
                <?php } ?>
            </select>
        </label>
        <label class="filter__select-label">
            Страна :
            <select name="country" class="filter__select">
                <option class="filter__option" value="">Все странны</option>
                <?php
                foreach ($countries as $ct) { ?>
                    <option class="filter__option" value="<?= $ct['country'] ?>"><?= $ct['country'] ?></option>
                <?php } ?>
            </select>
        </label>
        <button class="button filter__button">Применить</button>
    </form>

    <!-- Контент -->

    <?php

    if (isset($filtered)) {

        require_once 'filtered.php';

    } else {

        require_once 'collections.php';

    }

    require_once 'footer.php';

} else {

   require_once $_SERVER['DOCUMENT_ROOT'] . '/views/404/index.php'; // Добавлено подключение  страницы ошибки если не указан "type" в URL

}
?>