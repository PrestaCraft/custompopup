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

require_once _PS_MODULE_DIR_.'custompopup/classes/utils/CP_Validation.php';

abstract class CP_PrestaCraftValidatorCore
{
    protected $module;
    protected $formName;
    protected $validatorFile;
    protected $validation;
    private $data;
    private $errors;
    private $allowEmpty = false;

    public function __construct($moduleObject, $formName)
    {
        if ($moduleObject instanceof Module) {
            $this->module = $moduleObject;
        } else {
            throw new \Exception("[PrestaCraft Exception] Incorrect module instance");
        }

        $validatorDir = _PS_MODULE_DIR_.$this->module->name.'/classes/form/validators/';
        $formDir = _PS_MODULE_DIR_.$this->module->name.'/classes/form/';
        $validatorName = str_replace('Form', 'Validator', $formName);

        if (!file_exists($formDir .$formName.'.php')) {
            throw new \Exception("[PrestaCraft Exception] Form '{$formName}' file not found in classes/form directory");
        } else {
            if (!file_exists($validatorDir.$validatorName.'.php')) {
                throw new \Exception(
                    "[PrestaCraft Exception] Validator '{$validatorName}'
                    file not found in classes/form/validators directory"
                );
            }

            $this->formName = $formName;
            $this->validatorFile = $validatorDir.$formName.'Validator.php';
            $this->validation = new CP_Validation($moduleObject);

            require_once $validatorDir.$validatorName.'.php';
        }
    }

    abstract protected function processCP_Validation();

    /**
     * CP_Validation & form saving
     * 1. Validate form and set errors if occured
     * 2. Set all fields errors to one variable
     * 3. Save
     *
     * @throws Exception
     */
    public function validate()
    {
        if (!$this->allowEmpty) {
            if (!$this->getData() || count($this->getData()) == 0) {
                throw new \Exception("[PrestaCraft Exception] Form data for '{$this->formName}' is not provided");
            }
        }

        if (Tools::isSubmit($this->formName)) {
            $this->processCP_Validation();
            $this->setErrorsIfOccured();
            $this->save();
        }
    }

    abstract protected function save();

    protected function setErrorsIfOccured()
    {
        if ($this->validation->getAllErrors()) {
            $errors = array();

            foreach ($this->validation->getAllErrors() as $value) {
                foreach ($value as $val) {
                    $errors[] = $val;
                }
            }

            $newLineErrors = implode("<br>", $errors);

            $this->setErrors($newLineErrors);
        }
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set form data to validate
     *
     * @param $array : Key->Value array where key should be equal to field from Tools::getValue(field)
     * @param bool $allowEmpty : Determine if you can pass empty data array (ex. form with only one checkbox option
     *                           with no checkbox selected)
     */
    public function setData($array, $allowEmpty = false)
    {
        $this->data = $array;

        if ($allowEmpty) {
            $this->allowEmpty = true;
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function getField($field)
    {
        if (array_key_exists($field, $this->data)) {
            return $this->data[$field];
        } else {
            throw new \Exception("[PrestaCraft Exception] Field not found in getField() method");
        }
    }
}
