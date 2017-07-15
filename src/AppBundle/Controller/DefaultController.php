<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Route("/retweet", name="retweet", methods={"POST"})
     */
    public function retweetAction(Request $request)
    {
        $id = $request->get('id');
        $data = $this->container->get('twitter.api')->retweet($id);
        return new Response(json_encode($data), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/like", name="like", methods={"POST"})
     */
    public function likeAction(Request $request)
    {
        $id = $request->get('id');
        $data = $this->container->get('twitter.api')->like($id);
        return new Response(json_encode($data), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/unretweet", name="unretweet", methods={"POST"})
     */
    public function unretweetAction(Request $request)
    {
        $id = $request->get('id');
        $data = $this->container->get('twitter.api')->unretweet($id);
        return new Response(json_encode($data), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/unlike", name="unlike", methods={"POST"})
     */
    public function unlikeAction(Request $request)
    {
        $id = $request->get('id');
        $data = $this->container->get('twitter.api')->unlike($id);
        return new Response(json_encode($data), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
