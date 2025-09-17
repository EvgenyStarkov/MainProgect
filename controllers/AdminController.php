<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/Model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/AdminModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/ContentModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/ContentsModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/HomeModel.php';

class AdminController extends Controller
{
    public function index($path, $params)
    {
        ?>
        <pre><?= print_r($params) ?></pre> <?php
        $data = $this->getData($params);
        if ($data['user']['role'] == 'admin') {
            $this->addToDB($data, $params);
            $data = $this->getData();
            $this->render($path, $data);

            if (isset($params['adminReHomeVideoSubmit'])) {
               $this->uploadFile('adminReHomeVideo');
            }


        } else {
            $this->error404();
        }
    }

    public function getData()
    {
        $m = new Model;
        $AdminModel = new AdminModel;
        $contentsModel = new ContentsModel;
        $homeModel = new HomeModel;

        $user = [
            'id' => 0,
            'role' => 'guest'
        ];

        if (isset($_SESSION['userId'])) {
            $user = $m->getUser($_SESSION['userId']);
        }

        $preContent = $AdminModel->getAllContent();
        $content = [];

        foreach ($preContent as $c) {
            $contentGenres = implode(' ,', json_decode($c['genres']));

            $content[] = [
                'id' => $c['id'],
                'title' => $c['title'],
                'description' => $c['description'],
                'trailer' => $c['trailer'],
                'video' => $c['video'],
                'views' => $c['views'],
                'rating' => $c['rating'],
                'year' => $c['year'],
                'country' => $c['country'],
                'cover' => $c['cover'],
                'type' => $c['type'],
                'genres' => $contentGenres
            ];

        }

        $episodes = $AdminModel->getAllEpisodes();
        $series = $contentsModel->getAllContentOnType('сериал');
        $preCollections = $homeModel->getAllCollections();
        $collections = [];

        foreach ($preCollections as $c) {
            $collectionContent = $m->getContentOnCollection($c['id']);
            $contentId = array_column($collectionContent, 'id');
            $collectionContent = array_column($collectionContent, 'title');
            $collectionContent = array_filter($collectionContent);
            $collectionContent = implode(' ,', $collectionContent);
            $collections[] = [
                'id' => $c['id'],
                'title' => $c['title'],
                'content' => $collectionContent,
                'contentId' => $contentId
            ];
        }


        $advertising = $homeModel->getAllHits();
        $heroSlides = $homeModel->getAllSlides();
        $subscriptions = $AdminModel->getAllSubscriptions();

        return [
            'user' => $user,
            'content' => $content,
            'episodes' => $episodes,
            'series' => $series,
            'collections' => $collections,
            'advertising' => $advertising,
            'heroSlides' => $heroSlides,
            'subscriptions' => $subscriptions
        ];

    }

    public function uploadFile($inputName)
    {
        echo $inputName;
        if (empty($_FILES[$inputName])) {
            return null;
        }

        $file = $_FILES[$inputName];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $mimeType = $file['type'];
        $basePath = strpos($mimeType, 'image/') === 0 ? '/assets/image/' : '/assets/video/';

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . $basePath;

        $filename = basename($file['name']);

        echo $filename;

        if ($inputName = 'adminReHomeVideo') {
            echo 'dgdg';
            $filename = 'HomeBackground.mp4';
        }

        echo '<br>' .  $filename;

        $targetPath = $uploadDir . $filename;

        return move_uploaded_file($file['tmp_name'], $targetPath) ? $basePath . $filename : null;

    }

    private function error404()
    {
        http_response_code(404);
        include $_SERVER['DOCUMENT_ROOT'] . '/views/404/index.php';
    }

