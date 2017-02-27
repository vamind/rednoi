<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $tweets = $this->container->get('twitter.api')->getData();
        return $this->render('AppBundle:default:index.html.twig', [
            'tweets' => $tweets,
        ]);
    }
}
