<?php

use lambda\neon\components\GridView;

if (isset($searchClass)) {
  $searchModel = Yii::createObject($searchClass);
  echo GridView::widget([
    'dataProvider' => $searchModel->search(),
    'filterModel' => $searchModel,
    'columns' => $searchModel->getGridColumns(),
  ]);
}


