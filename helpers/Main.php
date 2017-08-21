<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\helpers;

use Yii;
use yii\helpers\Html;

/**
 * Description of Base
 *
 * @author lambdaconsulting
 */
class Main {

  public static function getCurrentDateTime() {
    return date('Y-m-d H:i:s');
  }

  public static function getCurrentDate() {
    return date('Y-m-d');
  }

  public static function getCamelCase($text) {
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', ' ', $text);

    // trim
    $text = trim($text, '-');

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // lowercase
    $text = ucwords($text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', ' ', $text);

    $text = str_replace(' ', '', $text);

    return !empty($text) ? $text : FALSE;
  }

  public static function getSlug($text) {
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

    // trim
    $text = trim($text, '-');

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // lowercase
    $text = strtolower($text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    if (empty($text)) {
      return 'N/A';
    }

    return $text;
  }

  public static function makeDir($directoryPath, $chmod = 0777) {
    if (!is_dir($directoryPath)) {
      mkdir($directoryPath, $chmod, true);
    }
  }

  public static function setFeedback($message, $class = 'success') {
    return Yii::$app->session->addFlash('feedback-' . $class, $message);
  }

  public static function sendNotification($title, $content, $module, $tokens = FALSE, $color = '#FF0000') {

    $note = Yii::$app->fcm->createNotification($title, $content);
    $note->setIcon('notification_icon_resource_name')
        ->setColor($color)
        ->setSound('default')
        ->setClickAction('FCM_PLUGIN_ACTIVITY')
        ->setBadge(1);

    if ($tokens) {
      $message = Yii::$app->fcm->createMessage($tokens);
    } else {
      $message = Yii::$app->fcm->createMessage();
      $message->addRecipient(new Topic('all'));
    }

    $message->setNotification($note)->setData(['module' => $module]);

    $response = Yii::$app->fcm->send($message);
    return $response->getStatusCode() == 200;
  }

  public static function recordMessage($counter) {
    return Yii::t('app', 'There {n,plural,=0{are no entry found} =1{is one entry found} other{are # entries found}}!', ['n' => $counter]);
  }

  public static function getTimeDistance($upcomingDate) {
    $date = strtotime($upcomingDate); //Converted to a PHP date (a second count)

    $diff = $date - time(); //time returns current time in seconds
    $days = floor($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
    $hours = round(($diff - $days * 60 * 60 * 24) / (60 * 60));

    if ($days == 0) {
      if ($days < 1) {
        return Yii::t('app', "{hours} hours ago", [
              'hours' => $hours,
        ]);
      } else {
        return Yii::t('app', "{hours} hours remaining", [
              'hours' => $hours,
        ]);
      }
    } else {
      if ($days < 1) {
        $days = $days * -1;
        return Yii::t('app', "{days} days {hours} hours ago", [
              'days' => $days,
              'hours' => $hours,
        ]);
      } else {
        return Yii::t('app', "{days} days {hours} hours remaining", [
              'days' => $days,
              'hours' => $hours,
        ]);
      }
    }
  }

  public static function sortByKey($array, $on, $order = SORT_ASC) {

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
      foreach ($array as $k => $v) {
        if (is_array($v)) {
          foreach ($v as $k2 => $v2) {
            if ($k2 == $on) {
              $sortable_array[$k] = $v2;
            }
          }
        } else {
          $sortable_array[$k] = $v;
        }
      }

      switch ($order) {
        case SORT_ASC:
          asort($sortable_array);
          break;
        case SORT_DESC:
          arsort($sortable_array);
          break;
      }

      foreach ($sortable_array as $k => $v) {
        $new_array[$k] = $array[$k];
      }
    }

    return $new_array;
  }

  public static function generateTimelineItemAsArray($arrParams) {
    $timelineItems = [];

    foreach ($arrParams as $params) {
      $timelineItems[] = self::generateTimelineItem($params);
    }

    return $timelineItems;
  }

  public static function generateTimelineItem($params) {
    $timelineItem = Html::beginTag('time', ['class' => 'cbp_tmtime']);
    $timelineItem .= Html::tag('span', Yii::$app->formatter->asDate($params['datetime'], 'medium'));
    $timelineItem .= Html::tag('span', Yii::$app->formatter->asTime($params['datetime']));
    $timelineItem .= Html::endtag('time');

    $timelineItem .= Html::beginTag('div', ['class' => 'cbp_tmicon bg-' . $params['class']]);
    $timelineItem .= Html::tag('span', FALSE, ['class' => $params['icon']]);
    $timelineItem .= Html::endtag('div');

    $timelineItem .= Html::beginTag('div', ['class' => key_exists('description', $params) ? 'cbp_tmlabel' : 'cbp_tmlabel empty']);

    $timelineItem .= Html::beginTag('h2');
    $timelineItem .= Html::a($params['author'] . ' ');
    $timelineItem .= Html::tag('span', $params['title']);
    $timelineItem .= Html::endtag('h2');

    $timelineItem .= key_exists('description', $params) ? Html::tag('p', $params['description']) : FALSE;

    $timelineItem .= Html::endtag('div');
    return $timelineItem;
  }

}
