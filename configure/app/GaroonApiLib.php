<?php

class GaroonApiLib
{
    const WSDL_STR = 'http://cyb-grn.alphawave.co.jp/cgi-bin/grn210/grn.cgi?WSDL';

    private $soapOptions = array('soap_version' => SOAP_1_2, 'trace' => 1, 'cache_wsdl' => WSDL_CACHE_BOTH, 'style' => SOAP_DOCUMENT, 'uri' => 'soap');

    /**
     * SOAPヘッダーの作成
     *
     * @param string $userName   ユーザー名
     * @param string $password   パスワード
     * @param string $actionName API名称
     *
     * @return array SOAPヘッダ
     */
    private function _createHeader($userName, $password, $actionName)
    {
        $soapHeaders = array();

        // Action
        $work_str = '<Action xmlns="http://schemas.xmlsoap.org/ws/2003/03/addressing">' . $actionName . '</Action>';
        $soapHeaders[] = new SoapHeader('http://wsdl.cybozu.co.jp/util_api/2008', 'Action', new SoapVar($work_str, XSD_ANYXML));

        // Security(ユーザー名、パスワード)
        $work_str = '<Security xmlns:wsu="http://schemas.xmlsoap.org/ws/2002/12/secext"><UsernameToken>'
                    . '<Username>' . $userName . '</Username>'
                    . '<Password>' . $password . '</Password>'
                    . '</UsernameToken></Security>';
        $soapHeaders[] = new SoapHeader("http://wsdl.cybozu.co.jp/util_api/2008", "Security", new SoapVar($work_str, XSD_ANYXML));

        // Timestamp
        $created_date = gmdate('Y-m-d\TH:i:sP', time() - 24 * 3600);
        $expires_date = gmdate('Y-m-d\TH:i:sP', time() + 24 * 3600);
        $work_str = '<Timestamp>' . '<Created>' . $created_date . '</Created>'
                    . '<Expires>' . $expires_date . '</Expires>'
                    . '</Timestamp>';
        $soapHeaders[] = new SoapHeader("http://wsdl.cybozu.co.jp/util_api/2008", "Timestamp", new SoapVar($work_str, XSD_ANYXML));

        // locale
        $tmp = 'jp';
        $work_str = '<Locale>' . $tmp . '</Locale>';
        $soapHeaders[] = new SoapHeader("http://wsdl.cybozu.co.jp/util_api/2008", "Locale", new SoapVar($work_str, XSD_ANYXML));

        return $soapHeaders;
    }


    /**
     * ログイン処理
     *
     * @param string $userName ユーザー名
     * @param string $password パスワード
     *
     * @return stdClass ログイン情報
     */
    public function utilLogin($userName, $password)
    {
        $actionName = 'UtilLogin';

        $args = array();
        $args['login_name'] = $userName;
        $args['password'] = $password;


        // SOAP通信
        $soapClient = new SoapClientDummy(self::WSDL_STR, $this->soapOptions);

        $soapHeaders = $this->_createHeader('Administrator', 'password', $actionName);
        $soapClient->__setSoapHeaders($soapHeaders);

        $result = $soapClient->UtilLogin($args);

        if (!empty($result)) {
            $result->login_name = str_replace(array("\r\n", "\n", "\r", " "), '', $result->login_name);
            $result->status = str_replace(array("\r\n", "\n", "\r", " "), '', $result->status);
            $result->cookie = str_replace(array("\r\n", "\n", "\r", " "), '', $result->cookie);
        }

        return $result;
    }

