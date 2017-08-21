<?php

namespace lambda\neon\components;

use Yii;
use yii\bootstrap\ButtonDropdown;

class ButtonDropdown {

  const BUTTON_CLASS = 'btn btn-sm btn-primary';

  public $buttonDropdown;
  public $dropdownItems;

  public function __construct() {
    $this->buttonDropdown = '';
    $this->dropdownItems = [];
  }

  public function addLink($label, $url = "#", $confirm = FALSE) {
    if ($confirm) {
      array_push($this->dropdownItems, ['label' => $label, 'url' => $url, 'options', 'linkOptions' => ['onclick' => 'return confirm("' . $confirm . '");']]);
    } else {
      array_push($this->dropdownItems, ['label' => $label, 'url' => $url]);
    }
  }

  public function adDivider() {
    array_push($this->dropdownItems, ['label' => '', 'url' => FALSE, 'options' => ['class' => 'divider']]);
  }

  public function getButton() {
    $btnClass = self::BUTTON_CLASS;
    $btnDropdown = ButtonDropdown::widget([
          'label' => 'Pilihan',
          'options' => ['class' => $btnClass],
          'dropdown' => [
            'items' => $this->dropdownItems,
          ],
    ]);
    return $btnDropdown;
  }

}
