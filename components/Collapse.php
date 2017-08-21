<?php

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

class Collapse extends \yii\bootstrap\Collapse {

  public function renderItems() {
    $items = [];
    $index = 0;
    foreach ($this->items as $item) {
      if (!array_key_exists('label', $item)) {
        throw new InvalidConfigException(Yii::t('app', 'The \'label\' option is required.'));
      }
      $header = $item['label'];
      $options = ArrayHelper::getValue($item, 'options', []);
      Html::addCssClass($options, ['panel' => 'panel', 'widget' => 'panel-invert']);
      $items[] = Html::tag('div', $this->renderItem($header, $item, ++$index), $options);
    }

    return implode("\n", $items);
  }

}
