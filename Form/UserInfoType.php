<?php

namespace Magice\Bundle\UserBundle\Form;

use Magice\Bundle\UserBundle\Entity\UserInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserInfoType extends AbstractType
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

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
            ->add(
                'birthday',
                'birthday',
                array(
                    'label'    => '_mg.user.info.form.label.birthday',
                    'widget'   => 'single_text',
                    'required' => true
                )
            )
            ->add('displayName', 'text', array('label' => '_mg.user.info.form.label.display_name', 'required' => false))
            ->add('email', 'email', array('label' => '_mg.user.info.form.label.email', 'required' => false))
            ->add('avatar', 'text', array('label' => '_mg.user.info.form.label.avatar', 'required' => false))
            ->add('personalId', 'text', array('label' => '_mg.user.info.form.label.personal_id', 'required' => false))
            ->add('mobile', 'tel', array('label' => '_mg.user.info.form.label.mobile', 'required' => false))
            ->add('telHome', 'tel', array('label' => '_mg.user.info.form.label.tel_home', 'required' => false))
            ->add('telWork', 'tel', array('label' => '_mg.user.info.form.label.tel_work', 'required' => false))
            ->add('telWorkExt', 'text', array('label' => '_mg.user.info.form.label.tel_work_ext', 'required' => false));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'      => $this->class,
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
                'intention'       => 'update_user_info'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mg_user_form_type_info';
    }
}
