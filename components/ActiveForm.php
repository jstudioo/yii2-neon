<?php

/*
 *  This project developed by Lambda Consulting Teams
 */

namespace lambda\neon\components;

use yii\bootstrap\Html;

/**
 * Description of ActiveForm
 *
 * @author Mohammad Nadzif <nadzif.lambda@gmail.com>
 */
class ActiveForm extends \kartik\form\ActiveForm {

  public $type = 'horizontal';
  public $formConfig = [
    'showErrors' => TRUE,
    'showHints' => TRUE,
  ];
  public $size = 'sm';
  public $submitLabel = TRUE;
  public $resetLabel = TRUE;
  public $submitIcon = TRUE;
  public $resetIcon = TRUE;
  public $submitOptions = ['class' => 'btn btn-info'];
  public $resetOptions = ['class' => 'btn btn-danger'];
  public $showResetButton = TRUE;
  public $fieldConfig = ['template' => '{label}{beginWrapper}{input}{error}{endWrapper}{hint}',
    'horizontalCssClasses' => [
      'label' => 'control-label col-md-3 col-sm-3 col-xs-12',
      'wrapper' => 'col-md-6 col-sm-9 col-xs-12',
      'input' => 'form-control input-sm col-md-9 col-xs-12',
    ]
  ];

  public function formButtons($isNewRecord = TRUE, $wrapperClass = 'col-md-6 col-sm-6 col-xs-12 col-md-offset-3', $position = 'default') {
    $submitLabel = is_bool($this->submitLabel) ? ($isNewRecord ? \Yii::t('app', 'Submit') : \Yii::t('app', 'Update')) : $this->submitLabel;
    $resetLabel = is_bool($this->resetLabel) ? \Yii::t('app', 'Reset') : $this->resetLabel;

    $resetButton = $this->showResetButton ? Html::resetButton($resetLabel, $this->resetOptions) : FALSE;
    $submitButton = Html::submitButton($submitLabel, $this->submitOptions);

    $formButtons = Html::tag('div', $resetButton . ' ' . $submitButton, ['class' => $wrapperClass]);
    return Html::tag('div', $formButtons, ['class' => 'form-group']);
  }

  public function panelFormButtons($isNewRecord = TRUE, $wrapperClass = 'col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3', $position = 'default') {

    $submitLabel = is_bool($this->submitLabel) ? ($isNewRecord ? \Yii::t('app', 'Submit') : \Yii::t('app', 'Update')) : $this->submitLabel;
    $resetLabel = is_bool($this->resetLabel) ? \Yii::t('app', 'Reset') : $this->resetLabel;
    $submitIcon = is_bool($this->submitIcon) ? Html::tag('i', FALSE, ['class' => 'entypo entypo-check']) : FALSE;
    $resetIcon = is_bool($this->resetIcon) ? Html::tag('i', FALSE, ['class' => 'entypo entypo-ccw']) : FALSE;

    $resetButton = $this->showResetButton ? Html::resetButton(($resetIcon ? $resetIcon . ' ' : FALSE) . $resetLabel, $this->resetOptions) : FALSE;
    $submitButton = Html::submitButton(($submitIcon ? $submitIcon . ' ' : FALSE) . $submitLabel, $this->submitOptions);
    if ($this->type == 'horizontal') {
      $formButtons = Html::tag('div', $submitButton . ' ' . $resetButton, ['class' => $wrapperClass]);
    } else {
      $formButtons = $submitButton . ' ' . $resetButton;
    }

    return Html::tag('div', $formButtons, ['class' => 'form-group']);
  }

}