    /**
     * ログインアカウントのユーザーID取得
     *
     * @param string $loginInfo ログイン情報
     *
     * @return stdClass ユーザーID
     */
    public function utilGetLoginUserId($loginInfo)
    {
        $actionName = 'UtilGetLoginUserId';

        // SOAP通信
        $soapClient = new SoapClientDummy(self::WSDL_STR, $this->soapOptions);

        $soapHeaders = $this->_createHeader('', '', $actionName);
        $soapClient->__setSoapHeaders($soapHeaders);
        $soapClient->__setCookie('CBSESSID', $loginInfo);

        try {
            $result = $soapClient->UtilGetLoginUserId();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

        return $result;
    }

    /**
     * ユーザー情報の取得（ログイン名からユーザー情報を取得）
     *
     * @param string $name      ログイン名
     * @param string $loginInfo ログイン情報
     *
     * @return stdClass 検索結果(ユーザー情報)
     */
    public function baseGetUsersByLoginName($name, $loginInfo)
    {
        $actionName = 'BaseGetUsersByLoginName';

        $args = $name;

        // SOAP通信
        $soapClient = new SoapClientDummy(self::WSDL_STR, $this->soapOptions);

        $soapHeaders = $this->_createHeader('', '', $actionName);
        $soapClient->__setSoapHeaders($soapHeaders);
        $soapClient->__setCookie('CBSESSID', $loginInfo);

        try {
            $result = $soapClient->BaseGetUsersByLoginName($args);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

        return $result;
    }

    /**
     * メッセージの取得
     *
     * @param string $id        メッセージID
     * @param string $loginInfo ログイン情報
     *
     * @return stdClass メッセージ情報
     */
    public function getMessageById($id, $loginInfo)
    {
        $actionName = 'MessageGetThreadsById';

        $args = array();
        $args['thread_id'] = $id;

        // SOAP通信
        $soapClient = new SoapClientDummy(self::WSDL_STR, $this->soapOptions);
        $soapClient->__setCookie('CBSESSID', $loginInfo);

        $soapHeaders = $this->_createHeader('', '', $actionName);
        $soapClient->__setSoapHeaders($soapHeaders);

        $result = $soapClient->MessageGetThreadsById($args);

        return $result;

    }


    /**
     * メッセージの更新情報を取得
     *
     * @param string $loginInfo ログイン情報
     * @param string $startDate   取得開始日時
     * @param array  $threadItems 更新情報を取得するメッセージID＆バージョン
     * @param string $folderId    更新情報を取得するフォルダのID
     *
     * @return stdClass 取得結果
     */
    public function messageGetThreadVersions($loginInfo, $startDate = null, $threadItems = null, $folderId = null)
    {
        $actionName = 'MessageGetThreadVersions';

        $args = array();
        if (empty($startDate)) {
            $startDate = '20020101';
        }
        $startDate = strtotime($startDate);
        $args['start'] = gmdate('Y-m-d\TH:i:sP', $startDate);

        if (!empty($threadItem)) {
            $threadItems = array();
            foreach ($threadItems as $item) {
                $threadItems[] = array('id' => $item['id'], 'version' => $item['version']);
            }
        }

        if (!empty($folderId)) {
            $args['folder_id'][0] = $folderId;
        }

        // SOAP通信
        $soapClient = new SoapClientDummy(self::WSDL_STR, $this->soapOptions);
        $soapClient->__setCookie('CBSESSID', $loginInfo);

        $soapHeaders = $this->_createHeader('', '', $actionName);
        $soapClient->__setSoapHeaders($soapHeaders);

        $result = $soapClient->MessageGetThreadVersions($args);

        return $result;
    }

    /**
     * メッセージ送信
     *
     * @param string $loginInfo ログイン情報
     * @param array  $userId      ユーザーID
     * @param array  $sendAddress 送信先アドレス('user_id','name')
     * @param string $bodyText    メッセージ本文
     * @param string $subjectText メッセージタイトル
     * @param string $token       リクエストトークン
     *
     * @return Object 送信したメッセージ情報
     */
    public function messageCreateThreads($loginInfo, $userId, $userName, $sendAddress, $bodyText, $subjectText, $token)
    {

        $actionName = 'MessageCreateThreads';

        $thread = array();

        $addressee = array();
        $addressee['user_id'] = $sendAddress['user_id'];
        $addressee['name'] = 'dummy';
        $addressee['deleted'] = false;

        $creator = array();
        $creator['user_id'] = $userId;
        $creator['name'] = $userName;

        $modifier = array();
        $modifier['user_id'] = $userId;
        $modifier['name'] = $userName;

        $content = array();
        $content['body'] = $bodyText;

        $folder = array();

        $thread['addressee'] = $addressee;
        $thread['content'] = $content;
        $thread['folder'] = $folder;
        $thread['id'] = 'dummy';
        $thread['version'] = 'dummy';
        $thread['subject'] = $subjectText;
        $thread['confirm'] = false;
        $thread['creator'] = $creator;
        $thread['modifier'] = $modifier;

        $args = array();
        $args['create_thread']['thread'] = $thread;
        $args['request_token'] = $token->request_token;

        // SOAP通信
        $soapClient = new SoapClientDummy(self::WSDL_STR, $this->soapOptions);
        $soapClient->__setCookie('CBSESSID', $loginInfo);

        $soapHeaders = $this->_createHeader('', '', $actionName);
        $soapClient->__setSoapHeaders($soapHeaders);

        $result = $soapClient->MessageCreateThreads($args);

        return $result;
    }

    /**
     * リクエストトークンの取得（メッセージ送信等に使用する）
     *
     * @param string $loginInfo ログイン情報
     *
     * @return stdClass リクエストトークン情報(request_token)
     */
    public function utilGetRequestToken($loginInfo)
    {

        $actionName = 'UtilGetRequestToken';

        // SOAP通信
        $soapClient = new SoapClientDummy(self::WSDL_STR, $this->soapOptions);
        $soapClient->__setCookie('CBSESSID', $loginInfo);

        $soapHeaders = $this->_createHeader('', '', $actionName);
        $soapClient->__setSoapHeaders($soapHeaders);

        $result = $soapClient->UtilGetRequestToken();

        return $result;

    }


}
