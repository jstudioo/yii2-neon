<?php

use lambda\neon\components\ActiveForm;
use lambda\neon\components\Panel;
use app\models\SourceMessage;
use yii\bootstrap\Html;

Panel::begin([
  'title' => Yii::t('app', 'Change Email')
]);

$formChangeAccount = ActiveForm::begin();

echo $formChangeAccount->field($changeAccount, 'username')->textInput();
echo $formChangeAccount->field($changeAccount, 'email')->textInput();
echo $formChangeAccount->field($changeAccount, 'confirmPassword')->passwordInput();

echo $formChangeAccount->panelFormButtons();

ActiveForm::end();
Panel::end();


Panel::begin([
  'title' => Yii::t('app', 'Change Password')
]);

$formChangePassword = ActiveForm::begin();

echo $formChangePassword->field($changePassword, 'newPassword')->passwordInput();
echo $formChangePassword->field($changePassword, 'confirmPassword')->passwordInput();
echo $formChangePassword->field($changePassword, 'currentPassword')->passwordInput();

echo $formChangePassword->panelFormButtons();

ActiveForm::end();
Panel::end();



Panel::begin([
  'title' => Yii::t('app', 'Change Language')
]);

$userLanguage = Yii::$app->user->identity->configLanguage;

echo Html::beginTag('div', ['class' => 'btn-toolbar']);
foreach (SourceMessage::languageCodeList() as $languageCode => $languageCountry) {

  echo Html::a(ucwords($languageCountry), ['/site/change-language', 'code' => $languageCode], ['class' => ($userLanguage == $languageCode ? 'btn btn-success' : 'btn btn-primary'), 'data-method' => 'post']);
}
echo Html::endTag('div');

Panel::end();
