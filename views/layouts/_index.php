<?php

use yii\bootstrap\Html;

$this->title = isset($title) ? $title : Yii::$app->params['appInfo']['name'];

if (isset($contents) && is_array($contents)) {
  echo Html::beginTag('div', ['class' => 'row']);
  echo Html::beginTag('div', ['class' => 'col-md-12 col-sm-12 col-xs-12']);
  foreach ($contents as $content) {

    echo $content;
  }
  echo Html::endTag('div');
  echo Html::endTag('div');
}
