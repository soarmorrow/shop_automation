<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Orders_model extends CI_Model {
 
   
    public function __construct()
    {
        $this->load->database();
    }

    public function get_order_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('customers');
		$this->db->where('cust_id', $id);
		$query = $this->db->get();
		return $query->row_array(); 
    }    

 
    public function get_guest_orders($search_string=null,$search_field='first_name', $order=null, $order_type='Asc', $limit_start=null, $limit_end=null){
        $this->db->select('customers.*, items.item_name item,flavours.flavour flavour');
        $this->db->from('customers');
        $this->db->join('flavours', 'flavours.id = customers.flavour_id');
        $this->db->join('items', 'items.id = customers.item_id');
        $this->db->where('added_by', -1);
        $this->db->join('catalogues' , 'catalogues.id = customers.cat_id');

        if($search_string){
         $this->db->like($search_field, $search_string);
            $this->db->or_like('item.'.$search_field, $search_string);
            $this->db->or_like('flavours'.$search_field, $search_string);
        }

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('customers.cust_id', $order_type);
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
    public function get_orders( $search_string=null,$search_field='first_name', $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('customers.*, items.item_name item,flavours.flavour flavour');
		$this->db->from('customers');
        $this->db->join('flavours', 'flavours.id = customers.flavour_id');
        $this->db->join('items', 'items.id = customers.item_id');
        $this->db->join('catalogues' , 'catalogues.id = customers.cat_id');

		if($search_string){
		   $this->db->like($search_field, $search_string);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('customers.cust_id', $order_type);
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

    function count_orders($search_string=null, $order=null)
    {
		$this->db->select('customers.* ');
		$this->db->from('customers');
        $this->db->join('catalogues' , 'catalogues.id = customers.cat_id');
		if($search_string){
            $this->db->like($search_field, $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('cust_id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store_order($data)
    {
		$insert = $this->db->insert('customers', $data);
	    return $insert;
	}

    function update_order($id, $data)
    {
		$this->db->where('cust_id', $id);
		$this->db->update('customers', $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_order($id){
		$this->db->where('cust_id', $id);
		$this->db->delete('customers'); 
	}

    function delete_orders($ids){
        $this->db->where_in('cust_id', $ids);
        $this->db->delete('customers'); 
    }

    function change_status($part,$id){

        $this->db->where('cust_id', $id);
        $query = $this->db->get('customers');
        $row = $query->row_array();
        if($row){
            if($row[$part] == 1)
                $data[$part] = 0;
            else
                $data[$part] = 1;
            $this->db->where('cust_id', $id);
            $this->db->update('customers', $data);
        } 
    }
 
}