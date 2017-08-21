<?php

use app\components\Menu;
use yii\bootstrap\Html;

$dashboardMenu = [
  'label' => Yii::t('app', 'Dashboard'),
  'url' => ['/site/index'],
  'icon' => 'entypo-home'
];

$contractMenu = [
  'label' => Yii::t('app', 'Contract'),
  'url' => '#',
  'icon' => 'entypo-docs',
  'active' => in_array($this->context->route, ['contract-type/index', 'contract/index']),
  'items' => [
    ['label' => Yii::t('app', 'Type'), 'url' => ['/contract-type/index']],
    ['label' => Yii::t('app', 'Manage'), 'url' => ['/contract/index']],
  ]
];

$userMenu = [
  'label' => Yii::t('app', 'User'),
  'url' => '#',
  'icon' => 'entypo-user',
  'active' => in_array($this->context->route, ['region/index', 'supervisor/index', 'user/index']),
  'items' => [
    ['label' => Yii::t('app', 'Region'), 'url' => ['/region/index']],
    ['label' => Yii::t('app', 'Manage'), 'url' => ['/user/index']],
    ['label' => Yii::t('app', 'Supervisor'), 'url' => ['/supervisor/index']],
  ]
];

$vendorMenu = [
  'label' => Yii::t('app', 'Vendor'),
  'url' => '#',
  'icon' => 'entypo-user',
  'active' => in_array($this->context->route, ['bank/index', 'company-type/index', 'company-status/index', 'vendor/index']),
  'items' => [
    ['label' => Yii::t('app', 'Bank'), 'url' => ['/bank/index']],
    ['label' => Yii::t('app', 'Type'), 'url' => ['/company-type/index']],
    ['label' => Yii::t('app', 'Status'), 'url' => ['/company-status/index']],
    ['label' => Yii::t('app', 'Manage'), 'url' => ['/vendor/index']],
  ]
];

$authorizationMenu = [
  'label' => Yii::t('app', 'Authorization'),
  'url' => '#',
  'icon' => 'entypo-key',
  'active' => in_array($this->context->route, ['authorization/permission', 'authorization/role']),
  'items' => [
    ['label' => Yii::t('app', 'Permission'), 'url' => ['/authorization/permission']],
    ['label' => Yii::t('app', 'Role'), 'url' => ['/authorization/role']],
  ],
];


$settingMenu = [
  'label' => Yii::t('app', 'Setting'),
  'url' => '#',
  'icon' => 'entypo-cog',
  'active' => in_array($this->context->route, ['setting/general', 'setting/translation', 'setting/log']),
  'items' => [
    ['label' => Yii::t('app', 'General'), 'url' => ['/setting/general']],
    ['label' => Yii::t('app', 'Translation'), 'url' => ['/setting/translation']],
    ['label' => Yii::t('app', 'Log'), 'url' => ['/setting/log']],
  ],
];



$accountMenu = [
  'label' => Yii::t('app', 'Account'),
  'url' => '#',
  'icon' => 'entypo-user',
  'options' => ['class' => 'visible-xs root-level'],
  'active' => in_array($this->context->route, ['site/profile', 'site/account', 'site/help']),
  'items' => [
    ['label' => Yii::t('app', 'Profile'), 'url' => ['/site/profile']],
    ['label' => Yii::t('app', 'Account'), 'url' => ['/site/account']],
    ['label' => Yii::t('app', 'Help'), 'url' => ['/site/help']],
  ]
];

$logoutMenu = [
  'label' => Yii::t('app', 'Logout'),
  'url' => ['/site/logout'],
  'icon' => 'entypo-logout',
  'options' => ['class' => 'visible-xs root-level'],
  'linkOptions' => [
    'data-method' => 'post',
    'data-confirm' => Yii::t('app', 'Are you sure want to Logout?')
  ]
];

$menuItems = [];

if (Yii::$app->user->can('SuperUser')) {
  $menuItems = [
    $dashboardMenu,
    $authorizationMenu,
    $userMenu,
    $vendorMenu,
    $contractMenu,
    $settingMenu,
    $accountMenu,
    $logoutMenu
  ];
} elseif (Yii::$app->user->can('Administrator')) {
  $menuItems = [
    $dashboardMenu,
    $userMenu,
    $vendorMenu,
    $authorizationMenu,
    $contractMenu,
    $settingMenu,
    $accountMenu,
    $logoutMenu
  ];
}


echo Html::beginTag('div', ['class' => 'navbar-inner']);
echo Html::beginTag('div', ['class' => 'navbar-brand']);

echo Html::a(Html::img('@web/images/logo_red.png', [ 'width' => 88]), ['site/index']);
echo Html::endTag('div');

echo Menu::widget([
  'options' => ['class' => 'navbar-nav'],
  'items' => $menuItems,
]);


echo Html::beginTag('ul', ['class' => 'nav navbar-right']);
echo Html::beginTag('li');


echo Html::beginTag('ul', ['class' => 'user-info']);
echo Html::beginTag('li', ['class' => 'profile-info dropdown']);
echo Html::a(Html::tag('span', Yii::$app->user->identity->name, ['class' => 'profile-title']) . Html::img(Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar->source : Yii::$app->urlManager->baseUrl . Yii::$app->params['default']['avatar'], ['class' => 'img-circle', 'width' => 44]), '#', ['data-toggle' => 'dropdown', 'class' => 'dropdown-toggle']);

echo Html::ul([
  Html::a(Html::tag('i', FALSE, ['class' => 'entypo-info']) . Yii::t('app', 'Profile'), ['/site/profile']),
  Html::a(Html::tag('i', FALSE, ['class' => 'entypo-user']) . Yii::t('app', 'Account'), ['/site/account']),
  Html::a(Html::tag('i', FALSE, ['class' => 'entypo-help-circled']) . Yii::t('app', 'Help'), ['/site/help']),
  Html::a(Html::tag('i', FALSE, ['class' => 'entypo-logout']) . Yii::t('app', 'Logout'), ['/site/logout'], ['data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure want to Logout?')])
    ], ['encode' => FALSE, 'class' => 'dropdown-menu']);


echo Html::endTag('li');
echo Html::endTag('ul');

echo Html::endTag('li');

echo Html::beginTag('li', ['class' => 'visible-xs']);

echo Html::beginTag('div', ['class' => 'horizontal-mobile-menu visible-xs']);
echo Html::a(Html::tag('i', FALSE, ['class' => 'entypo-menu']), '#', ['class' => 'with-animation']);
echo Html::endTag('div');

echo Html::endTag('li');

echo Html::endTag('ul');
echo Html::endTag('div');
