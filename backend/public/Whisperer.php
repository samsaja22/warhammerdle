<?php 
declare(strict_types=1);

use Dotenv\Dotenv;
use Warhammerdle\Controllers\WhispererController;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$whisperer = new WhispererController; 

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET["whisper"])) {
    try {
        $result = $whisperer->genWhisper($_GET["whisper"]);
        echo json_encode($result);
    } catch (\Throwable $e) { 
        echo json_encode(["error" => "Something went wrong."]);
    }
} else {
    echo json_encode(["error" => "GET parameter 'whisper' not set."]);
}