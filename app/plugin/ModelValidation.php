<?php
namespace App\Plugin;

use \Tx\Validator;


trait ModelValidation
{

    protected $errors;

    public function save(array $options = array()) {

        if (isset($options['data']) && $this->validate($options['data']) == false) {

            return false;
        }

        return parent::save($options);
    }

    public function validate($data) {

        // make a new validator object
        $v = Validator::make($data, $this->rules, $this->customMessages());

        // check for failure
        if ($v->fails())
        {
            // set errors and return false
            $this->errors = $v->errors();
            return false;
        }

        // validation pass
        return true;
    }

    public function errors() {
        return $this->errors;
    }


    function customMessages() {

        $app = \Slim\Slim::getInstance();

        $translate = $app->t;

        $messages = [];

        $validations = ['required', 'min', 'max', 'digits', 'email', 'date'];

        foreach ($validations as $val) {

            $messages[$val] = $translate['validation.' . $val];
        }
        return $messages;
    }
}