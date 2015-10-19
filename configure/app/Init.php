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


//### テスト用の処理（コントローラが呼び出せるまでここに書きます）ST ###

// require_once 'CybozuGaroonAPI.php';
// $api = new CybozuGaroonAPI('shibasaki', 'arishiba_0221');
// // // $api->setUser('user', 'password');
// // // $api->UtilGetLoginUserId();
// // // $api->MessageGetThreadsById();
// // // $api->MessageSearchThreads();
// // $startDate = strtotime('20151008');
// // $api->MessageGetThreadVersions($startDate, false, false, '4802');
// // // $api->BaseGetApplicationStatus();
// $param = new CbgrnThreadType();
// $param->addressee = new CbgrnMessageAddressee();
// $param->addressee->user_id = '10';
// $param->addressee->name = '柴崎稚人';
// $param->addressee->deleted = false;
// $param->creator = new CbgrnChangeLogType();
// $param->creator->user_id = '10';
// $param->creator->name = '柴崎稚人';
// $param->modifier = new CbgrnChangeLogType();
// $param->modifier->user_id = '10';
// $param->modifier->name = '柴崎稚人';
// // $param->content = new CbgrnThreadType();
// // $param->content->body = 'TestMessage Body\nAAA';
// $param->id = 'dummy';
// $param->version = 'dummy';
// $param->subject = 'TestMessage Title\nBBB';
// $param->confirm = false;
// try {
// $result = $api->MessageCreateThreads($param);
// } catch (Exception $exp) {
//     echo $exp->getMessage();
// }

//     $wsdl = 'http://cyb-grn.alphawave.co.jp/cgi-bin/grn210/grn.cgi?WSDL';
// // //     $api_parameters = array("shibasaki", "password", "MessageGetThreadsById",
// // //             '<parameters>
// // //                 <thread_id>305335</thread_id>
// // //             </parameters>'
// // //         );
// //     $api_parameters = array(
// //             '<parameters><thread_id>305335</thread_id></parameters>'
// //         );
// //     $url = 'http://cyb-grn.alphawave.co.jp/cgi-bin/grn210/grn.cgi/cbpapi/message/api?';


//     $options = array('soap_version' => SOAP_1_2, 'trace' => 1, 'cache_wsdl' => WSDL_CACHE_BOTH, 'style' => SOAP_DOCUMENT, 'uri' => 'soap');
// //     $soapClient = new SoapClient($wsdl, $options);
// $soapClient = new SoapClientDummy($wsdl, $options);

// //     $actionName = 'UtilGetLoginUserId';
// //     $actionName = 'MessageGetThreadsById';
// //     $actionName = 'MessageSearchThreads';
//     $actionName = 'MessageGetThreadVersions';
// // $actionName = 'BaseGetApplicationStatus';
// //     $actionName = 'UtilLogin';
//     $userName = 'shibasaki';
//     $password = 'password';
//     $userId = '10';

//     $soapHeaders = array();
//     $work_str = '<Action xmlns="http://schemas.xmlsoap.org/ws/2003/03/addressing">' . $actionName . '</Action>';
//     $soapHeaders[] = new SoapHeader('http://wsdl.cybozu.co.jp/util_api/2008', 'Action', new SoapVar($work_str, XSD_ANYXML));


//     $work_str = '<Security xmlns:wsu="http://schemas.xmlsoap.org/ws/2002/12/secext"><UsernameToken>'
//                 . '<Username>' . $userName . '</Username>'
//                 . '<Password>' . $password . '</Password>'
//                 . '</UsernameToken></Security>';
//     $soapHeaders[] = new SoapHeader("http://wsdl.cybozu.co.jp/util_api/2008", "Security", new SoapVar($work_str, XSD_ANYXML));


//     $created_date = gmdate('Y-m-d\TH:i:sP', time() - 24 * 3600);
//     $expires_date = gmdate('Y-m-d\TH:i:sP', time() + 24 * 3600);
//     $work_str = '<Timestamp>' . '<Created>' . $created_date . '</Created>'
//                 . '<Expires>' . $expires_date . '</Expires>'
//                 . '</Timestamp>';
//     $soapHeaders[] = new SoapHeader("http://wsdl.cybozu.co.jp/util_api/2008", "Timestamp", new SoapVar($work_str, XSD_ANYXML));


//     $tmp = 'jp';
//     $work_str = '<Locale>' . $tmp . '</Locale>';
//     $soapHeaders[] = new SoapHeader("http://wsdl.cybozu.co.jp/util_api/2008", "Locale", new SoapVar($work_str, XSD_ANYXML));

//     $soapClient->__setSoapHeaders(NULL);
//     $soapClient->__setSoapHeaders($soapHeaders);

// //     $result = $soapClient::UtilGetLoginUserId();
// try {
// //     $result = $soapClient->UtilGetLoginUserId();
// // $result = $soapClient->MessageGetThreadsById();
// // $result = $soapClient->MessageSearchThreads();
// // $args = array();
// // $startDate = strtotime('20151008');
// // $startDate = gmdate('Y-m-d\TH:i:sP', $startDate);
// // // $args['start'] = gmdate('Y-m-d\TH:i:sP', $startDate);
// // $args['start'] = mb_convert_encoding($startDate, "UTF-8", __PHP_SCRIPT_ENCODING__);
// // $args['folder_id'][0] = mb_convert_encoding('4802', "UTF-8", __PHP_SCRIPT_ENCODING__);
// // $result = $soapClient->MessageGetThreadVersions($args);
// // //     $result = $soapClient->BaseGetApplicationStatus();
// // echo 'userid[' . $result->user_id . ']';


// //*** メッセージ取得のサンプル ST ***
// // $garoonApi = new GaroonApiLib();
// // $result = $garoonApi->messageGetThreadVersions($userName, $password, '20151008', null, '4802');

// // $messages = array();
// // if (!empty($result)) {
// //     $messageList = $result->thread_item;
// //     foreach ($messageList as $item) {
// //         $messages[] = $garoonApi->getMessageById($userName, $password, $item->id);
// //     }
// // }

// // echo $messages;
// //*** メッセージ取得のサンプル ED ***


// //*** メッセージ送信のサンプル ST ***
// $garoonApi = new GaroonApiLib();
// $sendAddress = array();
// $sendAddress['user_id'] = '10';
// $sendAddress['name'] = '柴崎稚人';
// $result = $garoonApi->messageCreateThreads($userName, $password);
// echo $result;
// //*** メッセージ送信のサンプル ED ***
// } catch (Exception $ex) {
//     echo $ex->getMessage();
// }

// // //     $result = $soapClient->__soapCall('MessageGetThreadsById', $api_parameters);

// //     $result = $soapClient->__getFunctions();
// //     $result = $soapClient->__soapCall('UtilGetLoginUserId', array());
// // echo var_dump($result);
// // //     $result = $soapClient->MessageGetThreadsById($api_parameters);
// // //     $result = $soapClient->__doRequest($request, $location, $action, $version)
// //     $result = $soapClient->__soapCall('MessageGetThreadsById', $api_parameters);
// // echo var_dump($result);

//### テスト用の処理（コントローラが呼び出せるまでここに書きます）ED ###




// メイン処理の実行
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$application->bootstrap();
$application->run();
