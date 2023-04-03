<?php

namespace App\Controller;

use App\Repository\CharacService;
use App\Repository\ClassService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ClassesController extends AbstractController
{
    private ClassService $classService;
    private CharacService $characService;

    public function __construct(ClassService $classService,
                                CharacService $characService
    ) {
        $this->classService = $classService;
        $this->characService = $characService;
    }
    public function index(): Response
    {
        // Data retrieval
        $data = array();
        $data['classes'] = $this->classService->getAll();

        foreach ($data['classes'] as $class) {
            $characs = $this->characService->getByClass($class);
            $data['characs'][$class->getName()] = $characs;
        }

        return $this->render("classes/index.html.twig", $data);
    }
}