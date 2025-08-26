
console.log('video script started');

// Защита от ошибок расширений браузера
window.addEventListener('error', (event) => {
    if (event.message && event.message.includes('Could not establish connection')) {
        event.preventDefault();
        console.warn('Extension-related error suppressed');
    }
    return true;
});

(function() {
    // Основные элементы
    const playerContainer = document.querySelector('.video-player');
    const video = playerContainer ? playerContainer.querySelector('.video-player__video') : null;
    const playBtn = playerContainer ? playerContainer.querySelector('.play') : null;
    const progressBar = playerContainer ? playerContainer.querySelector('.progress input') : null;
    const timeDisplay = playerContainer ? playerContainer.querySelector('.time') : null;
    const volumeIcon = playerContainer ? playerContainer.querySelector('.audio img') : null;
    const volumeBar = playerContainer ? playerContainer.querySelector('.audio input') : null;
    const fullscreenBtn = playerContainer ? playerContainer.querySelector('.full-scrin') : null;
    const controls = playerContainer ? playerContainer.querySelector('.video-player__controls') : null;
    const seriasSelect = playerContainer ? playerContainer.querySelectorAll('.video-player__serias') : null;

    // Элементы для героя
    const heroFilmBtn = document.querySelector('.hero__info-link');
    const heroFilm = document.querySelector('.hero__info');
    const heroFilm2 = document.querySelector('.hero__film-background');

    // Элементы для серий
    const seasonSelect = document.getElementById('seasonSelect');
    const episodeSelect = document.getElementById('episodeSelect');

    // Иконки
    const icons = {
        play: './assets/icons/Play.svg',
        pause: './assets/icons/Pause.svg',
        sound: './assets/icons/Sound.svg',
        mute: './assets/icons/Mute.svg',
        fullscreen: './assets/icons/Full Screen.svg',
        exitFullscreen: './assets/icons/Normal Screen.svg'
    };

    // Переменные состояния
    let lastVolume = 1;
    let controlsTimeout;
    let isMouseMoving = false;
    let mouseMoveTimer;
    let seasonsData = {};
    let currentEpisode = null;

    // Инициализация плеера
    function initPlayer() {
        if (!video) return;

        // Начальные значения
        video.currentTime = 0;
        updateTimeDisplay();
        showControls();
        hideControlsAfterTimeout();

        if (volumeBar) {
            volumeBar.min = 0;
            volumeBar.max = 1;
            volumeBar.step = 0.01;
            volumeBar.value = video.volume;
        }

        updateVolumeIcon();

        // Привязка обработчиков для сезонов и серий
        if (seasonSelect && episodeSelect) {
            seasonSelect.addEventListener('change', function() {
                updateEpisodesForSeason(this.value);
                if (seasonsData[this.value] && seasonsData[this.value].length > 0) {
                    setCurrentEpisode(seasonsData[this.value][0]);
                }
            });

            episodeSelect.addEventListener('change', function() {
                const season = seasonSelect.value;

                // Защита от отсутствия данных
                if (!seasonsData[season] || !Array.isArray(seasonsData[season])) {
                    console.error(`Данные для сезона ${season} не найдены`);
                    return;
                }

                const episodeId = parseInt(this.value);
                const episode = seasonsData[season].find(ep => ep.id === episodeId);

                if (episode) {
                    setCurrentEpisode(episode);
                }
            });
        }
    }

    // Функции для работы с сериалами
    function initSeriesData(seriesData) {
        // Сбрасываем данные
        seasonsData = {};

        // Обрабатываем сезоны
        if (seriesData.seasons) {
            // Копируем сезоны с преобразованием ключей
            for (const seasonKey in seriesData.seasons) {
                seasonsData[seasonKey] = seriesData.seasons[seasonKey].map(ep => ({
                    ...ep,
                    season: String(ep.season) // Гарантируем строковый формат
                }));
            }
        }
        // Обрабатываем отдельные эпизоды
        else if (seriesData.episodes) {
            seriesData.episodes.forEach(episode => {
                const seasonKey = String(episode.season);
                if (!seasonsData[seasonKey]) {
                    seasonsData[seasonKey] = [];
                }
                seasonsData[seasonKey].push({
                    ...episode,
                    season: seasonKey
                });
            });
        }

        // Сохраняем данные в глобальный объект
        window.__videoPlayerSeasonsData = seasonsData;

        // Обновляем селекторы
        if (seasonSelect) {
            seasonSelect.innerHTML = '';

            // Сортируем сезоны
            const sortedSeasons = Object.keys(seasonsData).sort((a, b) => parseInt(a) - parseInt(b));

            sortedSeasons.forEach(season => {
                const option = document.createElement('option');
                option.value = season;
                option.textContent = `Сезон ${season}`;
                seasonSelect.appendChild(option);
            });

            // Выбираем первый сезон
            if (sortedSeasons.length > 0) {
                const firstSeason = sortedSeasons[0];
                updateEpisodesForSeason(firstSeason);

                // Выбираем первую серию
                if (seasonsData[firstSeason] && seasonsData[firstSeason].length > 0) {
                    setCurrentEpisode(seasonsData[firstSeason][0]);
                }
            }
        }
    }

    function updateEpisodesForSeason(season) {
        if (!episodeSelect) return;

        episodeSelect.innerHTML = '';

        if (seasonsData[season]) {
            seasonsData[season].forEach(episode => {
                const option = document.createElement('option');
                option.value = episode.id;
                option.textContent = `Серия ${episode.number}`;

                // Выделяем текущий эпизод
                if (currentEpisode && currentEpisode.id === episode.id) {
                    option.selected = true;
                }

                episodeSelect.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.value = '0';
            option.textContent = 'Нет доступных серий';
            episodeSelect.appendChild(option);
        }
    }

    function setCurrentEpisode(episode) {
        if (!video) return;

        // Сохраняем текущую серию
        window.__videoPlayerCurrentEpisode = episode;
        currentEpisode = episode;

        const wasPlaying = !video.paused;
        const currentTime = video.currentTime;

        // Обновляем источник видео
        if (episode.video) {
            video.src = './' + episode.video;
            video.load();
        } else {
            console.error('URL видео не указан для эпизода', episode);
            return;
        }

        video.onloadeddata = () => {
            if (currentTime > 0) {
                video.currentTime = currentTime;
            }
            if (wasPlaying) {
                video.play().catch(e => console.error('Ошибка воспроизведения:', e));
            }
            updatePlayButton();
            showControls();
        };

        video.onerror = () => {
            console.error('Ошибка загрузки видео:', episode.video);
        };

        // Обновляем селекторы
        if (seasonSelect && episode.season) {
            seasonSelect.value = episode.season;
        }
        if (episodeSelect && episode.id) {
            episodeSelect.value = episode.id;
        }

        // Обновляем список эпизодов
        if (episode.season) {
            updateEpisodesForSeason(episode.season);
        }
    }

    // Основные функции плеера
    function updateVolumeIcon() {
        if (!volumeIcon || !video) return;
        volumeIcon.src = (video.volume === 0) ? icons.mute : icons.sound;
    }

    function showControls() {
        if (!controls) return;

        controls.style.opacity = '1';
        controls.style.pointerEvents = 'auto';

        if (seriasSelect) {
            seriasSelect.forEach(el => {
                el.style.opacity = '1';
                el.style.pointerEvents = 'auto';
            });
        }

        resetControlsTimer();
    }

    function hideControls() {
        if (!controls) return;

        controls.style.opacity = '0';

        if (seriasSelect) {
            seriasSelect.forEach(el => {
                el.style.opacity = '0';
            });
        }

        setTimeout(() => {
            controls.style.pointerEvents = 'none';
            if (seriasSelect) {
                seriasSelect.forEach(el => {
                    el.style.pointerEvents = 'none';
                });
            }
        }, 150);
    }

    function resetControlsTimer() {
        clearTimeout(controlsTimeout);
        controlsTimeout = setTimeout(hideControls, 3000);
    }

    function hideControlsAfterTimeout() {
        if (!controls) return;
        controlsTimeout = setTimeout(hideControls, 3000);
    }

    function togglePlay() {
        if (!video) return;

        if (video.paused) {
            video.play().catch(e => console.error('Ошибка воспроизведения:', e));
        } else {
            video.pause();
        }
        updatePlayButton();
        showControls();
    }

    function updatePlayButton() {
        if (!playBtn || !video) return;
        playBtn.innerHTML = video.paused
            ? `<img src="${icons.play}" alt="Play">`
            : `<img src="${icons.pause}" alt="Pause">`;
    }

    function updateProgress() {
        if (!progressBar || !video || isNaN(video.duration) || video.duration <= 0) return;
        const percent = (video.currentTime / video.duration) * 100;
        progressBar.value = percent;
    }

    function updateTimeDisplay() {
        if (!timeDisplay || !video) return;
        const minutes = Math.floor(video.currentTime / 60);
        const seconds = Math.floor(video.currentTime % 60);
        timeDisplay.textContent = `${padZero(minutes)}:${padZero(seconds)}`;
    }

    function padZero(num) {
        return num.toString().padStart(2, '0');
    }

    function toggleFullscreen() {
        if (!playerContainer) return;

        if (!document.fullscreenElement) {
            if (playerContainer.requestFullscreen) {
                playerContainer.requestFullscreen()
                    .then(() => {
                        if (fullscreenBtn) {
                            fullscreenBtn.innerHTML = `<img src="${icons.exitFullscreen}" alt="Exit Fullscreen">`;
                        }
                    })
                    .catch(e => console.error('Ошибка полноэкранного режима:', e));
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen()
                    .then(() => {
                        if (fullscreenBtn) {
                            fullscreenBtn.innerHTML = `<img src="${icons.fullscreen}" alt="Fullscreen">`;
                        }
                    })
                    .catch(e => console.error('Ошибка выхода из полноэкранного режима:', e));
            }
        }
        showControls();
    }

    // Привязка обработчиков событий
    function setupEventListeners() {
        // Кнопка героя
        if (heroFilmBtn && heroFilm && heroFilm2) {
            heroFilmBtn.addEventListener('click', () => {
                if (heroFilm) heroFilm.style.display = 'none';
                if (heroFilm2) heroFilm2.style.display = 'none';
                if (playerContainer) {
                    playerContainer.style.display = 'flex';
                }
                window.scrollTo({top: 0, left: 0});
            });
        }

        // Основные элементы управления
        if (playBtn) playBtn.addEventListener('click', togglePlay);
        if (video) {
            video.addEventListener('click', togglePlay);
            video.addEventListener('timeupdate', () => {
                updateProgress();
                updateTimeDisplay();
            });
            video.addEventListener('ended', handleVideoEnded);
            video.addEventListener('loadedmetadata', initPlayer);
            video.addEventListener('error', (e) => {
                console.error('Ошибка видео:', video.error);
            });
        }

        if (progressBar) {
            progressBar.addEventListener('input', () => {
                if (!video || isNaN(video.duration)) return;
                const time = (progressBar.value * video.duration) / 100;
                video.currentTime = time;
                showControls();
            });
        }

        if (volumeBar) {
            volumeBar.addEventListener('input', () => {
                if (!video) return;
                video.volume = volumeBar.value;
                updateVolumeIcon();
            });
        }

        if (volumeIcon) {
            volumeIcon.addEventListener('click', () => {
                if (!video) return;
                if (video.volume > 0) {
                    lastVolume = video.volume;
                    video.volume = 0;
                    if (volumeBar) volumeBar.value = 0;
                } else {
                    video.volume = lastVolume;
                    if (volumeBar) volumeBar.value = lastVolume;
                }
                updateVolumeIcon();
            });
        }

        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', toggleFullscreen);
        }

        if (playerContainer) {
            playerContainer.addEventListener('mousemove', () => {
                if (!isMouseMoving) {
                    showControls();
                    isMouseMoving = true;
                }

                clearTimeout(mouseMoveTimer);
                mouseMoveTimer = setTimeout(() => {
                    isMouseMoving = false;
                }, 100);
                resetControlsTimer();
            });
        }

        // Обработка клавиатуры
        document.addEventListener('keydown', (e) => {
            // Проверяем, активен ли плеер
            const filmSection = document.querySelector('.hero.film');
            if (!filmSection || !video) return;

            // Игнорируем сочетания клавиш в полях ввода
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) return;

            switch (e.code) {
                case 'Space':
                    togglePlay();
                    e.preventDefault();
                    break;
                case 'ArrowRight':
                    video.currentTime += 5;
                    showControls();
                    break;
                case 'ArrowLeft':
                    video.currentTime -= 5;
                    showControls();
                    break;
                case 'ArrowUp':
                    video.volume = Math.min(1, video.volume + 0.05);
                    if (volumeBar) volumeBar.value = video.volume;
                    updateVolumeIcon();
                    showControls();
                    break;
                case 'ArrowDown':
                    video.volume = Math.max(0, video.volume - 0.05);
                    if (volumeBar) volumeBar.value = video.volume;
                    updateVolumeIcon();
                    showControls();
                    break;
                case 'KeyF':
                    toggleFullscreen();
                    break;
                case 'KeyM':
                    if (volumeIcon) volumeIcon.click();
                    showControls();
                    break;
                case 'KeyN':
                    goToNextEpisode();
                    break;
                case 'KeyP':
                    goToPreviousEpisode();
                    break;
            }
        });

        // Полноэкранный режим
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement && fullscreenBtn) {
                fullscreenBtn.innerHTML = `<img src="${icons.fullscreen}" alt="Fullscreen">`;
            }
        });
    }

    function handleVideoEnded() {
        updatePlayButton();
        goToNextEpisode();
    }

    function goToNextEpisode() {
        if (!currentEpisode || !seasonsData) return;

        // Приводим season к строке для совместимости
        const season = String(currentEpisode.season);

        // Защита от отсутствия сезона
        if (!seasonsData[season]) {
            console.error(`Сезон ${season} не найден при переходе к следующей серии`);
            return;
        }

        const currentIndex = seasonsData[season].findIndex(ep => ep.id === currentEpisode.id);

        if (currentIndex < seasonsData[season].length - 1) {
            // Следующая серия в текущем сезоне
            setCurrentEpisode(seasonsData[season][currentIndex + 1]);
        } else {
            // Переход к следующему сезону
            const seasons = Object.keys(seasonsData).sort((a, b) => parseInt(a) - parseInt(b));
            const currentSeasonIndex = seasons.indexOf(season);

            if (currentSeasonIndex < seasons.length - 1) {
                const nextSeason = seasons[currentSeasonIndex + 1];
                if (seasonsData[nextSeason] && seasonsData[nextSeason].length > 0) {
                    setCurrentEpisode(seasonsData[nextSeason][0]);
                }
            } else {
                // Последняя серия последнего сезона
                video.currentTime = 0;
                updatePlayButton();
                showControls();
            }
        }
    }

    function goToPreviousEpisode() {
        if (!currentEpisode || !seasonsData) return;

        // Приводим season к строке для совместимости
        const season = String(currentEpisode.season);

        // Защита от отсутствия сезона
        if (!seasonsData[season]) {
            console.error(`Сезон ${season} не найден при переходе к предыдущей серии`);
            return;
        }

        const currentIndex = seasonsData[season].findIndex(ep => ep.id === currentEpisode.id);

        if (currentIndex > 0) {
            // Предыдущая серия в текущем сезоне
            setCurrentEpisode(seasonsData[season][currentIndex - 1]);
        } else {
            // Переход к предыдущему сезону
            const seasons = Object.keys(seasonsData).sort((a, b) => parseInt(a) - parseInt(b));
            const currentSeasonIndex = seasons.indexOf(season);

            if (currentSeasonIndex > 0) {
                const prevSeason = seasons[currentSeasonIndex - 1];
                const prevSeasonEpisodes = seasonsData[prevSeason];
                if (prevSeasonEpisodes && prevSeasonEpisodes.length > 0) {
                    // Последняя серия предыдущего сезона
                    setCurrentEpisode(prevSeasonEpisodes[prevSeasonEpisodes.length - 1]);
                }
            } else {
                // Первая серия первого сезона
                video.currentTime = 0;
                updatePlayButton();
                showControls();
            }
        }
    }

    // Инициализация при загрузке
    document.addEventListener('DOMContentLoaded', () => {
        setupEventListeners();

        // Автоматическая инициализация при изменении класса hero
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    const hero = document.querySelector('.hero');
                    if (hero && hero.classList.contains('film')) {
                        initPlayer();

                        // Инициализация данных сериала
                        if (window.seriesData) {
                            initSeriesData(window.seriesData);
                        }
                    }
                }
            });
        });

        const hero = document.querySelector('.hero');
        if (hero) {
            observer.observe(hero, { attributes: true });
        }

        // Ручная инициализация, если уже на странице фильма
        const filmPage = document.querySelector('.hero.film');
        if (filmPage) {
            initPlayer();

            if (window.seriesData) {
                initSeriesData(window.seriesData);
            }
        }
    });

    // API для внешнего доступа
    window.videoPlayerAPI = {
        initSeriesData,
        setCurrentEpisode
    };
})();