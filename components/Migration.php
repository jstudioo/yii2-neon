<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\components;

/**
 * Description of Migration
 *
 * @author Lambda
 */
class Migration extends \yii\db\Migration {

  public $tableOptions = NULL;
  public $defaultBackupPath = 'lambda-tesseract';

  public function __construct($config = array()) {
    parent::__construct($config);
    if ($this->db->driverName === 'mysql') {
      $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }
  }

}
