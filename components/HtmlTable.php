<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\components;

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/**
 * Description of HtmlTable
 *
 * @author lambdaconsulting
 */
class HtmlTable extends \yii\bootstrap\Widget {

  public $headers = FALSE;
  public $responsive = TRUE;
  public $tableClass = 'table table-hover table-bordered kv-table-wrap';
  public $tableOptions = [];

  public function init() {
    ob_start();
    ob_implicit_flush(false);
  }

  public function run() {
    $content = ob_get_clean();

    echo $this->responsive ? Html::beginTag('div', ['class' => 'table-responsive']) : FALSE;
    $options['class'] = $this->tableClass;
    echo Html::beginTag('table', ArrayHelper::merge($this->tableOptions, $options));
    if ($this->headers && is_array($this->headers)) {
      echo $this->beginHead();
      echo $this->beginRow();
      echo $this->addRow($this->headers, 'th');
      echo $this->endRow();
      echo $this->endHead();
    } else {
      echo $this->beginBody();
    }

    echo $content;
    if (is_bool($this->headers) && !$this->headers) {
      echo $this->endBody();
    }

    echo Html::endTag('table');
    echo $this->responsive ? Html::endTag('div') : FALSE;
  }

  public function beginHead() {
    echo Html::beginTag('thead');
  }

  public function endHead() {
    echo Html::endTag('thead');
  }

  public function beginBody() {
    echo Html::beginTag('tbody');
  }

  public function endBody() {
    echo Html::endTag('tbody');
  }

  public function beginFoot() {
    echo Html::beginTag('tfoot');
  }

  public function endFoot() {
    echo Html::endTag('tfoot');
  }

  public function addRow($columns = array(), $columnTag = 'td') {
    echo Html::beginTag('tr');
    foreach ($columns as $column) {
      if (is_array($column)) {
        echo Html::tag($columnTag, $column['value'], $column['options']);
      } else {
        echo Html::tag($columnTag, $column);
      }
    }
    echo Html::endTag('tr');
  }

  public function beginRow() {
    echo Html::beginTag('tr');
  }

  public function endRow() {
    echo Html::endTag('tr');
  }

  public function addColumn($value, $options = [], $columnTag = 'td') {
    echo Html::tag($columnTag, $value, $options);
  }

}
