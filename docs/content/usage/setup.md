## Creating a Config object

Create a config object like this:
```php
  $config = new Config($data);
```
where `$data` can be an array or a json string, a file or folder path.  


## Creating a Config object with a file as a parameter

To directly include a json file, use 
```php
$config = new Config(PATH . 'example.json');
```
The only requirement is the complete path to be valid.
&nbsp;  

I often use php files like the one below to store configuration. So you could also use 
```php
$config = new Config(PATH . 'example.php');
```

PHP Config file:
```php
<?
return array(
  'foo' => 'bar'
);
```
&nbsp;  

## Creating a Config object with a folder as a parameter

To parse a folder containing your configuration files, use 
```php
$config = new Config(PATH . 'app/config');
```
The only requirement is the complete path to be valid.
&nbsp;  



## INI files
A note on INI files: Honestly, why would you want to use those for configuration when you have php and json at hand? Maybe when I get a good idea on how to implement that in an elegant way.
&nbsp;  

## Advice on implementation
**Incorporate this into your project:** Set the namespace to that of your app and keep it in the app system directory. That way, you can access the config the most hassle-free way.  
**Catch possible exceptions:** Libconfig will throw a RuntimeException immediately if data cannot be read. If you know something *could* go wrong, set up try-catch blocks.  
**Don't hardcode:** Use config files whenever possible - for files, routes, i18n, paths and classlists, ... .  
**Be reasonable:** There comes a point where redis or a real DB might make more sense. Reevaluate from time to time.  
**Hack the code however you like:** You are free to use this library in any project, commercial or not (see the license). However, it would be nice of you to drop a curious fella a note what you used my work for and what you changed :).
