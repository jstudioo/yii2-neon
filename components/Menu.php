<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\components;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu as WidgetMenu;

/**
 * Description of Menu
 *
 * @author Lambda
 */
class Menu extends WidgetMenu {

  public $linkTemplate = '<a href="{url}">{icon}<span class="title">{label}</span></a>';

  protected function renderItem($item) {
    if (isset($item['url'])) {
      $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

      if (key_exists('icon', $item)) {
        return strtr($template, [
          '{url}' => Html::encode(Url::to($item['url'])),
          '{label}' => $item['label'],
          '{icon}' => '<i class="' . $item['icon'] . '"></i>',
        ]);
      } else {
        return strtr($template, [
          '{url}' => Html::encode(Url::to($item['url'])),
          '{label}' => $item['label'],
          '{icon}' => FALSE,
        ]);
      }
    }

    $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

    if (key_exists('icon', $item)) {
      return strtr($template, [
        '{label}' => $item['label'],
        '{icon}' => '<i class="' . $item['icon'] . '"></i>',
      ]);
    } else {
      return strtr($template, [
        '{label}' => $item['label'],
        '{icon}' => FALSE,
      ]);
    }
  }

}
