<?php

namespace WorkshopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WorkshopBundle\Entity\Workshop;

/**
 * Workshop controller.
 *
 * @Route("/api/workshop")
 */
class WorkshopController extends Controller
{
    /**
     * Lists all Workshop entities.
     *
     * @Route("/", name="api_workshop_index", defaults={"_format"="json"})
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $workshops = $em->getRepository('WorkshopBundle:Workshop')->findAll();

        return $workshops;
    }

}
