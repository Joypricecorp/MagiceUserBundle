MagiceUserBundle
================

The Magice User Bundle

### Install Instruction

```
# app/config/config.yml
framework:
    translator: ~

doctrine:
    dbal:
        types:
            phone_number: Misd\PhoneNumberBundle\Doctrine\DBAL\Types\PhoneNumberType

    orm:
        auto_generate_proxy_classes: "%kernel.debug%"
        auto_mapping: true
        filters:
            softdeleteable:
                class: Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter
                enabled: true

stof_doctrine_extensions:
    default_locale: "%locale%"
    orm:
        default:
            softdeleteable: true
            timestampable: true

hwi_oauth:
    #this is my custom user provider, created from FOSUBUserProvider - will manage the
    #automatic user registration on your site, with data from the provider (facebook. google, etc.)
    #and also, the connecting part (get the token and the user_id)
    connect:
        account_connector: mg.user.provider
    # name of the firewall in which this bundle is active, this setting MUST be set
    firewall_name: main
    fosub:
        username_iterations: 30
        properties:
            # these properties will be used/redefined later in the custom FOSUBUserProvider service.
            facebook: '--facebook_id--'
            google: '--google_id--'
    resource_owners:
        facebook:
            type:                facebook
            user_response_class: Magice\Bundle\UserBundle\OAuth\Response\Facebook
            client_id:           "xxxx"
            client_secret:       "xxxx"
            scope:               "basic_info,email,user_birthday,user_likes,user_location,publish_stream"

fos_user:
    db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
    firewall_name: main
    user_class: Magice\Bundle\UserBundle\Entity\User
    group:
        group_class: Magice\Bundle\UserBundle\Entity\Group
    service:
        mailer: mg.user.mailer.twig_swift
    registration:
        form:
            type: mg_user_form_registration_type
            name: mg_user_form_registration_type
    from_email:
        address: noreply@joyprice.com
        sender_name: Joyprice Notify Mailer

twig:
    globals:
        img:
            blank: data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7
```

```
# app/config/routing.yml
mg_user:
    resource: "@MagiceUserBundle/Resources/config/routing.yml"
    prefix:   /user
```

```
# app/config/security.yml
security:
    encoders:
        FOS\UserBundle\Model\UserInterface: sha512

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN

    providers:
        fos_userbundle:
            id: fos_user.user_provider.username_email

    firewalls:
        dev:
            pattern:  ^/(_(profiler|wdt)|css|images|js)/
            security: false

        main:
            context:     user
            pattern:     /.*

            form_login:
                provider: fos_userbundle
                csrf_provider: form.csrf_provider
                check_path: /user/login_check
                login_path: /user/login
                default_target_path: /
                use_forward:  false
                use_referer: true

            remember_me:
                key: %secret%
                name: APP_REMEMBER_ME
                lifetime: 31536000
                always_remember_me: true
                remember_me_parameter: _remember_me

            oauth:
                resource_owners:
                    facebook: "/user/login/check-facebook"
                    google: "/user/login/check-google"
                login_path: /user/login
                failure_path: /user/login
                oauth_user_provider:
                    service: mg.user.provider

            logout:
                path: /user/logout
                target: /user/login

            anonymous: true

    access_control:
        #- { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY, requires_channel: https }
        - { path: ^/user/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/connect.*, role: IS_AUTHENTICATED_ANONYMOUSLY }

```

```
# app/AppKernel.php
new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
new Misd\PhoneNumberBundle\MisdPhoneNumberBundle(),
new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
new FOS\UserBundle\FOSUserBundle(),
new Magice\Bundle\UserBundle\MagiceUserBundle(),
```


