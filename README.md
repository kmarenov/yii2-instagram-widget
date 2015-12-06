Yii2 Instagram Widget
=====================
Yii2 Instagram Widget allows you to show your Instagram Photos on your Yii Framework 2 based Website.

This widget is based on [inwidget](https://github.com/aik27/inwidget/) by aik27 and use cosenary's [PHP wrapper for the Instagram API](https://github.com/cosenary/Instagram-PHP-API).

![Example](http://i.imgur.com/HcG3qGc.png)

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

1. Go to the [Instagram developer website](http://instagram.com/developer/authentication/)
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
* `showBy` : (`user` or `tag`) If `clientId` and `userName` options are set both, You can select how to show photos: by username or by hashtag 
* `isCacheEnabled` : (`true` or `false`) Enable cashing (default: true)
* `cacheTime` : Cache lifetime (default: 3600 = 1 hour)
* `width` : Widget width in pixels (default: 260)
* `imgWidth` : Width of one image in pixels (default: 0 = calculated automatically)
* `inline` : Count of image at one row (default: 4)
* `isShowToolbar` : (`true` or `false`) To show Toolbar or not (default: true)
* `count` : Total count of showed images (default: 12)
* `imgRes` : (`low_resolution` - 320x320, `thumbnail` - 150x150, `standard_resolution` - 640x640) Resolution of images (default: thumbnail)