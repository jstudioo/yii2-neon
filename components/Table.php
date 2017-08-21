<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\components;

use yii\base\InvalidCallException;
use yii\base\Widget;
use yii\bootstrap\Html;

/**
 * Description of Table
 *
 * @author Mohammad Nadzif <demihuman@live.com>
 */
class Table extends Widget {

  public $headers = [];
  public $columnFormats = [];
  public $options = ['class' => 'table table-bordered table-hover table-responsive'];
  private $_rows = [];

  public function row($columns = [], $options = []) {
    $columnData = '';
    foreach ($columns as $index => $column) {
      $columnData .= Html::tag('td', \Yii::$app->formatter->format($column, key_exists($index, $this->columnFormats) ? $this->columnFormats[$index] : 'text'));
    }

    return Html::tag('tr', $columnData, $options);
  }

  public function run() {
    if (!empty($this->_fields)) {
      throw new InvalidCallException('Each beginField() should have a matching endField() call.');
    }

    $content = ob_get_clean();
    echo Html::beginTag('table', $this->options);
    echo Html::beginTag('thead');
    echo Html::beginTag('tr');

    foreach ($this->headers as $header) {
      echo Html::tag('th', $header);
    }
    echo Html::endTag('tr');
    echo Html::endTag('thead');
    echo Html::beginTag('tbody');

    echo $content;
    echo Html::endTag('tbody');


    echo Html::endTag('table');
  }

  public function init() {
    if (!isset($this->options['id'])) {
      $this->options['id'] = $this->getId();
    }
    ob_start();
    ob_implicit_flush(false);
  }

}
