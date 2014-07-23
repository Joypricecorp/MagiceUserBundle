<?php

namespace Magice\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MagiceUserExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        //$configuration = new Configuration();
        //$config        = $this->processConfiguration($configuration, $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        // process the configuration
        $config = $container->getExtensionConfig($this->getAlias());

        // use the Configuration class to generate a config array with the settings
        $config = $this->processConfiguration(new Configuration(), $config);

        $this->FosUserConfig($container, $config);
        $this->HwiOauth($container, $config);
    }

    private function FosUserConfig(ContainerBuilder $container, array $config)
    {
        $name = 'fos_user';

        $container->setParameter('magice.user.class.entity.user', $config['class']['user']);
        $container->setParameter('magice.user.class.entity.group', $config['class']['group']);

        $defaults = array(
            'db_driver'     => $config['driver'],
            'firewall_name' => $config['firewall'],
            'user_class'    => $config['class']['user'],
            'group'         => array(
                'group_class' => $config['class']['group']
            ),
            'service'       => array(
                'mailer' => $config['email']['service']
            ),
            'registration'  => array(
                'form' => $config['form']['registration']
            ),
            'from_email'    => array(
                'address'     => $config['email']['address'],
                'sender_name' => $config['email']['sender']
            )
        );

        $config = $container->getExtensionConfig($name);
        $config = array_replace_recursive($defaults, $config[0]);

        $container->prependExtensionConfig($name, $config);
    }

    private function HwiOauth(ContainerBuilder $container, array $config)
    {
        $name = 'hwi_oauth';

        # properties to tell we want to use what user model property
        # to refer to hwi_oauth, But magice_user_bundle separate property into child entity
        # we just config to hwi_oauth properly work
        $properties = array();

        foreach ($config['oauth'] as $key => $value) {

            if (empty($config['oauth'][$key]['type'])) {
                $config['oauth'][$key]['type'] = $key;
            }

            if (empty($config['oauth'][$key]['user_response_class'])) {
                if (isset($config['class']['responder'][$key])) {
                    $class = $config['class']['responder'][$key];

                    $config['oauth'][$key]['user_response_class'] = $class;
                    $container->setParameter('magice.user.class.responder.' . $key, $class);
                }
            }

            $properties[$key] = true;
        }

        # Shorthand for Facebook
        if (isset($config['facebook'])) {
            $properties['facebook']      = true;
            $config['facebook']['type']  = 'facebook';
            $config['oauth']['facebook'] = $config['facebook'];

            $container->setParameter('magice.user.class.responder.facebook', $config['facebook']['user_response_class']);
        }

        $defaults = array(
            'firewall_name'   => $config['firewall'],
            'connect'         => array(
                'account_connector' => $config['provider']
            ),
            'fosub'           => array(
                'username_iterations' => $config['username_iterations'],
                'properties'          => $properties
            ),
            'resource_owners' => $config['oauth']
        );

        $config = $container->getExtensionConfig($name);
        $config = array_replace_recursive($defaults, $config[0]);

        $container->prependExtensionConfig($name, $config);
    }
}
