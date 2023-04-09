<?php

namespace App\Controller;

use App\Utils\DataInitialiser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    private DataInitialiser $dataInitialiser;

    public function __construct(DataInitialiser $dataInitialiser) {
        $this->dataInitialiser = $dataInitialiser;
    }
	public function index(): Response {
		return $this->render("default/index.html.twig", $this->dataInitialiser->getBaseData());
	}
}

