<?php

class Controller
{
    public function render($path, $data)
{
    extract($data);
    include $_SERVER['DOCUMENT_ROOT'] . $path . 'index.php';
}
}