    public function addToDB($params)
    {
        // Существующая логика добавления/редактирования/удаления контента/эпизодов/коллекций
        if (isset($params['adminAddContent'])) {
            $m = new AdminModel();
            $preGenres = $params['adminAddGenresContent'];
            $genres = explode(',', $preGenres);
            $genres = array_map('trim', $genres);
            $genres = array_filter($genres);
            $genres = array_values($genres);
            $genres = json_encode($genres, JSON_UNESCAPED_UNICODE);
            $m->addContent(
                $params['adminAddTitleContent'],
                $params['adminAddDescriptionContent'],
                $this->uploadFile('adminAddTrailerContent'),
                $this->uploadFile('adminAddVideoContent'),
                $params['adminAddRatingContent'],
                $params['adminAddYearContent'],
                $params['adminAddCountryContent'],
                $this->uploadFile('adminAddCoverContent'),
                $genres,
                $params['adminAddTypeContent']
            );
        } else {
            if (isset($params['adminReContent'])) {
                $m = new AdminModel();
                $mc = new ContentModel();
                $trailer = $mc->getContent($params['adminReIdContent'])['trailer'];
                $uploadTrailer = $this->uploadFile('adminReTrailerContent');
                if (isset($uploadTrailer)) {
                    $trailer = $uploadTrailer;
                }
                $video = $mc->getContent($params['adminReIdContent'])['video'];
                $uploadVideo = $this->uploadFile('adminReVideoContent');
                if (isset($uploadVideo)) {
                    $video = $uploadVideo;
                }
                $cover = $mc->getContent($params['adminReIdContent'])['cover'];
                $uploadCover = $this->uploadFile('adminReCoverContent');
                if (isset($uploadCover)) {
                    $cover = $uploadCover;
                }
                $preGenres = $params['adminReGenresContent'];
                $genres = explode(',', $preGenres);
                $genres = array_map('trim', $genres);
                $genres = array_filter($genres);
                $genres = array_values($genres);
                $genres = json_encode($genres, JSON_UNESCAPED_UNICODE);
                $m->reContent(
                    $params['adminReTitleContent'],
                    $params['adminReDescriptionContent'],
                    $trailer,
                    $video,
                    $params['adminReRatingContent'],
                    $params['adminReYearContent'],
                    $params['adminReCountryContent'],
                    $cover,
                    $genres,
                    $params['adminReTypeContent'],
                    $params['adminReIdContent']
                );
            } else {
                if (isset($params['adminDeleteContent'])) {
                    $m = new AdminModel();
                    $m->deleteContent($params['adminReIdContent']);
                }
            }
        }

        if (isset($params['adminAddEpisode'])) {
            $m = new AdminModel();
            $m->addEpisode(
                $params['adminAddNumberEpisode'],
                $params['adminAddSeasonEpisode'],
                $this->uploadFile('adminAddVideoEpisode'),
                $params['adminAddSeriesEpisode']

            );
        } else {
            if (isset($params['adminReEpisode'])) {
                $m = new AdminModel();
                $video = $m->getEpisode($params['adminReIdEpisode'])['video'];
                $uploadVideo = $this->uploadFile('adminReVideoEpisode');
                if (isset($uploadVideo)) {
                    $video = $uploadVideo;
                }
                $m->reEpisode($params['adminReNumberEpisode'], $params['adminReSeasonEpisode'], $video, $params['adminReContentIdEpisode'], $params['adminReIdEpisode']);
            } else {
                if (isset($params['adminDeleteEpisode'])) {
                    $m = new AdminModel();
                    $m->deleteEpisode($params['adminReIdEpisode']);
                }
            }
        }

        if (isset($params['adminAddCollection'])) {
            $m = new AdminModel();
            $m->addCollection(
                $params['adminAddTitleCollection']
            );
        } else {
            if (isset($params['adminReCollection'])) {
                $m = new AdminModel();
                $m->reCollection($params['adminReTitleCollection'], $params['adminReIdCollection']);
            } else {
                if (isset($params['adminDeleteCollection'])) {
                    $m = new AdminModel();
                    $m->deleteCollection($params['adminReIdCollection']);
                } else {
                    if (isset($params['adminAddContentToCollection'])) {
                        $m = new AdminModel();
                        $collectionId = isset($params['adminAddContentToCollectionOne']) ? (int)$params['adminAddContentToCollectionOne'] : 0;
                        $contentId = isset($params['adminAddContentToCollectionTwo']) ? (int)$params['adminAddContentToCollectionTwo'] : 0;
                        if ($collectionId > 0 && $contentId > 0) {
                            if (method_exists($m, 'isContentInCollection')) {
                                if (!$m->isContentInCollection($collectionId, $contentId)) {
                                    $m->addContentToCollection($collectionId, $contentId);
                                } else {
                                    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
                                    $_SESSION['admin_message'] = 'Контент уже добавлен в выбранную коллекцию';
                                }
                            } else {
                                $m->addContentToCollection($collectionId, $contentId);
                            }
                        }
                    }

                    if (isset($params['adminRemoveContentFromCollection'])) {
                        $m = new AdminModel();
                        $collectionId = isset($params['adminRemoveCollectionId']) ? (int)$params['adminRemoveCollectionId'] : 0;
                        $contentId = isset($params['adminRemoveContentId']) ? (int)$params['adminRemoveContentId'] : 0;
                        if ($collectionId > 0 && $contentId > 0 && method_exists($m, 'removeContentFromCollection')) {
                            $m->removeContentFromCollection($collectionId, $contentId);
                        }
                    }
                }
            }
        }

        // Collections Advertising
        if (isset($params['adminAddAdvertising'])) {
            $m = new AdminModel();
            $m->addAdvertising(
                $this->uploadFile('adminAddBackgroundAdvertising'),
                $this->uploadFile('adminAddMobileBackgroundAdvertising'),
                $params['adminAddTextAdvertising'],
                $params['adminAddCollectionAdvertising']
            );
        } else if (isset($params['adminReAdvertising'])) {
            $m = new AdminModel();
            $ad = $m->getAdvertising($params['adminReIdAdvertising']);

            $background = $ad['background'];
            $uploadBackground = $this->uploadFile('adminReBackgroundAdvertising');
            if (isset($uploadBackground)) {
                $background = $uploadBackground;
            }

            $mobileBackground = $ad['mobile_background'];
            $uploadMobileBackground = $this->uploadFile('adminReMobileBackgroundAdvertising');
            if (isset($uploadMobileBackground)) {
                $mobileBackground = $uploadMobileBackground;
            }

            $m->updateAdvertising(
                $params['adminReIdAdvertising'],
                $background,
                $mobileBackground,
                $params['adminReTextAdvertising'],
                $params['adminReCollectionAdvertising']
            );
        } else if (isset($params['adminDeleteAdvertising'])) {
            $m = new AdminModel();
            $m->deleteAdvertising($params['adminReIdAdvertising']);
        }

        // Hero Slides
        if (isset($params['adminAddHeroSlide'])) {
            $m = new AdminModel();
            $m->addHeroSlide(
                $params['adminAddTitleHeroSlide'],
                $params['adminAddTextHeroSlide'],
                $this->uploadFile('adminAddVideoHeroSlide'),
                $params['adminAddContentHeroSlide']
            );
        } else if (isset($params['adminReHeroSlide'])) {
            $m = new AdminModel();
            $slide = $m->getHeroSlide($params['adminReIdHeroSlide']);

            $video = $slide['video'];
            $uploadVideo = $this->uploadFile('adminReVideoHeroSlide');
            if (isset($uploadVideo)) {
                $video = $uploadVideo;
            }

            $m->updateHeroSlide(
                $params['adminReIdHeroSlide'],
                $params['adminReTitleHeroSlide'],
                $params['adminReTextHeroSlide'],
                $video,
                $params['adminReContentHeroSlide']
            );
        } else if (isset($params['adminDeleteHeroSlide'])) {
            $m = new AdminModel();
            $m->deleteHeroSlide($params['adminReIdHeroSlide']);
        }

        // Subscriptions
        if (isset($params['adminAddSubscription'])) {
            $m = new AdminModel();
            $m->addSubscription(
                $params['adminAddTitleSubscription'],
                $params['adminAddDescriptionSubscription'],
                $params['adminAddPriceSubscription']
            );
        } else if (isset($params['adminReSubscription'])) {
            $m = new AdminModel();
            $m->updateSubscription(
                $params['adminReIdSubscription'],
                $params['adminReTitleSubscription'],
                $params['adminReDescriptionSubscription'],
                $params['adminRePriceSubscription']
            );
        } else if (isset($params['adminDeleteSubscription'])) {
            $m = new AdminModel();
            $m->deleteSubscription($params['adminReIdSubscription']);
        } else if (isset($params['adminAddContentToSubscription'])) {
            $m = new AdminModel();
            $subscriptionId = (int)$params['adminAddContentToSubscriptionOne'];
            $contentId = (int)$params['adminAddContentToSubscriptionTwo'];

            if ($subscriptionId > 0 && $contentId > 0) {
                if (!$m->isContentInSubscription($subscriptionId, $contentId)) {
                    $m->addContentToSubscription($subscriptionId, $contentId);
                } else {
                    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
                    $_SESSION['admin_message'] = 'Контент уже добавлен в выбранную подписку';
                }
            }
        } else if (isset($params['adminRemoveContentFromSubscription'])) {
            $m = new AdminModel();
            $subscriptionId = (int)$params['adminRemoveSubscriptionId'];
            $contentId = (int)$params['adminRemoveContentId'];

            if ($subscriptionId > 0 && $contentId > 0) {
                $m->removeContentFromSubscription($subscriptionId, $contentId);
            }
        }
    }
}