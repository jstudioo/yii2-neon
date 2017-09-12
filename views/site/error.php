<?php

/**
 * @var $this yii\web\View
 * @var $name string
 * @var $message string
 * @var $exception \yii\web\HttpException
 */
use yii\helpers\Html;

$this->title = $name;
$textColor = $exception->statusCode === 404 ? "text-yellow" : "text-red";
?>


<div class="page-error-404">
  <div class="error-symbol">
    <i class="entypo-attention">
    </i>
  </div>
  <div class="error-text">
    <h2><?= $exception->statusCode ?></h2>
    <p>
      <?= nl2br(Html::encode($message)) ?>
    </p>

    <?= Html::a(Yii::t('app', 'Go to Previous Page'), Yii::$app->request->referrer, ['class' => 'btn btn-default entypo-back']); ?>
  </div>

</div>
