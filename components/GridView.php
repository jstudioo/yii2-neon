<?php

namespace lambda\neon\components;

use Closure;
use kartik\grid\GridView;
use Yii;
use yii\helpers\Html;

class GridView extends GridView {

  public $responsive = TRUE;
  public $hover = TRUE;
  public $bordered = TRUE;
  public $striped = FALSE;
//  public $floatHeader = TRUE;
//  public $floatHeaderOptions = ['scrollingTop' => '50'];
  public $pjax = TRUE;
  public $heading = FALSE;
  public $pjaxSettings = ['neverTimeout' => true, 'loadingCssClass' => 'kv-grid-loading'];
  public $autoXlFormat = TRUE;
  public $layout = '
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="toolbar pull-left">
          {heading}
        </div>
        <div class="toolbar pull-right">
          {toolbar}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        {items}
      </div>
    </div>
    {summary}
    {pager}
    ';
  public $export = [
    'fontAwesome' => true,
    'showConfirmAlert' => false,
    'target' => '_blank',
    'options' => [
      'class' => 'btn-custom-toolbar'
    ]
  ];
  public $toggleDataOptions = [
    'all' => [
      'class' => 'btn btn-custom-toolbar',
    ],
    'page' => [
      'class' => 'btn btn-custom-toolbar',
    ],
  ];
  public $dataColumnClass = 'app\components\DataColumn';

  protected function initLayout() {
    Html::addCssClass($this->filterRowOptions, 'skip-export');
    if ($this->resizableColumns && $this->persistResize) {
      $key = empty($this->resizeStorageKey) ? Yii::$app->user->id : $this->resizeStorageKey;
      $gridId = empty($this->options['id']) ? $this->getId() : $this->options['id'];
      $this->containerOptions['data-resizable-columns-id'] = (empty($key) ? "kv-{$gridId}" : "kv-{$key}-{$gridId}");
    }
    if ($this->hideResizeMobile) {
      Html::addCssClass($this->options, 'hide-resize');
    }
    $export = $this->renderExport();
    $toggleData = $this->renderToggleData();
    $toolbar = strtr(
        $this->renderToolbar(), [
      '{export}' => $export,
      '{toggleData}' => $toggleData,
        ]
    );

    $replace = ['{toolbar}' => $toolbar];
    if (strpos($this->layout, '{export}') > 0) {
      $replace['{export}'] = $export;
    }
    if (strpos($this->layout, '{toggleData}') > 0) {
      $replace['{toggleData}'] = $toggleData;
    }
    $this->layout = strtr($this->layout, $replace);
    $this->layout = str_replace('{items}', Html::tag('div', '{items}', $this->containerOptions), $this->layout);
    if (is_array($this->replaceTags) && !empty($this->replaceTags)) {
      foreach ($this->replaceTags as $key => $value) {
        if ($value instanceof Closure) {
          $value = call_user_func($value, $this);
        }
        $this->layout = str_replace($key, $value, $this->layout);
      }
    }
    $this->layout = strtr($this->layout, [
      '{heading}' => Html::tag('h4', $this->heading, ['class' => 'grid-heading']),
    ]);
  }

}
