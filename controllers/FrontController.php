<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/router/Router.php' ;
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/UserController.php' ;

$url = $_SERVER['REQUEST_URI'] ?? '/views/home/';
$path = parse_url($url ,  PHP_URL_PATH) ?: '/views/home/';
$strGetParams = parse_url($url ,  PHP_URL_QUERY) ?: 'none=0';
$postParams = $_POST;
$getParams = [];
parse_str($strGetParams, $getParams);
$params = array_merge($postParams,$getParams);

if(isset($params['user'])){;
    $userController = new UserController();  //Добавил вызов UserController при общем get-запросе 'user'
    $userController->index($params);
}

$router = new Router;
$router->redirect($path , $params);


if (isset($_SESSION['errorRegister'])) {
    ?>
    <script>
        alert("Произошла ошибка регистрации, аккаунт с указанным номером телефона или указаной электронной почтой уже существует. Попробуйте зарегистрироватся заново или обратитесь в службу поддержки")
    </script>
    <?php
    $_SESSION['errorRegister'] = null;
}
if (isset($_SESSION['errorLogin'])) {
    ?>
    <script>
        alert("Неверно указан логин или пароль")
    </script> <?php
    $_SESSION['errorLogin'] = null;
}

?>



