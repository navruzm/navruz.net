<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
    private $_rules = array(
        'required', 'matches', 'is_unique', 'min_length',
        'max_length', 'exact_length', 'greater_than', 'less_than',
        'alpha', 'alpha_numeric', 'alpha_dash', 'numeric',
        'integer', 'decimal', 'is_natural', 'is_natural_no_zero',
        'valid_email', 'valid_emails', 'valid_ip', 'valid_base64',
    );
    public $js_rules = array();

    public $rule_messages = array();

    public function __construct($rules = array())
    {
        parent::__construct($rules = array());
        $this->CI->lang->load('form_validation');
    }

    public function set_rules($field, $label = '', $rules = '')
    {
        if (is_array($field))
        {
            foreach ($field as $row)
            {
                if (!isset($row['field']) OR !isset($row['rules']))
                {
                    continue;
                }

                $label = (!isset($row['label'])) ? $row['field'] : $row['label'];

                return $this->set_rules($row['field'], $label, $row['rules']);
            }
        }

        if (!is_string($field) OR  !is_string($rules) OR $field == '')
        {
            return $this;
        }
        foreach (explode('|', $rules) as $rule)
        {
            if (strpos($rule, '[') !== FALSE)
            {
                $rule = current(explode('[', $field));
            }
            if (in_array($rule, $this->_rules))
            {
                if (FALSE === ($line = $this->CI->lang->line($rule)))
                {
                    $line = 'Unable to access an error message corresponding to your field name.';
                }
                $this->rule_messages[$rule] = $line;
            }
        }
        $label = ($label == '') ? $field : $label;
        $this->js_rules[] = array(
            'name' => $field,
            'display' => $label,
            'rules' => $rules,
        );
        return parent::set_rules($field, $label, $rules);
    }

    function run($group = '')
    {
        $return = parent::run($group);
        if ($return === FALSE)
        {
            $this->set_js_code();
        }
        return $return;
    }

    public function set_js_code()
    {
        if (count($this->js_rules))
        {
            $messages = array();

            foreach ($this->rule_messages as $rule => $message)
            {
                $messages[] = "validator.setMessage('$rule', '$message');";
            }
            $messages = implode('', $messages);
            $name = $this->CI->controller . '-' . $this->CI->method;
            $this->CI->asset->add_js_jquery("var validator = new FormValidator('{$name}',
            " . json_encode($this->js_rules) . ',
            function (errors, event) {
                $("form .clearfix").removeClass("error").children(".help-inline").html("");
                $("form .help-inline").remove();
                for (var i=0; i<errors.length; i++) {
                    $("#"+errors[i][0]).closest(".clearfix").addClass("error");
                    $("#"+errors[i][0]).parent().append("<span class=\"help-inline\">" + errors[i][1] + "</span>");
                }

                if (errors.length == 0) {
                            return true;
                        } else {
                            $("form:first .error *:input[type!=hidden]:first").first().focus();
                            return false;
                        }
});' . $messages
                , 'js/validate.js');
        }
    }

    // --------------------------------------------------------------------


    function valid_datetime($str)
    {
        $pattern = '/^([0-3][0-9])-([0-1][0-9])-([0-9]{2,4}) ([0-2][0-9]):([0-5][0-9])?$/';
        return (preg_match($pattern, $str) === 1) ? TRUE : FALSE;
    }


    function valid_url($str)
    {
        return (filter_var($str, FILTER_VALIDATE_URL)) ? TRUE : FALSE;
    }

}

