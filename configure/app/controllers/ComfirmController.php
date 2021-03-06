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
        $main = "氏名：".$userName."\n".
                "日付：".$Date."\n".
                "勤怠：".$Attendance."\n".
                "理由：".$Reason;

        $this->view->assign('main',$main);

        // 宛先の設定
        $loginInfo = $req->getCookie('CBSESSID');
        // ガルーンAPIの生成
        $garoonApi = new GaroonApiLib();
        $DestinationUserInfo = $garoonApi->baseGetUsersById($Destination, $loginInfo);
        $DestinationUserName = $DestinationUserInfo->user->name;
        $this->view->assign('Destination',$DestinationUserName);

		// 送信用パラメータの設定
        $this->view->assign('bodyText',htmlspecialchars($main));
        $this->view->assign('sendAddres',$Destination);
        $this->view->assign('userId',$userId);
        $this->view->assign('subjectText',$title);
        $this->view->assign('userName',$userName);

        // 表示
        $displayContent = $this->view->render($controllerName . '/comfirm.tpl');
        $res = $this->getResponse();
        $res->setBody($displayContent);

    }



    /**
     * メッセージ送信処理 Action
     *
     * @return void
     */
    public function sendmessageAction()
    {
    	$req = $this->getRequest();
    	$res = $this->getResponse();
    	$controllerName = $req->getControllerName();


    	$loginInfo = $req->getCookie('CBSESSID');
    	$userId = $req->getparam('userId');
    	$userName = $req->getparam('userName');
    	$sendAddress = $req->getparam('sendAddres');
    	$bodyText = $req->getparam('bodyText');
    	$subjectText = $req->getparam('subjectText');

    	// ガルーンAPIの生成
    	$garoonApi = new GaroonApiLib();
    	$token = $garoonApi->utilGetRequestToken($loginInfo);

    	// メッセージを送信
    	$result = $garoonApi->messageCreateThreads($loginInfo, $userId,$userName, $sendAddress, $bodyText, $subjectText, $token);
    }


}
