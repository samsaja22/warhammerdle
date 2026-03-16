<?php
declare(strict_types=1);

namespace Warhammerdle\Controllers;

use Warhammerdle\Database\GeneralRepository;

class GuessHandle
{
    private $genRepo;

    public function __construct()
    {
        $this->genRepo = new GeneralRepository;        
    }

    public function genGuess(): void {
        $guessFound  = false;
        $lastFifteenGuesses = $this->genRepo->simpleQuery("SELECT * FROM WH_Guesses ORDER BY `date` DESC LIMIT 15;");
        $recentUnitIds = array_column($lastFifteenGuesses, 'units_id');

        $firstUnitId = $this->genRepo->simpleQuery("SELECT id FROM WH_Units ORDER BY id ASC LIMIT 1;");
        $lastUnitId = $this->genRepo->simpleQuery("SELECT id FROM WH_Units ORDER BY id DESC LIMIT 1;");

        $firstUnitId = (int)$firstUnitId[0];
        $lastUnitId = (int)$lastUnitId[0];

        while ($guessFound === false) {
            $guessId = rand($firstUnitId, $lastUnitId);

            if (in_array($guessId, $recentUnitIds)) {
                continue; 
            }

            $sql = "SELECT * FROM WH_Units WHERE id LIKE :search";
            $params = [
                ':search' => $guessId
            ];
            $guessById = $this->genRepo->paramsQuery($sql, $params);

            if (!empty($guessById)) {
                $guessFound = true;
            }
        }
    }

    public function evaluateGuess(string $usersGuess): array {
        $unitByName = $this->genRepo->paramsQuery("SELECT * FROM WH_Units WHERE name = :search", [":search" => $usersGuess]);
        $todaysGuess = $this->genRepo->paramsQuery("SELECT * FROM WH_Guesses WHERE date = :search", [":search" => date("Y-m-d")]);

        // foreach ($unitByName as $unit => $unitData) {
        //     if (condition) {
        //         # code...
        //     }
        // }
    }
}