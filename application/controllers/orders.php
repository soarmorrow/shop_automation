<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class orders extends CI_Controller {

    var $admin;

    /**
     * Responsable for auto load the model
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('orders_model');

        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        } else {
            if ($this->session->userdata('admin'))
                $this->admin = true;
        }
    }

    /**
     * Load the main view with all the current model model's data.
     * @return void
     */
    public function index() {

        //all the posts sent by the view
        $search_string = $this->input->post('search_string');     
        $search_field = $this->input->post('search_field');     
        $order = $this->input->post('order');
        $order_type = $this->input->post('order_type');
        $config['per_page'] = $this->options_model->get_option('results_per_page');
        $config['base_url'] = base_url() . 'orders';
        $config['uri_segment'] = 2;

        //limit end
        $page = $this->uri->segment(2);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0) {
            $limit_end = 0;
        }

        //if order type was changed
        if ($order_type) {
            $filter_session_data['order_type'] = $order_type;
        } else {
            //we have something stored in the session? 
            if ($this->session->userdata('order_type')) {
                $order_type = $this->session->userdata('order_type');
            } else {
                //if we have nothing inside session, so it's the default "Asc"
                $order_type = 'Asc';
            }
        }
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;


        if ($search_string !== false && $search_field !== false && $order !== false || $this->uri->segment(3) == true) {

            if ($search_string) {
                $filter_session_data['search_string_selected'] = $search_string;
            } else {
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            if($search_field){
                $filter_session_data['search_field_selected'] = $search_field;
            }else{
                $search_field = $this->session->userdata('search_field_selected');
            }
            $data['search_field'] = $search_field;

            if ($order) {
                $filter_session_data['order'] = $order;
            } else {
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;

            //save session data into the session
            if (isset($filter_session_data)) {
                $this->session->set_userdata($filter_session_data);
            }


            //fetch sql data into arrays
            $data['count_orders'] = $this->orders_model->count_orders($search_string, $order);
            $config['total_rows'] = $data['count_orders'];

            //fetch sql data into arrays
            if ($search_string) {
                if ($order) {
                    $data['orders'] = $this->orders_model->get_orders($search_string,$search_field, $order, $order_type, $config['per_page'], $limit_end);
                } else {
                    $data['orders'] = $this->orders_model->get_orders($search_string,$search_field, '', $order_type, $config['per_page'], $limit_end);
                }
            } else {
                if ($order) {
                    $data['orders'] = $this->orders_model->get_orders('',$search_field, $order, $order_type, $config['per_page'], $limit_end);
                } else {
                    $data['orders'] = $this->orders_model->get_orders('',$search_field, '', $order_type, $config['per_page'], $limit_end);
                }
            }
        } else {

            //clean filter data inside section
            $filter_session_data['order_selected'] = null;
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['search_field']='';
            $data['order_selected'] = 0;
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['count_orders'] = $this->orders_model->count_orders();
            $data['orders'] = $this->orders_model->get_orders('','', '', $order_type, $config['per_page'], $limit_end);
            $customer = $addon = array();
            $this->load->model('flavours_model');
            $this->load->model('addon_model');
            foreach ($data['orders'] as $key => $order) {
                if(isset($order['add_on_id']) && $order['add_on_id']){
                    $data['orders'][$key]['add_ons'] = array();
                    $addons = $this->addon_model->get_add_on_by_ids(explode(',', $order['add_on_id'])); 
                    foreach ($addons as $value) {
                        array_push($data['orders'][$key]['add_ons'], $value['add_on']);
                    }
                }
            }
        }//!isset($search_string) && !isset($order)
        //initializate the panination helper 
        $this->pagination->initialize($config);

        //load the view
        $data['main_content'] = 'orders/list';
        $this->load->view('includes/template', $data);
    }

//index

    public function add() {
        if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') === 'POST') {
            $data['message'] = '';
            $output['status'] = 'failed';
            $this->form_validation->set_rules('first_name', 'First name', 'trim|required|alpha');
            $this->form_validation->set_rules('last_name', 'Last name', 'trim|required|alpha');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run()) {
                $addon_ids = $this->input->post('addon_id');
                if(isset($addon_ids) && $addon_ids){
                    $addon_id = implode(',', $addon_ids);
                }else{
                    $addon_id = 1;
                }
                $data_to_store = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'address' => $this->input->post('address'),
                    'phone ' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'item_id' => $this->input->post('item_id'),
                    'flavour_id' => $this->input->post('flavour_id'),
                    'add_on_id' => $addon_id,
                    'added_by' => $this->session->userdata('id'),
                    'note' => trim($this->input->post('note')),
                    'customer_status' => 1,
                    'order_status' => 0
                    );

                //if the insert has returned true then we show the flash message
                if ($this->orders_model->store_order($data_to_store)) {

                    $data['flash_message'] = TRUE;
                    $data['message'] = 'order added successfully';
                    $output['status'] = 'added';
                } else {
                    $data['flash_message'] = FALSE;
                }
            }
            $output['html'] = $this->load->view('includes/ajax_form', $data, true);
            echo json_encode($output);
        } else {

            $this->load->model('items_model');
            $this->load->model('flavours_model');
            $this->load->model('addon_model');

            $data['items'] = array();
            $items = $this->items_model->get_active_items();
            foreach ($items as $c) {
                $data['items'][$c['id']] = $c['item_name'];
            }

            $data['flavours'] = array();
            $flavours = $this->flavours_model->get_active_flavours();
            foreach ($flavours as $s) {
                $data['flavours'][$s['id']] = $s['flavour'];
            }
            $data['add_ons'] = array();
            $add_ons = $this->addon_model->get_active_add_ons();
            foreach ($add_ons as $s) {
                $data['add_ons'][$s['id']] = $s['add_on'];
            }
            $data['main_content'] = 'orders/add';
            $this->load->view('includes/template', $data);
        }
    }

    /**
     * Update item by his id
     * @return void
     */
    public function update() {
        //product id 
        $id = $this->uri->segment(3);

        if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') === 'POST') {
            $data['message'] = '';
            $output['status'] = 'failed';

            $this->form_validation->set_rules('first_name', 'First name', 'trim|required|alpha');
            $this->form_validation->set_rules('last_name', 'Last name', 'trim|required|alpha');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            $prev = $this->orders_model->get_order_by_id($id);
            //if the form has passed through the validation
            if ($this->form_validation->run()) {
                $addon_ids = $this->input->post('addon_id');
                if(isset($addon_ids) && $addon_ids){
                    $addon_id = implode(',', $addon_ids);
                }else{
                    $addon_id = 1;
                }
                $data_to_store = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'address' => $this->input->post('address'),
                    'phone ' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'item_id' => $this->input->post('item_id'),
                    'flavour_id' => $this->input->post('flavour_id'),
                    'add_on_id' => $addon_id,
                    'updated_by' => $this->session->userdata('id'),
                    'note' => trim($this->input->post('note')),
                    'customer_status' => $prev['customer_status'],
                    'order_status' => $prev['order_status'],
                    'updated_at' => date('Y-m-d H:i:s')
                    );
                if ($this->orders_model->update_order($id, $data_to_store) == TRUE) {
                    $data['flash_message'] = TRUE;
                    $data['message'] = 'order updated successfully';
                    $output['status'] = 'updated';
                } else {

                    $data['flash_message'] = FALSE;
                }
            }
            $output['html'] = $this->load->view('includes/ajax_form', $data, true);
            echo json_encode($output);
        } else {

            $this->load->model('items_model');
            $this->load->model('flavours_model');
            $this->load->model('addon_model');

            $data['items'] = array();
            $items = $this->items_model->get_active_items();
            foreach ($items as $c) {
                $data['items'][$c['id']] = $c['item_name'];
            }

            $data['flavours'] = array();
            $flavours = $this->flavours_model->get_active_flavours();
            foreach ($flavours as $s) {
                $data['flavours'][$s['id']] = $s['flavour'];
            }
            $data['add_ons'] = array();
            $add_ons = $this->addon_model->get_active_add_ons();
            foreach ($add_ons as $s) {
                $data['add_ons'][$s['id']] = $s['add_on'];
            }

            $data['order'] = $this->orders_model->get_order_by_id($id);

            $data['main_content'] = 'orders/edit';
            $this->load->view('includes/template', $data);
        }
    }

