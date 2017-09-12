<?php

use lambda\neon\components\DetailView;

if ($detailModel) {
  $detailConfig = [
    'model' => $detailModel,
    'header' => Yii::t('app', 'Record Information'),
  ];

  if (isset($modelColumns)) {
    $detailConfig['attributes'] = $modelColumns;
  }

  echo DetailView::widget($detailConfig);
}
