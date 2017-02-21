<?php

namespace AppBundle\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use AppBundle\Entity\User;

/**
 * Description of HashPasswordListener
 */
class HashPasswordListener implements EventSubscriber
{
    private $passwordEncoder;
    
    function __construct( UserPasswordEncoder $passwordEncoder ) 
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    
    public function prePersist( LifecycleEventArgs $args )
    {
        $entity = $args->getEntity();
        
        if(!$entity instanceof User) {
            return;
        }
        
        $this->encodePassword($entity);
    }
    
    public function preUpdate( LifecycleEventArgs $args )
    {
        $entity = $args->getEntity();
        
        if(!$entity instanceof User) {
            return;
        }
        
        $this->encodePassword($entity);
        
        // Block needed because of a quirk in Doctrine. To tell it that the object has changed.
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet( $meta, $entity );       
    }
    
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    /**
     * @param User $entity
     */
    private function encodePassword( User $entity )
    {
        if (!$entity->getPlainPassword()) {
            return;
        }
        
        $encoded = $this->passwordEncoder->encodePassword( $entity, $entity->getPlainPassword() );
        $entity->setPassword( $encoded );
    }
}
