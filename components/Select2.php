<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\components;

use kartik\select2\Select2 as KartikSelect2;

/**
 * Description of Select2
 *
 * @author Lambda
 */
class Select2 extends KartikSelect2 {

  public $size = 'md';
  public $theme = 'bootstrap';
  public $options = ['placeholder' => 'Select an option ...'];
  public $pluginOptions = [
    'closeOnSelect' => TRUE,
    'allowClear' => TRUE,
    'hideSearch' => FALSE,
  ];

}
