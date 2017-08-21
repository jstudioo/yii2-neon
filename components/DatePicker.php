<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\components;

/**
 * Description of DatePicker
 *
 * @author Nadzif Glovory
 */
class DatePicker extends \kartik\date\DatePicker {

  public $pluginOptions = [
    'autoclose' => true,
    'format' => 'yyyy-mm-dd'
  ];

}
