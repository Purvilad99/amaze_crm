<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Visit_Planner extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Visit_planner_model');
        $this->load->model('invoices_model');
    }

    /* Get all invoices in case user go on index page */
    public function index($id = '')
    {
        $this->list_visit_planner($id);
    }

    /* List all invoices datatables */
    public function list_visit_planner($id = '')
    {
        if (staff_cant('view', 'visit_planner')
            && staff_cant('view_own', 'visit_planner')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('visit_planner');
        }

        close_setup_menu();
        $data['title']                = _l('visit_planner');
        $data['visit_plannerid']            = $id;

        $data['visit_planner_data'] = $this->Visit_planner_model->get_all_data();
        $data['session_id'] = $this->session->userdata('staff_user_id');
//        echo "<pre>"; print_r($data['customer_visit']); exit;
        $data['table'] = App_table::find('visit_planner');
        $data['bodyclass']            = 'booking-unfix-total-manual';
        $this->load->view('admin/visit_planner/manage', $data);
    }

    public function table()
    {
        if (staff_cant('view', 'visit_planner') && staff_cant('view_own', 'visit_planner')) {
            ajax_access_denied();
        }

        App_table::find('visit_planner');
    }

    /* Add new booking unfix or update existing */
    public function visit_planner($id = '')
    {
//        echo "<pre>"; print_r($_POST); exit;
        if ($this->input->post()) {
//            echo "<pre>"; print_r($_POST); exit;
            $visit_planner_data = $this->input->post();

            if ($id == '') {
                if (staff_cant('create', 'visit_planner')) {
                    access_denied('visit_planner');
                }
                $id = $this->Visit_planner_model->add_visit_planner($visit_planner_data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('visit_planner')));
                    redirect(admin_url('Visit_Planner/list_visit_planner/' . $id));
                }
            } else {
                if (staff_cant('edit', 'visit_planner')) {
                    access_denied('visit_planner');
                }

                $success = $this->Visit_planner_model->update_visit_planner($id,$visit_planner_data);

                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('visit_planner')));
                }

                redirect(admin_url('Visit_Planner/list_visit_planner/' . $id));
            }
        }
        if ($id == '') {
            $title           = _l('create_new_visit_planner');
        } else {
            $visit_planner = $this->Visit_planner_model->get($id);
            $title           = _l('edit_new_visit_planner');
            $data['visit_planner']        = $visit_planner;
            $data['edit']           = true;
        }

        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

        $data['items'] =  $this->db->select(['id','description'])->get('tblitems')->result_array();

        $data['title']     = $title;
        $data['bodyclass'] = 'visit_planner';
        $this->load->view('admin/visit_planner/visit_planner', $data);
    }

    public function fetch_lead(){
        $response['lead'] = $this->db->where_in('id', $_POST['leadid'])->get('tblleads')->row_array();
        echo json_encode($response);
    }

    public function fetch_customer(){
        $response['customer'] = $this->db->where_in('userid', $_POST['clientid'])->get('tblclients')->row_array();
        $response['contact'] = $this->db->where_in('userid', $_POST['clientid'])->where_in('is_primary','1')->get('tblcontacts')->row_array();
        echo json_encode($response);
    }

    public function getContactByClient()
    {
        $client_id = $this->input->post('client');
        $contacts = $this->Visit_planner_model->getContactByClient($client_id);
        echo json_encode($contacts);
    }

    public function delete($id)
    {
        if (staff_cant('delete', 'visit_planner')) {
            access_denied('visit_planner');
        }

        $success = $this->Visit_planner_model->delete($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('visit_planner')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('visit_planner')));
        }
        redirect(admin_url('Visit_Planner'));
