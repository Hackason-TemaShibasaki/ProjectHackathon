<?php

/**
 * ZendとSmarty連携
 *
 * @category  Multibrand
 * @package   Zend_View_Smarty
 * @author
 * @copyright 2015 GOURMET NAVIGATOR INC.
 * @license
 * @link      http://host/multibrand
 */
class Zend_View_Smarty implements Zend_View_Interface
{

    /**
     * @var Smarty $_smarty Smartyオブジェクト
     */
    private $_smarty;

    /**
     * コンストラクタ
     *
     * @param string $tmplPath    テンプレートパス
     * @param array  $extraParams パラメータ
     *
     * @return void
     *
     */
    public function __construct($tmplPath = null, $extraParams = array())
    {
        /** smarty */
        include_once SMARTY_LIB_PATH . '/Smarty.class.php';

        $this->_smarty = new Smarty;

        if (null !== $tmplPath) {
            $this->setScriptPath($tmplPath);
        }

        foreach ($extraParams as $key => $value) {
            $this->_smarty->$key = $value;
        }
    }

    /**
     * テンプレートエンジンオブジェクトを返します
     *
     * @return Smarty
     */
    public function getEngine()
    {
        return $this->_smarty;
    }

    /**
     * テンプレートへのパスを設定します
     *
     * @param string $path パスとして設定するディレクトリ
     *
     * @return void
     */
    public function setScriptPath($path)
    {
        if (is_readable($path)) {
            $this->_smarty->template_dir = $path;
            return;
        }

        throw new Exception('無効なパスが指定されました');
    }

    /**
     * 現在のテンプレートディレクトリを取得します
     *
     * @return string
     */
    public function getScriptPaths()
    {
        // 本当は配列で返すのが正しいかもしれないが、
        // ViewRenderer.phpを使用すると警告が出るためtemplate_dirをそのまま返す
        return $this->_smarty->template_dir;
    }

    /**
     * setScriptPath へのエイリアス
     *
     * @param string $path   パス
     * @param string $prefix Unused
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setBasePath($path, $prefix = 'Zend_View')
    {
        return $this->setScriptPath($path);
    }

    /**
     * setScriptPath へのエイリアス
     *
     * @param string $path   パス
     * @param string $prefix Unused
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function addBasePath($path, $prefix = 'Zend_View')
    {
        return $this->setScriptPath($path);
    }

    /**
     * 変数をテンプレートに代入します
     *
     * @param string $key 変数名
     * @param mixed  $val 変数の値
     *
     * @return void
     */
    public function __set($key, $val)
    {
        $this->_smarty->assign($key, $val);
    }

    /**
     * empty() や isset() のテストが動作するようにします
     *
     * @param string $key キー
     *
     * @return boolean
     */
    public function __isset($key)
    {
        return (null !== $this->_smarty->get_template_vars($key));
    }

    /**
     * オブジェクトのプロパティに対して unset() が動作するようにします
     *
     * @param string $key キー
     *
     * @return void
     */
    public function __unset($key)
    {
        $this->_smarty->clear_assign($key);
    }

    /**
     * 変数をテンプレートに代入します
     *
     * 指定したキーを指定した値に設定します。あるいは、
     * キー => 値 形式の配列で一括設定します
     *
     * @param array $spec   使用する代入方式(キー、あるいは[キー=>値]の配列)
     * @param mixed $value  (オプション)名前を指定して代入する場合は、ここで値を指定します
     * @param bool  $escape 出力データをエスケープする。デフォルトはエスケープする。
     *
     * @return void
     */
    public function assign($spec, $value = null, $escape = true)
    {
        if (is_array($spec)) {
            if ($escape) {
                $spec = $this->_escape($spec);
            }
            $this->_smarty->assign($spec);
            return;
        }

        if ($escape) {
            $spec = $this->_escape($spec);
            $value = $this->_escape($value);
        }
        $this->_smarty->assign($spec, $value);
    }


    /**
     * HTMLエスケープを行う
     *
     * @param array|string $var エスケープ対象
     *
     * @return array|string エスケープ済オブジェクト
     */
    private function _escape($var)
    {
        $escaped = null;

        // 配列の場合
        if (is_array($var)) {
            foreach ($var as $key => $value) {
                // 再帰
                $escaped[$key] = $this->_escape($value);
            }
        // 文字列の場合
        } else if (is_string($var)) {
            $escaped = htmlentities($var, ENT_QUOTES, ENCODING);
        } else {
            $escaped = $var;
        }

        return $escaped;
    }
    /**
     * 代入済みのすべての変数を削除します
     *
     * Zend_View に {@link assign()} やプロパティ
     * ({@link __get()}/{@link __set()}) で代入された変数をすべて削除します
     *
     * @return void
     */
    public function clearVars()
    {
        $this->_smarty->clear_all_assign();
    }

    /**
     * テンプレートを処理し、結果を出力します
     *
     * @param string $name 処理するテンプレート
     *
     * @return string 出力結果
     */
    public function render($name)
    {
        // プレイスホルダヘルパーが保持するコンテンツ情報を取得
        $holder = new Zend_View_Helper_Placeholder();
        $data = $holder->placeholder('Zend_Layout')->getArrayCopy();

        // コンテンツ情報をビュー変数contentに割り当て
        if (isset($data['content'])) {

            $this->_smarty->assign('content', $data['content']);
        }

        return $this->_smarty->fetch($name);
    }
}
