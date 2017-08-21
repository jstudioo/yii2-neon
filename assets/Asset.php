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
    'src/css/font-icons/entypo/css/entypo.css',
    'src/css/font-icons/font-awesome/css/font-awesome.css',
    'src/css/noto-sans.css',
    'src/css/neon-core.css',
    'src/css/neon-theme.css',
    'src/css/skins/white.css',
    'src/js/jvectormap/jquery-jvectormap-1.2.2.css',
    'src/js/rickshaw/rickshaw.min.css',
    'src/js/vertical-timeline/css/component.css',
    'src/css/css-loader.css',
  ];
  public $js = [
    'src/js/gsap/main-gsap.js',
    'src/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js',
    'src/js/joinable.js',
    'src/js/resizeable.js',
    'src/js/neon-api.js',
    'src/js/jvectormap/jquery-jvectormap-1.2.2.min.js',
    'src/js/jvectormap/jquery-jvectormap-europe-merc-en.js',
    'src/js/jquery.sparkline.min.js',
    'src/js/rickshaw/vendor/d3.v3.js',
    'src/js/rickshaw/rickshaw.min.js',
    'src/js/raphael-min.js',
    'src/js/morris.min.js',
    'src/js/toastr.js',
    'src/js/neon-custom.js',
    'src/js/preloader.js'
  ];
  public $depends = [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
  ];

}
