# Motor Docs

Simple Laravel package for adding multi-package documentation to your projects. Also does search!

### Installation
You can install this package via composer using this command:

```bash
composer require motor-cms/motor-docs
```


### Configuration
Next, create a file 'motor-docs.php' in your config directory

```php
<?php

return [
    'route'     => 'documentation',
    'name'      => 'Motor-Docs',
    'logo'      => 'images/motor-logo-white-cropped.png',
    'copyright' => 'Reza Esmaili',
    'packages' => [
        'motor-docs' => [
            'name'       => 'Motor-Docs',
            'navigation' => '_navigation'
        ]
    ]

];
```

Repeat for your other packages. It is safe to only include the 'packages' array.
(You can also put all your packages in your main motor-docs.php file)

```php
<?php
return [
    'packages' => [
        'second-package' => [
            'name'       => 'Second package',
            'navigation' => '_navigation'
        ]
    ]
];
``` 

Add the following code to the boot() method of your service providers. Do this for each of your packages.

```php
$config = $this->app['config']->get('motor-docs', []);
$this->app['config']->set('motor-docs',
    array_replace_recursive(require __DIR__.'/../../config/motor-docs.php', $config));
```

### Writing documentation

Add Markdown (.md) files in your resource/documentation folder.
The package will automatically render a navigation tree defined in your _navigation.md file(s).

Have fun!

### Todo

* ajax search
* Better docs (lol)
* More configuration options

## Credits

- [Reza Esmaili](https://github.com/dfox288)

## About Motor
...

## License
...
