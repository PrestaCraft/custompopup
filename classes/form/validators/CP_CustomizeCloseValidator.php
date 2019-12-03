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

class CP_CustomizeCloseValidator extends CP_PrestaCraftValidatorCore
{
    public function __construct($moduleObject, $formName)
    {
        parent::__construct($moduleObject, $formName);
    }

    protected function processCP_Validation()
    {
        $this->validation = new CP_Validation($this->module);

        $this->validation->validate(
            $this->module->l('Color'),
            $this->getField('CUSTOMPOPUP_BUTTON_COLOR'),
            array(
                'ishex' => 1,
                'notempty' => 1
            )
        );

        $this->validation->validate(
            $this->module->l('Hover color'),
            $this->getField('CUSTOMPOPUP_BUTTON_HOVER_COLOR'),
            array(
                'ishex' => 1,
                'notempty' => 1
            )
        );

        $this->validation->validate(
            $this->module->l('Size'),
            $this->getField('CUSTOMPOPUP_BUTTON_SIZE'),
            array(
                'isnumber' => 1,
                'notempty' => 1
            )
        );

        $this->validation->validate(
            $this->module->l('Top padding'),
            $this->getField('CUSTOMPOPUP_BUTTON_TOP_PADDING'),
            array(
                'isnumber' => 1,
                'notempty' => 1
            )
        );
    }

    protected function save()
    {
        Configuration::updateValue('CUSTOMPOPUP_BUTTON_POSITION', $this->getField('CUSTOMPOPUP_BUTTON_POSITION'));

        if (!$this->validation->getError($this->module->l('Color'))) {
            Configuration::updateValue('CUSTOMPOPUP_BUTTON_COLOR', $this->getField('CUSTOMPOPUP_BUTTON_COLOR'));
        }

        if (!$this->validation->getError($this->module->l('Hover color'))) {
            Configuration::updateValue(
                'CUSTOMPOPUP_BUTTON_HOVER_COLOR',
                $this->getField('CUSTOMPOPUP_BUTTON_HOVER_COLOR')
            );
        }

        if (!$this->validation->getError($this->module->l('Size'))) {
            Configuration::updateValue('CUSTOMPOPUP_BUTTON_SIZE', $this->getField('CUSTOMPOPUP_BUTTON_SIZE'));
        }

        if (!$this->validation->getError($this->module->l('Top padding'))) {
            Configuration::updateValue(
                'CUSTOMPOPUP_BUTTON_TOP_PADDING',
                $this->getField('CUSTOMPOPUP_BUTTON_TOP_PADDING')
            );
        }
    }
}
