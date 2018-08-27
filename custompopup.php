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

if (!defined('_PS_VERSION_') || !defined('_PS_MODULE_DIR_')) {
    exit;
}

// Core
require_once _PS_MODULE_DIR_.$this->name.'/core/PrestaCraftModuleInterface.php';

// Database
require_once _PS_MODULE_DIR_.$this->name.'/classes/db/ResponsivePopup.php';
require_once _PS_MODULE_DIR_.$this->name.'/classes/db/ResponsivePopupPages.php';

// Forms
require_once _PS_MODULE_DIR_.$this->name.'/classes/form/CustomizeCloseForm.php';
require_once _PS_MODULE_DIR_.$this->name.'/classes/form/CustomizeStyleForm.php';
require_once _PS_MODULE_DIR_.$this->name.'/classes/form/DisplayForm.php';
require_once _PS_MODULE_DIR_.$this->name.'/classes/form/SettingsForm.php';
// Validators
require_once _PS_MODULE_DIR_.$this->name.'/classes/form/validators/CustomizeCloseValidator.php';
require_once _PS_MODULE_DIR_.$this->name.'/classes/form/validators/CustomizeStyleValidator.php';
require_once _PS_MODULE_DIR_.$this->name.'/classes/form/validators/DisplayValidator.php';
require_once _PS_MODULE_DIR_.$this->name.'/classes/form/validators/SettingsValidator.php';

// Utils
require_once _PS_MODULE_DIR_.$this->name.'/classes/utils/PrestaCraftTools.php';
require_once _PS_MODULE_DIR_.$this->name.'/classes/utils/PrestaCraftVariables.php';

class CustomPopup extends Module implements PrestaCraftModuleInterface
{
    private $installHooks = array(
      'header'
    );
    private $errors;
    private $success = false;
    private $dynamicHooking = true;

