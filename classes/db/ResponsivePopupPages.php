<?php
/**
 * This software is provided "as is" without warranty of any kind.
 *
 * Made by PrestaCraft
 *
 * Visit my website (http://prestacraft.com) for future updates, new articles and other awesome modules.
 *
 * @author     PrestaCraft
 * @copyright  PrestaCraft
 */

require_once(_PS_MODULE_DIR_.'custompopup/classes/utils/PrestaCraftTools.php');

class ResponsivePopupPages extends ObjectModel
{
    public $id;
    public $id_page;
    public $id_shop;
    public $enabled;

    public static $definition = array (
        'table' => 'responsive_popup_pages',
        'primary' => 'id',
        'fields' => array (
            'id_page' => array ('type' => self::TYPE_STRING),
            'id_shop' => array ('type' => self::TYPE_INT),
            'enabled' => array ('type' => self::TYPE_BOOL),
        ),
    );

    public static function createTable()
    {
        try {
            if (!Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'responsive_popup_pages` (
                `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
                `id_page` VARCHAR(250),
                `id_shop` INT(5),
                `enabled` TINYINT,
                PRIMARY KEY (`id`)
            ) AUTO_INCREMENT = 1 ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8')) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            throw new PrestaShopException($e->getMessage());
        }
    }

    /**
     * @param bool $frontOfficeOnly
     * @return bool
     * @throws PrestaShopException
     */
    public static function fixtures()
    {
        $shops = Shop::getShops(true, null, true);

        try {
            foreach ($shops as $shopid) {
                foreach (PrestaCraftTools::getHooks(true, false, true) as $hook) {
                    $rpp = new ResponsivePopupPages();
                    $rpp->id_page = $hook;
                    $rpp->id_shop = $shopid;
                    $rpp->enabled = 0;
                    $rpp->save();
                }
            }
        } catch (\Exception $e) {
            throw new PrestaShopException($e->getMessage());
        }

        return true;
    }


    public function getAll()
    {
        return Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'responsive_popup_pages');
    }


    public static function checkEnable($field)
    {
        $result = Db::getInstance()->getValue('
		SELECT enabled
		FROM `'._DB_PREFIX_.'responsive_popup_pages`
		WHERE id_page="'.$field.'"');

        return @$result;
    }
}