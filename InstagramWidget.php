<?php

namespace kmarenov\instagram;

use MetzWeb\Instagram\Instagram;

class InstagramWidget extends \yii\base\Widget
{
    public $clientId;
    public $userName;
    public $media;
    public $user;
    public $tag;
    public $showBy = 'user';
    public $isCacheEnabled = true;
    public $cacheTime = 3600;
    public $instagram;
    public $error_message;

    public $width = 260;
    public $imgWidth = 0;
    public $inline = 4;
    public $isShowToolbar = true;
    public $count = 12;
    public $imgRes = 'thumbnail';


    public function run()
    {
        $this->width -= 2;

        if ($this->width > 0) {
            $this->imgWidth = round(($this->width - (17 + (9 * $this->inline))) / $this->inline);
        }

        if ($this->showBy == 'tag') {
            $this->isShowToolbar = false;
        }

        $this->instagram = new Instagram($this->clientId);

        if ($this->showBy == 'user') {
            $this->user = $this->findUser();
            $this->media = $this->findMediaByUser();
        } elseif ($this->showBy == 'tag') {
            $this->media = $this > findMediaByTag();
        }

        return $this->render('default', ['widget' => $this]);
    }

    public function findUser()
    {
        $userName = $this->userName;

        if (empty($userName)) {
            return array();
        }

        $key = 'kmarenov_instagram_find_user_' . $userName;

        if ($this->isCacheEnabled) {
            $user = \Yii::$app->cache->get($key);
        }

        if ($user === false || !$this->isCacheEnabled) {
            $users = json_decode(json_encode($this->instagram->searchUser($userName, 1)), true);

            if (!empty($users['meta']['error_message'])) {
                $this->error_message = $users['meta']['error_message'];
            } else {
                $user_id = $users['data'][0]["id"];

                if (!empty($user_id)) {
                    $user = json_decode(json_encode($this->instagram->getUser($user_id)), true);
                }
            }

            if ($this->isCacheEnabled) {
                \Yii::$app->cache->set($key, $user, $this->cacheTime);
            }
        }

        if (!empty($user['meta']['error_message'])) {
            $this->error_message = $user['meta']['error_message'];
            return array();
        }

        if (empty($user['data'])) {
            return array();
        }

        return $user['data'];
    }

    public function findMediaByUser()
    {
        $key = 'kmarenov_instagram_find_media_by_user_' . $this->userName . '_' . $this->count;

        if ($this->isCacheEnabled) {
            $media = \Yii::$app->cache->get($key);
        }

        if ($media === false || !$this->isCacheEnabled) {
            $user = $this->findUser();

            if (!empty($user)) {
                $media = json_decode(json_encode($this->instagram->getUserMedia($user['id'], $this->count)), true);
            }

            if ($this->isCacheEnabled) {
                \Yii::$app->cache->set($key, $media, $this->cacheTime);
            }
        }

        if (!empty($media['meta']['error_message'])) {
            $this->error_message = $media['meta']['error_message'];
            return array();
        }

        return $media;
    }

    public function findMediaByTag()
    {
        $key = 'kmarenov_instagram_find_media_by_tag_' . $this->tag . '_' . $this->count;

        if ($this->isCacheEnabled) {
            $media = \Yii::$app->cache->get($key);
        }

        if ($media === false || !$this->isCacheEnabled) {
            if (!empty($this->tag)) {
                $media = json_decode(json_encode($this->instagram->getTagMedia($this->tag, $this->count)), true);
            }

            if ($this->isCacheEnabled) {
                \Yii::$app->cache->set($key, $media, $this->cacheTime);
            }
        }

        if (!empty($media['meta']['error_message'])) {
            $this->error_message = $media['meta']['error_message'];
            return array();
        }

        return $media;
    }
}
