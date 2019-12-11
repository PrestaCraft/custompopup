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
require_once _PS_MODULE_DIR_.'/custompopup/core/CP_PrestaCraftModuleInterface.php';

// Database
require_once _PS_MODULE_DIR_.'/custompopup/classes/db/CP_ResponsivePopupPages.php';

// Forms
require_once _PS_MODULE_DIR_.'/custompopup/classes/form/CP_CloseAndFooterForm.php';
require_once _PS_MODULE_DIR_.'/custompopup/classes/form/CP_CustomizeCloseForm.php';
require_once _PS_MODULE_DIR_.'/custompopup/classes/form/CP_CustomizeStyleForm.php';
require_once _PS_MODULE_DIR_.'/custompopup/classes/form/CP_DisplayForm.php';
require_once _PS_MODULE_DIR_.'/custompopup/classes/form/CP_SettingsForm.php';
// Validators
require_once _PS_MODULE_DIR_.'/custompopup/classes/form/validators/CP_CloseAndFooterValidator.php';
require_once _PS_MODULE_DIR_.'/custompopup/classes/form/validators/CP_CustomizeCloseValidator.php';
require_once _PS_MODULE_DIR_.'/custompopup/classes/form/validators/CP_CustomizeStyleValidator.php';
require_once _PS_MODULE_DIR_.'/custompopup/classes/form/validators/CP_DisplayValidator.php';
require_once _PS_MODULE_DIR_.'/custompopup/classes/form/validators/CP_SettingsValidator.php';

// Utils
require_once _PS_MODULE_DIR_.'/custompopup/classes/utils/CP_PrestaCraftHooks.php';
require_once _PS_MODULE_DIR_.'/custompopup/classes/utils/CP_PrestaCraftVariables.php';

class CustomPopup extends Module implements CP_PrestaCraftModuleInterface
{
    private $errors;
    private $success = false;
    private $dynamicHooking = true;

