<?php

namespace AppBundle\Controller\Cabinet;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Post controller.
 *
 * @Route("/cabinet")
 */
class DefaultController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @Route("", name="cabinet_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Post')->findAllByOwner($user->getId());

        $deleteForms = [];
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        }

        return [
            'entities' => $entities,
            'deleteForms' => $deleteForms
        ];
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/new", name="post_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $user = $this->getUser();
        $post->setOwner($user);

        $form = $this->createForm('AppBundle\Form\PostType', $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('cabinet_index');
        }

        return [
            'post' => $post,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/{id}/edit", name="cabinet_post_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Post $post)
    {
        $perm = $this->isGranted('edit', $post);
        if(!$perm)
            throw new HttpException(403, "Not allow");
        $editForm = $this->createForm('AppBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('cabinet_index');
        }

        return [
            'post' => $post,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Post entity.
     *
     * @Route("/{id}", name="cabinet_post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $perm = $this->isGranted('edit', $post);
        if(!$perm)
            throw new HttpException(403, "Not allow");

        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirectToRoute('cabinet_index');
    }

    /**
     * Creates a form to delete a Post entity.
     *
     * @param Post $post The Post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cabinet_post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, ['label' => ' ', 'attr' => ['class' => 'glyphicon glyphicon-trash btn-link']])
            ->getForm();
    }

}
