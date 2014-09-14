<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Items extends CI_Controller {

    var $admin;
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');

        if(!$this->session->userdata('is_logged_in')){
            redirect('login');
        }
        else{
            if($this->session->userdata('admin'))
                $this->admin = true;
        }
    }
 
    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function index()
    {
 
        $search_string = $this->input->post('search_string');        
        $order = $this->input->post('order'); 
        $order_type = $this->input->post('order_type'); 

        //pagination settings
        $config['per_page'] = $this->options_model->get_option('results_per_page');
        $config['base_url'] = base_url().'items';
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
        if( $search_string !== false && $order !== false || $this->uri->segment(3) == true){ 
           

            if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

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
            $data['count_items']= $this->items_model->count_items($search_string, $order);
            $config['total_rows'] = $data['count_items'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['items'] = $this->items_model->get_items( $search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['items'] = $this->items_model->get_items( $search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['items'] = $this->items_model->get_items( '', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['items'] = $this->items_model->get_items('', '', $order_type, $config['per_page'],$limit_end);        
                }
            }

        }else{

            //clean filter data inside section
            $filter_session_data['item_selected'] = null;
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['item_selected'] = 0;
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['count_items']= $this->items_model->count_items();
            $data['items'] = $this->items_model->get_items('', '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['count_items'];

        }//!isset($search_string) && !isset($order)
         
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'items/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function add()
    {
        if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') === 'POST')
        {
            $data['message'] = '';
            $output['status'] = 'failed';

            $this->form_validation->set_rules('name', 'name', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(
                    'item_name' => $this->input->post('name'),
                    'status' => 1
                );

                //if the insert has returned true then we show the flash message
                if($this->items_model->store_item($data_to_store)){
                    
                    $data['flash_message'] = TRUE; 
                    $data['message'] = 'item added successfully';
                    $output['status'] = 'added';
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }
            $output['html'] = $this->load->view('includes/ajax_form', $data ,true);
            echo json_encode($output);

        }
        else{

            $data['main_content'] = 'items/add';
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

            $prev = $this->items_model->get_item_by_id($id);
            
            $this->form_validation->set_rules('name', 'name', 'required');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
    
                $data_to_store = array(
                    'item_name' => $this->input->post('name'),
                    'updated_at' => date('Y-m-d H:i:s')
                );

                if($this->admin)
                    $data_to_store['status'] = $this->input->post('status');

                if($this->items_model->update_item($id, $data_to_store) == TRUE){
                
                    $data['flash_message'] = TRUE; 
                    $data['message'] = 'item updated successfully';
                    $output['status'] = 'updated';

                }else{

                    $data['flash_message'] = FALSE;

                }
            }
            $output['html'] = $this->load->view('includes/ajax_form', $data ,true);
            echo json_encode($output);

        }
        else{

            $data['item'] = $this->items_model->get_item_by_id($id);

            $data['main_content'] = 'items/edit';
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
            $this->items_model->delete_items($id);
        }
        redirect($this->input->post('url'));
    }//edit

}