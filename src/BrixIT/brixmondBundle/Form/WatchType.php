<?php

namespace BrixIT\brixmondBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WatchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'admin.watches.form.name.label'
            ])
            ->add('type', 'choice', [
                'choices' => [
                    'point' => 'admin.watches.types.point',
                    'info' => 'admin.watches.types.info',
                ],
                'label' => 'admin.watches.form.type.label'
            ])
            ->add('system', 'text', [
                'label' => 'admin.watches.form.system.label'
            ])
            ->add('expression', 'textarea', [
                'label' => 'admin.watches.form.expression.label'
            ])
            ->add('notificationTitle', 'text', [
                'label' => 'admin.watches.form.notificationtitle.label'
            ])
            ->add('notificationMessage', 'textarea', [
                'label' => 'admin.watches.form.notificationmessage.label'
            ])
            ->add('action', 'choice', [
                'choices' => [
                    'info' => 'admin.watches.actions.info',
                    'warning' => 'admin.watches.actions.warning',
                    'error' => 'admin.watches.actions.error',
                    'drop' => 'admin.watches.actions.drop'
                ],
                'label' => 'admin.watches.form.action.label'
            ])
            ->add('debug', 'checkbox', [
                'label' => 'admin.watches.form.debug.label'
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BrixIT\brixmondBundle\Entity\Watch'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brixit_brixmondbundle_watch';
    }
}
