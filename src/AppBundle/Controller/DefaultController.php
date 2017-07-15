<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Service\TwitterAPIService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function indexAction()
    {
        return $this->render('AppBundle:default:index.html.twig', [
            'tweets' => $this->twitterAPIService->getData(),
        ]);
    }
}
