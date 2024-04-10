<?php

namespace App\Controller;

use App\Entity\Champion;
use App\Entity\Fight;
use App\Entity\User;
use App\Entity\UserChampion;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class FightController extends AbstractController
{
    #[Route('/fight', name: 'app_fight')]
    public function createFight(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $email1 = $data['user1'];
        $email2 = $data['user2'];
        if (empty($email1) || empty($email2)) {
            return new JsonResponse(['error' => (empty($email1) ? "Email 1" : "Email 2") . " not given"], 400);
        }
        if ($email1 === $email2) {
            return new JsonResponse(['error' => "You can't fight yourself"], 400);
        }

        $userRepository = $entityManager
            ->getRepository(User::class);
        $userChampionRepository = $entityManager
            ->getRepository(UserChampion::class);
        $championRepository = $entityManager
            ->getRepository(Champion::class);
        $user1 = $userRepository->findOneBy(['email' => $email1],);
        $user2 = $userRepository->findOneBy(['email' => $email2],);

        if (empty($user1) || empty($user2)) {
            return new JsonResponse(['error' => (empty($user1) ? "User 1" : "User 2") . " not found"], 400);
        }

        $userChampion1 = $userChampionRepository->findOneBy(["user" => $user1]);
        $userChampion2 = $userChampionRepository->findOneBy(["user" => $user2]);

        if (empty($userChampion1) || empty($userChampion2)) {
            return new JsonResponse(['error' => (empty($userChampion1) ? "User 1" : "User 2") . " does not have a champion"], 400);
        }

        $champion1 =
            $championRepository->findOneBy(["id" => $userChampion1->getChampion()]);
        $champion2 =
            $championRepository->findOneBy(["id" => $userChampion2->getChampion()]);

        if (empty($champion1) || empty($champion2)) {
            return new JsonResponse(['error' => (empty($champion1) ? "Champion of user 1" : "Champion of user 1") . " does not exist anymore"], 400);
        }

        $championWinner = startFight($champion1, $champion2);

        $fight = new Fight();
        $fight->setUser1($user1)->setUser2($user2)->setWinner($championWinner === $champion1 ? $user1 : $user2)->setCreatedAt(new \DateTimeImmutable('now'));
        $entityManager->persist($fight);
        $entityManager->flush();

        return new JsonResponse(['winner' => ($championWinner === $champion1 ? $user1->getUsername() : $user2->getUsername()) . " with " . $championWinner->getName()], 200);
    }
}
function startFight(Champion $champion1, Champion $champion2): Champion
{
    $diceRoll1 = random_int(1, 6);
    $diceRoll2 = random_int(1, 6);
    while ($diceRoll1 === $diceRoll2) {
        $diceRoll1 = random_int(1, 6);
        $diceRoll2 = random_int(1, 6);
    }
    if ($diceRoll1 > $diceRoll2) {
        $playerTurn  = 1;
    } else {
        $playerTurn  = 0;
    }
    $lp1 = $champion1->getPv();
    $lp2 = $champion1->getPv();

    while ($lp1 > 0 || $lp2 > 0) {
        $diceRoll = random_int(1, 6);
        switch ($playerTurn) {
            case 0:
                $lp1 = $lp1 - ($champion2->getPower() / $diceRoll);
                $playerTurn = 1;
                break;

            default:
                $lp2 = $lp2 - ($champion1->getPower() / $diceRoll);
                $playerTurn = 0;
                break;
        }
    }
    if ($lp1 < 0) {
        return $champion2;
    } else {
        return $champion1;
    }
}
