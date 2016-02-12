<?php
namespace AppBundle\Services;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PostManager{

    protected $doctrine;
    protected $knpPaginator;
    protected $limit;
    protected $formFactory;
    protected $router;
    protected $tokenStorage;

    public function __construct(RegistryInterface $doctrine,
                                PaginatorInterface $knpPaginator,
                                $limit = 10,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                TokenStorageInterface $tokenStorage
    )
    {
        $this->doctrine = $doctrine;
        $this->knpPaginator = $knpPaginator;
        $this->limit = $limit;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    public function getPostsByTag(Request $request)
    {
        $tag = $request->get('tag');
        $currentPage = $request->query->getInt('page', 1);

        $em = $this->doctrine->getManager();
        $query = $em->getRepository('AppBundle:Post')->findByTagQuery($tag);

        $pagination = $this->knpPaginator->paginate(
            $query, /* query NOT result */
            $currentPage/*page number*/,
            $this->limit/*limit per page*/
        );
        return $pagination;
    }

    public function getPosts(Request $request)
    {
        $currentPage = $request->query->getInt('page', 1);
        $em = $this->doctrine->getManager();
        $query = $em->getRepository('AppBundle:Post')->findAllQuery();

        $pagination = $this->knpPaginator->paginate(
            $query, /* query NOT result */
            $currentPage/*page number*/,
            $this->limit/*limit per page*/
        );
        return $pagination;
    }

    public function getPostsBySearchQuery(Request $request)
    {
        $query = $request->get('q');
        $currentPage = $request->query->getInt('page', 1);

        $em = $this->doctrine->getManager();
        $query = $em->getRepository('AppBundle:Post')->findByQueryQuery($query);

        $pagination = $this->knpPaginator->paginate(
            $query, /* query NOT result */
            $currentPage/*page number*/,
            $this->limit/*limit per page*/
        );

        return $pagination;
    }


    public function getFrontShowElements(Request $request, $slug )
    {
        $em = $this->doctrine->getManager();
        $post = $em->getRepository('AppBundle:Post')->findBySlug($slug);
        $user = $this->getUser();

        if($post[0] === null)
            throw  new NotFoundHttpException('Post not found');

        $comment = new Comment();
        $comment->setPost($post[0]);
        $comment->setUser($user);

        $form = $this->formFactory->create(CommentType::class, $comment, [
            'method' => Request::METHOD_POST,
        ]);

        $form
            ->add('save', SubmitType::class, array(
                'label' => 'Submit Comment',
                'attr' => array('class' => "btn btn-primary")
            ));

        if ($request->getMethod() == 'POST' && $user) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($comment);
                $em->flush();
                $url = $this->router->generate('show_post', ['slug' => $slug]);
                return new RedirectResponse($url, 301);
            }
        }

        return ['post' => $post, 'formComment' => $form->createView()];
    }


    protected function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }



}