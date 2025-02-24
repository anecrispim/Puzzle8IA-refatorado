<?php

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../src/';
    $classPath = str_replace('\\', '/', $class) . '.php';
    $file = $baseDir . $classPath;
    if (file_exists($file)) {
        require_once $file;
    }
});

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

use Controllers\PuzzleController;

$controller = new PuzzleController();

switch ($action) {
    case 'solve':
        $controller->solve();
        break;
    default:
        $controller->index();
        break;
}
