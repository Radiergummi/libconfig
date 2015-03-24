## php-config
Simple php library to import settings from a config file into a modifyable config object.  

### Usage
Create a config object like this:
```php
  $config = new Config($data);
```
where `$data` can be an array or a json string.  

To directly include a json file, use
```php
  $config = new Config(file_get_contents('example.json'));
```
&nbsp;  
&nbsp;  

### Methods

##### set
inserts a value into a key:
```php
$config->set('foo', 'bar');
```

To set nested config values, you can use dots to seperate keys:
```php
$config->set('font.roboto.italic.regular', 'roboto-regular-italic.woff');
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
&nbsp;  
&nbsp;  

##### //TODO: add
Adds another array to the configuration
```php
$config->add(file_get_contents('another.json'));
```
