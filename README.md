# imagekit-bundle

[![Unit tests workflow status](https://github.com/geekcell/imagekit-bundle/actions/workflows/tests.yaml/badge.svg)](https://github.com/geekcell/imagekit-bundle/actions/workflows/tests.yaml) [![Coverage](https://sonarcloud.io/api/project_badges/measure?project=geekcell_imagekit-bundle&metric=coverage)](https://sonarcloud.io/summary/new_code?id=geekcell_imagekit-bundle) [![Bugs](https://sonarcloud.io/api/project_badges/measure?project=geekcell_imagekit-bundle&metric=bugs)](https://sonarcloud.io/summary/new_code?id=geekcell_imagekit-bundle) [![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=geekcell_imagekit-bundle&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=geekcell_imagekit-bundle) [![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=geekcell_imagekit-bundle&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=geekcell_imagekit-bundle)

A Symfony bundle for smooth integration with the [PHP SDK for ImageKit](https://github.com/imagekit-developer/imagekit-php).

## Installation

To use this package, require it in your Symfony project with Composer.

```bash
composer require geekcell/imagekit-bundle
```

Verify that the bundle has been enabled in `config/bundles.php`

```php
<?php

return [
    // other bundles ...
    GeekCell\ImagekitBundle\GeekCellImagekitBundle::class => ['all' => true],
];
```

## Usage

This bundle uses the concept of "providers" to return an ImageKit asset. The recommended way to interact with providers is to configure them inside `config/packages/geekcell_imagekit.yaml`

```yaml
geek_cell_imagekit:
    public_key: '%env(IMAGEKIT_PUBLIC_KEY)%'
    private_key: '%env(IMAGEKIT_PRIVATE_KEY)%'
    base_url: 'https://ik.imagekit.io'
    configurations:
        user_avatars:
            endpoint: '/user/avatars'
            transformation:
                width: 150
                height: 150
                quality: 70
            signed: false
        user_profile_images:
            endpoint: '/user/profile_images'
            transformation:
                width: 800
                height: 800
                quality: 80
            signed: true
            expires: 3600
```

In the example above, we've defined two configurations called `user_avatars` and `user_profile_images`. Currently, the following settings are supported:

- `endpoint` - Custom endpoints configured in your ImageKit account.
- `transformation` - Key-value pairs of transformations to apply to the asset. Click [here](https://github.com/imagekit-developer/imagekit-php#list-of-supported-transformations) the full list of supported transformations.
- `signed` - Append a signature to your asset's URL.
- `expires` - Expiration time in seconds; must be set if `signed` is set to `true`.

If you're using autowiring in your Symfony project, you can then simply typehint `GeekCell\ImagekitBundle\Imagekit\ProviderRegistry` in your services and/or controllers and to inject a registry from which you can retrieve every configured provider by its name.

```php
#[AsController]
class AvatarController extends AbstractController
{
    private Provider $avatarProvider;

    public function __construct(ProviderRegistry $registry)
    {
        $this->avatarProvider = $registry->getProvider('user_avatars');
    }

    #[Route('/avatar', name: 'avatar')]
    public function avatar()
    {
        $asset = $this->avatarProvider->provide('some-username.png');
        
        return new JsonResponse([
            'url' => $asset->getUrl(),
        ]);
    }
}
```
In a real-world application, of course, you would replace the hard-coded `some-username.png` path with a path returned from some datastore that corresponds to the asset source(s) you've configured in your ImageKit account.
