<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace lambda\neon\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Asset extends AssetBundle {

  public $sourcePath = '@lambda/neon/assets/src';
  public $css = [
    'css/font-icons/entypo/css/entypo.css',
    'css/font-icons/font-awesome/css/font-awesome.css',
    'css/noto-sans.css',
    'css/neon-core.css',
    'css/neon-theme.css',
    'css/skins/white.css',
    'js/jvectormap/jquery-jvectormap-1.2.2.css',
    'js/rickshaw/rickshaw.min.css',
    'js/vertical-timeline/css/component.css',
    'css/css-loader.css',
  ];
  public $js = [
    'js/gsap/main-gsap.js',
    'js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js',
    'js/joinable.js',
    'js/resizeable.js',
    'js/neon-api.js',
    'js/jvectormap/jquery-jvectormap-1.2.2.min.js',
    'js/jvectormap/jquery-jvectormap-europe-merc-en.js',
    'js/jquery.sparkline.min.js',
    'js/rickshaw/vendor/d3.v3.js',
    'js/rickshaw/rickshaw.min.js',
    'js/raphael-min.js',
    'js/morris.min.js',
    'js/toastr.js',
    'js/neon-custom.js',
    'js/preloader.js'
  ];
  public $depends = [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
  ];

}
