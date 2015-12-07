Yii2 Instagram Widget
=====================
Yii2 Instagram Widget allows you to show your Instagram Photos on your Yii Framework 2 based Website.

This widget is based on [inwidget](https://github.com/aik27/inwidget/) by aik27 and use cosenary's [PHP wrapper for the Instagram API](https://github.com/cosenary/Instagram-PHP-API).

![Example](http://i.imgur.com/q5NrAlo.png)

The Widget is available in Russian and English translations (depends on language configuration of application)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kmarenov/yii2-instagram-widget "*"
```

or add

```
"kmarenov/yii2-instagram-widget": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \kmarenov\instagram\InstagramWidget::widget([
        'clientId' => '<your-instagram-client-id>',
        'userName' => 'shnurovs'
    ]);
?>
```

You must put your Instagram Client Id to 'clientId' option.

How to get Instagram Client Id:

1. Go to the [Instagram developer website](http://instagram.com/developer/)
2. Register your application
3. You are now presented with a Client ID

Yii2 Instagram Widget can show photos by Instagram Username or by Hashtag.

if you want to get photos by tag then use this code :


```php
<?= \kmarenov\instagram\InstagramWidget::widget([
        'clientId' => '<your-instagram-client-id>',
        'tag' => 'cat'
    ]);
?>
```


Widget Options
-----

* `clientId` : Your Instagram Client Id
* `userName` : Instagram Username of the user whose photos You want to show
* `tag` : Hashtag if You want to show photos by tag
* `showBy` : (`user` or `tag`) If `userName` and `tag` options are set both, You can select how to show photos: by username or by hashtag 
* `isCacheEnabled` : (`true` or `false`) Enable cashing (default: true)
* `cacheTime` : Cache lifetime (default: 3600 = 1 hour)
* `width` : Widget width in pixels (default: 260)
* `imgWidth` : Width of one image in pixels (default: 0 = calculated automatically)
* `inline` : Count of image at one row (default: 4)
* `isShowToolbar` : (`true` or `false`) Show or hide Toolbar (default: true)
* `count` : Total count of showed images (default: 12)
* `imgRes` : (`low_resolution` - 320x320, `thumbnail` - 150x150, `standard_resolution` - 640x640) Resolution of images (default: thumbnail)

**Warning: Because the Instagram API has a limit of requests count for a day, then enable caching is strongly recommended!** 


Examples
-----

**By default**

```php
<?= \kmarenov\instagram\InstagramWidget::widget([
        'clientId' => '<your-instagram-client-id>',
        'userName' => 'shnurovs'
    ]);
?>
```

![Example](http://i.imgur.com/q5NrAlo.png)


**Without toolbar**

```php
<?= \kmarenov\instagram\InstagramWidget::widget([
        'clientId'      => '<your-instagram-client-id>',
        'userName'      => 'shnurovs',
        'isShowToolbar' => false
    ]);
?>
```

![Without toolbar](http://i.imgur.com/MxruRy5.png)


**Mini**

```php
<?= \kmarenov\instagram\InstagramWidget::widget([
        'clientId'      => '<your-instagram-client-id>',
        'userName'      => 'shnurovs',
        'isShowToolbar' => false,
        'width'         => 100,
        'inline'        => 2
    ]);
?>
```


![Mini](http://i.imgur.com/BkXojso.png)

```php
<?= \kmarenov\instagram\InstagramWidget::widget([
        'clientId'      => '<your-instagram-client-id>',
        'userName'      => 'shnurovs',
        'isShowToolbar' => false,
        'width'         => 100,
        'inline'        => 1,
        'count'         => 3
    ]);
?>
```

![Mini](http://i.imgur.com/w2LFN70.png)


**Horisontal**

```php
<?= \kmarenov\instagram\InstagramWidget::widget([
        'clientId'      => '<your-instagram-client-id>',
        'userName'      => 'shnurovs',
        'isShowToolbar' => false,
        'width'         => 800,
        'inline'        => 7,
        'count'         => 14
    ]);
?>
```

![Horisontal](http://i.imgur.com/dSyu1Ti.png)


**Big previews**

```php
<?= \kmarenov\instagram\InstagramWidget::widget([
        'clientId'      => '<your-instagram-client-id>',
        'userName'      => 'shnurovs',
        'isShowToolbar' => false,
        'width'         => 800,
        'inline'        => 3,
        'count'         => 9,
        'imgRes'        => 'low_resolution'
    ]);
?>
```

![Big previews](http://i.imgur.com/jD8mENu.png)