<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CMS_model extends CI_Model {

    /**
     * @uses : This function is used to update record
     * @param : @table, @user_id, @user_array = array of update  
     * @author : HPA
     */
    public function update_record($table, $condition, $user_array, $modified = true) {
        if ($modified)
            $data['modified_date'] = date('Y-m-d H:i:s');
        $this->db->where($condition);
        if ($this->db->update($table, $user_array)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * @uses : This function is used to insert record
     * @param : @table, @data = array of update  
     * @author : HPA
     */
    public function insert_data($table, $data, $escape = true) {
        $this->db->insert($table, $data, $escape);
        $last_id = $this->db->insert_id();
        return $last_id;
    }

    /**
     * @uses : This function is used get result from the table
     * @param : @table 
     * @author : HPA
     */
    public function get_result($table, $condition = null, $fields = null, $single = null, $total = null, $order_by = null, $sort = null) {
        if (!empty($fields))
            $this->db->select($fields);
        else
            $this->db->select('*');
        if ($order_by != null) {
            $this->db->order_by($order_by);
        }
        if (!is_null($condition)) {
            $this->db->where($condition);
        }
        $query = $this->db->get($table);

        if (!is_null($single)) {
            return $query->row_array();
        } else if (!is_null($total)) {
            return $query->num_rows();
        } else {
            return $query->result_array();
        }
    }

    /**
     * @uses : This function is used delete result from the table
     * @param : @table @where = condition
     * @author : HPA
     */
    public function delete_data($table, $where) {
        $this->db->where($where);
        $this->db->delete($table);
    }

    /**
     * @uses : This function is used count if result is exist in the table
     * @param : @table @conditions = condition
     * @author : HPA
     */
    public function record_exist($table, $conditions) {
        if (is_array($conditions) && count($conditions) > 0) {
            foreach ($conditions as $column_name => $value) {
                $this->db->where($column_name, $value);
            }
        }
        $records = $this->db->get($table);
        return count($records->result_array());
    }

    /**
     * @uses : This function is used insert or update records in the table
     * @param : @table @record_array = condition @primary_id @escape
     * @author : HPA
     */
    public function manage_record($table_name, $record_array, $primary_id = '', $escape = true) {
        if ($primary_id != '') {
            $this->db->where('id', $primary_id);
            if ($this->db->update($table_name, $record_array)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            if ($this->db->insert($table_name, $record_array, $escape)) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    /**
     * @uses : This function is used insert or update records in the table
     * @param : @table @record_array = condition @primary_id @escape
     * @author : HPA
     */
    public function manage_record_batch($table_name, $record_array, $primary_id = '', $escape = true) {
        if ($primary_id != '') {
            if ($this->db->update_batch($table_name, $record_array, $primary_id)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            if ($this->db->insert_batch($table_name, $record_array)) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    /**
     * @uses : This function is used delete records but only update is_delete field instead of delete records in the table
     * @param : @table @record_id = id @field = if another filed expect id @array = if field name is change like is_deleted.
     * @author : HPA
     */
    public function delete($table_name, $record_id, $field = null, $array = null) {
        if ($array != null) {
            $record_array = $array;
        } else {
            $record_array = array('is_delete' => 1);
        }
        if ($field != null) {
            $this->db->where($field, $record_id);
        } else {
            $this->db->where('id', $record_id);
        }
        if ($this->db->update($table_name, $record_array)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * @uses : This function is used get last inserted id from the table
     * @param : @table
     * @author : HPA
     */
    public function getLastInsertId($table) {
        $insert_id = $this->db->insert_id($table);
        return $insert_id;
    }

    public function update_multiple($table, $data, $field) {
        $this->db->update_batch($table, $data, $field);
    }

    public function insert_batch($table, $data) {
        return $this->db->insert_batch($table, $data);
    }

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }


    /**
     * @uses : This function is used to delete multiple records from the table
     * @param : @field name, array of IDs, table
     * @author : RR
     */

    function delete_multiple($field, $data, $table){
        $this->db->where_in($field, $data);
        $this->db->delete($table);
    }


}

?>
