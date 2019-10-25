<?php

namespace App\Service;

use App\Repository\GameRepository;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

class CPUMoveService 
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->gameRepository = $entityManager->getRepository(Game::class);
    }


    public function cpuMakeMove($id) {
        $game = $this->gameRepository->find($id);

        $data = [
            'game' => $game,
            'key' => ''
        ];

        $x1 = $game->getX1();
        $x2 = $game->getX2();
        $x3 = $game->getX3();
        $x4 = $game->getX4();
        $x5 = $game->getX5();
        $x6 = $game->getX6();
        $x7 = $game->getX7();
        $x8 = $game->getX8();
        $x9 = $game->getX9();

        $board = [
            "X1" => $x1,
            "X2" => $x2,
            "X3" => $x3,
            "X4" => $x4,
            "X5" => $x5,
            "X6" => $x6,
            "X7" => $x7,
            "X8" => $x8,
            "X9" => $x9
        ];

        // visos imanomos zaidimo linijos
        $lines = [
            ['X1' => $x1, 'X2' => $x2, 'X3' => $x3],
            ['X4' => $x4, 'X5' => $x5, 'X6' => $x6],
            ['X7' => $x7, 'X8' => $x8, 'X9' => $x9],
            ['X1' => $x1, 'X4' => $x4, 'X7' => $x7],
            ['X2' => $x2, 'X5' => $x5, 'X8' => $x8],
            ['X3' => $x3, 'X6' => $x6, 'X9' => $x9],
            ['X1' => $x1, 'X5' => $x5, 'X9' => $x9],
            ['X3' => $x3, 'X5' => $x5, 'X7' => $x7]
        ];

        // Jeigu pirmas CPU ejimas daryti ejima i vidurini langeli (tuo atveju kai zaidejo pirmasis ejimas ne viduriniame langelyje)
        // Statistiskai uzemus vidurini langeli maziausia tikimybe pralaimeti
        $movesCounter = 0;

        foreach ($board as $unit) {
            if ($unit != null) {
                $movesCounter++;
            }
        }

        if ($movesCounter === 1) {
            $data = $this->firstMove($game);
        } else {
            // Pagal prioriteta CPU pirmoje vietoje tikrina ar nera linijos masyvo, kuriame butu 2 CPU reiksmes
            // tai yra, jeigu CPU likusioje laisvoje tos linijos vietoje padarys savo ejima - laimes
            $data = $this->tryWinMove($lines, $game);
            if ($data == false) {
                // Jeigu nera ejimo, po kurio laimetu CPU, tikrinama ar nera liniju kuriu dvi reiksmes yra 'user' t.y. jeigu CPU neatliks ejimo toje linijoje - pralaimes
                $data = $this->tryBlockUser($lines, $game);
            } 
            if ($data == null) {
                // Jeigu nera case'o, kad kompiuteris laimetu arba blokuotu zaidejo lemiama ejima, atliekamas atsitiktinis ejimas
                $data = $this->randomMove($game, $board);
            }
        }

        $this->entityManager->flush();

        return $data['key'];
    }

    // Pirmojo ejimo funkcija
    function firstMove(Game $game)
    {
        $x1 = $game->getX1();
        $x2 = $game->getX2();
        $x3 = $game->getX3();
        $x4 = $game->getX4();
        $x5 = $game->getX5();
        $x6 = $game->getX6();
        $x7 = $game->getX7();
        $x8 = $game->getX8();
        $x9 = $game->getX9();

        $board = [
            "X1" => $x1,
            "X2" => $x2,
            "X3" => $x3,
            "X4" => $x4,
            "X5" => $x5,
            "X6" => $x6,
            "X7" => $x7,
            "X8" => $x8,
            "X9" => $x9
        ];

        // Jeigu po pirmo zaidejo ejimo vidurinis langelis laisvas, ejimas turi buti atliktas butent ten
        // Jeigu zaidejas pirmuoju ejimu uzima vidurini langeli, CPU atlieka ejima atsitiktinai
        if ($board["X5"] == null) {
            $game->setX5("CPU");
            $data = [
                'game' => $game,
                'key' => 'X5'
            ];
        } elseif ($board["X5"] != null) {
            $data = $this->randomMove($game, $board);
        }

        return $data;
    }

    // Atsitiktinio ejimo funkcija
    function randomMove(Game $game, $board)
    {
        $randomKey = array_rand($board);

        $randomElement = $board[$randomKey];

        while($randomElement != null) {
            $randomKey = array_rand($board);
            $randomElement = $board[$randomKey];
        }

        $action = "set" . $randomKey;
        $game->$action("CPU");

        return [
            'game' => $game,
            'key' => $randomKey
        ];
    }

    // ejimo cpu pergalei pasiekti funkcija
    function tryWinMove($lines, $game) {
        foreach ($lines as $line) {
            $cpuCounter = 0;
            $nullCounter = 0;
            foreach ($line as $key => $val) {
                if ($val == 'CPU') {
                    $cpuCounter++;
                } elseif ($val == null) {
                    $nullCounter++;
                }
            }
            // tikrinama ar nera liniju masyvo, kurio 2 reiksmes butu 'CPU', tai reiskia kad truksta vieno ejimo CPU pergalei
            if ($cpuCounter == 2 && $nullCounter == 1) {
                $nullIndex = array_search(null, $line);
                $action = 'set' . $nullIndex;
                $game->$action('CPU');

                $data = [
                    'game' => $game,
                    'key' => $nullIndex
                ];
                return $data;
            }
        }

        return false;
    }

    // funkcija neleidzianti zaidejui laimeti
    function tryBlockUser($lines, $game) {
        foreach ($lines as $line) {
            $userCounter = 0;
            $nullCounter = 0;
            foreach ($line as $key => $val) {
                if ($val == 'user') {
                    $userCounter++;
                } elseif ($val == null) {
                    $nullCounter++;
                }
            }
            // Tikrinama ar nera liniju masyvo, kurio 2 reiksmes butu 'user', tai yra CPU butinai turi blokuoti ta linija, kad nepralaimetu
            if ($userCounter == 2 && $nullCounter == 1) {
                $nullIndex = array_search(null, $line);
                $action = 'set' . $nullIndex;
                $game->$action('CPU');

                $data = [
                    'game' => $game,
                    'key' => $nullIndex
                ];
                return $data;
            }
        }

        return null;
    }
}