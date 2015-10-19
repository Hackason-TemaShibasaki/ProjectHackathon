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
      $sendAddress = array('user_id' => $req->getparam('sendAddres'));
      $bodyText = $req->getparam('bodyText');
      $subjectText = $req->getparam('subjectText');

      // ガルーンAPIの生成
      $garoonApi = new GaroonApiLib();
      $token = $garoonApi->utilGetRequestToken($loginInfo);

      // メッセージを送信
      $result = $garoonApi->messageCreateThreads($loginInfo, $userId,$userName, $sendAddress, $bodyText, $subjectText, $token);


      $loginUserInfo = array();
      $loginUserInfo['loginName'] = $userName;
      $loginUserInfo['userId'] = $userId;
      $loginUserInfo['userName'] = $userName;

      $this->view->assign('loginInfo', $loginUserInfo);

        // 表示
        $displayContent = $this->view->render($controllerName . '/complete.tpl');
        $res = $this->getResponse();
        $res->setBody($displayContent);
    }

//     public function sendmessageAction()
//     {
//         $req = $this->getRequest();
//         $res = $this->getResponse();
//         $controllerName = $req->getControllerName();

//         $userId = $req->getparam('userId');
//         $loginName = $req->getparam('userName');
//         $password = $req->getparam('password');

//         $loginInfo = $req->getCookie('CBSESSID');

//         $garoonApi = new GaroonApiLib();
// // $userName = $garoonApi->baseGetUsersById('10', $loginInfo);

//         $sendAddress = array('user_id' => '10');
//         $bodyText = "テスト用本文\nテスト本文２行目";
// //         $bodyText = htmlspecialchars("テスト用本文\nテスト本文２行目");
//         $subjectText = 'テスト用タイトル';

//         $token = $garoonApi->utilGetRequestToken($loginInfo);

//         $result = $garoonApi->messageCreateThreads($loginInfo, $userId, $loginName, $sendAddress, $bodyText, $subjectText, $token);

//         echo $result;


//     }
}
