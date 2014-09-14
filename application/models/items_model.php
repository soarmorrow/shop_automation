<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class items_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    public function get_item_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('items');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }    

    public function get_items($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('*');
		$this->db->from('items');

		if($search_string){
			$this->db->like('items.item_name', $search_string);
		}

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

    public function get_active_items()
    {
        
        $this->db->select('id , item_name');
        $this->db->where('status',  1);
        $this->db->from('items');
        
        $query = $this->db->get();
        
        return $query->result_array();  
    }

    function count_items($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('items');
		if($search_string){
			$this->db->like('item_name', $search_string);
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
    function store_item($data)
    {
		$insert = $this->db->insert('items', $data);
	    return $insert;
	}

    function update_item($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update('items', $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_item($id){
		$this->db->where('id', $id);
		$this->db->delete('items'); 
	}

    function delete_items($ids){
        $this->db->where_in('id', $ids);
        $this->db->delete('items'); 
    }
 
}