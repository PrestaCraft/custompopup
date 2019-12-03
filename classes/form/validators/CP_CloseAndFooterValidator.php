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

require_once _PS_MODULE_DIR_.'custompopup/core/CP_PrestaCraftValidatorCore.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/utils/CP_Validation.php';

class CP_CloseAndFooterValidator extends CP_PrestaCraftValidatorCore
{
    public function __construct($moduleObject, $formName)
    {
        parent::__construct($moduleObject, $formName);
    }

    protected function processCP_Validation()
    {
        $this->validation = new CP_Validation($this->module);

        foreach (Language::getLanguages(true) as $lang) {
            $this->validation->validate(
                $this->module->l("[Button 1] URL ({$lang["iso_code"]})"),
                $this->getField('CUSTOMPOPUP_BUTTON1_URL_'.$lang["id_lang"]),
                array('is_url_if_not_empty' => 1)
            );

            $this->validation->validate(
                $this->module->l("[Button 2] URL ({$lang["iso_code"]})"),
                $this->getField('CUSTOMPOPUP_BUTTON2_URL_'.$lang["id_lang"]),
                array('is_url_if_not_empty' => 1)
            );
        }

        $this->validation->validate(
            $this->module->l('[Button 1] Background color'),
            $this->getField('CUSTOMPOPUP_BUTTON1_BACKGROUND'),
            array('ishex' => 1)
        );

        $this->validation->validate(
            $this->module->l('[Button 2] Background color'),
            $this->getField('CUSTOMPOPUP_BUTTON2_BACKGROUND'),
            array('ishex' => 1)
        );

        $this->validation->validate(
            $this->module->l('Footer background color'),
            $this->getField('CUSTOMPOPUP_FOOTER_BACKGROUND'),
            array('ishex' => 1)
        );
    }

    protected function save()
    {
        if (!$this->validation->getError($this->module->l('[Button 1] Background color'))) {
            Configuration::updateValue('CUSTOMPOPUP_BUTTON1_BACKGROUND', $this->getField('CUSTOMPOPUP_BUTTON1_BACKGROUND'));
        }

        if (!$this->validation->getError($this->module->l('[Button 2] Background color'))) {
            Configuration::updateValue('CUSTOMPOPUP_BUTTON2_BACKGROUND', $this->getField('CUSTOMPOPUP_BUTTON2_BACKGROUND'));
        }

        if (!$this->validation->getError($this->module->l('Footer background color'))) {
            Configuration::updateValue('CUSTOMPOPUP_FOOTER_BACKGROUND', $this->getField('CUSTOMPOPUP_FOOTER_BACKGROUND'));
        }

        Configuration::updateValue('CUSTOMPOPUP_FOOTER', $this->getField('CUSTOMPOPUP_FOOTER'));
        Configuration::updateValue('CUSTOMPOPUP_FOOTER_ALIGN', $this->getField('CUSTOMPOPUP_FOOTER_ALIGN'));
        Configuration::updateValue('CUSTOMPOPUP_FOOTER_TYPE', $this->getField('CUSTOMPOPUP_FOOTER_TYPE'));
        Configuration::updateValue('CUSTOMPOPUP_BUTTON1_ENABLED', $this->getField('CUSTOMPOPUP_BUTTON1_ENABLED'));
        Configuration::updateValue('CUSTOMPOPUP_BUTTON2_ENABLED', $this->getField('CUSTOMPOPUP_BUTTON2_ENABLED'));
        Configuration::updateValue('CUSTOMPOPUP_BUTTON1_ACT_CLOSE', $this->getField('CUSTOMPOPUP_BUTTON1_ACT_CLOSE'));
        Configuration::updateValue('CUSTOMPOPUP_BUTTON2_ACT_CLOSE', $this->getField('CUSTOMPOPUP_BUTTON2_ACT_CLOSE'));
        Configuration::updateValue('CUSTOMPOPUP_BUTTON1_NEW_TAB', $this->getField('CUSTOMPOPUP_BUTTON1_NEW_TAB'));
        Configuration::updateValue('CUSTOMPOPUP_BUTTON2_NEW_TAB', $this->getField('CUSTOMPOPUP_BUTTON2_NEW_TAB'));

        Configuration::updateValue('CUSTOMPOPUP_OVERLAY', 0);

        foreach ($this->getData() as $key =>$value) {
            if (strpos($key, 'closetype_') === 0) {
                $name = 'CUSTOMPOPUP_';
                $nameFull = $name.str_replace("closetype_", "", $key);

                if ($value == 1) {
                    Configuration::updateValue($nameFull, 1);
                }
            }
        }

        foreach (Language::getLanguages(true) as $lang) {
            if (!$this->validation->getError($this->module->l('[Button 1] URL ('.$lang["iso_code"].')'))) {
                Configuration::updateValue(
                    'CUSTOMPOPUP_BUTTON1_URL_'.$lang["id_lang"],
                    $this->getField('CUSTOMPOPUP_BUTTON1_URL_'.$lang["id_lang"])
                );

                Configuration::updateValue("CUSTOMPOPUP_BUTTON1_URL", array(
                        $lang['id_lang'] => $this->getField('CUSTOMPOPUP_BUTTON1_URL_'.$lang['id_lang']),
                        $lang['id_lang'] => $this->getField('CUSTOMPOPUP_BUTTON1_URL_'.$lang['id_lang'])
                    )
                );
            }

            if (!$this->validation->getError($this->module->l('[Button 2] URL ('.$lang["iso_code"].')'))) {
                Configuration::updateValue("CUSTOMPOPUP_BUTTON2_URL", array(
                        $lang['id_lang'] => $this->getField('CUSTOMPOPUP_BUTTON2_URL_'.$lang['id_lang']),
                        $lang['id_lang'] => $this->getField('CUSTOMPOPUP_BUTTON2_URL_'.$lang['id_lang'])
                    )
                );
            }

            Configuration::updateValue("CUSTOMPOPUP_BUTTON1_TEXT", array(
                    $lang['id_lang'] => $this->getField('CUSTOMPOPUP_BUTTON1_TEXT_'.$lang['id_lang']),
                    $lang['id_lang'] => $this->getField('CUSTOMPOPUP_BUTTON1_TEXT_'.$lang['id_lang'])
                )
            );

            Configuration::updateValue("CUSTOMPOPUP_BUTTON2_TEXT", array(
                    $lang['id_lang'] => $this->getField('CUSTOMPOPUP_BUTTON2_TEXT_'.$lang['id_lang']),
                    $lang['id_lang'] => $this->getField('CUSTOMPOPUP_BUTTON2_TEXT_'.$lang['id_lang'])
                )
            );

            Configuration::updateValue("CUSTOMPOPUP_FOOTER_TEXT", array(
                    $lang['id_lang'] => $this->getField('CUSTOMPOPUP_FOOTER_TEXT_'.$lang['id_lang']),
                    $lang['id_lang'] => $this->getField('CUSTOMPOPUP_FOOTER_TEXT_'.$lang['id_lang'])
                ), true
            );
        }

    }
}
