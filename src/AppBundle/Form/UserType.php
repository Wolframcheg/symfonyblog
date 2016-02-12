<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true
            ])
            ->add('username', TextType::class, [
                'disabled' => true
            ])
            ->add('is_active', CheckboxType::class, [
                'label'    => 'Active',
                'required' => false
            ])
            ->add('role', ChoiceType::class, [
                'choices'  => [
                     'User' => User::ROLE_USER,
                     'Manager' => User::ROLE_MANAGER
                ],
                'choices_as_values' =>true,
                'expanded'  => true,
                'multiple'  => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User'
        ]);
    }

}