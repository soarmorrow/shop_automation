<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Staffs extends CI_Controller {

    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');

        if(!$this->session->userdata('is_logged_in')){
            redirect('login');
        }
        else if(!$this->session->userdata('admin')){
            redirect('');
        }
    }

    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function index()
    {

        //all the posts sent by the view
        $search_string = $this->input->post('search_string');        
        $search_field = $this->input->post('search_field');        
        $order = $this->input->post('order'); 
        $order_type = $this->input->post('order_type'); 

        //pagination settings
        $config['per_page'] = $this->options_model->get_option('results_per_page');
        $config['base_url'] = base_url().'staffs';
        $config['uri_segment'] = 2;

        //limit end
        $page = $this->uri->segment(2);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 

        //if order type was changed
        if($order_type){
            $filter_session_data['order_type'] = $order_type;
        }
        else{
            //we have something stored in the session? 
            if($this->session->userdata('order_type')){
                $order_type = $this->session->userdata('order_type');    
            }else{
                //if we have nothing inside session, so it's the default "Asc"
                $order_type = 'Asc';    
            }
        }
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;        


        //we must avoid a page reload with the previous session data
        //if any filter post was sent, then it's the first time we load the content
        //in this case we clean the session filter data
        //if any filter post was sent but we are in some page, we must load the session data

        //filtered && || paginated
        if( $search_string !== false && $search_field !== false && $order !== false || $this->uri->segment(3) == true){ 

            if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            if($search_field){
                $filter_session_data['search_field_selected'] = $search_field;
            }else{
                $search_field = $this->session->userdata('search_field_selected');
            }
            $data['search_field'] = $search_field;

            if($order){
                $filter_session_data['order'] = $order;
            }
            else{
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;

            //save session data into the session
            if(isset($filter_session_data)){
              $this->session->set_userdata($filter_session_data);    
          }

            //fetch sql data into arrays
          $data['count_users']= $this->users_model->count_users($search_string,$search_field, $order);
          $config['total_rows'] = $data['count_users'];

            //fetch sql data into arrays
          if($search_string){
            if($order){
                $data['users'] = $this->users_model->get_users( $search_string,$search_field, $order, $order_type, $config['per_page'],$limit_end);        
            }else{
                $data['users'] = $this->users_model->get_users( $search_string,$search_field, '', $order_type, $config['per_page'],$limit_end);           
            }
        }else{
            if($order){
                $data['users'] = $this->users_model->get_users( '',$search_field, $order, $order_type, $config['per_page'],$limit_end);        
            }else{
                $data['users'] = $this->users_model->get_users('',$search_field, '', $order_type, $config['per_page'],$limit_end);        
            }
        }

    }else{

            //clean filter data inside section
        $filter_session_data['search_string_selected'] = null;
        $filter_session_data['search_field_selected'] = null;
        $filter_session_data['order'] = null;
        $filter_session_data['order_type'] = null;
        $this->session->set_userdata($filter_session_data);

            //pre selected options
        $data['search_string_selected'] = '';
        $data['search_field'] = '';
        $data['order'] = 'id';

            //fetch sql data into arrays
        $data['count_users']= $this->users_model->count_users();
        $data['users'] = $this->users_model->get_users('','', '', $order_type, $config['per_page'],$limit_end);        
        $config['total_rows'] = $data['count_users'];

        }//!isset($search_string) && !isset($order)

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'staffs/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function add()
    {
        if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') === 'POST')
        {
            $data['message'] = '';
            $output['status'] = 'failed';

            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('user_name', 'Username', 'trim|required');
            $this->form_validation->set_rules('pass_word', 'Password', 'trim|required');
            $this->form_validation->set_rules('pass_word_confirm', 'Password Confirm', 'trim|required|matches[pass_word]');
            $this->form_validation->set_rules('email_address', 'Email Address', 'trim|valid_email');
            $this->form_validation->set_rules('user_level', 'User Level', 'trim|integer');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'user_name' => $this->input->post('user_name'),
                    'email_address' => $this->input->post('email_address'),
                    'status' => 1,
                    'user_level' => $this->input->post('user_level')
                    );
                $this->load->library('email');
                $config['protocol'] = 'mail';
                $this->email->initialize($config);

                $content_to_mail = 'A new user is created at ';
                $content_to_mail .= 'Your new password is ';
                $content_to_mail .= $this->input->post('pass_word');

                $data_to_store['pass_word'] = hash('sha256',"\$oar/\/\orro\/\/".$this->input->post('pass_word')."E/\/CrIpTiOn");

                if($this->users_model->store_user($data_to_store)){

                    $this->email->from('localhost.in', 'Local host backend');
                    $this->email->to($this->input->post('email_address')); 

                    $this->email->subject('New User Details');
                    $this->email->message($content_to_mail);
                    $this->email->send();

                    $data['flash_message'] = TRUE; 
                    $data['message'] = 'user added successfully';
                    $output['status'] = 'added';
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }
            $output['html'] = $this->load->view('includes/ajax_form', $data ,true);
            echo json_encode($output);

        }
        else{
            $data['main_content'] = 'staffs/add';
            $this->load->view('includes/template', $data);  
        }
    }       

    /**
    * Update item by his id
    * @return void
    */
    public function update()
    {
        //product id 
        $id = $this->uri->segment(3);

        if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') === 'POST')
        {
            $data['message'] = '';
            $output['status'] = 'failed';

            $prev = $this->users_model->get_user_by_id($id);
            
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('user_name', 'Username', 'trim|required');
            $this->form_validation->set_rules('email_address', 'Email Address', 'trim|valid_email');
            $this->form_validation->set_rules('user_level', 'User Level', 'trim|integer');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            if($this->input->post('email_address') != $prev['email_address'])
                $this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|is_unique[users.email_address]');
            if($this->input->post('user_name') != $prev['user_name'])
                $this->form_validation->set_rules('user_name', 'Username', 'trim|required');

            
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {

                $data_to_store = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'user_name' => $this->input->post('user_name'),
                    'email_address' => $this->input->post('email_address'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'user_level' => $this->input->post('user_level'),
                    'updated_at' => date('Y-m-d H:i:s')
                    );
                $this->load->library('email');
                $config['protocol'] = 'mail';
                $this->email->initialize($config);

                $content_to_mail = 'User is updated ';
                $content_to_mail .= 'Your new password is ';
                $content_to_mail .= $this->input->post('pass_word');

                if($this->users_model->update_user($id, $data_to_store) == TRUE){

                    $this->email->from('localhost.in', 'Local host backend');
                    $this->email->to($this->input->post('email_address')); 

                    $this->email->subject('User Details Updated');
                    $this->email->message($content_to_mail);
                    $this->email->send();

                    $data['flash_message'] = TRUE; 
                    $data['message'] = 'user updated successfully';
                    $output['status'] = 'updated';

                }else{

                    $data['flash_message'] = FALSE;

                }
            }
            $output['html'] = $this->load->view('includes/ajax_form', $data ,true);
            echo json_encode($output);

        }
        else{

            $data['user'] = $this->users_model->get_user_by_id($id);

            $data['main_content'] = 'staffs/edit';
            $this->load->view('includes/template', $data);     
        }       

    }//update

    /**
    * Delete product by his id
    * @return void
    */
    public function delete()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $id = $this->input->post('id');
            $id = explode(',', $id);
            $this->users_model->delete_users($id);
        }
        redirect('staffs');
    }//edit

    public function change()
    {
        $id = $this->uri->segment(3);
        $this->users_model->change_user_status($id);
        redirect('staffs');
    }

}