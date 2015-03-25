## php-config
Simple php class to create a key-value storage.


### Usage
Create a config object like this:
```php
  $config = new Config($data);
```
where `$data` can be an array or a json string.  

To directly include a json file like the one below, use 
```php
$config = new Config(file_get_contents('example.json'));
```
```json
{
  "foo" : "bar"
}
```
&nbsp;  

I often use php files like the one below to store configuration. So you could also use 
```php
$config = new Config(require('example.php'));
```
```php
<?
return array(
  'foo' => 'bar'
);
```

&nbsp;  
&nbsp;  


### Methods

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

##### add
Adds another array to the configuration. The data is merged, which means that keys with the same name will overwrite previously existing ones. Maybe a possibility to insert them with changed names should be implemented here? 
```php
$config->add(file_get_contents('another.json'));
```

&nbsp;  
&nbsp;  
### Array access
You can also access all data as an array:
```
$port = $config['app']['server']['port']; // 8080
```

&nbsp;  
&nbsp;  
### Magic methods

##### __tostring
returns the complete data array:
```php
print_r($config); // Array
```
