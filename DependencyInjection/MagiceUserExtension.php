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

        $this->forFosUser($container, $config);
        $this->forHwiOauth($container, $config);
        $this->forSecurity($container, $config);
        $this->forDoctrine($container, $config);
        $this->forStofforDoctrineExtensions($container, $config);
    }

    private function forStofforDoctrineExtensions(ContainerBuilder $container, array $config)
    {
        $name = 'stof_doctrine_extensions';

        $defaults = array(
            'orm' => array(
                'default' => array(
                    'softdeleteable' => true,
                    'timestampable'  => true
                )
            )
        );

        $config = $container->getExtensionConfig($name);
        $config = array_replace_recursive($defaults, $config[0]);

        $container->prependExtensionConfig($name, $config);
    }

    private function forDoctrine(ContainerBuilder $container, array $config)
    {
        $name = 'doctrine';

        $defaults = array(
            'dbal' => array(
                'types' => array(
                    'phone_number' => $config['class']['doctrine']['phone_number']
                )
            ),
            'orm'  => array(
                'filters' => array(
                    'softdeleteable' => array(
                        'class'   => $config['class']['doctrine']['softdeleteable'],
                        'enabled' => true
                    )
                )
            )
        );

        $config = $container->getExtensionConfig($name);
        $config = array_replace_recursive($defaults, $config[0]);

        $container->prependExtensionConfig($name, $config);
    }

    private function forSecurity(ContainerBuilder $container, array $config)
    {
        $name   = 'security';
        $prefix = $config['path_prefix'];

        $defaults = array(
            'encoders'  => array(
                'FOS\UserBundle\Model\UserInterface' => 'sha512'
            ),
            'providers' => array(
                'fos_userbundle' => array('id' => 'fos_user.user_provider.username_email')
            ),
            'firewalls' => array(
                'magice' => array(
                    'context'     => 'user',
                    'pattern'     => '/.*',
                    'form_login'  => array(
                        'provider'            => 'fos_userbundle',
                        'csrf_provider'       => 'form.csrf_provider',
                        'check_path'          => sprintf('%s/login_check', $prefix),
                        'login_path'          => sprintf('%s/login', $prefix),
                        'default_target_path' => '/',
                        'use_forward'         => false,
                        'use_referer'         => true,
                    ),
                    'remember_me' => array(
                        'key'                   => '%secret%',
                        'name'                  => 'MAGICE_REMEMBER_ME',
                        'lifetime'              => $config['remember_lifetime'],
                        'always_remember_me'    => true,
                        'remember_me_parameter' => '_remember_me'
                    ),
                    'oauth'       => array(
                        'resource_owners'     => array(),
                        'login_path'          => sprintf('%s/login', $prefix),
                        'failure_path'        => sprintf('%s/login', $prefix),
                        'oauth_user_provider' => array(
                            'service' => 'mg.user.provider'
                        )
                    ),
                    'logout'      => array(
                        'path'   => sprintf('%s/logout', $prefix),
                        'target' => sprintf('%s/login', $prefix)
                    ),
                    'anonymous'   => true
                )
            )
        );

        $config = $container->getExtensionConfig($name);
        $config = array_replace_recursive($defaults, $config[0]);

        // access_control cannot be override
        unset($config['access_control']);

        $container->prependExtensionConfig($name, $config);
    }

    private function forFosUser(ContainerBuilder $container, array $config)
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

    private function forHwiOauth(ContainerBuilder $container, array $config)
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
