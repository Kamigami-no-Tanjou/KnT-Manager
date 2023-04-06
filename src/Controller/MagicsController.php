<?php

namespace App\Controller;

use App\Entity\Charac;
use App\Repository\CharacService;
use App\Repository\MagicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MagicsController extends AbstractController
{
    private MagicService $magicService;
    private CharacService $characService;

    public function __construct(MagicService $magicService,
                                CharacService $characService
    ) {
        $this->magicService = $magicService;
        $this->characService = $characService;
    }

    public function index(): Response {
        // Data retrieval
        $data = array();
        $data['magics'] = $this->magicService->getAll();

        return $this->render('magics/index.html.twig', $data);
    }

    public function magicsInfo(int $id): Response {
        // Data retrieval
        $data = array();
        $data['magic'] = $this->magicService->getById($id);

        $data['users'] = $this->characService->getByMagic($data['magic']);
        $data['instinct'] = 0;
        $data['learned'] = 0;

        foreach($data['users'] as $user) {
            /* @var $user Charac */
            if ($user->getMagics()[0]->getId() == $data['magic']->getId()) {
                $data['instinct']++;
            } else {
                $data['learned']++;
            }
        }

        return $this->render('magics/infos.html.twig', $data);
    }

    public function magicsInstinct(int $id): Response {
        // Data retrieval
        $data = array();
        $data['magic'] = $this->magicService->getById($id);

        $data['instinctUsers'] = $this->characService->getByInstinctMagic($data['magic']);

        return $this->render('magics/instinct.html.twig', $data);
    }

    public function magicsLearnt(int $id): Response {
        // Data retrieval
        $data = array();
        $data['magic'] = $this->magicService->getById($id);

        $data['learntUsers'] = $this->characService->getByLearntMagic($data['magic']);

        return $this->render('magics/learnt.html.twig', $data);
    }
}