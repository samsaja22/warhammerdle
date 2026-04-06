<?php 
declare(strict_types=1);

use Dotenv\Dotenv;
use Warhammerdle\Controllers\WhispererController;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$controller = new WhispererController();
$controller->handleRequest();