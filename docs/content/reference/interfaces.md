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
