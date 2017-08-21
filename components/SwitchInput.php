<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\components;

use kartik\switchinput\SwitchInput as KartikSwitchInput;

/**
 * Description of SwitchInput
 *
 * @author Lambda
 */
class SwitchInput extends KartikSwitchInput {

  public $containerOptions = [];
  public $type = 1;
  public $pluginOptions = [
    'size' => 'small',
    'onText' => 'ON',
    'offText' => 'OFF',
    'onColor' => 'success',
    'offColor' => 'danger',
  ];

}
