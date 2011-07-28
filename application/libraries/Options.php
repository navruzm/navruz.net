<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Options {

    CONST TABLE = 'options';

    private $ci;
    private $db;
    private $options = array();

    /**
     *
     */
    function __construct()
    {
        $this->ci = & get_instance();
        $this->db = $this->ci->db;
        $this->initialize();
    }

    /**
     * Initialize library
     * @return
     */
    public function initialize()
    {
        $this->db->where('autoload', 'yes');
        $query = $this->db->get(self::TABLE);
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $option)
            {
                $this->options[$option['option_name']] = $option['option_value'];
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
    public function set_item($name, $value, $insert = FALSE)
    {

        if ($insert && $this->item($name) === FALSE)
        {
            $this->db->set('option_name', $name);
            $this->db->set('option_value', $value);
            $this->db->insert(self::TABLE);
        }
        else
        {
            $this->db->where('option_name', $name);
            $this->db->set('option_value', $value);
            $this->db->update(self::TABLE);
        }
        $this->options[$name] = $value;
    }

}

/* End of file Options.php */