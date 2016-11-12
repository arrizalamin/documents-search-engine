<?php
require_once 'vendor/autoload.php';

use App\SearchEngine;
use App\Router;

$router = new Router();

$router->add('/', function() {
    $this->render('home');
});

$router->add('/search', function ($req) {
    $dir = 'datatest';
    $articles = array_map(function($file) use($dir) {
        return __DIR__ . '/' . $dir . '/' . $file;
    }, array_slice(scandir($dir), 2));
    $searchEngine = new SearchEngine($articles);

    $query = $req['q'];
    $result = $searchEngine->search($query);

    $this->render('result', compact('result', 'query', 'dir'));
});

$router->start();