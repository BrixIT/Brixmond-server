<?php

namespace BrixIT\brixmondBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', [
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('password', 'password', [
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('email')
            ->add('pushoverKey');

        $options['attr']['autocomplete'] = 'off';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BrixIT\brixmondBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brixit_brixmondbundle_user';
    }
}
