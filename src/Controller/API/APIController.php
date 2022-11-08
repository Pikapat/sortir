<?php

namespace App\Controller\API;

use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    #[Route('/listeLieu/{id}', name: 'listeLieu')]
    public function listeLieuDesVille(Request $request, LieuRepository $lieuRepository, $id = 1)
    {
        $lieux = $lieuRepository->findAllLieuxByVille($id);

        return $this->json($lieux, 200, [],  ['groups' => 'show_product',

        ]);

    }
}