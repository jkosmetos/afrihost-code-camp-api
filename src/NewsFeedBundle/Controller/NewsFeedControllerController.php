<?php

namespace NewsFeedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * News Feed controller.
 *
 * @Route("/api")
 */
class NewsFeedControllerController extends Controller
{
    /**
     * Lists all News Feed Item entities.
     *
     * @Route("/news", name="api_news", defaults={"_format"="json"})
     * @Method("GET")
     */
    public function newsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $newsFeedItems = $em->getRepository('NewsFeedBundle:NewsFeedItem')->findAll();

        return $newsFeedItems;
    }

}
