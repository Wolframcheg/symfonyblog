<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;



class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $postManager = $this->container->get('app.post_manager');
        $pagination = $postManager->getPosts($request);

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('AppBundle:Common:renderPostList.html.twig',['posts' => $pagination]);
            return new Response($content);
        }

        return ['posts' => $pagination];
    }

    /**
     * @Route("/search", name="search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $postManager = $this->container->get('app.post_manager');
        $pagination = $postManager->getPostsBySearchQuery($request);

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('AppBundle:Common:renderPostList.html.twig',['posts' => $pagination]);
            return new Response($content);
        }

        return ['posts' => $pagination];
    }

    /**
     * @Route("/post/{slug}", name="show_post", requirements={"slug" = "[a-zA-Z1-9\-_\/]+"},)
     * @Template()
     */
    public function showAction(Request $request, $slug)
    {
        $postManager = $this->container->get('app.post_manager');

        return $postManager->getFrontShowElements($request, $slug);
    }


    /**
     * Deletes a Comment entity.
     *
     * @Route("/comment/{id}", name="front_comment_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function deleteCommentAction(Request $request, Comment $comment)
    {
        $response = new JsonResponse();

        // check for "edit" access: calls all voters
        $perm = $this->isGranted('edit', $comment);

        if($perm){
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
            $response->setData(['success' => true]);
        } else  $response->setData(['success' => false]);


        return $response;
    }




}
