<?php

namespace App\Controller;

use App\Repository\CharacService;
use App\Repository\ClassService;
use App\Utils\DataInitialiser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ClassesController extends AbstractController
{
    private ClassService $classService;
    private CharacService $characService;

    private DataInitialiser $dataInitialiser;

    public function __construct(ClassService $classService,
                                CharacService $characService,
                                DataInitialiser $dataInitialiser
    ) {
        $this->classService = $classService;
        $this->characService = $characService;

        $this->dataInitialiser = $dataInitialiser;
    }
    public function index(): Response
    {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data['classes'] = $this->classService->getAll();

        foreach ($data['classes'] as $class) {
            $characs = $this->characService->getByClass($class);
            $data['characs'][$class->getName()] = $characs;
        }

        return $this->render("classes/index.html.twig", $data);
    }
}