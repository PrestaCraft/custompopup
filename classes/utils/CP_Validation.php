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

class CP_Validation
{
    protected $module;
    protected $errors;
    
    public function __construct($moduleObject)
    {
        $this->module = $moduleObject;
    }

    public function validate($name, $field, $rules = array())
    {
        foreach ($rules as $key => $value) {
            switch ($key) {
                case 'notempty':
                    if (!$field || trim(Tools::strlen($field)) < 1) {
                        $this->setError(
                            $name,
                            sprintf(
                                $this->module->l("%s - can not be empty."),
                                $name
                            )
                        );
                    }
                    break;

                case 'maxlength':
                    if (Tools::strlen($field) > $value) {
                        $this->setError(
                            $name,
                            sprintf(
                                $this->module->l("%s - value '%s' is too long. Maximum is %s characters."),
                                $name,
                                $field,
                                $value
                            )
                        );
                    }
                    break;

                case 'minlength':
                    if (Tools::strlen($field) < $value) {
                        $this->setError(
                            $name,
                            sprintf(
                                $this->module->l("%s - value '%s' is too short. Minimum is %s characters."),
                                $name,
                                $field,
                                $value
                            )
                        );
                    }
                    break;

                case 'isnumber':
                    if (!is_numeric($field)) {
                        $this->setError($name, sprintf($this->module->l("%s - value '%s' is not a number."), $name, $field));
                    }
                    break;

                case 'ishex':
                    if ((Tools::strlen($field) !=4 || Tools::strlen($field) != 7)
                        && Tools::substr($field, 0, 1)!="#") {
                        $this->setError(
                            $name,
                            sprintf(
                                $this->module->l("%s - value '%s' is not valid HEX color."),
                                $name,
                                $field
                            )
                        );
                    }
                    break;

                case 'is_url_if_not_empty':
                    if (Tools::strlen(trim($field)) > 0) {
                        if (Tools::substr( $field, 0, 7 ) != "http://" && Tools::substr( $field, 0, 8 ) != "https://") {
                            $this->setError(
                                $name,
                                sprintf(
                                    $this->module->l(
                                        "%s - Entered URL '%s' is not valid. It must begin with http:// or https://"
                                    ),
                                    $name,
                                    $field
                                )
                            );
                        }
                    }
                    break;
            }
        }
    }

    private function setError($name, $msg)
    {
        $this->errors[$name][] = $msg;
    }

    public function getError($name)
    {
        return @$this->errors[$name];
    }

    public function getAllErrors()
    {
        return $this->errors;
    }
}
