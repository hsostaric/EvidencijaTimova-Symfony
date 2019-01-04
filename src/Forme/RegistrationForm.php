<?php

namespace App\Forme;
use  App\Entity\Korisnik;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

/**
 * Created by PhpStorm.
 * User: Hrvoje
 * Date: 03-Jan-19
 * Time: 17:40
 */

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('Name')
            ->add('Surname')
            ->add('Username')
            ->add("Email",EmailType::class)
            ->add("Password",PasswordType::class)->setMethod('POST')
           ;

    }


}
