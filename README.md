# Instagram Post Info
Instagam post info  - nicname, image, date, likes, location

Getting data about a post on Instagram. Nickname, location, post date, number of likes, photo.

Only links of public posts from Instagram will work, if this is a public account, the result will be an error.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run:

```bash
composer require tofers/instagram-post-info
```

or add

```bash
"tofers/instagram-post-info": "*"
```

to the require section of your `composer.json` file.

Usage
-----

Set link to extension in your view:

```php
<?php

use tofers\instagrampost\InstagramPostInfo;

$instagram_post = new InstagramPostInfo("https://www.instagram.com/p/CB7KzC6BPIn/"); 

echo 'instagram link: ' . $instagram_post->src;
echo '<br>';
echo '<br>';
echo 'Account name: ' . $instagram_post->account;
echo '<br>';
echo '<br>';
echo 'Image src: ' . $instagram_post->image;
echo '<br>';
echo '<br>';
echo 'Date post: ' . $instagram_post->date;
echo '<br>';
echo '<br>';
echo 'Like post: ' . $instagram_post->like;
echo '<br>';
echo '<br>';
echo 'Location: ' . $instagram_post->location;


?>