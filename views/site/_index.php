<?php

use lambda\neon\helpers\Main;
use yii\bootstrap\Html;

echo Html::beginTag('table', ['class' => 'table table-responsive table-bordered table-hover']);

echo Html::beginTag('thead');
echo Html::beginTag('tr');
echo Html::tag('th', Yii::t('app', '#'));
echo Html::tag('th', Yii::t('app', 'Leading Agreement Number'));
echo Html::tag('th', Yii::t('app', 'Contract Number'));
echo Html::tag('th', Yii::t('app', 'Vendor'));
echo Html::tag('th', Yii::t('app', 'End Date'));
echo Html::tag('th', Yii::t('app', 'Remaining Time'));
echo Html::tag('th', Html::tag('i', FALSE, ['class' => 'glyphicon glyphicon-info-sign']), ['class' => 'text-center']);
echo Html::endTag('tr');
echo Html::endTag('thead');

echo Html::beginTag('tbody');
$iC = 0;
foreach ($contractByDate as $mContract) {
  $iC++;
  echo Html::beginTag('tr');
  echo Html::tag('td', Yii::$app->formatter->asInteger($iC));
  echo Html::tag('td', Yii::$app->formatter->asText($mContract->leadingAgreementNumber));
  echo Html::tag('td', Yii::$app->formatter->asText($mContract->contractNumber));
  echo Html::tag('td', Yii::$app->formatter->asText($mContract->vendor ? $mContract->vendor->name : NULL));
  echo Html::tag('td', Yii::$app->formatter->asDate($mContract->contractEndDate));
  echo Html::tag('td', Main::getTimeDistance($mContract->contractEndDate));
  echo Html::tag('td', Html::a(Html::tag('i', FALSE, ['class' => 'glyphicon glyphicon-info-sign']), ['/contract/detail', 'hashId' => $mContract->hashId], ['title' => 'Detail']), ['class' => 'text-center']);

  echo Html::endTag('tr');
}
echo Html::endTag('tbody');

echo Html::endTag('table');


echo Html::beginTag('table', ['class' => 'table table-responsive table-bordered table-hover']);

echo Html::beginTag('thead');
echo Html::beginTag('tr');
echo Html::tag('th', Yii::t('app', '#'));
echo Html::tag('th', Yii::t('app', 'Leading Agreement Number'));
echo Html::tag('th', Yii::t('app', 'Contract Number'));
echo Html::tag('th', Yii::t('app', 'Vendor'));
echo Html::tag('th', Yii::t('app', 'Registered Value'));
echo Html::tag('th', Yii::t('app', 'Approved Realization Value'));
echo Html::tag('th', Yii::t('app', 'Remaining Value'));
echo Html::tag('th', Html::tag('i', FALSE, ['class' => 'glyphicon glyphicon-info-sign']), ['class' => 'text-center']);
echo Html::endTag('tr');
echo Html::endTag('thead');

echo Html::beginTag('tbody');
$iZ = 0;

foreach ($contractByValue as $mContract) {

  $iZ++;
  echo Html::beginTag('tr');
  echo Html::tag('td', Yii::$app->formatter->asInteger($iZ));
  echo Html::tag('td', Yii::$app->formatter->asText($mContract->leadingAgreementNumber));
  echo Html::tag('td', Yii::$app->formatter->asText($mContract->contractNumber));
  echo Html::tag('td', Yii::$app->formatter->asText($mContract->vendor ? $mContract->vendor->name : NULL));
  echo Html::tag('td', $mContract->totalValue, ['class' => 'text-right']);
  echo Html::tag('td', $mContract->approvedRealizationTotal, ['class' => 'text-right']);
  echo Html::tag('td', $mContract->totalRemainingValue, ['class' => 'text-right']);
  echo Html::tag('td', Html::a(Html::tag('i', FALSE, ['class' => 'glyphicon glyphicon-info-sign']), ['/contract/detail', 'hashId' => $mContract->hashId], ['title' => 'Detail']), ['class' => 'text-center']);

  echo Html::endTag('tr');
}
echo Html::endTag('tbody');

echo Html::endTag('table');
