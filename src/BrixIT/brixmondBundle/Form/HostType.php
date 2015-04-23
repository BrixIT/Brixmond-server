<?php

namespace BrixIT\brixmondBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'admin.hosts.form.name.label'
            ])
            ->add('hostname', null, [
                'label' => 'admin.hosts.form.hostname.label'
            ])
            ->add('type', 'choice', [
                'label' => 'admin.hosts.form.type.label',
                'choices' => [
                    'server' => 'admin.hosts.types.server',
                    'vps' => 'admin.hosts.types.vps',
                    'vm' => 'admin.hosts.types.vm',
                    'edgerouter' => 'admin.hosts.types.edgerouter'
                ],
                'expanded' => false,
                'multiple' => false
            ])
            ->add('client', null, [
                'label' => 'admin.hosts.form.client.label'
            ])
            ->add('parent', null, [
                'label' => 'admin.hosts.form.parent.label'
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BrixIT\brixmondBundle\Entity\Host'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brixit_brixmondbundle_host';
    }
}
