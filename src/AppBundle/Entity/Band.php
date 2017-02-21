<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BandRepository")
 * @ORM\Table(name="band")
 */
class Band 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SubGenre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subGenre;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $funFact;
    
    /**
     * @ORM\Column(type="boolean") 
     */
    private $isPublished;
    
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     */
    private $firstDiscoveredAt;
    
    
    // GETTERS AND SETTERS **************************************
    
    public function getId() 
    {
        return $this->id;
    }
    
    public function getName() 
    {
        return $this->name;
    }

    public function setName( $name ) 
    {
        $this->name = $name;
    }
    
    /**
     * @return SubGenre
     */
    public function getSubGenre() 
    {
        return $this->subGenre;
    }
    
    public function setSubGenre(SubGenre $subGenre = null) 
    {
        $this->subGenre = $subGenre;
    }

    public function getFunFact() 
    {
        return $this->funFact;
    }

    public function setFunFact($funFact) 
    {
        $this->funFact = $funFact;
    }

    public function getUpdatedAt()
    {
        return new \DateTime('-' . rand(0, 100). ' days');
    }
    
    function getIsPublished() 
    {
        return $this->isPublished;
    }

    function setIsPublished($isPublished) 
    {
        $this->isPublished = $isPublished;
    }

    public function getFirstDiscoveredAt()
    {
        return $this->firstDiscoveredAt;
    }

    public function setFirstDiscoveredAt(\DateTime $firstDiscoveredAt = null)
    {
        $this->firstDiscoveredAt = $firstDiscoveredAt;
    }
}