    public function __construct()
    {
        $this->name = 'custompopup';
        $this->tab = 'front_office_features';
        $this->version = '1.2.0';
        $this->author = 'PrestaCraft';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Custom popup notification');
        $this->description = $this->l('Customize and display a responsive popup window for chosen page(s).');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    /**
     * Magic method to call hooks dynamically, based on user choice
     *
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if ($this->dynamicHooking) {
            $prevent = false;

            // Ignore hook functions registered in install() method
            foreach ($this->installHooks as $hook) {
                if ('hook'.strtolower($hook) == strtolower($method)) {
                    $prevent = true;
                }
            }

            if (!$prevent) {
                if (function_exists($method))
                    return call_user_func_array($method, $args);

                // Check for a call to an hook
                if (strpos($method, 'hook') !== false) {
                    return $this->functionHook($args[0]);
                }
            }
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        foreach (Language::getLanguages(true) as $lang) {
            $content='CUSTOMPOPUP_CONTENT_'.$lang['id_lang'];
            Configuration::updateValue($content, $lang['name']);
        }

        PrestaCraftVariables::setDefaultValues();

        foreach ($this->installHooks as $hook) {
            $this->registerHook($hook);
        }

        return parent::install() &&
            ResponsivePopup::createTable() &&
            ResponsivePopupPages::createTable() &&
            ResponsivePopupPages::fixtures();
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }

    /**
     * Module configuration page
     *
     * @return mixed
     */
    public function getContent()
    {
        $this->postProcess();
        $this->hookService();

        $data = Tools::file_get_contents(
            'http://prestacraft.com/version_checker.php?module='.$this->name.'&version='.$this->version.''
        );
        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('colorpicker_path', __PS_BASE_URI__.'js/jquery/plugins/jquery.colorpicker.js');
        $this->context->smarty->assign('CUSTOMPOPUP_COLOR', Configuration::get('CUSTOMPOPUP_COLOR'));
        $this->context->smarty->assign('CUSTOMPOPUP_BACK_COLOR', Configuration::get('CUSTOMPOPUP_BACK_COLOR'));
        $this->context->smarty->assign('CUSTOMPOPUP_BUTTON_COLOR', Configuration::get('CUSTOMPOPUP_BUTTON_COLOR'));
        $this->context->smarty->assign(
            'CUSTOMPOPUP_BUTTON_HOVER_COLOR',
            Configuration::get('CUSTOMPOPUP_BUTTON_HOVER_COLOR')
        );
        $this->context->smarty->assign('VERSION_CHECKER', $data);
        $this->context->smarty->assign('POS', trim(Tools::getValue('pos')));

        $this->context->smarty->assign('TAB_SETTINGS', $this->renderSettings());
        $this->context->smarty->assign('TAB_CUSTOMIZE_STYLE', $this->renderCustomizeStyle());
        $this->context->smarty->assign('TAB_CUSTOMIZE_CLOSE', $this->renderCustomizeClose());
        $this->context->smarty->assign('TAB_DISPLAY', $this->renderDisplay());

        if ($this->errors) {
            $this->context->smarty->assign('errors', $this->errors);
        }

        if ($this->success) {
            $this->context->smarty->assign('success', $this->l('The settings have been updated.'));
        }

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output;
    }

    /**
     * Handling form validation & sending
     *
     * @return string
     */
    public function postProcess()
    {
        $settingsData = array(
            'CUSTOMPOPUP_ENABLED' => Tools::getValue('CUSTOMPOPUP_ENABLED'),
            'CUSTOMPOPUP_COOKIE' => Tools::getValue('CUSTOMPOPUP_COOKIE'),
            'CUSTOMPOPUP_DELAY' => Tools::getValue('CUSTOMPOPUP_DELAY'),
        );

        $langContent = array();

        foreach (Language::getLanguages(true) as $la) {
            $langContent['CUSTOMPOPUP_CONTENT_'.$la['id_lang']] = Tools::getValue('CUSTOMPOPUP_CONTENT_'.$la['id_lang']);
        }

        $settingsDataAll = array_merge($settingsData, $langContent);

        $settingsValidator = new SettingsValidator($this, 'SettingsForm');
        $settingsValidator->setData($settingsDataAll);
        $settingsValidator->validate();

        if ($settingsValidator->getErrors()) {
            $this->errors = $settingsValidator->getErrors();
        } else {
            if ($settingsValidator->getSuccess()) {
                $this->success = true;
            }
        }

        $customizeStyleData = array(
            'CUSTOMPOPUP_COLOR' => Tools::getValue('CUSTOMPOPUP_COLOR'),
            'CUSTOMPOPUP_BACK_COLOR' => Tools::getValue('CUSTOMPOPUP_BACK_COLOR'),
            'CUSTOMPOPUP_PADDING' => Tools::getValue('CUSTOMPOPUP_PADDING'),
            'CUSTOMPOPUP_TOP_PADDING' => Tools::getValue('CUSTOMPOPUP_TOP_PADDING'),
        );

        $customizeStyleValidator = new CustomizeStyleValidator($this, 'CustomizeStyleForm');
        $customizeStyleValidator->setData($customizeStyleData);
        $customizeStyleValidator->validate();

        if ($customizeStyleValidator->getErrors()) {
            $this->errors = $customizeStyleValidator->getErrors();
        }

        if ($customizeStyleValidator->getSuccess()) {
            $this->success = true;
        }

        $custoimzeCloseData = array(
            'CUSTOMPOPUP_BUTTON_COLOR' => Tools::getValue('CUSTOMPOPUP_BUTTON_COLOR'),
            'CUSTOMPOPUP_BUTTON_HOVER_COLOR' => Tools::getValue('CUSTOMPOPUP_BUTTON_HOVER_COLOR'),
            'CUSTOMPOPUP_BUTTON_SIZE' => Tools::getValue('CUSTOMPOPUP_BUTTON_SIZE'),
            'CUSTOMPOPUP_BUTTON_TOP_PADDING' => Tools::getValue('CUSTOMPOPUP_BUTTON_TOP_PADDING'),
            'CUSTOMPOPUP_BUTTON_POSITION' => Tools::getValue('CUSTOMPOPUP_BUTTON_POSITION'),
        );

        $customizeCloseValidator = new CustomizeCloseValidator($this, 'CustomizeCloseForm');
        $customizeCloseValidator->setData($custoimzeCloseData);
        $customizeCloseValidator->validate();

        if ($customizeCloseValidator->getErrors()) {
            $this->errors = $customizeCloseValidator->getErrors();
        }

        if ($customizeCloseValidator->getSuccess()) {
            $this->success = true;
        }

        $displayData = array();

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'pages_') === 0) {
                if ($value) {
                    $displayData[str_replace("pages_", "", $key)] = 1;
                }
            }
        }

        $displayValidator = new DisplayValidator($this, 'DisplayForm');
        $displayValidator->setData($displayData, true);
        $displayValidator->validate();

        if ($displayValidator->getSuccess()) {
            $this->success = true;
        }

        return '';
    }

    // ---- Forms [start] ----
    public function renderSettings()
    {
        $form = new SettingsForm($this);
        return $form->render()->buildForm();
    }

    public function renderCustomizeStyle()
    {
        $form = new CustomizeStyleForm($this);
        return $form->render()->buildForm();
    }

    public function renderCustomizeClose()
    {
        $form = new CustomizeCloseForm($this);
        return $form->render()->buildForm();
    }

    public function renderDisplay()
    {
        $form = new Displayform($this);
        return $form->render()->buildForm();
    }
    // ---- Forms [end] ----

    // ---- Hooks [start] ----
    private function hookService()
    {
        $enabledHooks = array();
        $rpp = new ResponsivePopupPages();

        foreach ($rpp->getAll() as $item) {
            if ($item["enabled"] == 1) {
                $this->registerHook($item["id_page"]);
                $enabledHooks[] = $item["id_page"];
            } else {
                $this->unregisterHook($item["id_page"]);
            }
        }
    }

    public function functionHook()
    {
        $langContent = array();

        foreach (ResponsivePopup::getContentForLanguages() as $langID => $content) {
            $langContent['content_'.$langID] = trim(json_encode($content), '"');
        }

        $assign = PrestaCraftVariables::getTemplateVars();
        $all = array_merge($langContent, $assign);
        $this->context->smarty->assign($all);

        return $this->display(__FILE__, 'custompopup.tpl');
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/tingle.min.css', 'all');
        $this->context->controller->addCSS($this->_path.'views/css/popup.css', 'all');

        $this->context->smarty->assign(array(
            'prestacraft_cookie' => $this->_path.'views/js/cookie.js',
            'tingle' => $this->_path.'views/js/tingle.min.js'
        ));

        return $this->display(__FILE__, 'header.tpl');
    }
    // ---- Hooks [end] ----
}
