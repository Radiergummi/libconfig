## Some thoughts on Exceptions
I have only implemented `RuntimeException` as the exception type throughout libconfig. Why? Possible things that can go wrong are files not being found or data not being parsable. In either case, you've got a problem you should catch:  

```php
try {
  $config->add('inexistent/path/to/file.json');
} catch (Exception $e) {
  $app->shutdown(502);
  if (ENV === 'dev') throw new \MyApp\Exceptions\FileNotFoundException($e);
}
```
That way, exception handling is bound to your app and you don't have a few more arbitrary exception types to deal with. 
