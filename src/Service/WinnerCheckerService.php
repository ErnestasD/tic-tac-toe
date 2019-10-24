<?php

namespace App\Service;

use App\Repository\GameRepository;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

class WinnerCheckerService 
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->gameRepository = $entityManager->getRepository(Game::class);
    }

    public function checkForWinner($id)
    {
        $game = $this->gameRepository->find($id);

        $x1 = $game->getX1();
        $x2 = $game->getX2();
        $x3 = $game->getX3();
        $x4 = $game->getX4();
        $x5 = $game->getX5();
        $x6 = $game->getX6();
        $x7 = $game->getX7();
        $x8 = $game->getX8();
        $x9 = $game->getX9();

        // Visos imanomos linijos norint laimeti zaidima.
        // Jeigu linijos masyvo visos reiksmes vienodos, zaidimas yra baigtas ir laimetojas yra betkuri tos linijos reiksme
        $winningLines = [
            [$x1, $x2, $x3],
            [$x4, $x5, $x6],
            [$x7, $x8, $x9],
            [$x1, $x4, $x7],
            [$x2, $x5, $x8],
            [$x3, $x6, $x9],
            [$x1, $x5, $x9],
            [$x3, $x5, $x7]
        ];

        $drawLines = [];

        // Po kiekvieno ejimo tikrinamos liniju reiksmes t.y. ar nera liniju su visom vienodom vertem jeigu ne 
        // ta linija pridedama prie "Lygiuju" masyvo
        foreach ($winningLines as $line) {
            if (count(array_unique($line)) === 1) {
                return $line[0];
            } elseif (array_search(null, $line) == false && count(array_unique($line)) !== 1) {
                $line = false;
                array_push($drawLines, $line);
            }
        }

        // Jeigu visos "Lygiuju" masyvo reiksmes yra vienodos t.y. nei viena is liniju neturi 3-ju vienodu reiksmiu, service'as grazina lygiasas 
        if (count(array_unique($drawLines)) === 1 && count($drawLines) === 8) {
            return "Draw";
        }
    }
}