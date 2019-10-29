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

require_once _PS_MODULE_DIR_.'custompopup/core/CP_PrestaCraftFormCore.php';

class CP_CloseAndFooterForm extends CP_PrestaCraftFormCore
{
    public function __construct($moduleObject)
    {
        parent::__construct($moduleObject, __CLASS__);
    }

    public function render()
    {
        $footerType = array(
            array(
                'id' => 'text',
                'name' => $this->module->l('Your text')
            ),
            array(
                'id' => 'button',
                'name' => $this->module->l('Button(s)')
            ),
            array(
                'id' => 'text_buttons',
                'name' => $this->module->l('Your text and button(s)')
            ),
        );

        $types = array(
            array(
                'id' => 'OVERLAY',
                'name' => $this->module->l('Overlay (clicking outside popup)')
            ),
        );

        $align = array(
            array(
                'id' => 'left',
                'name' => $this->module->l('Left')
            ),
            array(
                'id' => 'center',
                'name' => $this->module->l('Center')
            ),
            array(
                'id' => 'right',
                'name' => $this->module->l('Right')
            ),
        );

        $this->fields = array(
            'form' => array(
                'legend' => array('title' => $this->module->l('Close & Footer')),
                'submit' => array(
                    'title' => $this->module->l('Save')
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'TAB_3',
                        'value' => '1',
                    ),
                    array(
                        'type'    => 'checkbox',
                        'name'    => 'closetype',
                        'label'   => $this->module->l('Additional close method'),
                        'values'  => array(
                            'query' => $types,
                            'id'    => 'id',
                            'name'  => 'name'
                        ),
                        'desc'    => $this->module->l(
                            'By default user can close popup by clicking [X] button only or footer buttons (if enabled). 
                            You may allow user to close popup by clicking outside popup.'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('Display popup footer?'),
                        'name' => 'CUSTOMPOPUP_FOOTER',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->module->l('Footer background color'),
                        'name' => 'CUSTOMPOPUP_FOOTER_BACKGROUND',
                        'class' => 'leftfix',
                    ),
                    array(
                        'type'    => 'select',
                        'name'    => 'CUSTOMPOPUP_FOOTER_TYPE',
                        'label'   => $this->module->l('Footer type'),
                        'options'  => array(
                            'query' => $footerType,
                            'id'    => 'id',
                            'name'  => 'name'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->module->l('Your text'),
                        'name' => 'CUSTOMPOPUP_FOOTER_TEXT',
                        'lang' => true,
                        'desc' => $this->module->l("If you want to display a text instead buttons, type it here."),
                        'class' => 'rte',
                        'autoload_rte' => true,
                        'type' => 'textarea',
                    ),
                    array(
                        'type'    => 'select',
                        'name'    => 'CUSTOMPOPUP_FOOTER_ALIGN',
                        'label'   => $this->module->l('Footer alignment'),
                        'options'  => array(
                            'query' => $align,
                            'id'    => 'id',
                            'name'  => 'name'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('[Button 1] Enable'),
                        'name' => 'CUSTOMPOPUP_BUTTON1_ENABLED',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->module->l('[Button 1] Text'),
                        'name' => 'CUSTOMPOPUP_BUTTON1_TEXT',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->module->l('[Button 1] Background color'),
                        'name' => 'CUSTOMPOPUP_BUTTON1_BACKGROUND',
                        'class' => 'leftfix',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->module->l('[Button 1] URL'),
                        'name' => 'CUSTOMPOPUP_BUTTON1_URL',
                        'hint' => $this->module->l("Must begin with http:// or https://"),
                        'lang' => true,
                        'desc' => $this->module->l(
                            "If you enter here a link, it will redirect to this page on click. 
                            If not, this button will close popup on click."
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('[Button 1] Act like close button'),
                        'desc' => $this->module->l('If you set cookie time in first tab, this option will not show this popup again after clicking this button'),
                        'name' => 'CUSTOMPOPUP_BUTTON1_ACT_CLOSE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('[Button 1] Open in new tab?'),
                        'name' => 'CUSTOMPOPUP_BUTTON1_NEW_TAB',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->module->l('No')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('[Button 2] Enable'),
                        'name' => 'CUSTOMPOPUP_BUTTON2_ENABLED',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->module->l('[Button 2] Text'),
                        'name' => 'CUSTOMPOPUP_BUTTON2_TEXT',
                        'lang' => true,
                    ),

                    array(
                        'type' => 'color',
                        'label' => $this->module->l('[Button 2] Background color'),
                        'name' => 'CUSTOMPOPUP_BUTTON2_BACKGROUND',
                        'class' => 'leftfix',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->module->l('[Button 2] URL'),
                        'name' => 'CUSTOMPOPUP_BUTTON2_URL',
                        'hint' => $this->module->l("Must begin with http:// or https://"),
                        'lang' => true,
                        'desc' => $this->module->l(
                            "If you enter here a link, it will redirect to this page on click. 
                            If not, this button will close popup on click."
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('[Button 2] Act like close button'),
                        'desc' => $this->module->l('If you set cookie time in first tab, this option will not show this popup again after clicking this button'),
                        'name' => 'CUSTOMPOPUP_BUTTON2_ACT_CLOSE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('[Button 2] Open in new tab?'),
                        'name' => 'CUSTOMPOPUP_BUTTON2_NEW_TAB',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->module->l('No')
                            )
                        ),
                    ),
                ),
            ),
        );

        return $this;
    }

    public function getFieldsValues()
    {
        $fields = array();

        $fields['TAB_3'] = Configuration::get('TAB_3');
        $fields['closetype_OVERLAY'] = Configuration::get('CUSTOMPOPUP_OVERLAY');
        $fields['CUSTOMPOPUP_FOOTER'] = Configuration::get('CUSTOMPOPUP_FOOTER');
        $fields['CUSTOMPOPUP_BUTTON1_BACKGROUND'] = Configuration::get('CUSTOMPOPUP_BUTTON1_BACKGROUND');
        $fields['CUSTOMPOPUP_BUTTON2_BACKGROUND'] = Configuration::get('CUSTOMPOPUP_BUTTON2_BACKGROUND');
        $fields['CUSTOMPOPUP_FOOTER_ALIGN'] = Configuration::get('CUSTOMPOPUP_FOOTER_ALIGN');
        $fields['CUSTOMPOPUP_FOOTER_TYPE'] = Configuration::get('CUSTOMPOPUP_FOOTER_TYPE');
        $fields['CUSTOMPOPUP_BUTTON1_ENABLED'] = Configuration::get('CUSTOMPOPUP_BUTTON1_ENABLED');
        $fields['CUSTOMPOPUP_BUTTON2_ENABLED'] = Configuration::get('CUSTOMPOPUP_BUTTON2_ENABLED');
        $fields['CUSTOMPOPUP_BUTTON1_NEW_TAB'] = Configuration::get('CUSTOMPOPUP_BUTTON1_NEW_TAB');
        $fields['CUSTOMPOPUP_BUTTON2_NEW_TAB'] = Configuration::get('CUSTOMPOPUP_BUTTON2_NEW_TAB');
        $fields['CUSTOMPOPUP_BUTTON1_ACT_CLOSE'] = Configuration::get('CUSTOMPOPUP_BUTTON1_ACT_CLOSE');
        $fields['CUSTOMPOPUP_BUTTON2_ACT_CLOSE'] = Configuration::get('CUSTOMPOPUP_BUTTON2_ACT_CLOSE');
        $fields['CUSTOMPOPUP_FOOTER_BACKGROUND'] = Configuration::get('CUSTOMPOPUP_FOOTER_BACKGROUND');

        foreach (Language::getLanguages(true) as $lang) {
            $fields['CUSTOMPOPUP_BUTTON1_URL'][$lang["id_lang"]] = Configuration::get(
                'CUSTOMPOPUP_BUTTON1_URL', $lang["id_lang"]
            );

            $fields['CUSTOMPOPUP_BUTTON2_URL'][$lang["id_lang"]] = Configuration::get(
                'CUSTOMPOPUP_BUTTON2_URL', $lang["id_lang"]
            );

            $fields['CUSTOMPOPUP_FOOTER_TEXT'][$lang["id_lang"]] = Configuration::get(
                'CUSTOMPOPUP_FOOTER_TEXT', $lang["id_lang"]
            );

            $fields['CUSTOMPOPUP_BUTTON1_TEXT'][$lang["id_lang"]] = Configuration::get(
                'CUSTOMPOPUP_BUTTON1_TEXT', $lang["id_lang"]
            );

            $fields['CUSTOMPOPUP_BUTTON2_TEXT'][$lang["id_lang"]] = Configuration::get(
                'CUSTOMPOPUP_BUTTON2_TEXT', $lang["id_lang"]
            );
        }

        return $fields;
    }
}
