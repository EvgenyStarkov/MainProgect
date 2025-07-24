console.log('video script started');
const heroFilmBtn = document.querySelector('.hero__info-link');
const heroFilm = document.querySelector('.hero__info');
const heroFilm2 = document.querySelector('.hero__film-background');
const videoPlayer = document.querySelector('.video-player');
console.log(heroFilm, heroFilmBtn, videoPlayer);
if (heroFilmBtn) {
    heroFilmBtn.addEventListener('click', () => {
        heroFilm.style.display = 'none';
        heroFilm2.style.display = 'none';
        videoPlayer.style.display = 'flex';
        window.scrollTo({top: 0, left: 0});
    })
}

 // Основные элементы
    const playerContainer = document.querySelector('.video-player');
    const video = playerContainer.querySelector('.video-player__video');
    const playBtn = playerContainer.querySelector('.play');
    const progressBar = playerContainer.querySelector('.progress input');
    const timeDisplay = playerContainer.querySelector('.time');
    const volumeIcon = playerContainer.querySelector('.audio img');
    const volumeBar = playerContainer.querySelector('.audio input');
    const fullscreenBtn = playerContainer.querySelector('.full-scrin');
    const controls = playerContainer.querySelector('.video-player__controls');
    const seriasSelect = playerContainer.querySelector('.video-player__serias');

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

    // Инициализация плеера
    function initPlayer() {
        // Установка начального времени в 0
        video.currentTime = 0;
        updateTimeDisplay();
        
        // Настройка ползунка громкости
        volumeBar.min = 0;
        volumeBar.max = 1;
        volumeBar.step = 0.01;
        volumeBar.value = video.volume;
        
        updateVolumeIcon();
        showControls();
        hideControlsAfterTimeout();
    }

    // ========================
    // Управление звуком (плавное)
    // ========================
    volumeBar.addEventListener('input', () => {
        video.volume = volumeBar.value;
        updateVolumeIcon();
    });
    
    volumeIcon.addEventListener('click', () => {
        if (video.volume > 0) {
            lastVolume = video.volume;
            video.volume = 0;
            volumeBar.value = 0;
        } else {
            video.volume = lastVolume;
            volumeBar.value = lastVolume;
        }
        updateVolumeIcon();
    });
    
    function updateVolumeIcon() {
        volumeIcon.src = (video.volume === 0) ? icons.mute : icons.sound;
    }
    
    // ==============================
    // Управление видимостью контролов (ускоренное)
    // ==============================
    function showControls() {
        controls.style.opacity = '1';
        controls.style.pointerEvents = 'auto';
        if (seriasSelect) {
            seriasSelect.style.opacity = '1';
            seriasSelect.style.pointerEvents = 'auto';
        }
        resetControlsTimer();
    }
    
    function hideControls() {
        controls.style.opacity = '0';
        if (seriasSelect) {
            seriasSelect.style.opacity = '0';
        }
        
        setTimeout(() => {
            controls.style.pointerEvents = 'none';
            if (seriasSelect) {
                seriasSelect.style.pointerEvents = 'none';
            }
        }, 150); // Ускоренное скрытие
    }
    
    function resetControlsTimer() {
        clearTimeout(controlsTimeout);
        controlsTimeout = setTimeout(hideControls, 1500); // Быстрее скрываем (1.5 секунды)
    }
    
    function hideControlsAfterTimeout() {
        controlsTimeout = setTimeout(hideControls, 1500);
    }
    
    playerContainer.addEventListener('mousemove', () => {
        if (!isMouseMoving) {
            showControls();
            isMouseMoving = true;
        }
        
        clearTimeout(mouseMoveTimer);
        mouseMoveTimer = setTimeout(() => {
            isMouseMoving = false;
        }, 50); // Более быстрая реакция
        
        resetControlsTimer();
    });
    
    // Показываем контролы при взаимодействии
    const controlElements = [controls, seriasSelect].filter(el => el);
    controlElements.forEach(el => {
        el.addEventListener('mouseenter', showControls);
    });
    
    // ========================
    // Основные функции плеера
    // ========================
    playBtn.addEventListener('click', togglePlay);
    video.addEventListener('click', togglePlay);
    
    function togglePlay() {
        if (video.paused) {
            video.play();
            playBtn.innerHTML = `<img src="${icons.pause}" alt="Pause">`;
        } else {
            video.pause();
            playBtn.innerHTML = `<img src="${icons.play}" alt="Play">`;
        }
        showControls();
    }
    
    video.addEventListener('timeupdate', () => {
        updateProgress();
        updateTimeDisplay();
    });
    
    function updateProgress() {
        const percent = (video.currentTime / video.duration) * 100;
        if (!isNaN(percent)) {
            progressBar.value = percent;
        }
    }
    
    function updateTimeDisplay() {
        const minutes = Math.floor(video.currentTime / 60);
        const seconds = Math.floor(video.currentTime % 60);
        timeDisplay.textContent = `${padZero(minutes)}:${padZero(seconds)}`;
    }
    
    progressBar.addEventListener('input', () => {
        const time = (progressBar.value * video.duration) / 100;
        video.currentTime = time;
        showControls();
    });
    
    function padZero(num) {
        return num.toString().padStart(2, '0');
    }
    
    // Полноэкранный режим
    fullscreenBtn.addEventListener('click', toggleFullscreen);
    
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            if (playerContainer.requestFullscreen) {
                playerContainer.requestFullscreen();
                fullscreenBtn.innerHTML = `<img src="${icons.exitFullscreen}" alt="Exit Fullscreen">`;
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                fullscreenBtn.innerHTML = `<img src="${icons.fullscreen}" alt="Fullscreen">`;
            }
        }
        showControls();
    }
    
    document.addEventListener('fullscreenchange', () => {
        if (!document.fullscreenElement) {
            fullscreenBtn.innerHTML = `<img src="${icons.fullscreen}" alt="Fullscreen">`;
        }
    });
    
    video.addEventListener('ended', () => {
        playBtn.innerHTML = `<img src="${icons.play}" alt="Play">`;
        video.currentTime = 0;
        updateProgress();
        updateTimeDisplay();
    });
    
    // Обработка клавиатуры
    document.addEventListener('keydown', (e) => {
        if (document.querySelector('.hero.film')) {
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
                    volumeBar.value = video.volume;
                    updateVolumeIcon();
                    showControls();
                    break;
                case 'ArrowDown':
                    video.volume = Math.max(0, video.volume - 0.05);
                    volumeBar.value = video.volume;
                    updateVolumeIcon();
                    showControls();
                    break;
                case 'KeyF':
                    toggleFullscreen();
                    break;
                case 'KeyM':
                    volumeIcon.click();
                    showControls();
                    break;
            }
        }
    });

    // Инициализация
    video.addEventListener('loadedmetadata', initPlayer);
    
    // Автоматическая инициализация
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                const hero = document.querySelector('.hero');
                if (hero.classList.contains('film')) {
                    initPlayer();
                }
            }
        });
    });
    
    observer.observe(document.querySelector('.hero'), {
        attributes: true
    });