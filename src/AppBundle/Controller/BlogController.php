<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $limit = 5;

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Post')->findAllQuery();

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $limit/*limit per page*/
        );

        return [
            'posts' => $pagination,
        ];
    }

    /**
     * @Route("/search", name="search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $query = $request->get('q');
        $limit = 5;

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Post')->findByQueryQuery($query);

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $limit/*limit per page*/
        );

        return ['posts' => $pagination];
    }

    /**
     * @Route("/post/{slug}", name="show_post", requirements={"slug" = "[a-zA-Z1-9\-_\/]+"},)
     * @Template()
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findBy(['slug' => $slug]);

        return ['post' => $post];
    }



}
