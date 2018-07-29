<?php

// src/A2C/PlatformBundle/Form/SendBroadcastMailType.php

namespace A2C\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * This class build the form used to send a collective email to all the users.
 *
 * @author Vincent
 */
class SendBroadcastMailType extends AbstractType {

    /**
     * @TODO add a choice to select a subset of users
     * By default, send to all users.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', TextType::class)
                ->add('message', TextareaType::class)
                ->add('send', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'A2C\PlatformBundle\Entity\BroadcastMessage'
        ));
    }
}