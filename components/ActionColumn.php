<?php

namespace lambda\neon\components;

use app\models\Base;
use kartik\grid\ActionColumnAsset;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class ActionColumn extends \kartik\grid\ActionColumn {

  public $template = '{detail} {update} {delete}';
  public $header = 'Option';
  public $controller;
  public $beforeAction;
  public $keyName = 'hashId';
  public $isDetailPopup = TRUE;

  public function __construct($config = array()) {
    parent::__construct($config);
    $this->header = yii\bootstrap\Html::tag('i', FALSE, ['class' => 'entypo entypo-cog']);
  }

  protected function initDefaultButtons() {

    if (!isset($this->buttons['detail'])) {
      $this->buttons['detail'] = function ($url) {
        if ($this->isDetailPopup) {
          return Html::a(Html::tag('i', FALSE, ['class' => 'entypo entypo-info-circled']), FALSE, ['title' => 'Detail', 'class' => 'showModalButton', 'value' => $url]);
        } else {
          $options = $this->viewOptions;
          $title = Yii::t('kvgrid', 'Detail');
          $icon = Html::tag('i', FALSE, ['class' => 'entypo entypo-info-circled']);
          $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
          $options = array_replace_recursive(['title' => $title, 'data-pjax' => '0'], $options);
          if ($this->_isDropdown) {
            $options['tabindex'] = '-1';
            return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
          } else {
            return Html::a($label, $url, $options);
          }
        }
      };
    }
    if (!isset($this->buttons['update'])) {
      $this->buttons['update'] = function ($url) {
        $options = $this->updateOptions;
        $title = Yii::t('kvgrid', 'Update');
        $icon = Html::tag('i', FALSE, ['class' => 'entypo entypo-pencil']);
        $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
        $options = array_replace_recursive(['title' => $title, 'data-pjax' => '0'], $options);
        if ($this->_isDropdown) {
          $options['tabindex'] = '-1';
          return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
        } else {
          return Html::a($label, $url, $options);
        }
      };
    }

    if (!isset($this->buttons['delete'])) {
      $this->buttons['delete'] = function ($url, $model) {
        if ($model instanceof Base) {
          if (!$model->isDeleted) {
            $options = $this->deleteOptions;

            $title = Yii::t('kvgrid', 'Delete');
            $icon = Html::tag('i', FALSE, ['class' => 'entypo entypo-trash']);
            $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
            $msg = ArrayHelper::remove($options, 'message', Yii::t('kvgrid', 'Are you sure to delete this item?'));
            $defaults = ['title' => $title, 'data-pjax' => 'false'];
            $pjax = $this->grid->pjax ? true : false;
            $pjaxContainer = $pjax ? $this->grid->pjaxSettings['options']['id'] : '';
            if ($pjax) {
              $defaults['data-pjax-container'] = $pjaxContainer;
            }
            $options = array_replace_recursive($defaults, $options);
            $options['data-method'] = 'post';
            $css = $this->grid->options['id'] . '-action-del';
            Html::addCssClass($options, $css);
            $view = $this->grid->getView();
            $delOpts = Json::encode(
                    [
                      'css' => $css,
                      'pjax' => $pjax,
                      'data-method' => 'post',
                      'pjaxContainer' => $pjaxContainer,
                      'lib' => ArrayHelper::getValue($this->grid->krajeeDialogSettings, 'libName', 'krajeeDialog'),
                      'msg' => $msg,
                    ]
            );
            ActionColumnAsset::register($view);
            $js = "kvActionDelete({$delOpts});";
            $view->registerJs($js);
            $this->initPjax($js);
            if ($this->_isDropdown) {
              $options['tabindex'] = '-1';
              return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
            } else {
              return Html::a($label, $url, $options);
            }
          } else {
            $options = $this->deleteOptions;
            $title = Yii::t('kvgrid', 'Restore');
            $icon = Html::tag('i', FALSE, ['class' => 'entypo entypo-arrows-ccw']);
            $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
            $msg = ArrayHelper::remove($options, 'message', Yii::t('kvgrid', 'Are you sure to restore this item?'));
            $defaults = ['title' => $title, 'data-pjax' => 'false'];
            $pjax = $this->grid->pjax ? true : false;
            $pjaxContainer = $pjax ? $this->grid->pjaxSettings['options']['id'] : '';
            if ($pjax) {
              $defaults['data-pjax-container'] = $pjaxContainer;
            }
            $options = array_replace_recursive($defaults, $options);
            $options['data-method'] = 'post';

            $css = $this->grid->options['id'] . '-action-del';
            Html::addCssClass($options, $css);
            $view = $this->grid->getView();
            $delOpts = Json::encode(
                    [
                      'css' => $css,
                      'pjax' => $pjax,
                      'pjaxContainer' => $pjaxContainer,
                      'lib' => ArrayHelper::getValue($this->grid->krajeeDialogSettings, 'libName', 'krajeeDialog'),
                      'msg' => $msg,
                    ]
            );
            ActionColumnAsset::register($view);
            $js = "kvActionDelete({$delOpts});";
            $view->registerJs($js);
            $this->initPjax($js);
            if ($this->_isDropdown) {
              $options['tabindex'] = '-1';
              return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
            } else {
              return Html::a($label, $url, $options);
            }
          }
        } else {
          $options = $this->deleteOptions;
          $title = Yii::t('kvgrid', 'Delete');
          $icon = Html::tag('i', FALSE, ['class' => 'entypo entypo-trash']);
          $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
          $msg = ArrayHelper::remove($options, 'message', Yii::t('kvgrid', 'Are you sure to delete this item?'));
          $defaults = ['title' => $title, 'data-pjax' => 'false'];
          $pjax = $this->grid->pjax ? true : false;
          $pjaxContainer = $pjax ? $this->grid->pjaxSettings['options']['id'] : '';
          if ($pjax) {
            $defaults['data-pjax-container'] = $pjaxContainer;
          }
          $options = array_replace_recursive($defaults, $options);
          $options['data-method'] = 'post';
          $css = $this->grid->options['id'] . '-action-del';
          Html::addCssClass($options, $css);
          $view = $this->grid->getView();
          $delOpts = Json::encode(
                  [
                    'css' => $css,
                    'pjax' => $pjax,
                    'pjaxContainer' => $pjaxContainer,
                    'lib' => ArrayHelper::getValue($this->grid->krajeeDialogSettings, 'libName', 'krajeeDialog'),
                    'msg' => $msg,
                  ]
          );
          ActionColumnAsset::register($view);
          $js = "kvActionDelete({$delOpts});";
          $view->registerJs($js);
          $this->initPjax($js);
          if ($this->_isDropdown) {
            $options['tabindex'] = '-1';
            return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
          } else {
            return Html::a($label, $url, $options);
          }
        }
      };
    }
  }

  public function createUrl($action, $model, $key, $index) {
    if (is_callable($this->urlCreator)) {
      return call_user_func($this->urlCreator, $action, $model, $key, $index);
    } else {
      $params = is_array($key) ? $key : [$this->keyName => (string) $key];

      $params[0] = $this->controller ? $this->controller . '-' . $action : $this->controller . $action;

      return Url::toRoute($params);
    }
  }

}
