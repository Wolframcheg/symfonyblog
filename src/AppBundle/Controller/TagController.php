<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
        $tag = $request->get('tag');

        $limit = 5;

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Post')->findByTagQuery($tag);

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $limit/*limit per page*/
        );

        return ['posts' => $pagination];
    }
}
