# ShutterStock Image Formatter

Search for images that automatically filter out editorial and are exclusive for editorial use. Built using Symfony

## Installation

Download via git and inside the shutterstock-image-formatter directory, access site via localhost in comamndline: 



```bash
php -S localhost:3000 -t public
```

# Functionalty

An example of an working URl can be found here:
```bash
http://localhost:3000/shutterstocksearch/?usersearch=phones
```

## Requirements

```php
  "php": ">=7.2.5",
        "ext-ctype": "*",
        "ext-iconv": "*",
        "monolog/monolog": "^2.2",
        "sensio/framework-extra-bundle": "^6.1",
        "symfony/cache": "5.2.*",
        "symfony/console": "5.2.*",
        "symfony/dotenv": "5.2.*",
        "symfony/flex": "^1.3.1",
        "symfony/form": "5.2.*",
        "symfony/framework-bundle": "5.2.*",
        "symfony/twig-bundle": "5.2.*",
        "symfony/yaml": "5.2.*"

```

## Shutterstack account
Please make sure you are signed up to the Shutterstack API on developer portal to enable your own secret and client keys. They have been removed on this repository 21 and 22 for security. 

