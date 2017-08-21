<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\components;

/**
 * Description of ErrorAction
 *
 * @author Mohammad Nadzif <demihuman@live.com>
 */
class ErrorAction extends \yii\web\ErrorAction {

  //put your code here
  protected function renderHtmlResponse() {
    if (\Yii::$app->user->isGuest) {
      return $this->controller->renderPartial($this->view ? : $this->id, $this->getViewRenderParams());
    } else {
      return $this->controller->render($this->view ? : $this->id, $this->getViewRenderParams());
    }
  }

}
