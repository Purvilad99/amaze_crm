<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div id="vueApp">
            <div class="row">
                <?php include_once(APPPATH.'views/admin/visit_planner/filter_params.php');
                $data['customer_visit_data'] = $customer_visit_data;
                $data['visit_planner_id'] = $visit_planner_id;
                ?>
                <?php $this->load->view('admin/visit_planner/customer_visit_list_template',$data); ?>
            </div>
        </div>
    </div>
    <!--    --><?php //$this->load->view('admin/includes/footer'); ?>
</div>
<?php //$this->load->view('admin/includes/booking_unfix/sales_attach_file'); ?>
<div id="modal-wrapper"></div>
<!--<script>var hidden_columns = [2,6,7,8];</script>-->
<?php init_tail(); ?>
<script>
    $(function(){
        // init_booking_unfix();
        initDataTable('.table-customer_visit', admin_url + 'Visit_planner/cvtable', undefined, undefined,
            {});
    });
</script>
</body>
</html>