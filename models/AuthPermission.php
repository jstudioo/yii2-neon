<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use app\models\AuthItem;
use Yii;
use yii\rbac\Permission;

/**
 * Description of AuthPermission
 *
 * @author Mohammad Nadzif <demihuman@live.com>
 */
class AuthPermission extends AuthItem {

  public $type = 2;

  public static function tableName() {
    return '{{%auth_item}}';
  }

  public static function find() {
    return parent::find()->where(['type' => Permission::TYPE_PERMISSION]);
  }

  public function add() {
    $authManager = Yii::$app->authManager;
    $tempPermission = $authManager->createPermission($this->name);
    $tempPermission->description = $this->description;
    $authManager->add($tempPermission);
    return TRUE;
  }

  public function updateByName($name) {
    $authManager = Yii::$app->authManager;
    $updatedPermission = $authManager->createPermission($this->name);
    $updatedPermission->description = $this->description;
    return $authManager->update($name, $updatedPermission);
  }

  public function delete() {
    $authManager = Yii::$app->authManager;
    $tempPermission = $authManager->createPermission($this->name);
    return $authManager->remove($tempPermission);
  }

}
