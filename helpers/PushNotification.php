<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\helpers;

use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Recipient\Topic;
use Yii;

/**
 * Description of Notification
 *
 * @author Nadzif Glovory
 */
class PushNotification {

  //put your code here

  public $title = FALSE;
  public $content = FALSE;
  public $color = '#FF0000';
  public $module = 'default';
  public $data = FALSE;
  public $tokens = FALSE;
  public $topic = FALSE;

  public function setTitle($title) {
    $this->title = $title;
  }

  public function setContent($content) {
    $this->content = $content;
  }

  public function setColor($color) {
    $this->color = $color;
  }

  public function setModule($module) {
    $this->module = $module;
  }

  public function setData($data = []) {
    $this->data = $data;
  }

  public function setTokenRecipients($tokens) {
    if ($tokens != []) {
      $this->tokens = $tokens;
    } else {
      $this->tokens = FALSE;
    }
  }

  public function setTopic($topic) {
    $this->topic = $topic;
  }

  public function send() {
    if ($this->tokens || $this->topic) {

      $note = Yii::$app->fcm->createNotification($this->title, $this->content);
      $note->setIcon('notification_icon_resource_name')
          ->setColor($this->color)
          ->setSound('default')
          ->setClickAction('FCM_PLUGIN_ACTIVITY')
          ->setBadge(1);

      $message = Yii::$app->fcm->createMessage();

      if ($this->topic) {
        $message->addRecipient(new Topic($this->topic));
      }

      if ($this->tokens) {
        foreach ($this->tokens as $userToken) {
          $message->addRecipient(new Device($userToken));
        }
      }


      $messageNotifData = $this->data ? \yii\helpers\ArrayHelper::merge(['module' => $this->module], $this->data) : ['module' => $this->module];
      $message->setNotification($note)->setData($messageNotifData);

      $response = Yii::$app->fcm->send($message);
      return $response->getStatusCode() == 200;
    } else {
      return FALSE;
    }
  }

}
