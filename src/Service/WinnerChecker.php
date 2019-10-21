<?php

namespace App\Service;

use App\Repository\GameRepository;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

class WinnerChecker 
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

        foreach ($winningLines as $line) {
            if (count(array_unique($line)) === 1) {
                return $this->addFlash(
                    'success',
                    "Congratulations winner is: " . $line[0]
                );
            } elseif (array_search(null, $line) !== false && count(array_unique($line)) !== 1) {
                return $this->addFlash(
                    'success',
                    "It's draw!"
                );
            }
        }
    }
}