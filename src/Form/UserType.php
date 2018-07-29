<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Groups;
use App\Entity\User;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Name',
                'required' => true
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password')
            ))
            ->add('Groups', EntityType::class, array(
                'class' => Groups::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => true,
                'expanded' => false
            ))
            ->add('save', SubmitType::class)
        ;
    }
}