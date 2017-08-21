<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\components;

/**
 * Description of DetailView
 *
 * @author Lambda
 */
class DetailView extends \yii\widgets\DetailView {

  public $template = '<tr><th width="30%"><strong class="pull-right">{label}</strong></th><td>{value}</td></tr>';
  public $headerTemplate = '<tr style="background-color: #ed1b2f !important;"><th colspan="2" style="color: #fff;"><i class="entypo entypo-info"></i><strong>{value}</strong></th></tr>';
  public $options = ['class' => 'table table-bordered table-hover table-responsive detail-view'];
  public $header = FALSE;
  public $wrapper = 'col-md-12 col-sm-12 col-xs-12';

  public function run() {
    $rows = [];
    $i = 0;

    if ($this->header) {
      $rows[] = $this->renderHeader($this->header);
    }

    foreach ($this->attributes as $attribute) {
      $rows[] = $this->renderAttribute($attribute, $i++);
    }

    $options = $this->options;
    $tag = \yii\helpers\ArrayHelper::remove($options, 'tag', 'table');

    echo $this->wrapper ? \yii\helpers\Html::beginTag('div', ['class' => $this->wrapper]) : FALSE;
    echo $this->wrapper ? \yii\helpers\Html::beginTag('div', ['class' => 'row']) : FALSE;
    echo \yii\bootstrap\Html::tag($tag, implode("\n", $rows), $options);
    echo $this->wrapper ? \yii\helpers\Html::endTag('div') : FALSE;
    echo $this->wrapper ? \yii\helpers\Html::endTag('div') : FALSE;
  }

  protected function renderHeader($header) {
    if (is_string($this->headerTemplate)) {
      return strtr($this->headerTemplate, [
        '{value}' => $header,
      ]);
    }
  }

}
