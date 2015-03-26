## libconfig
Simple php class to create a key-value storage.
Basically, you can just throw your configuration at it, be it arrays, json, those things as files or even directories, and libconfig will make it accessible in an intuitive way. You can `get`, `set` and `add`, count it, iterate over it or access it as an array. Each method has its tricks up its sleeve. More on that below.  
Finally, whenever something goes so wrong you should know it, libconfig will simply throw an Exception. 


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
### Magic methods

##### __tostring
returns the complete data array:
```php
print_r($config); // Array
```

&nbsp;  
&nbsp;
## Implemented Interfaces

#### Array access
You can also access and edit all data as an array:
```
$port = $config['app']['server']['port']; // 8080
```

&nbsp;  
&nbsp;  
#### Countable
The Config object can be counted:
```
$settings = count($config);
```

&nbsp;  
&nbsp;  
#### Iterator
You are able to iterate over the Config object:
```
array_walk_recursive($config, function ($value, $key) {
    echo "$key holds $value\n";
});
```
