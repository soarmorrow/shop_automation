<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class report extends CI_Controller {

    var $admin;

    /**
     * Responsable for auto load the model
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('deliveries_model');
    }
//index

    public function generate_report() {
        //all the posts sent by the view
        $search_string = $this->input->post('search_string');
        $order = $this->input->post('order');
        $order_type = $this->input->post('order_type');

        //pagination settings
        $config['per_page'] = $this->options_model->get_option('results_per_page');
        $config['base_url'] = base_url() . 'deliveries/generate_report/';
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
            $data['count_deliveries'] = $this->deliveries_model->count_deliveries($search_string, $order);
            $config['total_rows'] = $data['count_deliveries'];

            //fetch sql data into arrays
            if ($search_string) {
                if ($order) {
                    $data['deliveries'] = $this->deliveries_model->get_deliveries($search_string, $order, $order_type, $config['per_page'], $limit_end);
                } else {
                    $data['deliveries'] = $this->deliveries_model->get_deliveries($search_string, '', $order_type, $config['per_page'], $limit_end);
                }
            } else {
                if ($order) {
                    $data['deliveries'] = $this->deliveries_model->get_deliveries('', $order, $order_type, $config['per_page'], $limit_end);
                } else {
                    $data['deliveries'] = $this->deliveries_model->get_deliveries('', '', $order_type, $config['per_page'], $limit_end);
                }
            }
        } else {

            //clean filter data inside section
            $filter_session_data['delivery_selected'] = null;
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['delivery_selected'] = 0;
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['count_deliveries'] = $this->deliveries_model->count_deliveries();
            $data['deliveries'] = $this->deliveries_model->get_deliveries('', '', $order_type, $config['per_page'], $limit_end);
            $config['total_rows'] = $data['count_deliveries'];
        }//!isset($search_string) && !isset($order)
        //initializate the panination helper 
        $this->pagination->initialize($config);

        //load the view
        $data['main_content'] = 'deliveries/generate_report';
        $this->load->view('includes/generate', $data);
    }

//edit
}
