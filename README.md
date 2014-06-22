PrettyURL
=========

Class to create clean Urls for blogs, forums and other websites

## Quick Start and Examples

### PrettyUrl class

#### Methods

setUrl, getUrl, setBlackList, getBlackList, Urlify

#### Examples

To generate URLs for titles or sections

```php

require 'lib/PrettyUrl.php';
use PrettyUrl;

$url = new PrettyUrl('TweetBeeg to write tweets with more than 140 characters');
$newUrl = $url->Urlify();
echo $newUrl;   // or echo $url->getUrl(), is the same
// result: 'tweetbeeg-to-write-tweets-with-more-than-140-characters'

```

Generate URLs and eliminate words from the black list

```php

require 'lib/PrettyUrl.php';
use PrettyUrl;

$url = new PrettyUrl('TweetBeeg to write tweets with more than 140 characters');
// black list is case insensitive so 'WItH' is the same that 'with'
$url->setBlackList(array(
    'with', 'to'
));
$url->Urlify();
// now with the encoded url and without with and than words
echo $url->getUrl();
// result: 'tweetbeeg-write-tweets-more-than-140-characters'

```

Automatically delete invalid characters from URLs

```php
require 'lib/PrettyUrl.php';
use PrettyUrl;

$text = '  bes\t    tíñtle$ $ that.\'_ _can# be mundo/ / ( \3000€ /set%&$@   ';
$url = new PrettyUrl($text);
$url->Urlify();
echo $url->getUrl();
// result: 'best-tintle-that-can-be-mundo-3000-set'

```
