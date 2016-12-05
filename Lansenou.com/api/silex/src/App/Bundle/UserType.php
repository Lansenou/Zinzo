<?php
/**
 * Created by PhpStorm.
 * User: Lansenou
 * Date: 8/12/2016
 * Time: 18:05
 */

namespace App\Bundle;

use Symfony\Component\Form\Extension\Core\Type\UsernameType;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'constraints' => new Assert\Email()
            ))
            ->add('username', TextType::class, array(
                'constraints' => array(
                    new Assert\Regex(array(
                            'pattern' => '/[^a-z_\-0-9]/i', // FOR SOME FUCKING REASON NOT WORKING KILL ME DOING IT THE BAD WAY IN DOCSCONTROLLER BYE
                            'message' => 'Only alphanumeric characters are allowed.',
                            'match' => false)),
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 3, 'max' => 20))
                ))
            )
            ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                    'constraints' => array(new Assert\NotBlank(), new Assert\Regex(array(
                        'pattern' => '#.*^(?=.{4,255})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#',
                        'message' => 'Password too weak. Needs at least one lower, upper character and a digit.'))
            )));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Bundle\User',
        ));
    }
}