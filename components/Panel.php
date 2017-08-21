<?php

/*
 *  This project developed by Lambda Consulting Teams
 */

namespace lambda\neon\components;

use yii\helpers\Html;
use yii\base\Widget;

/**
 * Description of Panel
 *
 * @author Mohammad Nadzif <nadzif.lambda@gmail.com>
 */
class Panel extends Widget {

  public $title = '';
  public $titleClass = FALSE;
  public $collapsed = FALSE;
  public $panelClass = 'panel panel-primary';
  public $showCollapse = TRUE;
  public $showReload = FALSE;
  public $showClose = FALSE;
  public $footer = FALSE;
  public $wrapper = 'col-md-12';
  public $asRow = TRUE;

  public function init() {
    ob_start();
    ob_implicit_flush(false);
  }

  public function run() {
    $content = ob_get_clean();


    echo Html::beginTag('div', ['class' => $this->wrapper]);
    echo $this->asRow ? Html::beginTag('div', ['class' => 'row']) : FALSE;

    echo Html::beginTag('div', ['class' => $this->collapsed ? $this->panelClass . ' panel-collapse' : $this->panelClass]);

    echo Html::beginTag('div', ['class' => 'panel-heading']);
    echo Html::beginTag('div', ['class' => $this->titleClass ? 'panel-title ' . $this->titleClass : 'panel-title']);
//    echo Html::a($this->title, '#', ['data-rel' => 'collapse']);
    echo $this->title;
    echo Html::endTag('div');
    echo Html::beginTag('div', ['class' => 'panel-options']);
    echo $this->showCollapse ? Html::a(Html::tag('i', '', ['class' => 'entypo-down-open']), '#', ['data-rel' => 'collapse']) : FALSE;
    echo $this->showReload ? Html::a(Html::tag('i', '', ['class' => 'entypo-arrows-ccw']), '#', ['data-rel' => 'reload']) : FALSE;
    echo $this->showClose ? Html::a(Html::tag('i', '', ['class' => 'entypo-cancel']), '#', ['data-rel' => 'close']) : FALSE;
    echo Html::endTag('div');
    echo Html::endTag('div');

    echo Html::beginTag('div', ['class' => 'panel-body', 'style' => $this->collapsed ? 'display: none;' : 'display: block;']);
    echo $content;


    if ($this->footer) {

    } else {
      echo Html::endTag('div');
    }

    echo Html::endTag('div');

    echo $this->asRow ? Html::endTag('div') : FALSE;
    echo Html::endTag('div');
  }

  public function beginFooter() {
    echo Html::endTag('div');

    echo Html::beginTag('div', ['class' => 'panel-footer']);
  }

  public function endFooter() {
    echo Html::endTag('div');
  }

}
