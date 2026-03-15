<?php

namespace Warhammerdle\Controllers;

use Warhammerdle\Database\GeneralRepository;

class WhispererController
{
    private $genRepo;

    public function __construct()
    {
        $this->genRepo = new GeneralRepository;        
    }

    public function genWhisper(string $userInput): array {
        $error = [];
        $userInput = trim($userInput);
        if (strlen($userInput) < 0) {
            $error[] = "Your request cannot be empty.";
        }
        if (!preg_match('/^[\p{L}0-9\s\-_]+$/u', $userInput)) {
            $error[] = "Your request cannot have spacial characters in it.";
        }

        $sql = "SELECT name FROM WH_Units WHERE name LIKE :search";
        $params = [
            ':search' => $userInput.'%'
        ];
        $wisper = $this->genRepo->paramsQuery($sql, $params);
        return $wisper;
    }
}