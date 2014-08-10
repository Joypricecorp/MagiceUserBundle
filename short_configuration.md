### MagiceUserBundle configuration reference.

````yaml
magice_user:
    driver: orm
    path_prefix: /user
    # firewall name (magice build-in firewall)
    firewall: magice
    # User provider service
    provider: mg.user.provider
    username_iterations: 30
    remember_lifetime: 31536000
    
    email:
        address: webmaster@example.com
        sender: webmaster
        # send mail service
        service: mg.user.mailer.twig_swift
    
    form:
        # FOSUser registration form
        registration:
            name: mg_user_form_type_registration
            type: mg_user_form_type_registration
        
    # shorthand facebook config for oauth    
    facebook:
        client_id: xxx
        client_secret: xxx
        scope: xxx
        
    # Full oauth config
    oauth:
        facebook:
            client_id: xxx
            client_secret: xxx
            scope: xxx
            user_response_class: xxx
            service: xxx
            type: xxx
            
    class:
        user: Magice\Bundle\UserBundle\Model\User
        group: Magice\Bundle\UserBundle\Model\Group
        
        # oauth respons handle (hwi_oauth bundle)
        responder:
            facebook: Magice\Bundle\UserBundle\OAuth\Response\Facebook
            ....
        
        doctrine:
            # phone_number type for DBAL
            phone_number: Misd\PhoneNumberBundle\Doctrine\DBAL\Types\PhoneNumberType
            # soft delete filter class
            softdeleteable: Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter

````
