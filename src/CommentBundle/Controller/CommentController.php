<?php

namespace CommentBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CommentBundle\Entity\Comment;
use WorkshopBundle\Entity\Workshop;
use WorkshopBundle\Repository\WorkshopRepository;

/**
 * Comment controller.
 *
 * @Route("/api")
 */
class CommentController extends Controller
{
    /**
     * Lists a Workshop entity.
     *
     * @Route("/comment/add", name="api_add_comment", defaults={"_format"="json"})
     * @Method("POST")
     */
    public function addCommentAction(Request $request)
    {

        try {

            /* @var $em EntityManager */
            /* @var $workshopRepo WorkshopRepository */
            $em = $this->getDoctrine()->getManager();
            $workshopRepo = $em->getRepository('WorkshopBundle:Workshop');

            $workshopiId = $request->get('workshop_id', null);
            $parentId = $request->get('parent_id', null);
            $body = $request->get('comment', null);

            /* @var $workshop Workshop */
            $workshop = $workshopRepo->findOneBy(array('id' => $workshopiId));

            if(!$workshop instanceof Workshop) {
                throw new \Exception('Please provide a valid Workshop ID');
            }

            if(!$body) {
                throw new \Exception('Please provide a Comment');
            }

            $comment = new Comment();
            $comment->setBody($body);
            $comment->setWorkshop($workshop);
            $comment->setParentId($parentId);
            $comment->setAuthor($this->getUser());

            // TODO this needs to be removed, Gedmo/Timestampable isnt fucking working
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());

            $em->persist($comment);
            $em->flush($comment);

            return $comment;

        } catch (\Exception $e) {

            return array('code' => 0, 'message' => $e->getMessage());

        }

    }
}
