<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property CI_DB_active_record $db
 * @property CI_Form_validation $form_validation
 * @property CI_Loader $load
 * @property CI_Router $router
 * @property CI_Session $session
 * @property CI_URI $uri
 * @property Template $template
 * @property Redirect $redirect
 *
 * @property News_Model $news_model
 * @property Article_Model $article_model
 * @property Forum_Model $forum_model
 * @property Category_Model $category_model
 * @property Page_Model $page_model
 */
class MY_Controller extends CI_Controller
{

    var $module;
    var $controller;
    var $method;

    function __construct()
    {
        parent::__construct();
        $this->module = $this->router->fetch_module();
        $this->controller = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->m_config = $this->module_config->get_module_config();


        //@todo module breadcrumb
        //add_breadcrumb($title);
        //Maintenance mode
        if (get_option('maintenance') == 1 && $this->module != 'admin' & !is_admin())
        {
            $this->template->load('maintenance');
            die($this->output->get_output());
        }

        //Old browser
        $this->load->library('user_agent');
        if ($this->agent->is_browser()
                && $this->agent->browser() == 'Internet Explorer'
                && (int) $this->agent->version() < 7)
        {
            $this->template->load('browser');
            die($this->output->get_output());
        }
        elseif ($this->agent->is_mobile())
        {
            $this->template->set_layout('mobile');
        }
//$this->template->set_layout('mobile');

        if (get_option('maintenance') == 1 && $this->module != 'admin' & !is_admin())
        {
            $this->template->load('maintenance');
            die($this->output->get_output());
        }

        //@todo sorunu hallet

        if (file_exists(APPPATH . 'modules/' . $this->module . '/models/' . $this->module . '_model.php'))
        {
            $this->load->model($this->module . '_model');
        }
    }

}