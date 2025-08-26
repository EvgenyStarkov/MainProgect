<footer class="footer">
    <div class="footer__inner">
        <div class="footer__logo logo">
            <img src="../../assets/icons/logo.svg" alt="" class="logo__img">
            <span class="logo__title">MEGAFILM </span>
        </div>
        <div class="footer__soc1als">
            <h1 class="footer__soc1als-title">Мы в соц.сетях</h1>
            <div class="footer__soc1als-links">
                <a href="" class="footer_soc1als-link"><img src="../../assets/icons/Telegram.svg" alt=""
                                                            class="footer__soc1als-img"></a>
                <a href="" class="footer_soc1als-link"><img src="../../assets/icons/Instagram.svg" alt=""
                                                            class="footer__soc1als-img"></a>
                <a href="" class="footer_soc1als-link"><img src="../../assets/icons/VK%20com.svg" alt=""
                                                            class="footer__soc1als-img"></a>
                <a href="" class="footer_soc1als-link"><img src="../../assets/icons/Gmail.svg" alt=""
                                                            class="footer__soc1als-img"></a>
            </div>
            <p class="footer__soc1als-tel">В службу поддержки вы можете обратится по данному номеру
                телефона <br>
                <span class="footer__soc1als-tel-accent">+7 (999) 999-99-99</span>
            </p>
        </div>
        <div class="footer__main">
            Megafilms — ваш премиальный медиацентр для всей семьи: фильмы, сериалы, спортивные
            трансляции,
            музыкальные клипы и ТВ-эфир.<br>
            Эксклюзивный контент в HD/4K/HDR. Персонализированные подборки. Идеальная картинка и
            звук на любом
            устройстве.<br>
            Смотрите вместе с близкими — безопасная среда и контент на все возрасты.<br>
            <br>
            Фильмы, сериалы, ТВ-эфир и спортивные события предоставляются только для личного
            просмотра. Все права
            защищены.<br>
            <br>
            © 2025 Megafilms<br>
            ООО "Мегафильм Онлайн", ОГРН 1234567890, ИНН 1234567890<br>
            Юридический адрес: 123456, г. Москва, ул. Киношная, д. 7<br>
            <br>
            <a href="" class="footer__main-link">Пользовательское соглашение</a> | <a href=""
                                                                                      class="footer__main-link">Политика
                конфидициальности</a> | <a href=""
                                           class="footer__main-link">Реквизиты</a>
        </div>
    </div>
</footer>
<div class="register">
    <form action="../../controllers/UserController.php" name="register" class="register__body" method="post"
          enctype="multipart/form-data">
        <div class="register__body-header">
            <h1 class="register__title"><b>ВАШИ ДАННЫЕ</b></h1>
            <label class="register__close-label" for="closeRegister">Закрыть окно
                регистрации</label>
            <button class="register__close" type="button" id="closeRegister">
                <img src="../../assets/icons/Close.svg" alt="" class="register__close-img">
            </button>
        </div>
        <ul class="register__body-list">
            <li class="register__body-item">
                <label class="register__label">
                    Аватар
                    <input type="file" class="register__input file" name="avatar"
                           id="rName" placeholder="Введите свое Фио">
                </label>
            </li>
            <li class="register__body-item">
                <label class="register__label">
                    ФИО
                    <input type="text" class="register__input" name="reName" value="<?= $user['name'] ?>"
                           id="rName" placeholder="Введите свое Фио">
                </label>
            </li>
            <li class="register__body-item">
                <label class="register__label">
                    НИКНЕЙМ
                    <input type="text" class="register__input" name="reUserName" value="<?= $user['username'] ?>"
                           id="rUserName" placeholder="Введите свое имя пользователя">
                </label>
            </li>
            <li class="register__body-item">
                <label class="register__label">
                    ЭЛЕКТРОННАЯ ПОЧТА
                    <input type="email" class="register__input" name="reEmail" value="<?= $user['email'] ?>"
                           id="rEmail" placeholder="Введите свою электоную почту">
                </label>
            </li>
            <li class="register__body-item">
                <label class="register__label">
                    НОМЕР ТЕЛЕФОНА
                    <input type="tel" class="register__input" name="reTel" id="rTel" value="<?= $user['tel'] ?>"
                           placeholder="Введите свой номер телефона">
                </label>
            </li>
            <li class="register__body-item">
                <label class="register__label">
                    ПАРОЛЬ
                    <input type="text" class="register__input" name="rePassword" value="<?= $user['password'] ?>"
                           id="rPassword" placeholder="Введите свой пароль">
                </label>
            </li>
        </ul>
        <button type="submit" name="reId" value="<?= $_SESSION['userId'] ?>" class="register__submit button">
            Изменить
        </button>
        <button type="submit" name="deId" value="<?= $_SESSION['userId'] ?>" class="register__submit button">
            удалить аккаунт
        </button>
    </form>
</div>
</body>

</html>