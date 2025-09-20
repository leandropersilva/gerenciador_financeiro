<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$url = $_SERVER['REQUEST_URI'] != '/' ? explode('/', substr($_SERVER['REQUEST_URI'], 1)) : '';

$controller = $url[0] ?? 'home';
$metodo = $url[1] ?? 'dashboard';

$caminho_controller = __DIR__ . "/./controller/" . ucfirst($controller) . ".php";

if (!file_exists($caminho_controller)) exit("Erro, controller {$controller} não encontrado");
require_once $caminho_controller;

if(!method_exists($controller, $metodo)) exit("Erro, método {$metodo} não encontrado");

$classe = new $controller();
$classe->$metodo();