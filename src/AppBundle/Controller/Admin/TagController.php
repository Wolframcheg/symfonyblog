<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Tag controller.
 *
 * @Route("/admin/tag")
 */
class TagController extends Controller
{
    /**
     * Lists all Tag entities.
     *
     * @Route("/", name="admin_tag_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Tag')->findAll();

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
     * Creates a new Tag entity.
     *
     * @Route("/new", name="tag_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm('AppBundle\Form\TagType', $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('admin_tag_index');
        }

        return [
            'tag' => $tag,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Tag entity.
     *
     * @Route("/{id}/edit", name="admin_tag_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Tag $tag)
    {
        $editForm = $this->createForm('AppBundle\Form\TagType', $tag);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('admin_tag_index');
        }

        return [
            'tag' => $tag,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Tag entity.
     *
     * @Route("/{id}", name="admin_tag_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Tag $tag)
    {
        $form = $this->createDeleteForm($tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tag);
            $em->flush();
        }

        return $this->redirectToRoute('admin_tag_index');
    }

    /**
     * Creates a form to delete a Tag entity.
     *
     * @param Tag $tag The Tag entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tag $tag)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tag_delete', array('id' => $tag->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, ['label' => ' ', 'attr' => ['class' => 'glyphicon glyphicon-trash btn-link']])
            ->getForm();
    }
}
