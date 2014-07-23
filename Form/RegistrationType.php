<?php
namespace Magice\Bundle\UserBundle\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType;

class RegistrationType extends RegistrationFormType
{
    public function getName()
    {
        return 'mg_user_form_type_registration';
    }
}
