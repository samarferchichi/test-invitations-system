<?php

namespace App\Form;

use App\Entity\Notification;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
{
    /**
     * buildForm.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('receiver', EntityType::class, [
                'class' => User::class,
            ])
        ;
    }

    /**
     * configureOptions.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'allow_extra_fields' => false,
            'extra_fields_message' => 'This form should not contain extra fields : "{{ extra_fields }}".',
            'data_class' => Notification::class,
        ]);
    }

    /**
     * getBlockPrefix.
     *
     * @return string|null
     */
    public function getBlockPrefix()
    {
        return null;
    }
}
