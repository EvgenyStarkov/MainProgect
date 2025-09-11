<?php

if (isset($video['id'])) {

   /* @var $contentType */

    require_once 'header.php';

    /* @var $video */
    /* @var $user */
    /* @var $seasons */
    /* @var $episodes */
    /* @var $firstEpisode */
    /* @var $members */
    /* @var $recommendations */
    /* @var $user */
    /* @var $comments */

    if ($contentType != 'музыкальный клип') { ?>
        <video class="hero__film-background" autoplay muted loop playsinline>
            <source src="/<?= $video['trailer'] ?>" type="video/mp4">
            <h1>Увы рекламное видео не загрузилось</h1>
        </video>
        <div class="hero__info">
            <div class="hero__info-inner">
                <h2 class="hero__info-title "><?= $video['title'] ?> </h2>
                <p class="hero__info-text"><?= $video['description'] ?></p>

                <?php
                if (isset($userContentSubscriptions)  || !isset($contentSubscriptions)) { ?>
                    <button class="button hero__info-link">Смотреть</button>
                <?php } else { ?>
                    <div class="subscription-required">
                        <?php if ($user['id'] === 0) { ?>
                            <p>Данный контент являетс платным, пожалуйста зарегистрируйтесь или авторизуйтесь</p>
                        <?php } else { ?>
                            <p> Данный контент являетс платным,для просмотра его требуется одна из подписок:</p>
                            <div class="subscription-list">
                                <?php foreach ($contentSubscriptions as $subscription) { ?>
                                    <div class="subscription-item">
                                        <p><?= htmlspecialchars($subscription['title']) ?></p>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div> <?php
    } ?>

    <!-- player -->

    <div class="video-player">
        <div class="video-player__inner">
          <?php  if ($contentType != 'музыкальный клип' || isset($userContentSubscriptions)  || !isset($contentSubscriptions)) {
              if($contentType == 'сериал'){?>
                  <label class="video-player__serias">
                      <select class="video-player__serias-list" id="seasonSelect">

                          <?php


                           foreach ($seasons as $seasonNum => $seasonEpisodes){ ?>

                              <option value="<?= $seasonNum ?>">Сезон <?= $seasonNum ?></option>

                          <?php } ?>
                      </select>
                  </label>
                  <label class="video-player__serias">
                      <select class="video-player__serias-list" id="episodeSelect">
                          <?php if (!empty($seasons['1'])){ ?>
                              <?php foreach ($seasons['1'] as $episode){ ?>
                                  <option value="<?= $episode['id'] ?>">Серия <?= $episode['number'] ?></option>
                              <?php }}
                          else { ?>
                              <option value="0">Нет серий</option>
                          <?php } ?>
                      </select>
                  </label>
                  <video class="video-player__video" id="mainVideoPlayer">
                      <source src="<?= '/' . $firstEpisode['video'] ?>" type="video/mp4">
                      <h1>Видео не загружено</h1>
                  </video>
              <script>
                  // Передаем данные в правильном формате
                  window.seriesData = {
                      episodes: <?= json_encode($episodes); ?>,
                      seasons: <?= json_encode($seasons) ?>
                  };
              </script>
              <?php } else {?>

              <video class="video-player__video">
                <source src="/<?= $video['video'] ?>" type="video/mp4">
                <h1>Увы рекламное видео не загрузилось</h1>
            </video>
                  <?php } ?>
            <div class="video-player__controls">
                <div class="video-player__controls-item play">
                    <img src="/assets/icons/Play.svg" alt="" class="video-player__controls-img">
                </div>
                <div class="video-player__controls-item progress">
                    <input type="range" class="video-player__controls-input">
                </div>
                <div class="video-player__controls-item time">00:00</div>
                <div class="video-player__controls-item audio">
                    <img src="/assets/icons/Sound.svg" alt="" class="video-player__controls-img">
                    <input type="range" class="video-player__controls-input">
                </div>
                <div class="video-player__controls-item full-scrin">
                    <img src="/assets/icons/Full%20Screen.svg" alt="" class="video-player__controls-img">
                </div>
            </div>
        <?php } else { ?>
              <div class="subscription-required">
                  <?php if ($user['id'] === 0) { ?>
                      <p>Данный контент являетс платным, пожалуйста зарегистрируйтесь или авторизуйтесь</p>
                  <?php } else { ?>
                      <p> Данный контент являетс платным,для просмотра его требуется одна из подписок:</p>
                      <div class="subscription-list">
                          <?php foreach ($contentSubscriptions as $subscription) { ?>
                              <div class="subscription-item">
                                  <p><?= htmlspecialchars($subscription['title']) ?></p>
                              </div>
                          <?php } ?>
                      </div>
                  <?php } ?>
              </div>
          <?php } ?>
        </div>
    </div>
    </section>

    <!-- Характиристики контента-->

    <section class="film-stats">
        <div class="film-stats__item"><?= $video['views']?></div>
        <div class="film-stats__item"><?= $video['rating']?></div>
        <div class="film-stats__item">
            Год:
            <form method="post" action="/views/contents/?type=<?= $contentType ?>">
                <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;" value="<?= $video['year'] ?>" name="year"> <?= $video['year'] ?> г </button>
            </form>
        </div>
        <div class="film-stats__item">
            Страна:
            <form method="post" action="/views/contents/?type=<?= $contentType ?>">
                <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;" value="<?= $video['country'] ?>" name="country"><?=  $video['country'] ?></button>
            </form>
        </div>
        <?php if($video['genres'] != null) {?>
        <div class="film-stats__item">
            Жанры:
            <?php

            $genres = json_decode($video['genres']) ;


            foreach ($genres as $g){ ?>
                <form method="post" action="/views/contents/?type=<?= $contentType ?>" style="margin-left: 7px">
                    <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;" value="<?= $g ?>" name="genre"> <?= $g ?> </button>
                </form>  <?php } ?></div><?php } ?>
    </section>


            <?php
            if (!empty($members)){ ?>
                <section class="film-actors">
                <h1 class="film-actors__title">Актеры:</h1>
                <div class="film-actors__inner">

                <?php foreach ($members as $member){ ?>
                    <div class="film-actors__item">
                        <img src="/<?= htmlspecialchars($member['photo']) ?>"
                             alt="<?= htmlspecialchars($member['name']) ?>"
                             class="film-actors__img">
                        <h3 class="film-actors__name"><?= htmlspecialchars($member['name']) ?></h3>
                    </div>
                <?php } ?>

                </div>
                </section>
            <?php }

            $count = 1;

            if (count($recommendations) > 0) { ?>

                    <!-- Рекомендованный контент -->

                <section class="collections">
                    <div class="collections__item">
                        <h2 class="collections__item-title h1">Смотрите так же</h2>
                        <div class="collections__item-body">

                            <?php foreach ($recommendations as $f) { ?>

                                <a class="collections__item-link" href="/views/content/?id=<?= $f['id'] ?>&type=<?= $contentType ?>">
                                        <img src="<?= '/' . $f['cover'] ?>" alt="<?= $f['title'] ?>" class="collections__item-link-img">
                                </a>

                                <?php } ?>
                        </div>
                    </div>
                </section>

                <section class="coments">

                    <?php

                    if (count($comments) > 0) { ?>
                        <h1 class="coments__title">Коментарии</h1>
                        <div class="coments__body">
                            <?php
                            foreach ($comments as $cm) {

                                $user = $cm['commentsAuthor'];
                                $avatar = "/assets/icons/Test Account.svg";

                                if ($user['avatar'] != null && $user['avatar'] != '') {
                                    $avatar = '/' . $user['avatar'];
                                } ?>

                                <div class="coments__item">
                                    <img src="<?= $avatar ?>" alt="" class="coments__item-img">
                                    <h2 class="coments__item-title"><?= htmlspecialchars($user['username']) ?></h2>
                                    <p class="coments__item-content"><?= htmlspecialchars($cm['content']) ?> </p>
                                </div>

                            <?php } ?>
                        </div> <?php } else {
                        echo "<h1>Коментариев у этого фильма нет. Оставте первый коминтарий!!!</h1>";
                    } ?>
                    <?php if ($user['role'] != 'guest') { ?>
                        <form method="post" action="/views/content/?id=<?= $video['id']?>&&type=<?= $contentType?>" >
                            <input type="text" class="coments__input" name="comContent">
                            <button type="submit" class="coments__button button">
                                Отправить коментарий
                            </button>
                        </form>
                    <?php } else { ?> <h1> Мы всегда рады вашим коментарием! Зарегистрируйтесь на сайте или войдите в уже
                        существующий аккаунт, что бы поделится своим мнением о данном фильме!!!</h1> <?php } ?>
                </section>

            <?php }

    require_once 'footer.php';

} else echo 'поста не получено';
