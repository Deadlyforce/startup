<?php
namespace AppBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of User
 * 
 * @UniqueEntity(fields={"email"}, message="You already have an account")
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
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", unique=true)
     * @var string 
     */
    private $email;
    
    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];
    
    /**
     * The encoded password
     * 
     * @ORM\Column(type="string")
     */
    private $password;
    
    /**
     * A non-persisted field that's used to create the encoded password.
     * 
     * @Assert\NotBlank(groups={"Registration"})
     * @var string
     */
    private $plainPassword;
    

    public function getUsername(): string 
    {
        return $this->email;
    }
    
    public function getRoles() 
    {
        $roles = $this->roles;
        
        if( !in_array('ROLE_USER', $roles) ) {
            $roles[] = 'ROLE_USER';
        }
        
        return $roles;
    }
    
    public function eraseCredentials() 
    {
        $this->plainPassword = null;
    }

    public function getPassword() 
    {
        return $this->password;
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
    
    public function setPassword($password) 
    {
        $this->password = $password;
    }

    public function getPlainPassword() 
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword) 
    {
        $this->plainPassword = $plainPassword;
        
        // guarantees that the entity looks "dirty" to Doctrine
        // when changing the plainPassword
        $this->password = null;
    }

    public function setRoles($roles) 
    {
        $this->roles = $roles;
    }


}
