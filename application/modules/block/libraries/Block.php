<?php

class Block
{

    private $ci;
    public $config = array();
    private static $_module_blocks;

    function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->model('block/block_model');
        //@todo sorunu hallet;controller yüklenmediği için modül ayarlarını alamıyoruz.
        //if ($this->ci->m_config[$this->ci->module]['is_frontend'] == 1)
        //{
        //$this->_get_blocks_by_module();
        //}
    }

    public function get_frontend_modules()
    {
        $frontend_module = array();
        $modules = $this->ci->module_config->get_module_config();
        foreach ($modules as $name => $module)
        {
            if ($module['is_frontend'] == 1)
            {
                $frontend_module[$name] = $module['name'];
            }
        }
        return $frontend_module;
    }

    public function block_files($module=NULL)
    {
        $general = glob(APPPATH . 'modules/*/blocks/block_*.php');
        foreach ($general as $file)
        {
            $this->ci->load->file($file);
        }
        return $this->config;
    }

    public function get_all_blocks()
    {
        $locations = array(
            'left' => array(),
            'right' => array(),
            'center_top' => array(),
            'center_bottom' => array(),
        );
        $blocks = $this->ci->block_model->get_blocks();
        foreach ($blocks as $block)
        {
            $locations[$block['location']][] = $block;
        }
        return $locations;
    }

    public function get_all_blocks_by_module()
    {
        $modules = array();
        foreach ($this->ci->module_config->get_module_config() as $name => $module)
        {
            if ($module['is_frontend'] == 1)
            {
                $blocks = $this->ci->block_model->get_all($name);
                $modules[$name] = array(
                    'left' => array(),
                    'right' => array(),
                    'center_top' => array(),
                    'center_bottom' => array(),
                    'block_count' => count($blocks)
                );
                foreach ($blocks as $block)
                {
                    $modules[$name][$block['location']][] = $block;
                }
            }
        }
        return $modules;
    }

    public function get_blocks_by_module($location)
    {
        $this->_get_blocks_by_module();
        $block_html = '';
        foreach (self::$_module_blocks[$location] as $block)
        {
            $block_title = '<div class="title">' . $block['title'] . '</div>';
            if ($block['type'] == 'html')
            {
                $block_content = $block['content'];
            }
            elseif ($block['type'] == 'file')
            {
                $this->ci->load->file(APPPATH . 'modules/' . $block['module'] . '/blocks/block_' . $block['content'] . '.php');
                $block_content = call_user_func('block_' . $block['content']);
            }
            elseif ($block['type'] == 'menu')
            {
                $block_content = get_navigation($block['content'], url_title('nav-' . $block['content']));
            }
            if ($block_content != '')
                $block_html = $block_html . $block_title . '<div class="block-content">' . $block_content . '</div>';
        }
        return $block_html;
    }

    public function _get_blocks_by_module()
    {
        if (count(self::$_module_blocks))
            return self::$_module_blocks;
        self::$_module_blocks = array(
            'left' => array(),
            'right' => array(),
            'center_top' => array(),
            'center_bottom' => array(),
        );
        if (is_admin ())
        {
            $levels = array(0, 2, 3);
        }
        elseif (is_user ())
        {
            $levels = array(0, 2);
        }
        else
        {
            $levels = array(0, 1);
        }
        if ($this->ci->m_config[$this->ci->module]['is_frontend'] == 1)
        {
            $blocks = $this->ci->block_model->get_all($this->ci->module, $levels, 1);
            foreach ($blocks as $block)
            {
                self::$_module_blocks[$block['location']][] = $block;
            }
        }
        return self::$_module_blocks;
    }

    /**
     * @fix İçeriği olmayan bloklar olunca gizleme yapmıyor. 
     * @return strıng
     */
    public function css_class_name()
    {
        $this->_get_blocks_by_module();
        $class_name = '';
        if (count(self::$_module_blocks['left']) == 0 && count(self::$_module_blocks['right']) == 0)
        {
            $class_name = 'hide-both';
        }
        elseif (!count(self::$_module_blocks['left']))
        {
            $class_name = 'hide-left';
        }
        elseif (!count(self::$_module_blocks['right']))
        {
            $class_name = 'hide-right';
        }
        return $class_name;
    }

}

/* End of file Block.php */