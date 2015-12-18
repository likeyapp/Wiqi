# Wiqi - (Wi)kipedia API (Q)uery (I)nterface

### About 

This project is a Wikipedia API Query Interface for Laravel 5. 
A simple interface for performing "action=query" requests to the Wikipedia API.

### Installation

Add to your composer.json:
```js
{
    "require": {
        "likey/wiqi": "dev-master"
    }
}
```
or from command line:
```
composer require likey/wiqi
```

Your /config/app.php
Add the service provider:
```php
    Likey\Wiqi\WiqiServiceProvider::class,
```
Example:
```php
'providers' => [
    Likey\Wiqi\WiqiServiceProvider::class,
],
```

Add the facade:
```php
    'Wiqi'      => Likey\Wiqi\Facades\Wiqi::class,
```
Example:
```php
'aliases' => [
    'Wiqi'      => Likey\Wiqi\Facades\Wiqi::class,
]
```

### Usage
##### May change since in-dev.

#### get() - Return Array of Query Result:
```php
$wiqiResults = Wiqi::query("like")->get();
print_r($wiqiResults);
```

#### count(int) - More Pages in Results:
```php
$wiqiResults = Wiqi::query("like")->count(5)->get();
print_r($wiqiResults);
```

#### brief() - Add First Sentance and Image to Results:
```php
$wiqiResults = Wiqi::query("like")->brief()->count(5)->get();
print_r($wiqiResults);
```

### Example return:
```php
[
{
"pageid": 567140,
"title": "Like",
"extract": "In the English language, the word like has a very flexible range of uses, ranging from conventional to non-standard."
},
{
"pageid": 1215338,
"title": "Like a Rolling Stone",
"extract": "\"Like a Rolling Stone\" is a 1965 song by the American singer-songwriter Bob Dylan.",
"image": "https://upload.wikimedia.org/wikipedia/en/1/1e/Bob_Dylan_-_Like_a_Rolling_Stone.jpg"
},
{
"pageid": 167924,
"title": "Like Mike",
"extract": "Like Mike is a 2002 American comedy film directed by John Schultz and written by Michael Elliot and Jordan Moffet.",
"image": "https://upload.wikimedia.org/wikipedia/en/e/ee/Like_Mike_poster.jpg"
},
{
"pageid": 28504903,
"title": "Like a G6",
"extract": "\"Like a G6\" is a 2010 song written and performed by Far East Movement, The Cataracs, and Dev, with the latter two being credited as featured artists.",
"image": "https://upload.wikimedia.org/wikipedia/en/5/59/Like_a_G6_single_cover.jpg"
},
{
"pageid": 9737001,
"title": "Like Sonny",
"extract": "Like Sonny is a compilation album combining two sessions from 1958 and 1960 with jazz musician John Coltrane.",
"image": "https://upload.wikimedia.org/wikipedia/en/f/fd/Like_Sonny.jpeg"
}
]
```

### License
Copyright 2015 Likey, LLC.
