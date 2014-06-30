<?php

namespace Magice\Bundle\UserBundle\Form;

use Magice\Bundle\UserBundle\Entity\UserInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserInfoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'gender',
                'choice',
                array(
                    'label'   => '_mg.user.info.form.label.gender',
                    'choices' => array(
                        'M' => '_mg.user.info.form.label.gender.male',
                        'F' => '_mg.user.info.form.label.gender.female'
                    )
                )
            )
            ->add('firstname', 'text', array('label' => '_mg.user.info.form.label.firstname'))
            ->add('lastname', 'text', array('label' => '_mg.user.info.form.label.lastname'))
            ->add('displayName', 'text', array('label' => '_mg.user.info.form.label.display_name'))
            ->add('avatar', 'text', array('label' => '_mg.user.info.form.label.avatar'))
            ->add('personalId', 'text', array('label' => '_mg.user.info.form.label.personal_id'))
            ->add(
                'birthday',
                'birthday',
                array(
                    'label'       => '_mg.user.info.form.label.birthday',
                    'years'       => $this->years(),
                    'empty_value' => '...',
                    'required'    => true
                )
            )
            ->add('mobile', 'tel', array('label' => '_mg.user.info.form.label.mobile'))
            ->add('telHome', 'tel', array('label' => '_mg.user.info.form.label.tel_home', 'required' => false))
            ->add('telWork', 'tel', array('label' => '_mg.user.info.form.label.tel_work', 'required' => false))
            ->add('telWorkExt', 'text', array('label' => '_mg.user.info.form.label.tel_work_ext', 'required' => false));
    }

    protected function years()
    {
        $years = array();
        $start = (date('Y')) - UserInfo::YEAR_MIN_AGE;

        for ($i = 0; $i < UserInfo::YEAR_MAG_AGE; $i++) {
            $start--;
            $years[$start] = $start;
        }

        return $years;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'      => 'Magice\Bundle\UserBundle\Entity\UserInfo',
                'csrf_protection' => false
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mg_user_form_info_type';
    }
}
