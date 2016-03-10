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

            $workshopiId = $request->get('workshop_id', null);
            $parentId = $request->get('parent_id', null);
            $body = $request->get('comment', null);

            /* @var $em EntityManager */
            /* @var $workshop Workshop */
            /* @var $parent Comment */
            $em = $this->getDoctrine()->getManager();
            $workshop = $em->getReference('WorkshopBundle:Workshop', $workshopiId);

            if(!$workshop instanceof Workshop) {
                throw new \Exception('Please provide a valid Workshop ID');
            }

            if(!$body) {
                throw new \Exception('Please provide a Comment');
            }

            $comment = new Comment();
            $comment->setBody($body);
            $comment->setWorkshop($workshop);
            $comment->setAuthor($this->getUser());

            if($parentId) {

                $parent = $em->getReference('CommentBundle:Comment', $parentId);
                $comment->setParent($parent);

            }

            // TODO this needs to be removed, Gedmo/Timestampable isnt fucking working
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());

            $em->persist($comment);
            $em->flush($comment);

            return ['code' => 1, 'comment' => $comment];

        } catch (\Exception $e) {

            return ['code' => 0, 'message' => $e->getMessage()];

        }

    }
}
