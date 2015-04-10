## php-config
Simple php class to create a key-value storage.
This is a static variant which may be more useful when you need data inside multiple classes and don't want to inject the config object as a dependency at all times.

### Usage
The config needs to be populated initially. Note that you can only do this once, if `populate()` gets executed when there is already data present, it will throw an exception. To add another file or array, use [add](#add).  

Populate the config like this:
```php
Config::populate($data);
```
where `$data` can be an array or a json string.  

To directly include a json file like the one below, use 
```php
Config::populate(file_get_contents('example.json'));
```
```json
{
  "foo" : "bar"
}
```
&nbsp;  

I often use php files like the one below to store configuration. So you could also use 
```php
Config::populate(require('example.php'));
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
Config::set('foo', 'bar');
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
Config::set('font.roboto.italic.regular', 'roboto-regular-italic.woff');
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
Config::get('foo'); // bar
```

To access nested config values, you can use dots to seperate keys:
```php
$fontlist = Config::get('font.roboto.italic'); // Array
$fontfile = Config::get('font.roboto.italic.regular'); // roboto-regular-italic.woff
```

You can optionally specify a fallback value in case the desired key is not present:
```
Config::get('app.server.port', '8080');

```
To retrieve the whole configuration, just call get without arguments:
```
Config::get();
```

&nbsp;  
&nbsp;  

##### has
checks wether a setting exists or not
```php
Config::has('foo.sub.key'); // true
```

&nbsp;  
&nbsp;  

##### erase
removes a key from the settings
```php
Config::erase('foo.sub.key');
```

&nbsp;  
&nbsp;  

##### add
Adds another array to the configuration. The data is merged, which means that keys with the same name will overwrite previously existing ones. Maybe a possibility to insert them with changed names should be implemented here? 
```php
Config::add(file_get_contents('another.json'));
```
