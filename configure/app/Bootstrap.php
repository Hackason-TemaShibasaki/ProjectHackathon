<?php
require_once LIBRARY_PATH . '/View/Zend_View_Smarty.class.php';

/**
 * ブートストラップクラス
 *
 * @category
 * @package   Bootstrap
 * @author
 * @copyright Alphawave inc.
 * @license
 * @link
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * ブートストラップ
     *
     * @return void
     */
    protected function _initView()
    {
        /*
         * Zend_Controller_Action_Helper_ViewRendererオブジェクトに
         * Zend_View_Smartyクラスを登録
         */
        $view = new Zend_View_Smarty(
            TEMPLATES_PATH,
            array(
                'compile_dir' => SMARTY_COMPILE_PATH,
                'cache_dir'   => SMARTY_CACHE_PATH
            )
        );
        $render = new Zend_Controller_Action_Helper_ViewRenderer($view);
        $render->setViewBasePathSpec(':moduleDir' . ABSOLUTE_VIEWS_TEMPLATE_PATH)->setViewSuffix('tpl');

        // ヘルパーブローカにViewRendererヘルパーを登録
        Zend_Controller_Action_HelperBroker::addHelper($render);
    }
}
