<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\components;

use kartik\select2\Select2;

/**
 * Description of Select2
 *
 * @author Lambda
 */
class AjaxSelect2 extends Select2 {

  public $ajaxUrl = FALSE;

  public function __construct($config = array()) {
    $defaultAjax = [
      'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 3,
        'ajax' => [
          'url' => $config['ajaxUrl'],
          'dataType' => 'json',
          'type' => 'POST',
        ],
    ]];
    $allConfig = array_merge($defaultAjax, $config);


    return parent::__construct($allConfig);
  }

  public function run() {
    return parent::run();
  }

}
