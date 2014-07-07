<?php

namespace Oblady\ZuulBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RequestConnectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Amélioration possible : ajouter une validation sur la longueur du message 
        // changer les label par quelque chose de plus parlant + placeholder
        $builder->add('message','textarea')
                ->add('server', 'entity', array(
                'class' => 'ObladyZuulBundle:Server',
                'property' => 'name',
                 ))
                ->add('send', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array());
    }

    public function getName()
    {
        return 'request_connect';
    }
}
