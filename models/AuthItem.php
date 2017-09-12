<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Description of AuthItem
 *
 * @author Mohammad Nadzif <demihuman@live.com>
 */
class AuthItem extends ActiveRecord {

  public $datatablePageSize = 10;

  public function rules() {
    return [
      [['name', 'description'], 'required'],
      [['name', 'description'], 'unique'],
      ['name', 'string', 'max' => 64],
      ['description', 'string', 'max' => 255],
    ];
  }

}
