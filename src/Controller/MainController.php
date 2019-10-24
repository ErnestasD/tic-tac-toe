<?php

namespace App\Controller;

use App\Entity\Game;
use App\Service\WinnerCheckerService;
use App\Service\CPUMoveService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends AbstractController
{
    private $winnerCheckerService;

    private $cpuMoveService;

    public function __construct(WinnerCheckerService $winnerCheckerService, CPUMoveService $cpuMoveService)
    {
        $this->winnerCheckerService = $winnerCheckerService;
        $this->cpuMoveService = $cpuMoveService;
    }

    /**
     * @Route("/")
     */
    public function main()
    {
        return $this->render(
            'main.html.twig'
        );
    }

    /**
     * @Route("/start-new-game", name="start_new_game")
     */
    public function startNewGame()
    {
        $game = new Game();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($game);
        $entityManager->flush();

        return $this->redirectToRoute('game_page', [
            'id' => $game->getId()
        ]);
    }

    /**
     * @Route("/game-{id}", name="game_page")
     */
    public function gamePage($id)
    {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);

        return $this->render('game.html.twig', [
            'game' => $game
        ]);
    }

    /**
     * @Route("/make-move/{id}/{cell}")
     */
    public function makeMove(string $cell, $id)
    {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);

        //Paduodamos reiksmes validacija ar string ilgis yra 2, bei ar pirmasis stringo elementas yra X bet antrasis skaicius
        if(strlen($cell) == 2 && (str_split($cell)[0] === 'X' && is_numeric(str_split($cell)[1]) ) ) {
            $cell = "set" . $cell;
            $game->$cell('user');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $serviceResponse = $this->winnerCheckerService->checkForWinner($id);
            $this->cpuMoveService->cpuMakeMove($id);

            if ($serviceResponse !== null && $serviceResponse === "Draw") {
                return new JsonResponse('Draw');
            } elseif ($serviceResponse !== null) {
                return new JsonResponse($serviceResponse);
            }

            return new JsonResponse('OK');
        }        
    }
}