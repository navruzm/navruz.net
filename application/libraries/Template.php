<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Template
{

    private $ci;
    private $layout = 'layout';
    private $layout_path = 'layouts';
    private $theme = 'default';
    private $theme_path = 'themes/';
    private $view;
    private $head_more = array();
    private $keywords = array();
    private $title;
    private $description;
    private $meta = array();
    private $breadcrumbs = array();
    private $last_breadcrumbs;
    public $language = 'tr';
    public $debug = FALSE;


    public function __construct()
    {
        $this->ci = get_instance();
        $this->ci->load->library('asset');
        $this->ci->load->helper('template');
        if ((get_option('debug') == 1) && check_permission('dummy'))
        {
            $this->ci->output->enable_profiler(TRUE);
            $this->debug = TRUE;
            error_reporting(E_ALL);
        }

        if (get_option('theme'))
        {
            $this->theme = get_option('theme');
        }
    }


    public function view($view, $data = array(), $true = TRUE)
    {
        if ($true)
        {
            $this->ci->load->vars($data);
        }
        $this->view = $this->_find_view($view, $data);

        if ($this->ci->input->is_ajax_request())
        {
            $this->ci->session->keep_all_flashdata();
        }
        else
        {
            $messages = $this->ci->session->flashdata('messages');
            if (is_array($messages) && count($messages))
            {
                $_messages = array();
                foreach ($messages as $message)
                {
                    $_messages[] = '<div class="alert-message ' . $message['type'] . '" data-alert="alert"><a class="close">&times;</a>' . $message['message'] . '</div>';
                }
                $this->view = implode("\n", $_messages) . $this->view;
                add_js_link('js/bootstrap-alerts.js');
            }
        }
        if (file_exists($this->theme_path . $this->theme . '/views/' . $this->ci->module . '/layout.php'))
        {
            $this->view = $this->_load_view($this->theme_path . $this->theme . '/views/' . $this->ci->module . '/layout', array('content' => $this->view));
        }
        elseif (file_exists(APPPATH . 'modules/' . $this->ci->module . '/views/layout.php'))
        {
            $this->view = $this->ci->load->view('layout', array('content' => $this->view), TRUE);
        }

        return $this;
    }


    public function render()
    {

        if ($this->view === NULL)
        {
            $this->view($this->ci->controller . '/' . $this->ci->method, array());
        }

        if ($this->ci->input->is_ajax_request())
        {
            $this->ci->output->set_output($this->view);
            return;
        }
        if (file_exists($this->theme_path . $this->theme . '/' . $this->layout_path . '/defaults.php'))
        {
            $this->_load_view($this->theme_path . $this->theme . '/' . $this->layout_path . '/defaults', array());
        }


        $data['body'] = $this->view;
        $data['css'] = $this->ci->asset->render_css();
        $data['js'] = $this->ci->asset->render_js();

        $data['more'] = '';
        if (count($this->head_more) > 0)
        {
            $data['more'] = implode("\n", $this->head_more);
        }
        $data['title'] = $this->get_title();

        $data['meta'] = $this->get_meta();

        $layout = $this->theme_path . $this->theme . '/' . $this->layout_path . '/' . $this->layout;
        if ($this->layout == 'layout')
        {
            $mcm = ($this->ci->module == $this->ci->controller) ? $this->ci->module . '-' . $this->ci->method : $this->ci->module . '-' . $this->ci->controller . '-' . $this->ci->method;
            if (file_exists($this->theme_path . $this->theme . '/' . $this->layout_path . '/' . $mcm . '.php'))
            {
                $layout = $this->theme_path . $this->theme . '/' . $this->layout_path . '/' . $mcm;
            }
        }
        $this->ci->output->set_output($this->_load_view($layout, $data));
    }


    public function add_more($more)
    {
        if ($more != NULL && !in_array($more, $this->head_more))
        {
            $this->head_more[] = $more;
        }
        return $this;
    }


    public function set_keyword($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }


    public function add_meta($meta)
    {
        if ($meta != NULL)
        {
            $this->meta[] = $meta;
        }
        return $this;
    }


    public function set_title($title, $site_name = FALSE)
    {
        if ($title != '')
        {
            $this->title = $title;
        }
        if ($site_name)
        {
            $this->title = $this->title . ' - ' . get_option('site_name');
        }
        return $this;
    }


    public function set_description($description, $site_name = FALSE)
    {
        if ($description != '')
        {
            $this->description = $description;
        }
        if ($site_name)
        {
            $this->description = $this->description . ' - ' . get_option('site_name');
        }
        return $this;
    }


    private function get_meta()
    {
        return $this->meta;
    }

    public function add_breadcrumb($title, $url = NULL)
    {
        if ($url !== NULL)
        {
            $this->breadcrumbs[$url] = $title;
        }
        else
        {
            $this->last_breadcrumbs = $title;
        }
        return $this;
    }

    public function get_breadcrumb()
    {
        $array[] = anchor('', 'Anasayfa', 'class="homes"');
        foreach ($this->breadcrumbs as $url => $title)
        {
            $array[] = anchor($url, $title);
        }
        $array[] = '<strong>' . $this->last_breadcrumbs . '</strong>';
        return ul($array);
    }

    private function page_num()
    {
        if (isset($this->ci->pagination))
        {
            $page_num = $this->ci->pagination->cur_page;
            if ($page_num > 1)
            {
                return ' - Sayfa ' . $page_num;
            }
        }
        return;
    }

    public function get_description()
    {
        if ($this->description == '')
        {
            return $this->ci->m_config['name'] . ' - ' . get_option('site_name') . self::page_num();
        }
        else
        {
            return $this->description . self::page_num();
        }
    }

    public function get_keywords()
    {
        return $this->keywords;
    }

    private function get_title()
    {
        if ($this->title == '')
        {
            return $this->ci->m_config['name'] . ' - ' . get_option('site_name') . self::page_num();
        }
        else
        {
            return $this->title . self::page_num();
        }
    }

    /**
     * Taken from PyroCMS
     *
     * @param type $view
     * @param array $data
     * @return type string
     */
    public function _load_view($view, array $data)
    {
        return $this->ci->load->_ci_load(array(
            '_ci_path' => $view . EXT,
            '_ci_vars' => $data,
            '_ci_return' => TRUE
        ));
    }

    public function _find_view($view, $data)
    {
        $locations = array(
            $this->theme_path . '/' . $this->theme . '/views/' . $this->ci->module . '/' . $view,
            $this->theme_path . '/' . $this->theme . '/views/' . $view,
        );
        foreach ($locations as $_view)
        {
            if (file_exists($_view . EXT))
            {
                return $this->_load_view($_view, $data);
            }
        }
        return $this->ci->load->view($view, $data, TRUE);
    }

    public function set_theme($theme)
    {
        $this->theme = $theme;
    }

    public function get_theme()
    {
        return $this->theme;
    }

    public function layout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

}

/* End of file Template.php */