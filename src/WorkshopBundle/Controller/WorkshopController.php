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
 * @Route("/api")
 */
class WorkshopController extends Controller
{
    /**
     * Lists all Workshop entities.
     *
     * @Route("/workshops", name="api_workshops", defaults={"_format"="json"})
     * @Method("GET")
     */
    public function workshopsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $workshops = $em->getRepository('WorkshopBundle:Workshop')->findBy([], ['startsAt' => 'DESC']);

        return $workshops;
    }

    /**
     * Lists a Workshop entity.
     *
     * @Route("/workshop/{id}", name="api_workshop", defaults={"_format"="json"})
     * @Method("GET")
     */
    public function workshopAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id', null);

        if(!$id) {
            throw new \Exception('Please provide an ID');
        }

        $workshop = $em->getRepository('WorkshopBundle:Workshop')->findOneBy(['id' => $id]);

        return $workshop;
    }

}
