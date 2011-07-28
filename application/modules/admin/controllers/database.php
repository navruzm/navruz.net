<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Database extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        log_message('debug', 'Database Controller Initialized');
    }

    public function index()
    {
        $this->template->view('admin/admin/database');
        $this->template->load('admin_layout');
    }

    public function download()
    {
        $this->load->dbutil();
        $this->load->helper('download');
        $backup = &$this->dbutil->backup(array('format' => 'txt'));
        force_download(date('y_m_d_H_i_') . 'backup.sql', $backup);
    }

    public function backup()
    {
        $data = array();
        $this->load->dbutil();
        $backup = & $this->dbutil->backup();
        $this->load->helper('file');
        if (write_file(config_item('sql_backup_path') . date('Y_m_d_H_i_') . 'backup.gz', $backup))
        {
            flash_message('success', 'Veritabanı yedeklendi.');
        }
        else
        {
            flash_message('error', 'Veritabanı yedeklenemedi.');
        }

        redirect('admin/database/index');
    }

    public function optimize()
    {
        $this->load->dbutil();
        $data['result'] = $this->dbutil->optimize_database();
        $this->template->view('admin/admin/database_optimize', $data);
        $this->template->load('admin_layout');
    }

}

/* End of file dashboard.php */