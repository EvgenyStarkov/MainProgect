<?php

/* @var $m */
/* @var $contentType */

if (isset($_GET['type'])) {

    require_once 'header.php';

    $genres = $m->getAllGenresOnType($contentType);
    $years = $m->getAllYearsOnType($contentType);
    $countries = $m->getAllCountrysOnType($contentType);
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

    if (isset($_POST['genre']) || isset($_POST['country']) || isset($_POST['year'])) {
        $genre = empty($_POST['genre']) ? null : $_POST['genre'];
        $country = empty($_POST['country']) ? null : $_POST['country'];
        $year = empty($_POST['year']) ? null : (int)$_POST['year'];
        $orderBy = empty($_POST['orderBy']) ? 'year_desc' : $_POST['orderBy'];
        $filtered = $m->getFilteredContent($genre, $country, $year, $orderBy, $contentType);

        require_once 'filtered.php';

    } else {

        require_once 'collections.php';

    } ?>

    <?php


    require_once 'footer.php';

} else {
    echo 'Вы перешли в несуществующий расдел';
}
?>