    public function __construct()
    {
        $this->name = 'custompopup';
        $this->tab = 'front_office_features';
        $this->version = '2.3.0';
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

        CP_PrestaCraftVariables::setDefaultValues();

        return parent::install() &&
            CP_ResponsivePopupPages::createTable() &&
            CP_ResponsivePopupPages::fixtures();
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        CP_ResponsivePopupPages::drop();
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
        $this->context->smarty->assign('IF_REQUIRE_HOOK_UPDATE', CP_PrestaCraftHooks::ifRequireHookUpdate());

        // Tabs
        $this->context->smarty->assign('TAB_SETTINGS', $this->renderSettings());
        $this->context->smarty->assign('TAB_CUSTOMIZE_STYLE', $this->renderCustomizeStyle());
        $this->context->smarty->assign('TAB_CUSTOMIZE_CLOSE', $this->renderCustomizeClose());
        $this->context->smarty->assign('TAB_CLOSE_AND_FOOTER', $this->renderCloseAndFooter());
        $this->context->smarty->assign('TAB_DISPLAY', $this->renderDisplay());

        if (Configuration::get("PS_MULTISHOP_FEATURE_ACTIVE")) {
            $this->context->smarty->assign('multistore', true);
        }

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
        if (Tools::isSubmit('submitHookUpdate')) {
            CP_PrestaCraftHooks::updateHooks();
        } else {
            $settingsData = array(
                'CUSTOMPOPUP_ENABLED' => Tools::getValue('CUSTOMPOPUP_ENABLED'),
                'CUSTOMPOPUP_COOKIE' => Tools::getValue('CUSTOMPOPUP_COOKIE'),
                'CUSTOMPOPUP_DELAY' => Tools::getValue('CUSTOMPOPUP_DELAY'),
            );

            $langContent = array();

            foreach (Language::getLanguages(true) as $la) {
                $langContent['CUSTOMPOPUP_CONTENT_'.$la['id_lang']] = Tools::getValue(
                    'CUSTOMPOPUP_CONTENT_'.$la['id_lang']
                );
            }

            $settingsDataAll = array_merge($settingsData, $langContent);

            $CP_SettingsValidator = new CP_SettingsValidator($this, 'CP_SettingsForm');
            $CP_SettingsValidator->setData($settingsDataAll);
            $CP_SettingsValidator->validate();

            if ($CP_SettingsValidator->getErrors()) {
                $this->errors = $CP_SettingsValidator->getErrors();
            }

            $customizeStyleData = array(
                'CUSTOMPOPUP_COLOR' => Tools::getValue('CUSTOMPOPUP_COLOR'),
                'CUSTOMPOPUP_BACK_COLOR' => Tools::getValue('CUSTOMPOPUP_BACK_COLOR'),
                'CUSTOMPOPUP_PADDING' => Tools::getValue('CUSTOMPOPUP_PADDING'),
                'CUSTOMPOPUP_TOP_PADDING' => Tools::getValue('CUSTOMPOPUP_TOP_PADDING'),
            );

            $CP_CustomizeStyleValidator = new CP_CustomizeStyleValidator($this, 'CP_CustomizeStyleForm');
            $CP_CustomizeStyleValidator->setData($customizeStyleData);
            $CP_CustomizeStyleValidator->validate();

            if ($CP_CustomizeStyleValidator->getErrors()) {
                $this->errors = $CP_CustomizeStyleValidator->getErrors();
            }

            $custoimzeCloseData = array(
                'CUSTOMPOPUP_BUTTON_COLOR' => Tools::getValue('CUSTOMPOPUP_BUTTON_COLOR'),
                'CUSTOMPOPUP_BUTTON_HOVER_COLOR' => Tools::getValue('CUSTOMPOPUP_BUTTON_HOVER_COLOR'),
                'CUSTOMPOPUP_BUTTON_SIZE' => Tools::getValue('CUSTOMPOPUP_BUTTON_SIZE'),
                'CUSTOMPOPUP_BUTTON_TOP_PADDING' => Tools::getValue('CUSTOMPOPUP_BUTTON_TOP_PADDING'),
                'CUSTOMPOPUP_BUTTON_POSITION' => Tools::getValue('CUSTOMPOPUP_BUTTON_POSITION'),
            );

            $CP_CustomizeCloseValidator = new CP_CustomizeCloseValidator($this, 'CP_CustomizeCloseForm');
            $CP_CustomizeCloseValidator->setData($custoimzeCloseData);
            $CP_CustomizeCloseValidator->validate();

            if ($CP_CustomizeCloseValidator->getErrors()) {
                $this->errors = $CP_CustomizeCloseValidator->getErrors();
            }

            $displayData = array();

            foreach ($_POST as $key => $value) {
                if (strpos($key, 'pages_') === 0) {
                    if ($value) {
                        $displayData[str_replace("pages_", "", $key)] = 1;
                    }
                }
            }

            $CP_DisplayValidator = new CP_DisplayValidator($this, 'CP_DisplayForm');
            $CP_DisplayValidator->setData($displayData, true);
            $CP_DisplayValidator->validate();

            $closeAndFooterDataCloseType = array();

            foreach ($_POST as $key => $value) {
                if (strpos($key, 'closetype_') === 0) {
                    if ($value) {
                        $closeAndFooterDataCloseType[$key] = 1;
                    }
                }
            }

            $closeAndFooterLangData = array();

            foreach (Language::getLanguages(true) as $la) {
                $closeAndFooterLangData['CUSTOMPOPUP_BUTTON1_TEXT_'.$la['id_lang']] =
                    Tools::getValue('CUSTOMPOPUP_BUTTON1_TEXT_'.$la['id_lang']);

                $closeAndFooterLangData['CUSTOMPOPUP_BUTTON2_TEXT_'.$la['id_lang']] =
                    Tools::getValue('CUSTOMPOPUP_BUTTON2_TEXT_'.$la['id_lang']);

                $closeAndFooterLangData['CUSTOMPOPUP_FOOTER_TEXT_'.$la['id_lang']] =
                    Tools::getValue('CUSTOMPOPUP_FOOTER_TEXT_'.$la['id_lang']);

                $closeAndFooterLangData['CUSTOMPOPUP_BUTTON1_URL_'.$la['id_lang']] =
                    Tools::getValue('CUSTOMPOPUP_BUTTON1_URL_'.$la['id_lang']);

                $closeAndFooterLangData['CUSTOMPOPUP_BUTTON2_URL_'.$la['id_lang']] =
                    Tools::getValue('CUSTOMPOPUP_BUTTON2_URL_'.$la['id_lang']);
            }

            $closeAndFooterData = array(
                'CUSTOMPOPUP_FOOTER' => Tools::getValue('CUSTOMPOPUP_FOOTER'),
                'CUSTOMPOPUP_BUTTON1_TEXT' => Tools::getValue('CUSTOMPOPUP_BUTTON1_TEXT'),
                'CUSTOMPOPUP_BUTTON2_TEXT' => Tools::getValue('CUSTOMPOPUP_BUTTON2_TEXT'),
                'CUSTOMPOPUP_BUTTON1_URL' => Tools::getValue('CUSTOMPOPUP_BUTTON1_URL'),
                'CUSTOMPOPUP_BUTTON2_URL' => Tools::getValue('CUSTOMPOPUP_BUTTON2_URL'),
                'CUSTOMPOPUP_BUTTON1_BACKGROUND' => Tools::getValue('CUSTOMPOPUP_BUTTON1_BACKGROUND'),
                'CUSTOMPOPUP_BUTTON2_BACKGROUND' => Tools::getValue('CUSTOMPOPUP_BUTTON2_BACKGROUND'),
                'CUSTOMPOPUP_FOOTER_TEXT' => Tools::getValue('CUSTOMPOPUP_FOOTER_TEXT'),
                'CUSTOMPOPUP_FOOTER_ALIGN' => Tools::getValue('CUSTOMPOPUP_FOOTER_ALIGN'),
                'CUSTOMPOPUP_FOOTER_TYPE' => Tools::getValue('CUSTOMPOPUP_FOOTER_TYPE'),
                'CUSTOMPOPUP_BUTTON1_ENABLED' => Tools::getValue('CUSTOMPOPUP_BUTTON1_ENABLED'),
                'CUSTOMPOPUP_BUTTON2_ENABLED' => Tools::getValue('CUSTOMPOPUP_BUTTON2_ENABLED'),
                'CUSTOMPOPUP_BUTTON1_ACT_CLOSE' => Tools::getValue('CUSTOMPOPUP_BUTTON1_ACT_CLOSE'),
                'CUSTOMPOPUP_BUTTON2_ACT_CLOSE' => Tools::getValue('CUSTOMPOPUP_BUTTON2_ACT_CLOSE'),
                'CUSTOMPOPUP_BUTTON1_NEW_TAB' => Tools::getValue('CUSTOMPOPUP_BUTTON1_NEW_TAB'),
                'CUSTOMPOPUP_BUTTON2_NEW_TAB' => Tools::getValue('CUSTOMPOPUP_BUTTON2_NEW_TAB'),
                'CUSTOMPOPUP_FOOTER_BACKGROUND' => Tools::getValue('CUSTOMPOPUP_FOOTER_BACKGROUND'),
            );

            $closeAndFooterDataAll = array_merge(
                $closeAndFooterDataCloseType,
                $closeAndFooterData,
                $closeAndFooterLangData
            );

            $CP_CloseAndFooterValidator = new CP_CloseAndFooterValidator($this, 'CP_CloseAndFooterForm');
            $CP_CloseAndFooterValidator->setData($closeAndFooterDataAll);
            $CP_CloseAndFooterValidator->validate();

            if ($CP_CloseAndFooterValidator->getErrors()) {
                $this->errors = $CP_CloseAndFooterValidator->getErrors();
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (!$this->errors) {
                    $this->success = true;
                }
            }
        }

        return '';
    }

