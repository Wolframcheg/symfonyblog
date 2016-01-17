<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 08.01.16
 * Time: 18:45
 */

namespace AppBundle\Twig;


use Symfony\Bridge\Doctrine\RegistryInterface;

class AppExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('tagsList',
                [$this, 'getTags'],
                [
                    'needs_environment' => true,
                    'is_safe' => array('html')
                ]
            ),
            new \Twig_SimpleFunction('lastComments',
                array($this, 'getLastComments'),
                array(
                    'needs_environment' => true,
                    'is_safe' => array('html'))
            ),
            new \Twig_SimpleFunction('popularPosts',
                array($this, 'getPopularPosts'),
                array(
                    'needs_environment' => true,
                    'is_safe' => array('html'))
            ),
        ];
    }


    public function getTags(\Twig_Environment $twig)
    {
        $em = $this->doctrine->getManager();
        $tags = $em->getRepository("AppBundle:Tag")
            ->getTagsWithCount();

        return $twig->render(
            'AppBundle:Common:tagsJson.html.twig',
            array(
                'tags' => $tags,
            )
        );
    }

    public function getLastComments(\Twig_Environment $twig)
    {
        $em = $this->doctrine->getManager();
        $comments = $em->getRepository("AppBundle:Comment")
            ->getLastComments(5);

        return $twig->render(
            'AppBundle:Common:lastComments.html.twig',
            array(
                'comments' => $comments,
            )
        );
    }

    public function getPopularPosts(\Twig_Environment $twig)
    {
        $em = $this->doctrine->getManager();
        $populars= $em->getRepository("AppBundle:Post")
            ->getPopularPosts(5);

        //var_dump($populars);exit();
        return $twig->render(
            'AppBundle:Common:popularPosts.html.twig',
            array(
                'posts' => $populars,
            )
        );
    }

    public function getName()
    {
        return 'app_extension';
    }
}