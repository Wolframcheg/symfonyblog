<?php
namespace Tests\AppBundle\Form\Type;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Tag;
use AppBundle\Form\CommentType;
use AppBundle\Form\TagType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'rating' => 1,
            'content' => 'Some text'
        );

        $form = $this->factory->create(CommentType::class);

        $object = new Comment();
        $object->setRating(1);
        $object->setContent('Some text');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}