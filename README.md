PrettyURL
=========

Class to create clean Urls for blogs, forums and other websites

## Composer

	$ composer require prettyurl/prettyurl:dev-master

## Quick Start and Examples

### PrettyUrl class

#### Methods

setUrl, getUrl, setType, getType, setMaxLength, getMaxLength, setBlackList, getBlackList, loadDict, addChars, validate, Urlify

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

Generating URLs for files

```php

require 'lib/PrettyUrl.php';
use PrettyUrl;

/* if the second parameter is true adapt the URL
 * to files keeping the extension
 * by default the value is false
 */

$url = new PrettyUrl('TweetBeeg tall tweets.jpg', true);
$newUrl = $url->Urlify();
echo $newUrl;
// result: 'tweetbeeg-tall-tweets.jpg'

```

Generating URLs with a determinate length. If the
length is higher that the defined the Url will be shorted.

```php

require 'lib/PrettyUrl.php';
use PrettyUrl;

$url = new PrettyUrl('TweetBeeg tall tweets $$Jlkjs~write more than  ?¿  140 character$=@');
/*
 * also valid
 * $url = new PrettyUrl('TweetBeeg tall tweets $$Jlkjs~write more than  ?¿  140 character$=@', false, 20);
 */
$url->setMaxLength(20);
$newUrl = $url->Urlify();
echo $newUrl;
// result: 'tweetbeeg-tall-tweet'

```