<?php
namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of User
 * 
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer") 
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", unique=true)
     * @var string 
     */
    private $email;

    public function getUsername(): string 
    {
        return $this->email;
    }
    
    public function getRoles() 
    {
        return ['ROLE_USER'];
    }
    
    public function eraseCredentials() 
    {
        
    }

    public function getPassword(): string 
    {
        
    }

    public function getSalt() 
    {
        
    }
    
    /****************** GETTERS AND SETTERS ********************/
    
    function getId() 
    {
        return $this->id;
    }   
    
    public function getEmail() 
    {
        return $this->email;
    }
    
    public function setEmail($email) 
    {
        $this->email = $email;
    }
   
}
