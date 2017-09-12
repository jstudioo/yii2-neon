<?php

use lambda\neon\components\ActiveForm;
use lambda\neon\components\Panel;
use lambda\neon\models\AppFile;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\bootstrap\Html;

echo Html::beginTag('div', ['class' => 'col-md-8 col-sm-12 col-xs-12']);
Panel::begin([
  'title' => Yii::t('app', 'Edit Profile'),
]);

$profileForm = ActiveForm::begin(['type' => 'vertical']);

echo $profileForm->field($mUser, 'firstName')->textInput();
echo $profileForm->field($mUser, 'lastName')->textInput();
echo $profileForm->field($mUser, 'address')->textarea();
echo $profileForm->field($mUser, 'countryCode')->textInput();
echo $profileForm->field($mUser, 'areaCode')->textInput();
echo $profileForm->field($mUser, 'phoneNumber')->textInput();
echo $profileForm->field($mUser, 'mobileNumber')->textInput();

echo $profileForm->panelFormButtons();

ActiveForm::end();
Panel::end();
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'col-md-4 col-sm-12 col-xs-12']);
Panel::begin([
  'title' => Yii::t('app', 'Change Avatar'),
]);

echo FileInput::widget([
  'name' => 'avatarFile',
  'options' => [
    'multiple' => FALSE
  ],
  'pluginOptions' => [
    'uploadAsync' => true,
    'overwriteInitial' => false,
    'allowedFileExtensions' => AppFile::getAllowedImageExtensions(),
    'uploadPath' => Yii::$app->basePath . '/uploads/',
    'uploadUrl' => Url::to(['/site/upload-avatar']),
  ],
]);

Panel::end();
echo Html::endTag('div');
