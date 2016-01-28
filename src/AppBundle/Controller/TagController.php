<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

class TagController extends Controller
{
    /**
     * @Route("/tag", name="posts_by_tag")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $postManager = $this->container->get('app.post_manager');
        $pagination = $postManager->getPostsByTag($request);

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('AppBundle:Common:renderPostList.html.twig',['posts' => $pagination]);
            return new Response($content);
        }

        return ['posts' => $pagination];
    }
}
