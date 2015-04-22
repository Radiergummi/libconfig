
##### set
inserts a value into a key:
```php
$config->set('foo', 'bar');
```
That will add the following to the config array:
```
Array
(
    [foo] => bar
)
```

To set nested config values, you can use dots to seperate keys:
```php
$config->set('font.roboto.italic.regular', 'roboto-regular-italic.woff');
```
That will add the following to the config array:
```
Array
(
  [font] => Array
    (
      [roboto] => Array
        (
          [italic] => Array
            (
              [regular] => roboto-regular-italic.woff
            )
        )
    )
)
```

&nbsp;  
&nbsp;  

##### get
returns the value for a given key:
```php
$config->get('foo'); // bar
```

To access nested config values, you can use dots to seperate keys:
```php
$fontlist = $config->get('font.roboto.italic'); // Array
$fontfile = $config->get('font.roboto.italic.regular'); // roboto-regular-italic.woff
```

You can optionally specify a fallback value in case the desired key is not present:
```
$config->get('app.server.port', '8080');
```

To retrieve the whole configuration, just call get without arguments:
```
$config->get();
```

&nbsp;  
&nbsp;  

##### has
checks wether a setting exists or not
```php
$config->has('foo.sub.key'); // true
```

&nbsp;  
&nbsp;  

##### erase
removes a key from the settings
```php
$config->erase('foo.sub.key');
```

&nbsp;  
&nbsp;  

##### add
Adds another array to the configuration. The data is merged, which means that keys with the same name will overwrite previously existing ones. Maybe a possibility to insert them with changed names should be implemented here? 
```php
$config->add(file_get_contents('another.json'));
```
