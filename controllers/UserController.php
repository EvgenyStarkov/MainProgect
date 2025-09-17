<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/model/UserModel.php';

// далена сессия т.к она во фронт-контролере, заменены все запросы на элементы массива $params
class UserController
{

    public function login($params)
    {
        $m = new UserModel;
        $user = $m->getUserForEmail($params['lEmail'], $params['lPassword']);
        if ($user != '') {
            $_SESSION['userId'] = $user['id']; // Удалена проверка на роль пользователя, и последующий редирект т.к
            //данный функционал должен быть у фронт-контроллера, последующие редиректы так же удалены
        } else {
            $_SESSION['errorLogin'] = 1;
        }
    }

    public function register($params)
    {
        $m = new UserModel;
        $emailCount = 0;
        $consent = 0;
        $users = $m->getAllUser();

        foreach ($users as $u) {
            if ($params['email'] == $u['email'] || $params['tel'] == $u['tel']) {
                $emailCount = $emailCount + 1;
            }
        }

        if (isset($params['password']) == isset($params['repeatPassword']) && $emailCount < 1) {

            if (isset($params['consentToMailing'])) {
                $consent = $params['consentToMailing'];
            }

            $m->addUser($params['name'], $params['userName'], $params['email'], $params['tel'], $params['password'], $consent);
            $user = $m->getUserForEmail($params['email'], $params['password']);
            $_SESSION['userId'] = $user['id'];

        } else {
            $_SESSION['errorRegister'] = 1;
        }
    }

    function updateUserStart($params)
    {
        $m = new UserModel;

        if (isset($params['reName'])) {
            $m->updateUser($params['reId'],
                ['name' => $params['reName']
                ]);
        }

        if (isset($params['reUserName'])) {
            $m->updateUser($params['reId'], [
                'username' => $params['reUserName']
            ]);

        }

        if (isset($params['reEmail'])) {

            $emailCount1 = 0;
            $users = $m->getAllUser();

            foreach ($users as $u) {
                if ($params['reEmail'] == $u['email'] && $params['reId'] != $u['id']) {
                    $emailCount1 = $emailCount1 + 1;
                }
            }

            if ($emailCount1 < 1) {
                $m->updateUser($params['reId'], [
                    'email' => $params['reEmail']
                ]);
            } else {
                $_SESSION['errorRefactor'] = 1;
            }
        }

        if (isset($_POST['reTel'])) {

            $emailCount1 = 0;
            $users = $m->getAllUser();

            foreach ($users as $u) {
                if ($params['reTel'] == $u['tel'] && $params['reId'] != $u['id']) {
                    $emailCount1 = $emailCount1 + 1;
                }
            }

            if ($emailCount1 < 1) {
                $m->updateUser($params['reId'], [
                    'tel' => $params['reTel']
                ]);
            } else {
                $_SESSION['errorRefactor'] = 1;
            }
        }

        if (isset($params['rePassword'])) {
            $m->updateUser($params['reId'], [
                'password' => $params['rePassword']
            ]);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_FILES['avatar'])) {
                // нет файла
                return;
            }

            $file = $_FILES['avatar'];

            // Проверка ошибок загрузки
            if ($file['error'] !== UPLOAD_ERR_OK) {
                // можно логировать/обрабатывать коды ошибок
                return;
            }

            // Настройки
            $maxSize = 5 * 1024 * 1024; // 5 MB
            $allowed = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif'
            ];

            if ($file['size'] > $maxSize) {
                // файл слишком большой
                return;
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);
            if ($mime === false || !array_key_exists($mime, $allowed)) {
                // неподдерживаемый формат
                return;
            }

            $ext = $allowed[$mime];

            // Создаём директорию загрузок
            $uploadDir = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                    // не удалось создать директорию
                    return;
                }
            }

            // Генерируем уникальное имя и полный путь
            $fileName = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
            $destination = $uploadDir . $fileName;

            // Перемещаем tmp-файл в директорию загрузок
            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                // не удалось переместить файл
                return;
            }

            // Относительный путь, который будет храниться в БД
            $relativePath = '/uploads/avatars/' . $fileName;

            // Получаем текущий путь аватара пользователя (если хотите удалить старый)
            // Предполагаю, что у вас есть метод getUserById или аналогичный
            $userId = $params['reId']; // убедитесь, что $params['reId'] задан
            $currentUser = $m->getUser($userId); // Добавьте этот метод, если его нет

            if ($currentUser && !empty($currentUser['avatar'])) {
                // Удаляем старый файл если он лежит в /uploads/avatars и файл существует
                $oldPath = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . $currentUser['avatar'];
                if (strpos(realpath($oldPath) ?: '', realpath($uploadDir) ?: '') === 0 && is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // Обновляем пользователя в БД
            $m->updateUser($userId, ['avatar' => $relativePath]);

        }


    }

    public function subRefresh()
    {

        /* @var $user */

        $m = new UserModel;
        $subscriptions = $m->getUserSubscriptions($_SESSION['userId']);

        foreach ($subscriptions as $sb) {

            $user = $m->getUser($_SESSION['userId']);

            $date = date('Y-m-d');
            $expirationDate = $m->getUserSubscriptionExpirationDate($user['id'], $sb['id']);

            if ($date > $expirationDate) {

                if ($user['cash'] - $sb['price'] >= 0) {

                    $m->updateUserCash($user['id'], (-$sb['price']));
                    $newDate = $m->getDateAfter31Days();
                    $m->updateUserSubscriptionDate($user['id'], $sb['id'], $newDate);

                } else {
                    $m->removeUserSubscription($user['id'], $sb['id']); ?>
                    <script> alert("Вам не хватило средст для продления  подписки : '<?= $sb['title'] ?>' В следствии чего она была удалена из ваших подписок") </script>
                <?php }
            }
        }

    }

    public function index($params)
    {


        if (isset($params['lEmail']) && isset($params['lPassword'])) {

            $this->login($params);

        } else {

            if (isset($params['exit'])) {
                $_SESSION['userId'] = null;
                session_destroy();
            } else {

                if (isset($params['name']) && isset($params['password']) && isset($params['repeatPassword'])) {
                    $this->register($params);
                } else {

                    if (isset($params['reId'])) {
                        $this->updateUserStart($params);
                    } else {

                        if (isset($params['deId'])) {
                            $m = new UserModel;
                            $m->deleteUser($params['deId']);
                            session_destroy();

                        }

                    }
                }
            }

        }

        if (isset($params['subBuy'])) {

            $m = new UserModel();

            $user = $m->getUser($_SESSION['userId']);

            $subscription = $m->getSubscriptionById($params['subBuy']);

            if ($user['cash'] - $subscription['price'] >= 0) {
                $date = $m->getDateAfter31Days();
                $m->updateUserCash($user['id'], (-$subscription['price']));
                $m->buySubscription($user['id'], $_POST['subBuy'], $date);

            } else { ?>

                <script> alert("Недостаточно средств для получения подписки") </script>

            <?php }
        } else {

            /* @var $user */

            if (isset($params['subDelete'])) {

                $m = new UserModel();

                $m->removeUserSubscription($_SESSION['userId'], $params['subDelete']);

            }

        }

        if (isset($_SESSION['userId'])) {
            $this->subRefresh();
        }

    }

}




