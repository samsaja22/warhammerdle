<?php
declare(strict_types=1);

namespace Warhammerdle\Controllers;

use Warhammerdle\Database\GeneralRepository;

abstract class Endpoint
{
    protected GeneralRepository $db;
    
    public function __construct()
    {
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Origin: *");

        $this->db = new GeneralRepository;
    }

    protected function requireMethod(string $method): void
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== strtoupper($method)) {
            $this->sendError("Method Not Allowed. Expected {$method}.", 405);
        }
    }

    protected function sendSuccess(mixed $data = null): void
    {
        http_response_code(200);
        $response = ["status" => "success"];
        
        if ($data !== null) {
            $response["data"] = $data;
        }

        echo json_encode($response);
        exit();
    }

    protected function sendError(string $message, int $statusCode = 400, array $errors = []): void
    {
        http_response_code($statusCode);
        $response = [
            "status" => "error",
            "message" => $message
        ];

        if (!empty($errors)) {
            $response["errors"] = $errors;
        }

        echo json_encode($response);
        exit();
    }
}
