<?php
namespace Tests\AppBundle\Form\Type;

use AppBundle\Form\PostType;
use AppBundle\Tests\PHPUnit\FormTypeTestCase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;


class PostTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
//        $formData = array(
//            'pagetitle' => 'test',
//            'content' => 'Some text',
//            //'tags' => []
//        );
//
//        $form = $this->factory->create(PostType::class);
//
//        $form->submit($formData);
//
//        $this->assertTrue($form->isSynchronized());
//
//        $view = $form->createView();
//        $children = $view->children;
//
//        foreach (array_keys($formData) as $key) {
//            $this->assertArrayHasKey($key, $children);
//        }
    }




}