//        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
    }

    public function list_customer_visit($vid = '')
    {
        if (staff_cant('view', 'customer_visit')
            && staff_cant('view_own', 'customer_visit')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('customer_visit');
        }

        close_setup_menu();
        $data['title']                = _l('customer_visit');
        $data['visit_planner_id']            = $vid;

        $data['customer_visit_data'] = $this->Visit_planner_model->get_all_customer_data($vid);
//        echo "<pre>"; print_r($data['customer_visit_data']); exit;
        $data['session_id'] = $this->session->userdata('staff_user_id');

        $data['table'] = App_table::find('customer_visit');
        $data['bodyclass']            = 'booking-unfix-total-manual';
        $this->load->view('admin/visit_planner/customer_manage', $data);
    }

    public function cvtable()
    {
        if (staff_cant('view', 'customer_visit') && staff_cant('view_own', 'customer_visit')) {
            ajax_access_denied();
        }

        App_table::find('customer_visit');
    }

    public function customer_visit($vid='',$id = '')
    {
        if ($this->input->post()) {
//            echo "<pre>"; print_r($_POST); exit;
            $customer_visit_data = $this->input->post();
            if ($id == '') {

                if (staff_cant('create', 'customer_visit')) {
                    access_denied('customer_visit');
                }
                $customer_visit_data['file'] = $_FILES['file']['name'];
                $response = $this->Visit_planner_model->add_customer_visit($customer_visit_data);
                if ($response) {
                    //for file
                    $mydirectory = './assets/images/customer_visit/'.$response.'/';
                    $image_dir = $mydirectory;
                    if (!is_dir($image_dir)) {
                        mkdir($image_dir,0777,TRUE);
                    }
                    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && count(array_filter($_FILES['file']['name'])) > 0) {
                        $fileCount = count($_FILES['file']['name']);
                        for($i = 0;$i < $fileCount;$i++){
                            $tmp_name = $_FILES['file']['tmp_name'][$i];
                            $name = $_FILES['file']['name'][$i];
                            $uploads_dir = $image_dir;
                            $path_filename = $uploads_dir.$name;
                            move_uploaded_file($tmp_name, $path_filename);
                        }
                    }
                    set_alert('success', _l('added_successfully', _l('customer_visit')));
                    redirect(admin_url('Visit_Planner/list_customer_visit/' . $vid));
                }
            } else {
                if (staff_cant('edit', 'customer_visit')) {
                    access_denied('customer_visit');
                }
//                echo "<pre>"; print_r($booking_unfix_data); exit;
                //unlink file
                if (!empty($_POST['old_files']) && isset($_POST['old_files'])){
                    for($j=0;$j<count($_POST['old_files']);$j++){
                        $p_id = $this->db->get_where('tblcustomer_visit_attachment', array('customer_visit_id' => $id))->row_array();
                        $mydirectory = './assets/images/customer_visit/'.$id.'/';
                        $img = $mydirectory . $_POST['old_files'][$j];
                        if (file_exists($img)) {
                            unlink($img);
                        }
                    }
                }
                if(!empty($_POST['old_files']) && isset($_POST['old_files'])){
                    for($j=0;$j<count($_POST['old_files']);$j++){
                        $this->Visit_planner_model->delete_file_records($id,$_POST['old_files'][$j]);
                    }
                }
                for($i=0;$i<count($_FILES['file']['name']);$i++){
                    $formArrayImage['customer_visit_id'] = $id;
                    $formArrayImage['file'] = $_FILES['file']['name'][$i];
                    if(!empty($formArrayImage['file'])){
                        $this->Visit_planner_model->update_file_records($id,$formArrayImage);
                    }
                }

//                $visit_planner_data['file'] = $_FILES['file']['name'];
                $success = $this->Visit_planner_model->update_customer_visit($id,$customer_visit_data);

                if ($success) {
                    //for file
                    $mydirectory = './assets/images/customer_visit/'.$id.'/';
                    $image_dir = $mydirectory;
                    if (!is_dir($image_dir)) {
                        mkdir($image_dir,0777,TRUE);
                    }
                    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && count(array_filter($_FILES['file']['name'])) > 0) {
                        $fileCount = count($_FILES['file']['name']);
                        for($i = 0;$i < $fileCount;$i++){
                            $tmp_name = $_FILES['file']['tmp_name'][$i];
                            $name = $_FILES['file']['name'][$i];
                            $uploads_dir = $image_dir;
                            $path_filename = $uploads_dir.$name;
                            move_uploaded_file($tmp_name, $path_filename);
                        }
                    }
                    set_alert('success', _l('updated_successfully', _l('visit_planner')));
                }

                redirect(admin_url('Visit_Planner/list_visit_planner/' . $vid));
            }
        }
        if ($id == '') {
            $title           = _l('create_new_visit_planner');
//            $data['visit_planner']        = $this->db->where_in('id', $vid)->get('tblvisit_planner')->row_array();
        } else {
            $customer_visit = $this->Visit_planner_model->cv_get($id);
            $data['images'] = $this->db->where_in('customer_visit_id', $id)->get('tblcustomer_visit_attachment')->result_array();
            $title           = _l('edit_new_customer_visit');
            $data['customer_visit']        = $customer_visit;
            $data['edit']           = true;
        }

        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

        $data['items'] =  $this->db->select(['id','description'])->get('tblitems')->result_array();
        $data['visit_planner'] =  $this->db->where_in('id', $vid)->get('tblvisit_planner')->row_array();

        $data['visit_planner_id']     = $vid;
        $data['title']     = $title;
        $data['bodyclass'] = 'customer_visit';
        $this->load->view('admin/visit_planner/customer_visit', $data);
    }

    public function view($id){
        $data['customer_visit'] = $this->Visit_planner_model->cv_get($id);
//        echo "<pre>"; print_r($data['customer_visit']); exit;
        $data['visit_planner'] = $this->db->where_in('id', $data['customer_visit']['visit_planner_id'])->get('tblvisit_planner')->row_array();
        $data['inquiry'] = $this->db->where_in('customer_visit_id', $id)->get('tblcustomer_visit_inquiry')->result_array();
        $data['title']     = _l('visit_planner_view_customer_visit');
        $data['bodyclass'] = 'customer_visit';
        $this->load->view('admin/visit_planner/view_customer_visit', $data);
    }

    public function cv_delete($id)
    {
        if (staff_cant('delete', 'customer_visit')) {
            access_denied('customer_visit');
        }

        $success = $this->Visit_planner_model->cv_delete($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('customer_visit')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('customer_visit')));
        }
        redirect(admin_url('Visit_Planner'));
//        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
    }
}
