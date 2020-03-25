<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
	/**
	 * Index.
	 *
	 * @Route("/", name="app_home_index")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index()
	{
		return $this->render('Page/Home/index.html.twig');
	}
}
