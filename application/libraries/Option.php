<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Option
{

    private $ci;
    private $options = array();
    private $collection_name = 'options';

    /**
     *
     */
    function __construct()
    {
        $this->ci = get_instance();
        $this->ci->load->library('mongo_db');
        $this->ci->load->helper('option');
        $this->initialize();
    }

    /**
     * Initialize library
     * @return
     */
    public function initialize()
    {
        $query = $this->ci->mongo_db->{$this->collection_name}->find(array());
        if (count($query))
        {
            foreach ($query as $option)
            {
                $this->options[$option['name']] = $option['value'];
            }
        }
        return;
    }

    /**
     * Get option item
     * @param string $name
     * @return boolean|string
     */
    public function item($name)
    {
        if (array_key_exists($name, $this->options))
        {
            return $this->options[$name];
        }
        return FALSE;
    }

    /**
     * Set option item
     * @param string $name
     * @param string $value
     * @param boolean $insert
     */
    public function set_item($name, $value)
    {

        $this->ci->mongo_db->{$this->collection_name}->update(array(
            'name' => $name
                ), array(
            '$set' => array('value' => $value)
                ), array('upsert' => TRUE));
        $this->options[$name] = $value;
    }

}

/* End of file Options.php */