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

class CP_CustomizeStyleValidator extends CP_PrestaCraftValidatorCore
{
    public function __construct($moduleObject, $formName)
    {
        parent::__construct($moduleObject, $formName);
    }

    protected function processCP_Validation()
    {
        $this->validation = new CP_Validation($this->module);

        $this->validation->validate(
            $this->module->l('Popup color'),
            $this->getField('CUSTOMPOPUP_COLOR'),
            array(
                'ishex' => 1,
                'notempty' => 1
            )
        );

        $this->validation->validate(
            $this->module->l('Background color'),
            $this->getField('CUSTOMPOPUP_BACK_COLOR')
        );

        $this->validation->validate(
            $this->module->l('Content padding'),
            $this->getField('CUSTOMPOPUP_PADDING'),
            array(
                'isnumber' => 1,
                'notempty' => 1
            )
        );

        $this->validation->validate(
            $this->module->l('Content top padding'),
            $this->getField('CUSTOMPOPUP_TOP_PADDING'),
            array(
                'isnumber' => 1,
                'notempty' => 1
            )
        );
    }

    protected function save()
    {
        if (!$this->validation->getError($this->module->l('Popup color'))) {
            Configuration::updateValue('CUSTOMPOPUP_COLOR', $this->getField('CUSTOMPOPUP_COLOR'));
        }

        if (!$this->validation->getError($this->module->l('Background color'))) {
            Configuration::updateValue('CUSTOMPOPUP_BACK_COLOR', $this->getField('CUSTOMPOPUP_BACK_COLOR'));
        }

        if (!$this->validation->getError($this->module->l('Content padding'))) {
            Configuration::updateValue('CUSTOMPOPUP_PADDING', $this->getField('CUSTOMPOPUP_PADDING'));
        }

        if (!$this->validation->getError($this->module->l('Content top padding'))) {
            Configuration::updateValue('CUSTOMPOPUP_TOP_PADDING', $this->getField('CUSTOMPOPUP_TOP_PADDING'));
        }
    }
}
