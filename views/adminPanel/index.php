<?php
/* Этот файл — ваш шаблон админки (views/adminPanel.php).
   Я сохранил структуру вашего оригинального шаблона, добавил кнопку удаления
   для каждого элемента коллекции и корректные hidden-поля в форме.
*/

/* @var $user */
/* @var $content */
/* @var $episodes */
/* @var $series */
/* @var $collections */
/* @var $heroSlides */
/* @var $advertising */
/* @var $subscriptions */

if ($user['role'] === 'admin') {

    require_once 'header.php';

    ?>
    <!-- content -->
    <section class="content-setting">
        <h1 class="content-setting__table-title"> Таблица контента </h1>
        <table class="content-setting__table">
            <tr>
                <th class="content-setting__table-item"> ID</th>
                <th class="content-setting__table-item"> TITLE</th>
                <th class="content-setting__table-item"> DESCRIPTION</th>
                <th class="content-setting__table-item"> TRAILER</th>
                <th class="content-setting__table-item"> VIDEO</th>
                <th class="content-setting__table-item"> VIEWS</th>
                <th class="content-setting__table-item"> RATING</th>
                <th class="content-setting__table-item"> YEAR</th>
                <th class="content-setting__table-item"> COUNTRY</th>
                <th class="content-setting__table-item"> COVER</th>
                <th class="content-setting__table-item"> GENRES</th>
                <th class="content-setting__table-item"> TYPE</th>
                <th class="content-setting__table-item"> ИЗМЕНИТЬ</th>
                <th class="content-setting__table-item"> УДАЛИТЬ</th>
            </tr>
            <?PHP
            foreach ($content as $c) { ?>
                <tr>
                    <form action="/views/adminPanel/" enctype="multipart/form-data" method="post">
                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($c['id'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReIdContent"
                                    class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReTitleContent"
                                    class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($c['description'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReDescriptionContent"
                                    class="content-setting__table-input"></td>

                        <td class="content-setting__table-item">
                            <video class="content-setting__table-video" autoplay muted>
                                <source src="<?= htmlspecialchars($c['trailer'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                NULL
                            </video>
                            <input type="file" name="adminReTrailerContent" class="content-setting__table-input"></td>

                        <td class="content-setting__table-item">
                            <video class="content-setting__table-video" autoplay muted>
                                <source src="<?= htmlspecialchars($c['video'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                NULL
                            </video>
                            <input type="file" name="adminReVideoContent" class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($c['views'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReViewsContent"
                                    class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($c['rating'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReRatingContent"
                                    class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($c['year'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReYearContent"
                                    class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($c['country'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReCountryContent"
                                    class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><img
                                    src="<?= htmlspecialchars($c['cover'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt=""
                                    class="content-setting__table-video">
                            <input type="file" name="adminReCoverContent" class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($c['genres'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReGenresContent"
                                    class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($c['type'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReTypeContent"
                                    class="content-setting__table-input"></td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminReContent" value="1" type="submit">Изменить</button>
                        </td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminDeleteContent" value="1" type="submit"> Удалить
                            </button>
                        </td>
                    </form>
                </tr>


            <?php } ?>
        </table>

        <form class="content-setting__add-form" action="/views/adminPanel/" method="post" enctype="multipart/form-data">
            <h1> Добавить контент</h1>
            <div class="content-setting__add-body">
                <div class="content-setting__add-item">
                    <input placeholder="Введите название"
                           name="adminAddTitleContent">
                    <input placeholder="Введите описание"
                           name="adminAddDescriptionContent">
                    <input placeholder="Введите рейтинг (1 до 10)"
                           name="adminAddRatingContent">
                    <input placeholder="Введите год"
                           name="adminAddYearContent">
                    <input placeholder="Введите страну"
                           name="adminAddCountryContent">
                    <input placeholder="Введите жанры"
                           name="adminAddGenresContent">
                </div>
                <div class="content-setting__add-item">
                    <label>
                        Видео :
                        <input type="file" name="adminAddVideoContent">
                    </label>
                    <label>
                        Тизер :
                        <input type="file" name="adminAddTrailerContent">
                    </label>
                    <label>
                        Обложка :
                        <input type="file" name="adminAddCoverContent">
                    </label>
                </div>
                <label class="content-setting__add-item">
                    Тип контента :
                    <select name="adminAddTypeContent">
                        <option value="фильм">фильм</option>
                        <option value="сериал">сериал</option>
                        <option value="спортивное событие">спортивное событие</option>
                        <option value="музыкальный клип">музыкальный клип</option>
                    </select>
                </label>
            </div>
            <button class="button" type="submit" name="adminAddContent" value="1">Добавить</button>
        </form>
    </section>
    <!-- episode -->
    <section class="content-setting">
        <h1 class="content-setting__table-title"> Таблица эпизодов </h1>
        <table class="content-setting__table">
            <tr>
                <th class="content-setting__table-item"> ID</th>
                <th class="content-setting__table-item"> NUMBER</th>
                <th class="content-setting__table-item"> SEASON</th>
                <th class="content-setting__table-item"> VIDEO</th>
                <th class="content-setting__table-item"> CONTENT_ID</th>
                <th class="content-setting__table-item"> ИЗМЕНИТЬ</th>
                <th class="content-setting__table-item"> УДАЛИТЬ</th>
            </tr>
            <?PHP
            foreach ($episodes as $e) { ?>
                <tr>
                    <form action="/views/adminPanel/" enctype="multipart/form-data" method="post">
                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($e['id'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReIdEpisode"
                                    class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($e['number'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReNumberEpisode"
                                    class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($e['season'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReSeasonEpisode"
                                    class="content-setting__table-input"></td>
                        <td class="content-setting__table-item">
                            <video class="content-setting__table-video" autoplay muted>
                                <source src="<?= htmlspecialchars($e['video'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                NULL
                            </video>
                            <input type="file" name="adminReVideoEpisode" class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($e['content_id'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReContentIdEpisode"
                                    class="content-setting__table-input"></td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminReEpisode" value="1" type="submit">Изменить</button>
                        </td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminDeleteEpisode" value="1" type="submit"> Удалить</button>
                        </td>
                    </form>
                </tr>


            <?php } ?>
        </table>

        <form class="content-setting__add-form" action="/views/adminPanel/" method="post" enctype="multipart/form-data">
            <h1> Добавить контент</h1>
            <div class="content-setting__add-body">
                <input placeholder="Введите номер"
                       name="adminAddNumberEpisode">
                <input placeholder="Введите сезон"
                       name="adminAddSeasonEpisode">
                <label>
                    Видео :
                    <input type="file" name="adminAddVideoEpisode">
                </label>
                <label class="content-setting__add-item">
                    Сериал :
                    <select name="adminAddSeriesEpisode">
                        <?php foreach ($series as $s) { ?>
                            <option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['title'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php } ?>
                    </select>
                </label>
            </div>
            <button class="button" type="submit" name="adminAddEpisode" value="1">Добавить</button>
        </form>
    </section>
    <!-- collections -->
    <section class="content-setting">
        <h1 class="content-setting__table-title"> Таблица коллекций </h1>
        <table class="content-setting__table">
            <tr>
                <th class="content-setting__table-item"> ID</th>
                <th class="content-setting__table-item"> TITLE</th>
                <th class="content-setting__table-item"> CONTENT</th>
                <th class="content-setting__table-item"> ИЗМЕНИТЬ</th>
                <th class="content-setting__table-item"> УДАЛИТЬ</th>
            </tr>
            <?PHP
            foreach ($collections as $cl) { ?>
                <tr>
                    <form action="/views/adminPanel/" enctype="multipart/form-data" method="post">
                        <td class="content-setting__table-item"><input value="<?= (int)$cl['id'] ?: 'NULL' ?>"
                                                                       name="adminReIdCollection"
                                                                       class="content-setting__table-input"></td>

                        <td class="content-setting__table-item"><input
                                    value="<?= htmlspecialchars($cl['title'], ENT_QUOTES, 'UTF-8') ?: 'NULL' ?>"
                                    name="adminReTitleCollection"
                                    class="content-setting__table-input"></td>
                        <td class="content-setting__table-item">
                            <?php
                            $ids = $cl['contentId'] ?? [];
                            $titles = [];
                            if (!empty($cl['content'])) {
                                $titles = explode(' ,', $cl['content']);
                            }
                            foreach ($ids as $i => $cid) {
                                $title = isset($titles[$i]) ? htmlspecialchars($titles[$i], ENT_QUOTES, 'UTF-8') : 'Без названия';
                                ?>
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                                    <span><?= $title ?></span>
                                    <form style="display:inline;margin:0;padding:0;" method="post"
                                          action="/views/adminPanel/">
                                        <input type="hidden" name="adminRemoveCollectionId"
                                               value="<?= (int)$cl['id'] ?>">
                                        <input type="hidden" name="adminRemoveContentId" value="<?= (int)$cid ?>">
                                        <button class="button" name="adminRemoveContentFromCollection" value="1"
                                                type="submit">Удалить
                                        </button>
                                    </form>
                                </div>
                                <?php
                            }
                            ?>
                        </td>

                        <td class="content-setting__table-item">
                            <button class="button" name="adminReCollection" value="1" type="submit">Изменить</button>
                        </td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminDeleteCollection" value="1" type="submit"> Удалить
                            </button>
                        </td>
                    </form>
                </tr>


            <?php } ?>
        </table>

        <form class="content-setting__add-form" action="/views/adminPanel/" method="post" enctype="multipart/form-data">
            <h1> Добавить коллекцию</h1>
            <div class="content-setting__add-body">
                <input placeholder="Введите название"
                       name="adminAddTitleCollection">
            </div>
            <button class="button" type="submit" name="adminAddCollection" value="1">Добавить</button>
        </form>
        <form class="content-setting__add-form" action="/views/adminPanel/" method="post" enctype="multipart/form-data">
            <h1> Добавить контент в коллекцию</h1>
            <label class="content-setting__add-item">
                Коллекция :
                <select name="adminAddContentToCollectionOne">
                    <?php foreach ($collections as $с) { ?>
                        <option value="<?= (int)$с['id'] ?>"><?= htmlspecialchars($с['title'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php } ?>
                </select>
            </label>
            <label class="content-setting__add-item">
                Контент :
                <select name="adminAddContentToCollectionTwo">
                    <?php foreach ($content as $c) { ?>
                        <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php } ?>
                </select>
            </label>
            <button class="button" type="submit" name="adminAddContentToCollection" value="1">Добавить</button>
        </form>
    </section>
    <!-- addvertising-->
    <section class="content-setting">
        <h1 class="content-setting__table-title"> Таблица рекламы коллекций </h1>
        <table class="content-setting__table">
            <tr>
                <th class="content-setting__table-item"> ID</th>
                <th class="content-setting__table-item"> BACKGROUND</th>
                <th class="content-setting__table-item"> MOBILE BACKGROUND</th>
                <th class="content-setting__table-item"> TEXT</th>
                <th class="content-setting__table-item"> COLLECTION ID</th>
                <th class="content-setting__table-item"> ИЗМЕНИТЬ</th>
                <th class="content-setting__table-item"> УДАЛИТЬ</th>
            </tr>
            <?PHP foreach ($advertising as $a) { ?>
                <tr>
                    <form action="/views/adminPanel/" enctype="multipart/form-data" method="post">
                        <td class="content-setting__table-item">
                            <input value="<?= (int)$a['id'] ?>" name="adminReIdAdvertising"
                                   class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <img src="<?= htmlspecialchars($a['background'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt=""
                                 class="content-setting__table-video">
                            <input type="file" name="adminReBackgroundAdvertising"
                                   class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <img src="<?= htmlspecialchars($a['mobile_background'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                 alt="" class="content-setting__table-video">
                            <input type="file" name="adminReMobileBackgroundAdvertising"
                                   class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <input value="<?= htmlspecialchars($a['text'], ENT_QUOTES, 'UTF-8') ?>"
                                   name="adminReTextAdvertising" class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <select name="adminReCollectionAdvertising" class="content-setting__table-input">
                                <?php foreach ($collections as $c) { ?>
                                    <option value="<?= (int)$c['id'] ?>" <?= $c['id'] == $a['collection_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminReAdvertising" value="1" type="submit">Изменить
                            </button>
                        </td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminDeleteAdvertising" value="1" type="submit">Удалить
                            </button>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        </table>
        <form class="content-setting__add-form" action="/views/adminPanel/" method="post" enctype="multipart/form-data">
            <h1> Добавить рекламу коллекции</h1>
            <div class="content-setting__add-body">
                <input placeholder="Введите текст" name="adminAddTextAdvertising">
                <label class="content-setting__add-item">
                    Коллекция:
                    <select name="adminAddCollectionAdvertising">
                        <?php foreach ($collections as $c) { ?>
                            <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php } ?>
                    </select>
                </label>
                <label>
                    Фон:
                    <input type="file" name="adminAddBackgroundAdvertising">
                </label>
                <label>
                    Мобильный фон:
                    <input type="file" name="adminAddMobileBackgroundAdvertising">
                </label>
            </div>
            <button class="button" type="submit" name="adminAddAdvertising" value="1">Добавить</button>
        </form>
    </section>
    <!-- slides -->
    <section class="content-setting">
        <h1 class="content-setting__table-title"> Таблица слайдов </h1>
        <table class="content-setting__table">
            <tr>
                <th class="content-setting__table-item"> ID</th>
                <th class="content-setting__table-item"> TITLE</th>
                <th class="content-setting__table-item"> TEXT</th>
                <th class="content-setting__table-item"> VIDEO</th>
                <th class="content-setting__table-item"> CONTENT ID</th>
                <th class="content-setting__table-item"> ИЗМЕНИТЬ</th>
                <th class="content-setting__table-item"> УДАЛИТЬ</th>
            </tr>
            <?PHP foreach ($heroSlides as $hs) { ?>
                <tr>
                    <form action="/views/adminPanel/" enctype="multipart/form-data" method="post">
                        <td class="content-setting__table-item">
                            <input value="<?= (int)$hs['id'] ?>" name="adminReIdHeroSlide"
                                   class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <input value="<?= htmlspecialchars($hs['title'], ENT_QUOTES, 'UTF-8') ?>"
                                   name="adminReTitleHeroSlide" class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <input value="<?= htmlspecialchars($hs['text'], ENT_QUOTES, 'UTF-8') ?>"
                                   name="adminReTextHeroSlide" class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <video class="content-setting__table-video" autoplay muted>
                                <source src="<?= htmlspecialchars($hs['video'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                NULL
                            </video>
                            <input type="file" name="adminReVideoHeroSlide" class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <input value="<?= (int)$hs['content_id'] ?>" name="adminReContentHeroSlide"
                                   class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminReHeroSlide" value="1" type="submit">Изменить</button>
                        </td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminDeleteHeroSlide" value="1" type="submit">Удалить</button>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        </table>

        <form class="content-setting__add-form" action="/views/adminPanel/" method="post" enctype="multipart/form-data">
            <h1> Добавить герой-слайд</h1>
            <div class="content-setting__add-body">
                <input placeholder="Введите заголовок" name="adminAddTitleHeroSlide">
                <input placeholder="Введите текст" name="adminAddTextHeroSlide">
                <label class="content-setting__add-item">
                    Контент:
                    <select name="adminAddContentHeroSlide">
                        <?php foreach ($content as $c) { ?>
                            <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php } ?>
                    </select>
                </label>
                <label>
                    Видео:
                    <input type="file" name="adminAddVideoHeroSlide">
                </label>
            </div>
            <button class="button" type="submit" name="adminAddHeroSlide" value="1">Добавить</button>
        </form>
    </section>
    <!-- $subscriptions -->
    <section class="content-setting">
        <h1 class="content-setting__table-title"> Таблица подписок </h1>
        <table class="content-setting__table">
            <tr>
                <th class="content-setting__table-item"> ID</th>
                <th class="content-setting__table-item"> TITLE</th>
                <th class="content-setting__table-item"> DESCRIPTION</th>
                <th class="content-setting__table-item"> PRICE</th>
                <th class="content-setting__table-item"> CONTENT</th>
                <th class="content-setting__table-item"> ИЗМЕНИТЬ</th>
                <th class="content-setting__table-item"> УДАЛИТЬ</th>
            </tr>
            <?PHP foreach ($subscriptions as $s) { ?>
                <tr>
                    <form action="/views/adminPanel/" method="post">
                        <td class="content-setting__table-item">
                            <input value="<?= (int)$s['id'] ?>" name="adminReIdSubscription"
                                   class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <input value="<?= htmlspecialchars($s['title'], ENT_QUOTES, 'UTF-8') ?>"
                                   name="adminReTitleSubscription" class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <input value="<?= htmlspecialchars($s['description'], ENT_QUOTES, 'UTF-8') ?>"
                                   name="adminReDescriptionSubscription" class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <input value="<?= htmlspecialchars($s['price'], ENT_QUOTES, 'UTF-8') ?>"
                                   name="adminRePriceSubscription" class="content-setting__table-input">
                        </td>
                        <td class="content-setting__table-item">
                            <?php
                            $ids = $s['contentId'] ?? [];
                            $titles = [];
                            if (!empty($s['content'])) {
                                $titles = explode(' ,', $s['content']);
                            }
                            foreach ($ids as $i => $cid) {
                                $title = isset($titles[$i]) ? htmlspecialchars($titles[$i], ENT_QUOTES, 'UTF-8') : 'Без названия';
                                ?>
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                                    <span><?= $title ?></span>
                                    <form style="display:inline;margin:0;padding:0;" method="post"
                                          action="/views/adminPanel/">
                                        <input type="hidden" name="adminRemoveSubscriptionId"
                                               value="<?= (int)$s['id'] ?>">
                                        <input type="hidden" name="adminRemoveContentId" value="<?= (int)$cid ?>">
                                        <button class="button" name="adminRemoveContentFromSubscription" value="1"
                                                type="submit">Удалить
                                        </button>
                                    </form>
                                </div>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminReSubscription" value="1" type="submit">Изменить</button>
                        </td>
                        <td class="content-setting__table-item">
                            <button class="button" name="adminDeleteSubscription" value="1" type="submit">Удалить
                            </button>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        </table>

        <form class="content-setting__add-form" action="/views/adminPanel/" method="post">
            <h1> Добавить подписку</h1>
            <div class="content-setting__add-body">
                <input placeholder="Введите название" name="adminAddTitleSubscription">
                <input placeholder="Введите описание" name="adminAddDescriptionSubscription">
                <input placeholder="Введите цену" name="adminAddPriceSubscription">
            </div>
            <button class="button" type="submit" name="adminAddSubscription" value="1">Добавить</button>
        </form>

        <form class="content-setting__add-form" action="/views/adminPanel/" method="post">
            <h1> Добавить контент в подписку</h1>
            <label class="content-setting__add-item">
                Подписка:
                <select name="adminAddContentToSubscriptionOne">
                    <?php foreach ($subscriptions as $s) { ?>
                        <option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['title'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php } ?>
                </select>
            </label>
            <label class="content-setting__add-item">
                Контент:
                <select name="adminAddContentToSubscriptionTwo">
                    <?php foreach ($content as $c) { ?>
                        <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php } ?>
                </select>
            </label>
            <button class="button" type="submit" name="adminAddContentToSubscription" value="1">Добавить</button>
        </form>
    </section>
    <!-- home hero background-->
    <section class="content-setting">
        <form class="content-setting__add-form" action="/views/adminPanel/" method="post" enctype="multipart/form-data">
            <h1> Изменить заставку на главной </h1>
            <div class="content-setting__add-body">
                <label>
                    Видео:
                    <input type="file" name="adminReHomeVideo">
                </label>
            </div>
            <button class="button" type="submit" name="adminReHomeVideoSubmit" value="1">Добавить</button>
        </form>
    </section>

    <?php

    require_once 'footer.php';

} else {

    include $_SERVER['DOCUMENT_ROOT'] . '/views/404/index.php';

}
