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
        $this->ci->load->driver('cache');
        $this->_read_module_names();
        $this->_load_config();
    }

    /**
     * Read module names
     */
    private function _read_module_names()
    {
        self::$_modules = directory_map(APPPATH . 'modules/', 1);
    }

    /**
     * Get module names
     * @return array
     */
    static public function get_module_names()
    {
        return self::$_modules;
    }

    /**
     * Load module config
     */
    private function _load_config()
    {
        $cache_data = $this->ci->cache->file->get('module_config');
        if ($cache_data !== FALSE)
        {
            self::$_config = $cache_data;
        }
        else
        {
            self::_read_config_files();
            $this->ci->cache->file->save('module_config', self::$_config, 86400);
        }
    }

    /**
     * Read config data from info files
     */
    private function _read_config_files()
    {
        foreach (self::$_modules as $module)
        {
            if (file_exists(APPPATH . 'modules/' . $module . '/config/info.php'))
            {
                self::$_config[$module] = include(APPPATH . 'modules/' . $module . '/config/info.php');
            }
            else
            {
                log_message('error', APPPATH . $module . '/config/info.php');
            }
        }
    }

    /**
     * Return module config via array
     * @return array
     */
    static public function get_module_config()
    {
        return self::$_config;
    }

}

/* End of file Module_config.php */