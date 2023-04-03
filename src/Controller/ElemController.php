<?php

namespace App\Controller;

use App\Repository\ElemService;
use App\Repository\MagicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ElemController extends AbstractController
{
    private ElemService $elemService;
    private MagicService $magicService;

    public function __construct(ElemService $elemService,
                                MagicService $magicService
    ) {
        $this->elemService = $elemService;
        $this->magicService = $magicService;
    }
    public function index(): Response {
        // Data retrieval
        $data = array();
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
}