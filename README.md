# NTIImpersonationBundle

### Overview

This bundle lets you change the login to another user without needing the users' password. This is useful when you have a list of users and someone complains that they in particular are having a specific issue. Using this bundle you could change your login to that of the user without asking for the password.

### Installation

1. Install the bundle using composer:

    ```
    $ composer require ntidev/impersonation-bundle "dev-master"
    ```

2. Add the bundle configuration to the AppKernel
    
    ```
    public function registerBundles()
    {
        $bundles = array(
            ...
            new NTI\ImpersonationBundle\NTIImpersonationBundle(),
            ...
        );
    }
    ```

3. Setup the configuration in the ``config.yml``
    
    ```
    # NTI
    nti_impersonation:
        redirect_route: 'deshblard'
        user_class: 'AppBundle\Entity\User\User'
        user_class_property: 'username'
        firewall: 'main'
    ```

4. Update the database schema
    
    ```
    $ php app/console doctrine:schema:update
    ```



### Requirements

1. The entity that the bundle uses is currently hard coded to AppBundle\Entity\User\User and it uses the "username" property to find a valid user to impersonate.


### Usage

1. Generate an impersonation key

    ```
    $ php app/console nti:impersonation:generate-key [username]
    ```

2. Copy the provided key and go to the url: `/nti/impersonate/{key}`

3. You will be redirected to the configured route logged in as the user for which the key was generated.
