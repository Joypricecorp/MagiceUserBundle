MagiceUserBundle
================

The Magice User Bundle

### Install Instruction

```
# app/config/config.yml
framework:
    translator: ~

doctrine:
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
        account_connector: jp_user.userprovider
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
            client_id:           "687365851324174"
            client_secret:       "0960b2ae15835b33b48f50c1b1415fb7"
            scope:               "basic_info,email,user_birthday,user_likes,user_location,publish_stream"

fos_user:
    db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
    firewall_name: main
    user_class: Magice\Bundle\UserBundle\Entity\User
    group:
        group_class: Magice\Bundle\UserBundle\Entity\Group
    registration:
        form:
            type: mg_user_form_registration
            name: mg_user_form_registration
            validation_groups: [ Register, Default ]
    from_email:
        address: noreply@joyprice.com
        sender_name: Joyprice Notify Mailer


jms_serializer:
    metadata:
        auto_detection: true
        directories:
            FOSUserBundle:
                namespace_prefix: "FOS\UserBundle"
                path: "@MagiceUserBundle/Resources/config/serializer/fos"
```

```
# app/config/routing.yml
hwi_oauth_redirect:
    resource: "@HWIOAuthBundle/Resources/config/routing/redirect.xml"
    prefix:   /login

mg_user:
    resource: "@MagiceUserBungle/Resources/config/routing.xml"
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
            id: fos_user.user_provider.username

    firewalls:
        main:
            pattern: ^/
            form_login:
                provider: fos_userbundle
                csrf_provider: form.csrf_provider
            logout:       true
            anonymous:    true

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: ROLE_ADMIN }
```

```
# app/AppKernel.php
new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
new Misd\PhoneNumberBundle\MisdPhoneNumberBundle(),
new JMS\SerializerBundle\JMSSerializerBundle(),
new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
new FOS\UserBundle\FOSUserBundle(),
new Magice\Bundle\UserBundle\MagiceUserBundle(),
```


