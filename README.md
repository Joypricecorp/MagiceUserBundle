MagiceUserBundle
================

The Magice User Bundle for Symfony2 with lowest configuration.

### Install Instruction

```yaml
# app/config/config.yml

## Sample if use oauth like facebook.
# magice_user:
#     facebook:
#         client_id:           "xxx"
#         client_secret:       "xxx"
#         scope:               "xxx"
#
## Or nothing to config
```

```
# app/config/routing.yml
mg_user:
    resource: "@MagiceUserBundle/Resources/config/routing.yml"
    prefix:   /user
```

```yaml
# app/config/security.yml
security:
    ....

    access_control:
        #- { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY, requires_channel: https }
        - { path: ^/user/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/connect.*, role: IS_AUTHENTICATED_ANONYMOUSLY }

```

```php
# app/AppKernel.php
new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
new Misd\PhoneNumberBundle\MisdPhoneNumberBundle(),
new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
new FOS\UserBundle\FOSUserBundle(),
new Magice\Bundle\UserBundle\MagiceUserBundle(),
```

[Shorthand configration](https://github.com/Joypricecorp/MagiceUserBundle/blob/master/short_configuration.md)

[Full master configration](https://github.com/Joypricecorp/MagiceUserBundle/blob/master/full_configuration.md)
