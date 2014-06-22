<?php
namespace Magice\Bundle\UserBundle\OAuth\ResourceOwner;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\FacebookResourceOwner;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Facebook extends FacebookResourceOwner
{
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            array(
                'user_response_class' => 'Magice\Bundle\UserBundle\OAuth\Response\Facebook'
            )
        );
    }
}