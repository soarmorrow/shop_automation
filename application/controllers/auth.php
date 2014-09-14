<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

	class Auth extends CI_Controller {

	    /**
	    * Check if the user is logged in, if he's not, 
	    * send him to the login page
	    * @return void
	    */	
		function index()
		{
			if($this->session->userdata('is_logged_in')){
				redirect('dashboard');
	        }else{
	        	$this->load->view('login');	
	        }
		}

	    /**
	    * encript the password 
	    * @return mixed
	    */	
	    function __encrip_password($password) {
	        return hash('sha256',"R@nd0M".$password."!@#$%");
	    }	

	    /**
	    * check the username and the password with the database
	    * @return void
	    */
		function validate_credentials()
		{	
			$this->load->model('Users_model');

			$user_name = $this->input->post('user_name');
			$password = $this->__encrip_password($this->input->post('password'));

			$is_valid = $this->Users_model->validate($user_name, $password);
			
			if($is_valid)
			{
				$user = $this->Users_model->get_user_details($user_name);

				$data = array(
					'user_name' => $user_name,
					'is_logged_in' => true,
					'id' => $user->id
				);

				if($user->user_level == 1)
					$data['admin'] = true;

				$this->session->set_userdata($data);
				redirect('dashboard');
			}
			else // incorrect username or password
			{
				$data['message_error'] = TRUE;
				$this->load->view('login', $data);	
			}
		}	

	    /**
	    * Create new user and store it in the database
	    * @return void
	    */	
		function create_member()
		{
			$this->load->library('form_validation');
			
			// field name, error message, validation rules
			$this->form_validation->set_rules('first_name', 'Name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
			$this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->load->view('admin/signup_form');
			}
			
			else
			{			
				$this->load->model('Users_model');
				
				if($query = $this->Users_model->create_member())
				{
					$this->load->view('admin/signup_successful');			
				}
				else
				{
					$this->load->view('admin/signup_form');			
				}
			}
			
		}
		
		/**
	    * Destroy the session, and logout the user.
	    * @return void
	    */		
		function logout()
		{
			$this->session->sess_destroy();
			redirect('');
		}

		function forgot_password()
		{
			$data = array();
			if ($this->input->server('REQUEST_METHOD') === 'POST')
	        {

			$this->load->library('form_validation');
			$this->load->model('users_model');
			

			$this->form_validation->set_rules('user_name', 'Username', 'trim|required|min_length[4]');
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
			
			if($this->form_validation->run())
			{

				$this->db->where('user_name', $this->input->post('user_name'));
	            $this->db->or_where('email_addres', $this->input->post('user_name'));
	            $query = $this->db->get('membership');

	            if($query->num_rows == 0 ){
	            	$data['flash_message'] = FALSE;
	            }
	            else
	            {
	            	$this->load->library('email');
					$config['protocol'] = 'mail';
					$this->email->initialize($config);

	            	$username = $query->row()->user_name; 
	            	$email = $query->row()->email_addres;
	    			$new_password = $this->users_model->generatePassword();
	                $content_to_mail = 'Password request from your site...';
	                $content_to_mail .= 'Your new password is ';
	                $content_to_mail .= $new_password;

	                echo $new_password;


	                if($this->users_model->reset_password($username ,$new_password))
	                {

	                	$this->email->from('noreply@soarmorrow.com', 'SoarMorrow Solutions');
						$this->email->to($email); 

						$this->email->subject('Password Reset');
						$this->email->message($content_to_mail);
							
	                	if( $this->email->send())
	                	{
	                 		$data['flash_message'] = TRUE; 
	                 	}
	                 	else
	                 	{
	                 		$data['flash_message'] = FALSE; 
	                 	}
	                }else
	                {
	                    $data['flash_message'] = FALSE; 
	               	}
	            }

			}
			}
			$this->load->view('forgot_password', $data);	
		}
	}
