<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Users_model extends CI_Model {


    public function get_user_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row_array(); 
    } 

    public function get_users($search_string=null,$search_field='user_name', $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('*');
		$this->db->from('users');
		if($search_string){
			$this->db->like($search_field, $search_string);
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

    function count_users($search_string=null,$search_field='user_name', $order=null)
    {
		$this->db->select('*');
		$this->db->from('users');
		if($search_string){
			$this->db->like($search_field, $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

	function validate($user_name, $password)
	{
		$this->db->where('user_name', $user_name);
		$this->db->where('pass_word', $password);
		$this->db->where('status', 1 );
		$query = $this->db->get('users');
		
		if($query->num_rows == 1)
		{
			return true;
		}		
	}

	function get_user_details($user_name)
	{
		$this->db->select('*');
		$this->db->where('user_name', $user_name);
		$result = $this->db->get('users');
		$user = $result->row();
		return $user;
	}

	function get_active_staffs()
	{
		$this->db->select('id , user_name');
		$this->db->where('status', 1 );
		$this->db->where('user_level', 2 );
		$query = $this->db->get('users');
		return $query->result_array();
	}

	function get_db_session_data()
	{
		$query = $this->db->select('user_data')->get('ci_sessions');
		$user = array(); /* array to store the user data we fetch */
		foreach ($query->result() as $row)
		{
		    $udata = unserialize($row->user_data);
		    /* put data in array using username as key */
		    $user['user_name'] = $udata['user_name']; 
		    $user['is_logged_in'] = $udata['is_logged_in']; 
		}
		return $user;
	}
	
    function store_user($data)
    {
		$insert = $this->db->insert('users', $data);
	    return $insert;
	}

	function update_user($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function change_password($data)
	{
		$data_to_store = array('pass_word' => hash('sha256',"\$oar/\/\orro\/\/".$data['password']."E/\/CrIpTiOn"));	
		$this->db->where('user_name', $this->session->userdata('user_name'));
		$this->db->update('users', $data_to_store);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}		
	}

	function reset_password($username ,$password)
	{
		$data_to_store = array('pass_word' => hash('sha256',"\$oar/\/\orro\/\/".$password."E/\/CrIpTiOn"));	
		$this->db->where('user_name', $username);
		$this->db->update('users', $data_to_store);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}		
	}

	function generatePassword($length = 8) {
    	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    	$count = mb_strlen($chars);

    	for ($i = 0, $result = ''; $i < $length; $i++) {
        	$index = rand(0, $count - 1);
        	$result .= mb_substr($chars, $index, 1);
    	}
    	return $result;
	}

	function delete_user($id){
		$this->db->where('id', $id);
		$this->db->delete('users'); 
	}

    function delete_users($ids){
        $this->db->where_in('id', $ids);
        $this->db->delete('users'); 
    }

	function change_user_status($id){

    	$this->db->where('id', $id);
        $query = $this->db->get('users');
        $row = $query->row_array();
        if($row){
            if($row['status'] == 1)
                $data['status'] = 0;
            else
                $data['status'] = 1;
            $this->db->where('id', $id);
            $this->db->update('users', $data);
        } 
	}
}

