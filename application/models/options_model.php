<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Options_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    /**
    * Get product by his is
    * @param int $product_id 
    * @return array
    */
    public function get_option_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('options');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }    

    public function get_option($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('options');
        if($query->num_rows > 0 )
            return $query->row()->value; 
        else
            return false;
    } 

    /**
    * Fetch options data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_options($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('*');
		$this->db->from('options');

		if($search_string){
			$this->db->like('title', $search_string);
		}
		$this->db->group_by('id');

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('id', $order_type);
		}

        if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end);	
        }

        if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }
        
		$query = $this->db->get();
		
		return $query->result_array(); 	
    }

    public function get_user_options()
    {
        
        $this->db->select('*');
        $this->db->from('options');
        $this->db->where('type','user');
        
        $query = $this->db->get();
        
        return $query->result_array();  
    }

    /**
    * Count the number of rows
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_options($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('options');
		if($search_string){
			$this->db->like('title', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function add_option($option, $value , $type = 'system')
    {
        $data = array(
                'name' => $option,
                'value' => $value,
                'type' => $type
            );
		$insert = $this->db->insert('options', $data);
	    return $insert;
	}

    /**
    * Delete options
    * @param int $id - manufacture id
    * @return boolean
    */
	function delete_option($option){
		$this->db->where('name', $option);
		$this->db->delete('options'); 
	}

    function update_option($option, $value)
    {
        $data = array(
                'value' => $value
            );
            $this->db->where('name', $option);
            $this->db->update('options', $data);
            $report = array();
            $report['error'] = $this->db->_error_number();
            $report['message'] = $this->db->_error_message();
            if($report !== 0){
                return true;
            }else{
                return false;
            }
    }

    function update_options($options)
    {    
        $this->db->update_batch('options', $options , 'name');
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if($report !== 0){
            return true;
        }else{
            return false;
        }

    }
 
}
