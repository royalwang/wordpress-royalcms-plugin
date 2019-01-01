<?php
/**
 * @package Royalcms
 * @version 5.4.0
 */
/*
 Plugin Name: Royalcms Framework
 Plugin URI: http://wordpress.org/plugins/royalcms/
 Description: 基于royalcms的全栈PHP框架，使用现代PHP构建Wordpress的插件、主题更加方便、快捷。
 Author: Royal Wang
 Version: 5.4.0
 Author URI: http://royalcms.cn/
 */

define('ROYALCMS_FRAMEWORK_PLUGIN_URL', plugins_url('', __FILE__));
define('ROYALCMS_FRAMEWORK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ROYALCMS_FRAMEWORK_PLUGIN_FILE',  __FILE__);

// 站点根目录
if (! defined('SITE_ROOT')) {
    define('SITE_ROOT', ROYALCMS_FRAMEWORK_PLUGIN_DIR);
}

// 加载框架
require ROYALCMS_FRAMEWORK_PLUGIN_DIR . 'bootstrap/kernel.php';

// end