    // ---- Forms [start] ----
    public function renderSettings()
    {
        $form = new CP_SettingsForm($this);
        return $form->render()->buildForm();
    }

    public function renderCustomizeStyle()
    {
        $form = new CP_CustomizeStyleForm($this);
        return $form->render()->buildForm();
    }

    public function renderCustomizeClose()
    {
        $form = new CP_CustomizeCloseForm($this);
        return $form->render()->buildForm();
    }

    public function renderCloseAndFooter()
    {
        $form = new CP_CloseAndFooterForm($this);
        return $form->render()->buildForm();
    }

    public function renderDisplay()
    {
        $form = new CP_DisplayForm($this);
        return $form->render()->buildForm();
    }
    // ---- Forms [end] ----

    // ---- Hooks [start] ----
    private function hookService()
    {
        $enabledHooks = array();
        $rpp = new CP_ResponsivePopupPages();

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

        foreach (Language::getLanguages(true) as $lang) {
            $content = Configuration::get("CUSTOMPOPUP_CONTENT", $lang["id_lang"]);
            $langContent['pc_content_'.$lang["id_lang"]] = trim(json_encode($content), '"');
        }

        $scripts = array(
            'pc_css_tingle'  => $this->_path.'views/css/tingle.min.css',
            'pc_css_popup'  => $this->_path.'views/css/popup.css',
            'pc_js_cookie' => $this->_path.'views/js/cookie.js',
            'pc_js_tingle' => $this->_path.'views/js/tingle.min.js'
        );

        $assign = CP_PrestaCraftVariables::getTemplateVars();

        $all = array_merge($langContent, $scripts, $assign);
        $this->context->smarty->assign($all);

        if (!Context::getContext()->employee->id) {
            return $this->display(__FILE__, 'custompopup.tpl');
        }
    }

    // ---- Hooks [end] ----
}
