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
    public function indexAction()
    {
        var_dump('1');exit();
        return 1;//$this->render('', array('name' => $name));
    }
}
