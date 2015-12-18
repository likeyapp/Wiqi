# Wiqi - (Wi)kipedia API (Q)uery (I)nterface

### About 

This project is a Wikipedia API Query Interface for Laravel 5. 
A simple interface for performing "action=query" requests to the Wikipedia API.

### Installation

Composer:
```js
{
    "require": {
        "likey/wiqi": "dev-master"
    }
}
```

### Usage
#### May change since in-dev.

get() - Return Array of Query Result:
```php
$wiqiResults = Wiqi::query("like")->get();
print_r($wiqiResults);
```

count(int) - More Pages in Results:
```php
$wiqiResults = Wiqi::query("like")->count(5)->get();
print_r($wiqiResults);
```

brief() - Add First Sentance and Image to Results:
```php
$wiqiResults = Wiqi::query("like")->brief()->count(5)->get();
print_r($wiqiResults);
```
### License
Copyright 2015 Likey, LLC.
