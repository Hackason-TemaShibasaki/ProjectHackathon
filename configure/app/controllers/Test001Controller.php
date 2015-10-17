<?php

/**
 * Test001コントローラ
 *
 * @category
 * @package   Controllers
 * @author
 * @copyright Alphawave inc.
 * @license
 * @link
 *
 */
class Test001Controller extends Zend_Controller_Action
{
    /**
     * コンストラクタ
     *
     * @param Zend_Controller_Request_Abstract  $request
     * @param Zend_Controller_Response_Abstract $response
     * @param array                             $invokeArgs
     */
    function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
        // 自動的にレンダリングOFF
        $this->_helper->viewRenderer->setNoRender();
    }

    /**
     * ログイン画面表示 Action
     *
     * @return void
     */
    public function indexAction()
    {

        $req = $this->getRequest();
        $controllerName = $req->getControllerName();

        $displayContent = $this->view->render($controllerName . '/login.tpl');

        // 表示
        $res = $this->getResponse();
        $res->setBody($displayContent);

    }

    /**
     * ログイン処理 Action
     *
     * @return void
     */
    public function loginAction()
    {
        $req = $this->getRequest();
        $res = $this->getResponse();
        $controllerName = $req->getControllerName();

        $loginName = $req->getparam('userName');
        $password = $req->getparam('password');

        $garoonApi = new GaroonApiLib();

        $result = $garoonApi->utilLogin($loginName, $password);

        if ($result->status === 'Login') {
            $loginResult = explode('=', $result->cookie);

            // ログイン情報をcookieに出力
            $cookie = new Zend_Http_Cookie($loginResult[0], $loginResult[1], 'localhost');
            $res->setHeader('Set-Cookie', $cookie->__toString());

            // ログインユーザー情報取得
            $searchUserName = array();
            $searchUserName[] = $loginName;
            $userInfo = $garoonApi->baseGetUsersByLoginName($searchUserName, $loginResult[1]);
            $userId = $userInfo->user->key;
            $userName = $userInfo->user->name;

            $loginUserInfo = array();
            $loginUserInfo['loginName'] = $loginName;
            $loginUserInfo['userId'] = $userId;
            $loginUserInfo['userName'] = $userName;

            $this->view->assign('loginInfo', $loginUserInfo);

            $displayContent = $this->view->render($controllerName . '/input.tpl');
        } else {

            $this->view->assign('errorMessage', 'ログインに失敗しました。');
            $displayContent = $this->view->render($controllerName . '/error.tpl');
        }

        // 表示
        $res->setBody($displayContent);

    }
}
