<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Service\TwitterAPIService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DefaultController extends Controller
{
    /**
     * @var TwitterAPIService
     */
    private $twitterAPIService;

    public function __construct(TwitterAPIService $twitterAPIService)
    {
        $this->twitterAPIService = $twitterAPIService;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(): Response
    {
        return $this->render('AppBundle:default:index.html.twig', [
            'tweets' => $this->twitterAPIService->getData(),
        ]);
    }

    /**
     * @Route("/retweet", name="retweet", methods={"POST"})
     */
    public function retweetAction(Request $request): Response
    {
        $id = $request->get('id');
        $data = $this->twitterAPIService->retweet($id);
        
        return new JsonResponse($data);
    }

    /**
     * @Route("/like", name="like", methods={"POST"})
     */
    public function likeAction(Request $request): Response
    {
        $id = $request->get('id');
        $data = $this->twitterAPIService->like($id);
        
        return new JsonResponse($data);
    }

    /**
     * @Route("/unretweet", name="unretweet", methods={"POST"})
     */
    public function unretweetAction(Request $request): Response
    {
        $id = $request->get('id');
        $data = $this->twitterAPIService->unretweet($id);
        
        return new JsonResponse($data);
    }

    /**
     * @Route("/unlike", name="unlike", methods={"POST"})
     */
    public function unlikeAction(Request $request): Response
    {
        $id = $request->get('id');
        $data = $this->twitterAPIService->unlike($id);
        
        return new JsonResponse($data);
    }
}
