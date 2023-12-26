<?php

namespace App\Controller;

use App\Repository\ElemService;
use App\Repository\MagicService;
use App\Utils\DataInitialiser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ElemController extends AbstractController
{
    private ElemService $elemService;
    private MagicService $magicService;

    private DataInitialiser $dataInitialiser;

    public function __construct(ElemService $elemService,
                                MagicService $magicService,
                                DataInitialiser $dataInitialiser
    ) {
        $this->elemService = $elemService;
        $this->magicService = $magicService;

        $this->dataInitialiser = $dataInitialiser;
    }
    public function index(): Response {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data['elems'] = $this->elemService->getAll();

        foreach ($data['elems'] as $elem) {
            $magics = $this->magicService->getByElem($elem);
            $data['magics'][$elem->getName()] = $magics;

            foreach ($magics as $magic) {
                $usersAmt = $this->magicService->getUsersCount($magic);
                $data['magicCounts'][$magic->getId()] = $usersAmt;
            }
        }


        return $this->render("elems/index.html.twig", $data);
    }

    public function displayImage(int $id): Response {
        // Data retrieval
        $imageBytes = $this->elemService->getIllustrationById($id);
        if ($imageBytes == null) {
            return $this->render('errors/404.html.twig', $this->dataInitialiser->getBaseData());
        }

        $headers = array(
            'Content-Type' => 'image/png'
        );

        return new Response($imageBytes, 200, $headers);
    }
}