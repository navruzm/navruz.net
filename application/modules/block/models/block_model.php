<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @package Block_model
 *
 * @property CI_DB_active_record $db
 *
 */
class Block_model extends CI_Model {

    const TABLE = 'blocks';
    const TABLE_MODULE = 'block_module';

    /**
     * Yapıcı fonksiyon.
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        log_message('debug', 'Block_model Model Initialized');
    }

    public function get_all($module, $level = NULL, $active = NULL)
    {
        if ($level != NULL)
        {
            $this->db->where_in('access_level', $level);
        }
        if ($active != NULL)
        {
            $this->db->where('active', $active);
        }
        $this->db->select('b.*');
        $this->db->select('s.weight');
        $this->db->from(self::TABLE_MODULE . ' as s');
        $this->db->join(self::TABLE . ' as b', 's.block_id = b.id', 'left');
        $this->db->where('s.module', $module);
        $this->db->order_by('s.weight');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get(self::TABLE);
        return array_merge(
                $query->row_array(),
                array('modules' => $this->_get_block_module($id))
        );
    }

    public function add($sql_data, $modules)
    {
        $this->db->insert(self::TABLE, $sql_data);
        $id = $this->db->insert_id();
        if ($id)
        {
            foreach ($modules as $module)
            {
                $sql_data = array(
                    'weight' => $this->_get_max_weight($module),
                    'module' => $module,
                    'block_id' => $id
                );
                $this->db->insert(self::TABLE_MODULE, $sql_data);
            }
            return TRUE;
        }
        return FALSE;
    }

    public function update($sql_data, $modules)
    {
        $this->db->where('id', $sql_data['id']);
        $this->db->update(self::TABLE, $sql_data);

        foreach ($modules as $module)
        {
            if ($this->db->where('module', $module)->where('block_id', $sql_data['id'])->count_all_results(self::TABLE_MODULE) > 0)
                continue;
            $module_data = array(
                'weight' => $this->_get_max_weight($module),
                'module' => $module,
                'block_id' => $sql_data['id']
            );
            $this->db->insert(self::TABLE_MODULE, $module_data);
        }
        foreach ($this->_get_block_module($sql_data['id']) as $module)
        {
            if (!in_array($module, $modules))
            {
                $this->_delete_block_module($sql_data['id'], $module);
            }
        }
        return TRUE;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(self::TABLE);
        if ($this->db->affected_rows() > 0)
        {
            $this->db->where('block_id', $id);
            $this->db->delete(self::TABLE_MODULE);
            return TRUE;
        }
        return FALSE;
    }

    public function sort($id, $weight)
    {
        $this->db->where('block_id', $id);
        $this->db->update(self::TABLE_MODULE, array('weight' => $weight));
    }

    private function _get_max_weight($module)
    {
        $this->db->where('module', $module);
        $this->db->select_max('weight');
        $query = $this->db->get(self::TABLE_MODULE);
        $result = $query->row_array();
        return ($result['weight'] > 0) ? $result['weight'] + 1 : 1;
    }

    public function get_blocks()
    {
        $query = $this->db->get(self::TABLE);
        $blocks = array();
        foreach ($query->result_array() as $block)
        {
            $blocks[] = array_merge(
                            $block,
                            array('modules' => $this->_get_block_module($block['id']))
            );
        }
        return $blocks;
    }

    private function _get_block_module($id)
    {
        $this->db->select('module');
        $this->db->where('block_id', $id);
        $query = $this->db->get(self::TABLE_MODULE);
        $module = array();
        foreach ($query->result_array() as $result)
        {
            $module[] = $result['module'];
        }
        return $module;
    }

    private function _delete_block_module($id, $module)
    {
        $this->db->where('module', $module);
        $this->db->where('block_id', $id);
        $this->db->delete(self::TABLE_MODULE);
    }

    public function active($id, $action)
    {
        $action = ($action == 'unactivate') ? 0 : 1;
        $this->db->where('id', $id);
        $this->db->set('active', $action);
        $this->db->update(self::TABLE);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

}

/* End of file block_model.php */