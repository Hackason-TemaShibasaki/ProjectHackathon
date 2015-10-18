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
class ComfirmController extends Zend_Controller_Action
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
    public function displayAction()
    {
        $req = $this->getRequest();
        $controllerName = $req->getControllerName();



        // 勤怠情報の取得
        $req = $this->getRequest();
        $res = $this->getResponse();
        $controllerName = $req->getControllerName();

        $Date = $req->getparam('Date');
        $Attendance = $req->getparam('Attendance');
        $Reason = $req->getparam('Reason');
        $Destination = $req->getparam('Destination');
        $userName = $req->getparam('userName');
        $userId = $req->getparam('userId');


        // タイトルの設定
        $title = '勤怠報告('.$Date.')';
        $this->view->assign('title',$title);


        // 勤怠内容の設定
        // TODO：勤怠内容は何が取れる？取れた内容がintとかならそれでSwitchとかでString設定
        $main = '氏名：'.$userName.'<br>'.
                '日付：'.$Date.'<br>'.
                '勤怠：'.'<br>'.
                '理由：'.$Reason;


        $this->view->assign('main',$main);

        // 宛先の設定
        $this->view->assign('Destination',$Destination);

        $displayContent = $this->view->render($controllerName . '/comfirm.tpl');
        // 表示
        $res = $this->getResponse();
        $res->setBody($displayContent);

    }



    /**
     * メッセージ送信処理 Action
     *
     * @return void
     */
    public function sendMessage()
    {
    	$req = $this->getRequest();
    	$res = $this->getResponse();
    	$controllerName = $req->getControllerName();


    	$loginInfo = $req->getCookie('CBSESSID');
    	$userId = $req->getparam('userId');
    	$sendAddress =
    	$bodyText = $req->getparam('main');

    	// がルーンAPIの生成
    	$garoonApi = new GaroonApiLib();

    	// メッセージを送信
    	$result = $garoonApi->messageCreateThreads($loginInfo, $userId, $sendAddress, $bodyText, $subjectText);
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

        	// ログイン画面へ遷移
            $this->view->assign('errorMessage', 'ログインに失敗しました。');
            $displayContent = $this->view->render($controllerName . '/error.tpl');
        }

        // 表示
        $res->setBody($displayContent);

    }
}
