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
 */
class MY_Controller extends CI_Controller
{

    var $module;
    var $controller;
    var $method;
    var $userdata;

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('option');
        $this->load->library('module_config');
        $this->load->helper(array('url', 'html', 'form','date','navigation/navigation'));
        $this->load->library('user/auth');
        $this->load->library('template');
        $this->module = $this->router->fetch_module();
        $this->controller = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->m_config = $this->module_config->get($this->module);
        if($this->auth->is_user())
        {
            $this->userdata = $this->auth->get_user($this->session->userdata('user_id'));
        }
    }

    public function redirect($old_slug, $collection=NULL, $uri = NULL, $prefix='/')
    {
        $uri = ($uri == NULL) ? $this->uri->uri_string() : $uri;
        $collection = ($collection == NULL) ? $this->module : $collection;
        $redirect = $this->mongo_db->redirect->findOne(array('old_slug' => $old_slug, 'module' => $this->module));
        if (isset($redirect['new_slug']))
        {
            $uri = str_replace($old_slug, $redirect['new_slug'], $uri);
            $redirect['new_slug'] = str_replace($prefix, '', $redirect['new_slug']);
            if (!(int) $this->mongo_db->$collection->find(array('slug' => $redirect['new_slug']))->count())
            {
                $this->redirect($redirect['new_slug'], $collection, $uri, $prefix);
            }
            else
            {
                redirect($uri, 'location', 301);
            }
        }
        else
        {
            return FALSE;
        }
    }

}