<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contact extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    function index()
    {
        $this->form_validation->set_rules('email', 'E-Posta', 'trim|required|valid_email');
        $this->form_validation->set_rules('name', 'İsim', 'trim|required');
        $this->form_validation->set_rules('message', 'Mesaj', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $data['errors'] = array();
        if ($this->form_validation->run())
        {
            $edata = array(
                'name' => $this->form_validation->set_value('name'),
                'email' => $this->form_validation->set_value('email'),
                'message' => $this->form_validation->set_value('message')
            );
            if ($this->_send_email($edata))
            {
                $data['message'] = message_box('Mesajınız başarıyla gönderildi. Görüş ve önerileriniz için teşekkürler.', 'success');
            }
            else
            {
                $data['message'] = message_box('Teknik bir hatadan dolayı mesajınız <b>gönderilemedi</b>.', 'error');
            }

            $this->template->view('system/message', $data);
            $this->template->load();
            return;
        }
        $this->template->add_keyword('İletişim');
        $this->template->set_description('İletişim - ' . get_option('site_name'));
        $this->template->set_title('İletişim - ' . get_option('site_name'));
        $this->template->add_breadcrumb('İletişim');

        $this->template->view('contact', $data);
        $this->template->load();
    }

    function _send_email($data)
    {
        $this->load->library('email');
        $this->email->from($data['email'], $data['name']);
        $this->email->to(get_option('site_email'));
        $this->email->subject('İletişim Formundan Yeni Mesaj');
        $this->email->set_newline("\r\n");
        $this->email->message($this->load->view('contact-html', $data, TRUE));
        return $this->email->send();
    }

}