<?php
namespace AppBundle\EventSubscribers;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;


class DoctrineEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postPersist',
            'postUpdate'
        );
    }

    /**
     * Action to happen after persisting an entity
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof Comment) {
            $now = new \DateTime();
            $entity->setCreatedAt($now);
            $entity->setUpdatedAt($now);
        }

        if($entity instanceof Post) {
            $now = new \DateTime();
            $entity->setCreatedAt($now);
            $entity->setUpdatedAt($now);
            if (null !== $entity->getFile()) {
                $entity->setPath( $entity->getImageName() . '.' . $entity->getFile()->guessExtension());
            }
        }
    }

    /**
     * Action to happen after persisting an entity
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof Comment) {
            $now = new \DateTime();
            $entity->setUpdatedAt($now);
        }

        if($entity instanceof Post) {
            $now = new \DateTime();
            $entity->setUpdatedAt($now);
            if (null !== $entity->getFile()) {
                $entity->setPath( $entity->getImageName() . '.' . $entity->getFile()->guessExtension());
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Post) {
            $this->upload($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Post) {
            $this->upload($entity);
        }
    }

    protected function upload(Post $entity)
    {
        if (null === $entity->getFile()) {
            return;
        }

        // check if we have an old image
        if (null !== $entity->getTemp()){
            // delete the old image
            unlink($entity->getTemp());
            // clear the temp image path
            $entity->setTemp(null);
        }

        $entity->getFile()->move(
            $entity->getUploadRootDir() . '/' . $entity->getId(),
            $entity->getImageName() . '.' . $entity->getFile()->guessExtension()
        );

        $entity->setFile(null);
    }

}