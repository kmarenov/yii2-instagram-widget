<?php

namespace kmarenov\instagram;

use MetzWeb\Instagram\Instagram;

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
                'imgRes' => $this->imgRes
            ]
        );
    }

    public function findUser($userName)
    {
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

    public function findMediaByUser($user, $count)
    {
        if (empty($user)) {
            return array();
        }

        $key = 'kmarenov_instagram_find_media_by_user_' . $this->userName . '_' . $count;

        if ($this->isCacheEnabled) {
            $media = \Yii::$app->cache->get($key);
        }

        if ($media === false || !$this->isCacheEnabled) {
            $media = json_decode(json_encode($this->instagram->getUserMedia($user['id'], $count)), true);

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

    public function findMediaByTag($tag, $count)
    {
        $key = 'kmarenov_instagram_find_media_by_tag_' . $tag . '_' . $count;

        if ($this->isCacheEnabled) {
            $media = \Yii::$app->cache->get($key);
        }

        if ($media === false || !$this->isCacheEnabled) {
            if (!empty($this->tag)) {
                $media = json_decode(json_encode($this->instagram->getTagMedia($tag, $count)), true);
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
