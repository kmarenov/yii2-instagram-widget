<?php

namespace kmarenov\instagram;

use MetzWeb\Instagram\Instagram;
use Yii;

class InstagramWidget extends \yii\base\Widget
{
    public $clientId;
    public $userName;
    public $tag;
    public $showBy = 'user';
    public $isCacheEnabled = true;
    public $cacheTime = 3600;

    public $width = 260;
    public $imgWidth = 0;
    public $inline = 4;
    public $isShowToolbar = true;
    public $count = 12;
    public $imgRes = 'thumbnail';

    private $instagram;


    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['kmarenov/instagram/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@kmarenov/instagram/messages',
            'fileMap' => [
                'kmarenov/instagram/messages' => 'widget.php',
            ],
        ];
    }

    public function run()
    {
        $this->width -= 2;

        if ($this->width > 0) {
            $this->imgWidth = round(($this->width - (17 + (9 * $this->inline))) / $this->inline);
        }

        if (!empty($this->tag)) {
            $this->showBy = 'tag';
        }

        if ($this->showBy == 'tag') {
            $this->isShowToolbar = false;
        }

        $this->instagram = new Instagram($this->clientId);

        $user = false;
        if ($this->showBy == 'user') {
            $user = $this->findUser($this->userName);
            $media = $this->findMediaByUser($user, $this->count);
        } elseif ($this->showBy == 'tag') {
            $media = $this->findMediaByTag($this->tag, $this->count);
        }

        return $this->render('default',
            [
                'user' => $user,
                'media' => $media,
                'userName' => $this->userName,
                'width' => $this->width,
                'imgWidth' => $this->imgWidth,
                'inline' => $this->inline,
                'isShowToolbar' => $this->isShowToolbar,
                'imgRes' => $this->imgRes,

                'title' => InstagramWidget::t('messages', 'title'),
                'buttonFollow' => InstagramWidget::t('messages', 'buttonFollow'),
                'statPosts' => InstagramWidget::t('messages', 'statPosts'),
                'statFollowers' => InstagramWidget::t('messages', 'statFollowers'),
                'statFollowing' => InstagramWidget::t('messages', 'statFollowing'),
                'imgEmpty' => InstagramWidget::t('messages', 'imgEmpty'),
            ]
        );
    }

    public function findUser($userName)
    {
        if (empty($userName)) {
            throw new \Exception('Empty \'userName\' argument');
            return false;
        }

        $key = 'kmarenov_instagram_find_user_' . $userName;

        if ($this->isCacheEnabled) {
            $user = \Yii::$app->cache->get($key);
        }

        if ($user === false || !$this->isCacheEnabled) {
            $users = $this->instagram->searchUser($userName, 1);

            if (!empty($users->meta->error_message)) {
                throw new \Exception($users->meta->error_message);
            } else {
                $user_id = $users->data[0]->id;

                if (!empty($user_id)) {
                    $user = $this->instagram->getUser($user_id);
                }
            }

            if ($this->isCacheEnabled) {
                \Yii::$app->cache->set($key, $user, $this->cacheTime);
            }
        }

        if (!empty($user->meta->error_message)) {
            throw new \Exception($user->meta->error_message);
        }

        if (empty($user->data)) {
            throw new \Exception('User not found');
        }

        return $user->data;
    }

    public function findMediaByUser($user, $count)
    {
        if (empty($user)) {
            throw new \Exception('Empty \'user\' argument');
            return false;
        }

        $key = 'kmarenov_instagram_find_media_by_user_' . $this->userName . '_' . $count;

        if ($this->isCacheEnabled) {
            $media = \Yii::$app->cache->get($key);
        }

        if ($media === false || !$this->isCacheEnabled) {
            $media = $this->instagram->getUserMedia($user->id, $count);

            if ($this->isCacheEnabled) {
                \Yii::$app->cache->set($key, $media, $this->cacheTime);
            }
        }

        if (!empty($media->meta->error_message)) {
            throw new \Exception($media->meta->error_message);
        }

        return $media;
    }

    public function findMediaByTag($tag, $count)
    {
        $key = 'kmarenov_instagram_find_media_by_tag_' . $tag . '_' . $count;

        if ($this->isCacheEnabled) {
            $media = \Yii::$app->cache->get($key);
        }

        if ($media === false || !$this->isCacheEnabled) {
            if (!empty($this->tag)) {
                $media = $this->instagram->getTagMedia($tag, $count);
            }

            if ($this->isCacheEnabled) {
                \Yii::$app->cache->set($key, $media, $this->cacheTime);
            }
        }

        if (!empty($media->meta->error_message)) {
            throw new \Exception($media->meta->error_message);
        }

        return $media;
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('kmarenov/instagram/' . $category, $message, $params, $language);
    }
}
