<?php

namespace App\Controller;

use App\Entity\Champion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin')]
class ChampionController extends AbstractController
{
    #[Route('/champions/add', name: 'app_champion', methods: ['POST'])]
    public function createChampion(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $pv = $data['pv'];
        $power = $data['power'];


        // Check if the request is valid
        if (empty($name) || empty($pv) || empty($power)) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }
        if ($pv <= 0) {
            return new JsonResponse(['error' => 'Invalid PV variable'], 400);
        }
        if ($power <= 0) {
            return new JsonResponse(['error' => 'Invalid power variable'], 400);
        }
        $champion = $entityManager
            ->getRepository(Champion::class)
            ->findOneBy(['name' => $name]);

        if ($champion) {
            return new JsonResponse(['error' => 'Champion already exists'], 400);
        }

        $champion = new Champion();
        $champion->setName($name)->setPower($power)->setPv($pv);
        $entityManager->persist($champion);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Champion created'], 201);
    }
}
