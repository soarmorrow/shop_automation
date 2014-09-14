<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Dashboard extends CI_Controller {

    var $admin;
 
    public function __construct()
    {
        parent::__construct();

        if(!$this->session->userdata('is_logged_in')){
            redirect('login');
        }
        else{
            if($this->session->userdata('admin'))
                $this->admin = true;
        }
        

    }
 
    public function index()
    {

        $data['main_content'] = 'dashboard/index';
        $this->load->view('includes/template', $data);  

    }

    function change_password()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('current', 'Password', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[32]');
            $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation

           
            if($this->form_validation->run())
            {

                $this->db->where('user_name', $this->session->userdata('user_name'));
                $this->db->where('pass_word', hash('sha256',"\$oar/\/\orro\/\/".$this->input->post('current')."E/\/CrIpTiOn"));
                $query = $this->db->get('users');

                if($query->num_rows == 0 ){
                    $data['flash_message'] = FALSE; 
                    $data['err_message'] = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>';
                    $data['err_message'] .= "Current Password is incorrect";    
                    $data['err_message'] .= '</strong></div>';
                }
                else
                {
    
                    $data_to_store = array(
                        'password' => $this->input->post('password'),
                    );

                    $this->load->model('users_model');

                    if($this->users_model->change_password($data_to_store) == TRUE){
                        $data['flash_message'] = TRUE; 
                    }else{
                        $data['flash_message'] = FALSE; 
                    }
                }

            }
        }

        $data['main_content'] = 'dashboard/change_password';
        $this->load->view('includes/template', $data);
    }

    public function settings()
    {

        $options = $this->options_model->get_user_options();

        if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') === 'POST')
        {
            $data['message'] = '';
            $output['status'] = 'failed';

            foreach ($options as $option) {
                $this->form_validation->set_rules($option['name'], ucfirst(str_replace('_', ' ',$option['name'])), $option['validation']);
            }

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            
            if ($this->form_validation->run())
            {
                $data_to_store = array();
                foreach ($this->input->post() as $name => $value) {
                    $data = array(
                        'name' => $name,
                        'value' => $value
                        );
                    array_push($data_to_store, $data);
                }

                if($this->options_model->update_options($data_to_store)){
                    $data['flash_message'] = TRUE; 
                    $data['message'] = 'Settings saved successfully';
                    $output['status'] = 'updated';
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }
            $output['html'] = $this->load->view('includes/ajax_form', $data ,true);
            echo json_encode($output);

        }
        else{
            $data['options'] = $options;
            $data['main_content'] = 'dashboard/settings';
            $this->load->view('includes/template', $data);  
        }
    }

}