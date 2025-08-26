<?php

/* @var $m */

$pageTitle = 'MEGFILMS || КЛИПЫ';
$link = 'music';

require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$collections = $m->getClipOnlyCollections();
$genres = $m->getClipGenres();
$years = $m->getClipYears();
$countries = $m->getClipCountries();

?>

<form class="filter" action="#" method="post">
    <label class="filter__select-label">
        Сортировать по:
        <select name="orderBy" class="filter__select">
            <option value="year_desc">Дате добавления</option>
            <option value="views_desc">Просмотрам</option>
            <option value="raiting_desc">Оценкам</option>
        </select>
    </label>
    <label class="filter__select-label">
        Жанр:
        <select name="genre" class="filter__select">
            <option value="" selected>Все жанры</option>
            <?php foreach ($genres as $g): ?>
                <option value="<?= $g['id'] ?>"><?= $g['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label class="filter__select-label">
        Год выпуска:
        <select name="year" class="filter__select">
            <option value="" selected>Все года</option>
            <?php foreach ($years as $y): ?>
                <option value="<?= $y ?>"><?= $y ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label class="filter__select-label">
        Страна:
        <select name="country" class="filter__select">
            <option value="">Все страны</option>
            <?php foreach ($countries as $c): ?>
                <option value="<?= $c ?>"><?= $c ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button class="button filter__button">Применить</button>
</form>
<?php if (isset($_POST['genre']) || isset($_POST['country']) || isset($_POST['year'])) {
    $genre = empty($_POST['genre']) ? null : (int)$_POST['genre'];
    $country = empty($_POST['country']) ? null : $_POST['country'];
    $year = empty($_POST['year']) ? null : (int)$_POST['year'];
    $orderBy = empty($_POST['orderBy']) ? 'year_desc' : $_POST['orderBy'];
    $filtered = $m->getFilteredClips($genre, $country, $year, $orderBy);
    if (count($filtered) > 0) {
        require_once $_SERVER['DOCUMENT_ROOT'].'/filtered.php';
    } else {
        echo "<h1 style='text-align: center'>Клипов по вашим запросам не найдено</h1>";
    } ?>
    <?php
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/collections.php';
?> <section class="collections">
    <div class="collections__item">
        <h2 class="collections__item-title h1"> Все клипы </h2>
        <div class="collections__item-body.grid">

            <?php
            $allFilms = $m->getAllClips();
            foreach ($allFilms as $fr) { ?>

            <form class="collections__item-link" action="video.php" method="post">
                <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;"
                        value="<?= $fr['id'] ?>" name="filmFakeLink">
                    <img src="<?=  '/' . $fr['cover'] ?>" class="collections__item-link-img" alt="<?= $fr['title'] ?>">
                </button>
            </form>

            <?php }  ?>
        </div>
    </div>
    </section>

<?php

}

require_once $_SERVER['DOCUMENT_ROOT'].'/footer.php';

?>
