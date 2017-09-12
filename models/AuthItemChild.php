<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of AuthItemChild
 *
 * @author Mohammad Nadzif <demihuman@live.com>
 */
class AuthItemChild extends \yii\base\Model {

  public $authPermissions, $authRoles;

  public function rules() {
    return [
      [['authPermissions', 'authRoles'], 'each', 'rule' => ['string', 'max' => 64]]
    ];
  }

  public function attributeLabels() {
    return [
      'authRoles' => \Yii::t('app', 'Child Roles'),
      'authPermissions' => \Yii::t('app', 'Permissions'),
    ];
  }

}
