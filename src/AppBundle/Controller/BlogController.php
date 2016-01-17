<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function showAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findBySlug($slug);

        $comment = new Comment();
        $comment->setPost($post[0]);

        $form = $this->createForm(CommentType::class, $comment, [
            'method' => Request::METHOD_POST,
        ]);

        $form
            ->add('save', SubmitType::class, array(
                'label' => 'Submit Comment',
                'attr' => array('class' => "btn btn-primary")
            ));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($comment);
                $em->flush();
                return $this->redirectToRoute('show_post', ['slug' => $slug], 301);
            }
        }


        return ['post' => $post, 'formComment' => $form->createView()];
    }



}
