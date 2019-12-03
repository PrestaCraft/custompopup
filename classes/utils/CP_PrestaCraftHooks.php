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
 * @license    http://prestacraft.com/license
 */

require_once(_PS_MODULE_DIR_.'custompopup/classes/db/CP_ResponsivePopupPages.php');

class CP_PrestaCraftHooks
{
    /**
     * Get hooks using PrestaShop getHooks() method, but with extra filter - in case you want only FrontOffice hooks
     *
     * @param bool $frontOfficeOnly
     * @param bool $position
     * @param bool $hideActions
     *
     * @return array
     */
    public static function getHooks($frontOfficeOnly = false, $position = false, $hideActions = false)
    {
        $hooks = Hook::getHooks($position);
        $hooksReturn = array();

        if ($frontOfficeOnly) {
            foreach ($hooks as $hook) {
                if (!strpos($hook["name"], "Admin") && !strpos($hook["name"], "BackOffice")) {
                    $hooksReturn[] = $hook["name"];
                }
            }
        } else {
            foreach ($hooks as $hook) {
                $hooksReturn[] = $hook["name"];
            }
        }

        if ($hideActions) {
            foreach ($hooksReturn as $k => $v) {
                if (Tools::substr($v, 0, 6) == "action") {
                    unset($hooksReturn[$k]);
                }
            }
        }

        return $hooksReturn;
    }

    public static function ifRequireHookUpdate()
    {
        if (count(CP_PrestaCraftHooks::getMissingHooks()) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getMissingHooks()
    {
        $psHooks = CP_PrestaCraftHooks::getHooks(true, false, true);
        $rpp = new CP_ResponsivePopupPages();
        $moduleHookList = $rpp->getAll();

        $psHooksArray = array();
        $moduleHookListArray = array();
        $missing = array();

        foreach ($psHooks as $hook) {
            $psHooksArray[] = $hook;
        }

        foreach ($moduleHookList as $hook) {
            $moduleHookListArray[] = $hook["id_page"];
        }

        foreach ($psHooksArray as $hook) {
            if (!in_array($hook, $moduleHookListArray)) {
                $missing[] = $hook;
            }
        }

        return $missing;
    }

    /**
     * Add missing hooks
     */
    public static function updateHooks()
    {
        $shops = Shop::getShops(true, null, true);

        foreach ($shops as $shopid) {
            foreach (CP_PrestaCraftHooks::getMissingHooks() as $hook) {
                $rpp = new CP_ResponsivePopupPages();
                $rpp->id_page = $hook;
                $rpp->id_shop = $shopid;
                $rpp->enabled = 0;
                $rpp->save();
            }
        }
    }
}
