<?php
namespace Tests\AppBundle\Form\Type;

use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use AppBundle\Form\Tagype;
//use AppBundle\Model\TestObject;
use Symfony\Component\Form\Test\TypeTestCase;

class TagTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'test'
        );

        $type = new TagType();
        $form = $this->factory->create($type);

        $object = Tag::fromArray($formData);

        // submit the data to the form directly
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