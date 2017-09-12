<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\rbac\Permission;

/**
 * Description of AuthRole
 *
 * @author Mohammad Nadzif <demihuman@live.com>
 */
class AuthRole extends AuthItem {

  public $type = 1;

  public static function tableName() {
    return '{{%auth_item}}';
  }

  public static function find() {
    return parent::find()->where(['type' => Permission::TYPE_ROLE]);
  }

  public function add() {
    $authManager = Yii::$app->authManager;
    $tempRole = $authManager->createRole($this->name);
    $tempRole->description = $this->description;
    $authManager->add($tempRole);
    return TRUE;
  }

  public function updateByName($name) {
    $authManager = Yii::$app->authManager;
    $updatedRole = $authManager->createRole($this->name);
    $updatedRole->description = $this->description;
    return $authManager->update($name, $updatedRole);
  }

  public function delete() {
    $authManager = Yii::$app->authManager;
    $tempRole = $authManager->createPermission($this->name);
    return $authManager->remove($tempRole);
  }

}
