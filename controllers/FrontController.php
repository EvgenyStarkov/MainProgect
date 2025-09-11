<?php



require_once $_SERVER['DOCUMENT_ROOT'] . '/router/Router.php' ;

session_start();


$url = $_SERVER['REQUEST_URI'] ?? '/views/home/';
$path = parse_url($url ,  PHP_URL_PATH) ?: '/views/home/';
$strGetParams = parse_url($url ,  PHP_URL_QUERY) ?: 'none=0';
$postParams = $_POST;
$getParams = [];
parse_str($strGetParams, $getParams);
$params = array_merge($postParams,$getParams);


$router = new router\Router();
$router->redirect($path , $params);

?>



