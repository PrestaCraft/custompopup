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

class CP_SettingsValidator extends CP_PrestaCraftValidatorCore
{
    public function __construct($moduleObject, $formName)
    {
        parent::__construct($moduleObject, $formName);
    }

    protected function processCP_Validation()
    {
        $this->validation = new CP_Validation($this->module);

        $this->validation->validate(
            $this->module->l('Cookie length'),
            $this->getField('CUSTOMPOPUP_COOKIE'),
            array('isnumber' => 1)
        );

        $this->validation->validate(
            $this->module->l('Popup delay'),
            $this->getField('CUSTOMPOPUP_DELAY'),
            array('isnumber' => 1)
        );

        foreach (Language::getLanguages(true) as $lang) {
            $this->validation->validate(
                $this->module->l('Popup content'),
                $this->getField('CUSTOMPOPUP_CONTENT_'.$lang['id_lang']),
                array('notempty' => 1)
            );
        }
    }

    protected function save()
    {
        Configuration::updateValue('CUSTOMPOPUP_ENABLED', $this->getField('CUSTOMPOPUP_ENABLED'));

        // if no errors occured
        if (!$this->validation->getError($this->module->l('Popup content'))) {
            foreach (Language::getLanguages(true) as $lang) {
                Configuration::updateValue("CUSTOMPOPUP_CONTENT", array(
                        $lang['id_lang'] => $this->getField('CUSTOMPOPUP_CONTENT_'.$lang['id_lang']),
                        $lang['id_lang'] => $this->getField('CUSTOMPOPUP_CONTENT_'.$lang['id_lang'])
                    ), true
                );
            }
        }

        if (!$this->validation->getError($this->module->l('Cookie length'))) {
            Configuration::updateValue('CUSTOMPOPUP_COOKIE', $this->getField('CUSTOMPOPUP_COOKIE'));
        }

        if (!$this->validation->getError($this->module->l('Popup delay'))) {
            Configuration::updateValue('CUSTOMPOPUP_DELAY', $this->getField('CUSTOMPOPUP_DELAY'));
        }
    }
}
