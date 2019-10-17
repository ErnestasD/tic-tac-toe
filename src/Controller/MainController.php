<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
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

        return $this->redirectToRoute(
            'game_page',
            [
                'id' => $game->getId()
            ]
        );
    }

    /**
     * @Route("/game-{id}", name="game_page")
     */
    public function gamePage($id)
    {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);

        return $this->render('game.html.twig');
    }
}