<?php
require_once "../model/Model.php";
session_start();

class UserController
{
    public function login()
    {
        $m = new Model;
        $user = $m->getUserForEmail($_POST['lEmail'], $_POST['lPassword']);
        if ($user != '') {
            $_SESSION['userId'] = $user['id'];
            if($user['role'] == 'admin'){
                header("Location: ./AdminController.php");
            } else {header("Location: /index.php");}
        } else {
            header("Location: /index.php");
            $_SESSION['errorLogin'] = 1;
        }
    }

    public function register()
    {
        $m = new Model;
        $emailCount = 0;
        $consent = 0;
        $users = $m->getAllUser();

        foreach ($users as $u) {
            if ($_POST['email'] == $u['email']) {
                $emailCount = $emailCount + 1;
            }
        }

        foreach ($users as $u) {
            if ($_POST['tel'] == $u['tel']) {
                $emailCount = $emailCount + 1;
            }
        }

        if (isset($_POST['password']) == isset($_POST['repeatPassword']) && $emailCount < 1) {

            if (isset($_POST['consentToMailing'])) {
                $consent = $_POST['consentToMailing'];
            }

            $m->addUser($_POST['name'], $_POST['userName'], $_POST['email'], $_POST['tel'], $_POST['password'], $consent);
            $user = $m->getUserForEmail($_POST['email'], $_POST['password']);
            $_SESSION['userId'] = $user['id'];
            header("Location: /index.php");
        } else {
            header("Location: /index.php");
            $_SESSION['errorRegister'] = 1;
        }
    }


    function updateUserStart()
    {
        $m = new Model;

        if (isset($_POST['reName'])) {
            $m->updateUser($_POST['reId'], [
                'name' => $_POST['reName']
            ]);
        }

        if (isset($_POST['reUserName'])) {
            $m->updateUser($_POST['reId'], [
                'username' => $_POST['reUserName']
            ]);


            if (isset($_POST['reUserName'])) {

                $m->updateUser($_POST['reId'], [
                    'username' => $_POST['reUserName']
                ]);
            }

        }

        if (isset($_POST['reEmail'])) {

            $emailCount1 = 0;
            $users = $m->getAllUser();

            foreach ($users as $u) {
                if ($_POST['reEmail'] == $u['email'] && $_POST['reId'] != $u['id']) {
                    $emailCount1 = $emailCount1 + 1;
                    echo $_POST['reEmail'].'Почта с формы'."<br>";
                    echo $u['email'].'Почта с базы данных'."<br>";
                    echo $emailCount1."<br>";
                    echo $u['id']."<br>";
                    echo "Такая почта в базе есть"."<br><br>";
                } else{
                    echo $_POST['reEmail'].'Почта с формы'."<br>";
                    echo $u['email'].'Почта с базы данных'."<br>";
                    echo $emailCount1."<br>";
                    echo "Это указанный пользователь"."<br><br>";
                }
            }

            if ($emailCount1 < 1) {
                $m->updateUser($_POST['reId'], [
                    'email' => $_POST['reEmail']
                ]);
            } else {
                $_SESSION['errorRefactor'] = 1;
                echo "error";
            }
        }

        if (isset($_POST['reTel'])) {

            $emailCount1 = 0;
            $users = $m->getAllUser();

            foreach ($users as $u) {
                if ($_POST['reTel'] == $u['tel'] && $_POST['reId'] != $u['id']) {
                    $emailCount1 = $emailCount1 + 1;
                    echo $_POST['reTel'].'Телефон с формы'."<br>";
                    echo $u['tel'].'Телефон с базы данных'."<br>";
                    echo $emailCount1."<br>";
                    echo $u['id']."<br>";
                    echo "Такой телефон есть в базе данных"."<br><br>";
                } else {
                    echo $_POST['reTel'].'Телефон с формы'."<br>";
                    echo $u['tel'].'Телефон с базы данных'."<br>";
                    echo $emailCount1."<br>";
                    echo "Это указанный пользователь"."<br><br>";
                }
            }

            if ($emailCount1 < 1) {
                $m->updateUser($_POST['reId'], [
                    'tel' => $_POST['reTel']
                ]);
            } else {
                $_SESSION['errorRefactor'] = 1;
                echo "error";
            }
        }

        if (isset($_POST['rePassword'])) {
            $m->updateUser($_POST['reId'], [
                'password' => $_POST['rePassword']
            ]);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['avatar'];
                $fileName = $file['name'];

                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/avatars/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true); // Создаем рекурсивно
                }

                $fileName = uniqid() . '_' . basename($file['name']);
                $destination = $uploadDir . $fileName;


                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    // Сохраняем путь к файлу в БД: '/uploads/avatars/' . $fileName
                    echo "Файл загружен в: " . $destination;
                } else {
                    echo "Ошибка при сохранении файла!";
                }

                $m->updateUser($_POST['reId'], [
                    'avatar' =>  '/uploads/avatars/' . $fileName
                ]);
            }
        }
    }

    public function index()
    {
        if (isset($_POST['name']) && isset($_POST['password']) && isset($_POST['repeatPassword'])) {
            $this->register();
        }

        if (isset($_POST['lEmail']) && isset($_POST['lPassword'])) {
            $this->login();
        }

        if (isset($_POST['exit'])) {
            session_destroy();
            header("Location: /index.php");
        }

        if (isset($_POST['reId'])) {
            $this->updateUserStart();
            header("Location: /views/accaunt/index.php");
        }

        if(isset($_POST['deId'])){
            $m = new Model;
            $m->deleteUser($_POST['deId']);
            session_destroy();
            header("Location: /index.php");

        }


    }
}

$uc = new UserController;
$uc->index();



