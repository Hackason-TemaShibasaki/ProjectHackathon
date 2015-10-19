<?php

/**
 * 初期設定
 *
 * PHP versions 5.3.2
 *
 * @category
 * @package   App
 * @author
 * @copyright Alphawave inc.
 * @license
 * @link
 */

//定義値
define('APPLICATION_ENV', 'development');

/** ベースパス */
define('BASE_PATH', 'C:/xampp/htdocs/ProjectHackathon');
define('CONFIGURE_PATH', BASE_PATH . '/configure');

// applicationディレクトリのパス定義
define('APPLICATION_PATH', realpath(dirname(__FILE__)));
// views/templatesのapplicationディレクトリからの相対パス
define('ABSOLUTE_VIEWS_TEMPLATE_PATH', '/views/templates');
// テンプレートパス
define('TEMPLATES_PATH', APPLICATION_PATH . ABSOLUTE_VIEWS_TEMPLATE_PATH);
// config.iniパス
define('CONFIG_INI_PATH', APPLICATION_PATH . '/configs/config.ini');

/** smarty 基底パス */
define('SMARTY_HOME_PATH', 'C:/xampp/htdocs/tmp/smarty');


/** ライブラリ 基底パス */
define('LIBRARY_PATH', CONFIGURE_PATH . '/lib');

/** zend framework */
define('ZF_PATH', LIBRARY_PATH . '/_zf/ZendFramework-1.11.2/library/');

/** smarty cache */
define('SMARTY_CACHE_PATH', SMARTY_HOME_PATH . '/ProjectHackathon/cache/');
/** smarty compile */
define('SMARTY_COMPILE_PATH', SMARTY_HOME_PATH . '/ProjectHackathon/compile/');
/** smarty base */
define('SMARTY_LIB_PATH', LIBRARY_PATH . '/_smarty/Smarty-3.1.12/libs');

/** PHPネイティブエラーログファイルの出力先設定 */
define('LOG_PATH', BASE_PATH . '/error.log');

// 文字エンコーディング
define('ENCODING', 'UTF-8');
define('INTERNAL_ENCODING', ENCODING);

define('SAMPLE_API_PATH', LIBRARY_PATH . '/_apilib');

//include_pathの設定
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            APPLICATION_PATH,
            LIBRARY_PATH,
            ZF_PATH,
            SMARTY_LIB_PATH,
            SAMPLE_API_PATH,
            get_include_path()
        )
    )
);

// Smarty読み込み
require_once SMARTY_LIB_PATH . '/Smarty.class.php';

// $locale = new Zend_Locale('ja_JP');
// Zend_Registry::set('Zend_Locale', $locale);

// Autoloaderの有効化
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);
$resourceLoader = new Zend_Loader_Autoloader_Resource(
    array(
        'basePath'      => APPLICATION_PATH . '/models',
        'namespace'     => 'Model',
        'resourceTypes' => array(
            'common' => array(
                'path'      => 'common',
                'namespace' => 'Common',
            ),
        )
    )
);

// PHPネイティブエラーログファイルの出力先設定
ini_set('error_log', LOG_PATH);

// 文字コードの設定
mb_language('Japanese');
mb_internal_encoding(INTERNAL_ENCODING);
mb_regex_encoding(ENCODING);

// メイン処理の実行
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$application->bootstrap();
$application->run();
