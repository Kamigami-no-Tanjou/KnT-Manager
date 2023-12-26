<?php

namespace App\Controller;

use App\Entity\Charac;
use App\Repository\CharacService;
use App\Repository\MagicService;
use App\Utils\DataInitialiser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MagicsController extends AbstractController
{
    private MagicService $magicService;
    private CharacService $characService;

    private DataInitialiser $dataInitialiser;

    public function __construct(MagicService $magicService,
                                CharacService $characService,
                                DataInitialiser $dataInitialiser
    ) {
        $this->magicService = $magicService;
        $this->characService = $characService;

        $this->dataInitialiser = $dataInitialiser;
    }

    public function index(): Response {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data['magics'] = $this->magicService->getAll();

        return $this->render('magics/index.html.twig', $data);
    }

    public function magicsInfo(int $id): Response {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data['magic'] = $this->magicService->getById($id);
        if ($data['magic'] == null) {
            return $this->render("errors/404.html.twig", $data);
        }

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
        $data = $this->dataInitialiser->getBaseData();
        $data['magic'] = $this->magicService->getById($id);
        if ($data['magic'] == null) {
            return $this->render("errors/404.html.twig", $data);
        }

        $data['instinctUsers'] = $this->characService->getByInstinctMagic($data['magic']);

        return $this->render('magics/instinct.html.twig', $data);
    }

    public function magicsLearnt(int $id): Response {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data['magic'] = $this->magicService->getById($id);
        if ($data['magic'] == null) {
            return $this->render("errors/404.html.twig", $data);
        }

        $data['learntUsers'] = $this->characService->getByLearntMagic($data['magic']);

        return $this->render('magics/learnt.html.twig', $data);
    }
}