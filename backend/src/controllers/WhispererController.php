<?php
declare(strict_types=1);

namespace Warhammerdle\Controllers;

use Warhammerdle\Controllers\Endpoint;
use Warhammerdle\Controllers\WhispererValidation;

class WhispererController extends Endpoint
{
    public function __construct()
    {
        parent::__construct();
    }

    public function handleRequest(): void {
        $this->requireMethod('GET');
        $payload = json_encode($_GET);
        try {

            $validator = new WhispererValidation($payload);

            if (!$validator->validate()) {
                $validationErrors = $validator->getErrors();
                $this->sendError("Validation failed.", 422, $validationErrors);
            }

            $safeData = $validator->getSafeData();
            $userInput = trim($safeData['whisper']);

            $whisperData = $this->genWhisper($userInput);
            $this->sendSuccess($whisperData);

        } catch (\Throwable $th) {
            $this->sendError("Invalid JSON payload.", 400);
        }
    }

    private function genWhisper($userInput): array {
        $sql = "SELECT name FROM WH_Units WHERE name LIKE :search LIMIT 10";
        $params = [
            ':search' => $userInput . '%'
        ];
        return $this->db->paramsQuery($sql, $params);
    }
}
