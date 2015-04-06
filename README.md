## libconfig [![Build Status](https://travis-ci.org/Radiergummi/libconfig.svg?branch=master)](https://travis-ci.org/Radiergummi/libconfig)
Simple php class to create a key-value storage.
Basically, you can just throw your configuration at it, be it arrays, json, those things as files or even directories, and libconfig will make it accessible in an intuitive way. You can `get`, `set` and `add`, count it, iterate over it or access it as an array. Each method has its tricks up its sleeve. More on that below.  
Finally, whenever something goes so wrong you should know it, libconfig will simply throw an Exception.  
&nbsp;  

> Note: I also made a static version of this which is available [here](/../../tree/static).  
> Why? Because using stuff like `Config::get('key')` from anywhere without an instance is pretty darn comfortable.


### Usage
Create a config object like this:
```php
  $config = new Config($data);
```
where `$data` can be an array or a json string, a file or folder path.  

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

A note on INI files: Honestly, why would you want to use those for configuration when you have php and json at hand? Maybe when I get a good idea on how to implement that in an elegant way.
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

&nbsp;  
&nbsp;  
### Magic methods

##### __tostring
returns the complete data array:
```php
print_r($config); // Array
```
(Note: This is redundant. You can either call `$config->get()` or just `$config`, same effect. I find it to be not very self-documenting, but who am I to tell you how to code.)

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

&nbsp;  
&nbsp;  
## TODO
#### Bugs
- Counting the object recursively has no effect currently, and I don't know why.
- ~~Sometimes while parsing JSON, a JSON_LAST_ERROR 1 is thrown, meaning the maximum depth is reached, even if handling JSON with no nesting at all. There must be some recursion going on.~~ (Fixed)

#### Improvements
- Parts of the code are a bit messy right now, especially the `add`-method.
- I would like to modularize the internals a bit - parsing input should be done via drivers or at least inside of specialized methods for each content type. (In progress)
- ~~libconfig should be unit tested~~ (Done)

#### Features
- INI Support (but just to stay competitive) (In progress)
- ?
 
&nbsp;  
&nbsp;  
## How to contribute
I would love to see other people contribute their ideas to this project. However to ease the process, please keep the following in mind:  
- If you find a bug, please create an issue for it, tag the issue with *bug* and include the code to reproduce it.
- If you want to request or discuss a feature, please create an issue for it, tag it with *enhancement* and discribe it as detailed as possible.
- If you want to contribute actual code, please fork the repository, create a new branch in your fork (eg. *feature-ini-support*), make your changes in it, and create a pull request. 

Thank you!
