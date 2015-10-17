<?php

/**
 * オートローダー
 *
 * @category
 * @package   App
 * @author
 * @copyright Alphawave inc.
 * @license
 * @link
 */
class AutoLoader
{
    /**
     * オートロード用
     *
     * @param string $classname ロードクラス名
     *
     * @return void
     */
    public static function multibrandLoader($classname)
    {
        // ModelはZendFrameworkにお任せ
        if (preg_match('/^Model(_.+)$/', $classname)) {
            return;
        }
        // SmartyはZendFrameworkにお任せ
        if (preg_match('/^smarty(_.+)$/', $classname)) {
            return;
        }
        // SmartyはZendFrameworkにお任せ
        if (preg_match('/^Smarty(_.+)$/', $classname)) {
            return;
        }

        // Dao
        if (preg_match('/^Dao(_.+)$/', $classname)) {
            $classname = str_replace('Dao', 'dao', $classname);
        }

        return include_once str_replace('_', DIRECTORY_SEPARATOR, $classname) . '.php';
    }
}