//update

    public function report() {

        //all the posts sent by the view
        $search_string = $this->input->post('search_string');
        $order = $this->input->post('order');
        $order_type = $this->input->post('order_type');

//        $get = $this->input->get(null,true);
//        var_dump($get);exit;
        //pagination settings
        $config['per_page'] = $this->options_model->get_option('results_per_page');
        $config['base_url'] = base_url() . 'orders/report/';
        $config['uri_segment'] = 3;

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0) {
            $limit_end = 0;
        }

        //if order type was changed
        if ($order_type) {
            $filter_session_data['order_type'] = $order_type;
        } else {
            //we have something stored in the session? 
            if ($this->session->userdata('order_type')) {
                $order_type = $this->session->userdata('order_type');
            } else {
                //if we have nothing inside session, so it's the default "Asc"
                $order_type = 'Asc';
            }
        }
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;


        //filtered && || paginated
        if ($search_string !== false && $order !== false || $this->uri->segment(3) == true) {

            if ($search_string) {
                $filter_session_data['search_string_selected'] = $search_string;
            } else {
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            if ($order) {
                $filter_session_data['order'] = $order;
            } else {
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;

            //save session data into the session
            if (isset($filter_session_data)) {
                $this->session->set_userdata($filter_session_data);
            }


            //fetch sql data into arrays
            $data['count_orders'] = $this->orders_model->count_orders($search_string, $order);
            $config['total_rows'] = $data['count_orders'];

            //fetch sql data into arrays
            if ($search_string) {
                if ($order) {
                    $data['orders'] = $this->orders_model->get_orders($search_string, $order, $order_type, $config['per_page'], $limit_end);
                } else {
                    $data['orders'] = $this->orders_model->get_orders($search_string, '', $order_type, $config['per_page'], $limit_end);
                }
            } else {
                if ($order) {
                    $data['orders'] = $this->orders_model->get_orders('', $order, $order_type, $config['per_page'], $limit_end);
                } else {
                    $data['orders'] = $this->orders_model->get_orders('', '', $order_type, $config['per_page'], $limit_end);
                }
            }
        } else {

            //clean filter data inside section
            $filter_session_data['order_selected'] = null;
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['order_selected'] = 0;
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['count_orders'] = $this->orders_model->count_orders();
            $data['orders'] = $this->orders_model->get_orders('', '', $order_type, $config['per_page'], $limit_end);
            $config['total_rows'] = $data['count_orders'];
        }//!isset($search_string) && !isset($order)
        //initializate the panination helper 
        $this->pagination->initialize($config);

        //load the view
        $data['main_content'] = 'orders/report';
        $this->load->view('includes/template', $data);
    }

//index

    public function generate_report() {
        //all the posts sent by the view
        $search_string = $this->input->post('search_string');
        $order = $this->input->post('order');
        $order_type = $this->input->post('order_type');

        //pagination settings
        $config['per_page'] = $this->options_model->get_option('results_per_page');
        $config['base_url'] = base_url() . 'orders/generate_report/';
        $config['uri_segment'] = 3;

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0) {
            $limit_end = 0;
        }

        //if order type was changed
        if ($order_type) {
            $filter_session_data['order_type'] = $order_type;
        } else {
            //we have something stored in the session? 
            if ($this->session->userdata('order_type')) {
                $order_type = $this->session->userdata('order_type');
            } else {
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
        if ($search_string !== false && $order !== false || $this->uri->segment(3) == true) {

            if ($search_string) {
                $filter_session_data['search_string_selected'] = $search_string;
            } else {
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            if ($order) {
                $filter_session_data['order'] = $order;
            } else {
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;

            //save session data into the session
            if (isset($filter_session_data)) {
                $this->session->set_userdata($filter_session_data);
            }


            //fetch sql data into arrays
            $data['count_orders'] = $this->orders_model->count_orders($search_string, $order);
            $config['total_rows'] = $data['count_orders'];

            //fetch sql data into arrays
            if ($search_string) {
                if ($order) {
                    $data['orders'] = $this->orders_model->get_orders($search_string, $order, $order_type, $config['per_page'], $limit_end);
                } else {
                    $data['orders'] = $this->orders_model->get_orders($search_string, '', $order_type, $config['per_page'], $limit_end);
                }
            } else {
                if ($order) {
                    $data['orders'] = $this->orders_model->get_orders('', $order, $order_type, $config['per_page'], $limit_end);
                } else {
                    $data['orders'] = $this->orders_model->get_orders('', '', $order_type, $config['per_page'], $limit_end);
                }
            }
        } else {

            //clean filter data inside section
            $filter_session_data['order_selected'] = null;
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['order_selected'] = 0;
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['count_orders'] = $this->orders_model->count_orders();
            $data['orders'] = $this->orders_model->get_orders('', '', $order_type, $config['per_page'], $limit_end);
            $config['total_rows'] = $data['count_orders'];
        }//!isset($search_string) && !isset($order)
        //initializate the panination helper 
        $this->pagination->initialize($config);

        //load the view
        $data['main_content'] = 'orders/generate_report';
        $this->load->view('includes/generate', $data);
    }

    /**
     * Delete product by his id
     * @return void
     */
    public function delete() {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $id = $this->input->post('id');
            $id = explode(',', $id);
            $this->orders_model->delete_orders($id);
        }
        redirect('orders');
    }

//edit

    public function change()
    {
        $id = $this->uri->segment(4);
        $part = $this->uri->segment(3);
        $this->orders_model->change_status($part,$id);
        redirect('orders');
    }
}
