<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * Description of loginForm
 *
 * @author Norman
 */
class LoginForm extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) 
    {
        $builder
            ->add('_username')
            ->add('_password', PasswordType::class)
        ;
    }

}
