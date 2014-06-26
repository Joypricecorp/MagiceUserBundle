<?php

namespace Magice\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @deprecated now nothing to use this type
 */
class UserInfoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender')
            ->add('firstname')
            ->add('lastname')
            ->add('displayName')
            ->add('avatar')
            ->add('personalId')
            ->add('birthDay')
            ->add('mobile', 'tel')
            ->add('telHome', 'tel')
            ->add('telWork', 'tel')
            ->add('telWorkExt')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Magice\Bundle\UserBundle\Entity\UserInfo',
            'csrf_protection'   => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mg_user_form_info_type';
    }
}
