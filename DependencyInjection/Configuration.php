<?php

namespace Magice\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('magice_user');

        $supportedDrivers = array('orm', 'custom');

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue('orm')
                    ->validate()
                        ->ifNotInArray($supportedDrivers)
                        ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers))
                    ->end()
                ->end()

                ->scalarNode('path_prefix')->defaultValue('/user')->end()
                ->scalarNode('firewall')->defaultValue('magice')->end()
                ->scalarNode('firewall_pattern')->defaultValue('/.*')->cannotBeEmpty()->end()
                ->scalarNode('provider')->defaultValue('mg.user.provider')->end()
                ->scalarNode('confirmation')->defaultValue(true)->end()
                ->scalarNode('username_iterations')->defaultValue(30)->end()
                ->scalarNode('remember_lifetime')->defaultValue(31536000)->end()
                ->scalarNode('remember_param_name')->defaultValue('_remember_me')->end()
                ->scalarNode('remember_cookie_name')->defaultValue('MAGICE_REMEMBER_ME')->end()

                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('user')->defaultValue('Magice\Bundle\UserBundle\DefaultEntity\User')->cannotBeEmpty()->end()
                        ->scalarNode('group')->defaultValue('Magice\Bundle\UserBundle\DefaultEntity\Group')->cannotBeEmpty()->end()
                        ->scalarNode('info')->defaultValue('Magice\Bundle\UserBundle\DefaultEntity\UserInfo')->cannotBeEmpty()->end()
                        ->arrayNode('responder')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->cannotBeEmpty()->end()
                        ->end()
                        ->arrayNode('doctrine')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('phone_number')
                                    ->defaultValue('Misd\PhoneNumberBundle\Doctrine\DBAL\Types\PhoneNumberType')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('softdeleteable')
                                    ->defaultValue('Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter')
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('email')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('address')->defaultValue('webmaster@example.com')->cannotBeEmpty()->end()
                        ->scalarNode('sender')->defaultValue('webmaster')->cannotBeEmpty()->end()
                        ->scalarNode('service')->defaultValue('fos_user.mailer.twig_swift')->cannotBeEmpty()->end()
                    ->end()
                ->end()

                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('registration')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('mg_user_form_type_registration')->cannotBeEmpty()->end()
                                ->scalarNode('type')->defaultValue('mg_user_form_type_registration')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('oauth')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()

                            ->scalarNode('client_id')->cannotBeEmpty()->end()
                            ->scalarNode('client_secret')->cannotBeEmpty()->end()

                            ->scalarNode('scope')
                                ->validate()
                                    ->ifTrue(function($v) {
                                            return empty($v);
                                        })
                                    ->thenUnset()
                                ->end()
                            ->end()

                            ->scalarNode('user_response_class')
                                ->validate()
                                    ->ifTrue(function($v) {
                                            return empty($v);
                                        })
                                    ->thenUnset()
                                ->end()
                            ->end()

                            ->scalarNode('service')
                                ->validate()
                                    ->ifTrue(function($v) {
                                            return empty($v);
                                        })
                                    ->thenUnset()
                                ->end()
                            ->end()

                            ->scalarNode('type')
                                ->validate()
                                    ->ifTrue(function($v) {
                                            return empty($v);
                                        })
                                    ->thenUnset()
                                ->end()
                            ->end()

                        ->end()
                    ->end()
                ->end()

            ->end()
        ;

        $this->shorthandOauthFacebook($rootNode);
        $this->shorthandOauthGoogle($rootNode);
        $this->shorthandOauthGithub($rootNode);
        $this->shorthandOauthVkontakte($rootNode);

        return $treeBuilder;
    }

    private function shorthandOauthFacebook(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('facebook')
                    ->children()
                        ->scalarNode('client_id')->cannotBeEmpty()->end()
                        ->scalarNode('client_secret')->cannotBeEmpty()->end()

                        ->scalarNode('scope')
                            ->validate()
                                ->ifTrue(function($v) {
                                        return empty($v);
                                    })
                                ->thenUnset()
                            ->end()
                        ->end()

                        ->scalarNode('user_response_class')
                            ->defaultValue('Magice\Bundle\UserBundle\OAuth\Response\Facebook')
                            ->cannotBeEmpty()
                        ->end()

                        ->scalarNode('service')
                            ->validate()
                                ->ifTrue(function($v) {
                                        return empty($v);
                                    })
                                ->thenUnset()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end()
        ;
    }

    private function shorthandOauthGoogle(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('google')
                    ->children()
                        ->scalarNode('client_id')->cannotBeEmpty()->end()
                        ->scalarNode('client_secret')->cannotBeEmpty()->end()

                        ->scalarNode('scope')
                            ->validate()
                                ->ifTrue(function($v) {
                                        return empty($v);
                                    })
                                ->thenUnset()
                            ->end()
                        ->end()

                        ->scalarNode('user_response_class')
                            ->defaultValue('Magice\Bundle\UserBundle\OAuth\Response\Google')
                            ->cannotBeEmpty()
                        ->end()

                        ->scalarNode('service')
                            ->validate()
                                ->ifTrue(function($v) {
                                        return empty($v);
                                    })
                                ->thenUnset()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end()
        ;
    }

    private function shorthandOauthGithub(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('github')
                    ->children()
                        ->scalarNode('client_id')->cannotBeEmpty()->end()
                        ->scalarNode('client_secret')->cannotBeEmpty()->end()

                        ->scalarNode('scope')
                            ->validate()
                                ->ifTrue(function($v) {
                                        return empty($v);
                                    })
                                ->thenUnset()
                            ->end()
                        ->end()

                        ->scalarNode('user_response_class')
                            ->defaultValue('Magice\Bundle\UserBundle\OAuth\Response\Github')
                            ->cannotBeEmpty()
                        ->end()

                        ->scalarNode('service')
                            ->validate()
                                ->ifTrue(function($v) {
                                        return empty($v);
                                    })
                                ->thenUnset()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end()
        ;
    }

    private function shorthandOauthVkontakte(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('vkontakte')
                    ->children()
                        ->scalarNode('client_id')->cannotBeEmpty()->end()
                        ->scalarNode('client_secret')->cannotBeEmpty()->end()

                        ->scalarNode('scope')
                            ->validate()
                                ->ifTrue(function($v) {
                                        return empty($v);
                                    })
                                ->thenUnset()
                            ->end()
                        ->end()
                        ->arrayNode('paths')
                            ->useAttributeAsKey('name')
                            ->prototype('variable')
                                ->validate()
                                    ->ifTrue(function($v) {
                                        if (null === $v) {
                                            return true;
                                        }

                                        if (is_array($v)) {
                                            return 0 === count($v);
                                        }

                                        if (is_string($v)) {
                                            return empty($v);
                                        }

                                        return !is_numeric($v);
                                    })
                                    ->thenInvalid('Path can be only string or array type.')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('options')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()

                        ->scalarNode('user_response_class')
                            ->defaultValue('Magice\Bundle\UserBundle\OAuth\Response\Vkontakte')
                            ->cannotBeEmpty()
                        ->end()

                        ->scalarNode('service')
                            ->validate()
                                ->ifTrue(function($v) {
                                        return empty($v);
                                    })
                                ->thenUnset()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end()
        ;
    }
}
