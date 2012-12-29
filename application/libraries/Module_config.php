<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Module_config
{

    private $ci;
    static private $_modules = array();
    static private $_config = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->helper(array('directory', 'file'));
        $this->_read_module_names();
    }

    /**
     * Read module names
     */
    private function _read_module_names()
    {
        self::$_modules = directory_map(APPPATH . 'modules/', 1);
    }

    /**
     * Read config data from info files
     */
    private function _load_file($module)
    {
        if (file_exists(APPPATH . 'modules/' . $module . '/config/info.php'))
        {
            self::$_config[$module] = include(APPPATH . 'modules/' . $module . '/config/info.php');
        }
        else
        {
            log_message('error', APPPATH . $module . '/config/info.php not found');
        }
    }

    /**
     * Return module config via array
     * @return array
     */
    public function get($module=NULL)
    {
        if ($module === NULL)
        {
            self::$_config = array();
            foreach (self::$_modules as $_module)
            {
                $this->_load_file($_module);
            }
            return self::$_config;
        }
        elseif (in_array($module, self::$_modules))
        {
            $this->_load_file($module);
            return isset(self::$_config[$module]) ? self::$_config[$module] : array();
        }
        return array();
    }

}

/* End of file Module_config.php */