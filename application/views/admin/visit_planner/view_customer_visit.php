<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= _l('customer_visit_short') ?> - <?=  sprintf("%06d", $customer_visit['id']); ?></title>
    <link rel="shortcut icon" id="favicon" href="<?= base_url() ?>uploads/company/favicon.png">
    <link rel="apple-touch-icon”" id="favicon-apple-touch-icon" href="<?= base_url() ?>uploads/company/favicon.png">
    <link rel="stylesheet" type="text/css" id="reset-css" href="<?= base_url() ?>assets/css/reset.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="bootstrap-css" href="<?= base_url() ?>assets/plugins/bootstrap/css/bootstrap.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="inter-font" href="<?= base_url() ?>assets/plugins/inter/inter.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="datatables-css" href="<?= base_url() ?>assets/plugins/datatables/datatables.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="fontawesome-css" href="<?= base_url() ?>assets/plugins/font-awesome/css/fontawesome.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="fontawesome-brands" href="<?= base_url() ?>assets/plugins/font-awesome/css/brands.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="fontawesome-solid" href="<?= base_url() ?>assets/plugins/font-awesome/css/solid.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="fontawesome-regular" href="<?= base_url() ?>assets/plugins/font-awesome/css/regular.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="datetimepicker-css" href="<?= base_url() ?>assets/plugins/datetimepicker/jquery.datetimepicker.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="bootstrap-select-css" href="<?= base_url() ?>assets/plugins/bootstrap-select/css/bootstrap-select.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="lightbox-css" href="<?= base_url() ?>assets/plugins/lightbox/css/lightbox.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="colorpicker-css" href="<?= base_url() ?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="tailwind-css" href="<?= base_url() ?>assets/builds/tailwind.css?v=3.1.1">
    <link rel="stylesheet" type="text/css" id="theme-css" href="<?= base_url() ?>assets/themes/perfex/css/style.min.css?v=3.1.1">
    <script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
    <meta name="robots" content="noindex">
</head>
<body class="customers chrome viewinvoice">
<div id="wrapper">
    <div id="content">
        <div class="container">
            <div class="row">
            </div>
        </div>
        <div class="container">
            <div class="row">

                <div class="mtop15 preview-top-wrapper">
                    <!--<div class="row">
                        <div class="col-md-3">
                            <div class="mbot30">
                                <div class="invoice-html-logo">
                                    <a href="http://localhost/crm/" class="logo img-responsive">
                                        <img src="http://localhost/crm/uploads/company/4265cd212a6d157191c269c4ac82e0bc.png" class="img-responsive" alt="Free Demo">
                                    </a> </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>-->
                    <div class="top" data-sticky="" data-sticky-class="preview-sticky-header" style="">
                        <div class="container preview-sticky-container">
                            <div class="sm:tw-flex tw-justify-between -tw-mx-4">
                                <div class="sm:tw-self-end">
                                    <h3 class="bold tw-my-0 invoice-html-number">
                                        <span class="sticky-visible hide tw-mb-2">
                                            <?= _l('customer_visit_short') ?> - <?=  sprintf("%06d", $customer_visit['id']); ?>
                                        </span>
                                    </h3>
                                </div>
                                <div class="tw-flex tw-items-end tw-space-x-2 tw-mt-3 sm:tw-mt-0">
                                    <button onclick="download_pdf()" class="btn btn-default action-button">
                                        <i class="fa-regular fa-file-pdf"></i>
                                        Download </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="panel_s tw-mt-6" id="DownloadPdf">
                    <div class="panel-body">
                        <div class="col-md-11 col-md-offset-1" style="margin-left: 4% !important;">
                            <div class="row mtop20">
                                <div class="col-md-6 col-sm-6" style="float:right;">
                                    <h4 class="tw-font-semibold tw-text-neutral-700 invoice-html-number text-right">
                                        <?= _l('customer_visit_short') ?> - <?=  sprintf("%06d", $customer_visit['id']); ?>
                                    </h4>
                                    <address class="invoice-html-company-info tw-text-neutral-500 tw-text-normal text-right">
                                        <?php
                                        $country = $this->db->where('country_id',$customer_visit['visit_planner_country'])->get('tblcountries')->row_array();
                                        if(!empty($customer_visit['contacts'])){
                                            $contact = explode(',',$customer_visit['contacts']);
                                            $conts_d = [];
                                            for($i=0;$i<count($contact);$i++){
                                                $cont = $this->db->where('id',$contact[$i])->get('tblcontacts')->row_array();
                                                $conts_d[$i] = $cont['firstname'].' '.$cont['lastname'].'('.$cont['phonenumber'].')';
                                            }
                                            $details = implode(',',$conts_d);
                                        }else{
                                            $details = '';
                                        }
                                        ?>
                                        <b style="color:black" class="company-name-formatted text-right"><?= $visit_planner['visit_planner_to'] ?></b><br>
                                        <div style="color:black" class="company-name-formatted text-right"><?= $visit_planner['visit_planner_company_name'] ?></div>
                                        <div style="color:black" class="company-name-formatted text-right"><?= $details ?></div>
                                        <?= $visit_planner['visit_planner_address'] ?><br> <?= $visit_planner['visit_planner_city'] ?>&nbsp;<?= $visit_planner['visit_planner_state'] ?><br><?= $country['short_name'] ?>&nbsp;<?= $visit_planner['visit_planner_zip'] ?>
                                        <div style="color:black" class="company-name-formatted text-right"><?= date('Y-m-d h:i A',strtotime($customer_visit['datetime'])); ?></div>
                                    </address>
                                </div>
                                <div class="col-md-5">
                                    <div class="invoice-html-logo">
                                        <?php
                                        $company_logo = get_option('company_logo' . ($type == 'dark' ? '_dark' : ''));
                                        $company_name = get_option('companyname');
                                        ?>
                                        <a href="<?= base_url() ?>" class="logo img-responsive">
                                            <img src="<?= base_url('uploads/company/' . $company_logo) ?>" class="img-responsive" alt="Amaze Group">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop enquiry_form" style="border : 1px solid black;">
                                            <tr>
                                                <td style="border: 1px solid black;"><label for="end_product" class="control-label"><?php echo _l('visit_planner_end_product'); ?></label></td>
                                                <td colspan="2" style="border: 1px solid black;">
                                                    <?php $end_product = $this->db->select(['id','description'])->where_in('id', $customer_visit['end_product'])->get('tblitems')->row_array(); ?>
                                                    <?= $end_product['description'] ?>
                                                </td>
                                                <td colspan="4" rowspan="7"  class="text-center" style="border: 1px solid black;">
                                                    <img class="img img-responsive" src="<?php echo base_url('assets/images/pdf_diagaram.png') ?>" style="margin-left: 50px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="total_no_of_panels" class="control-label"><?php echo _l('visit_planner_total_no_of_panels'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['total_no_of_panels'] ?>&nbsp;<?php echo _l('visit_planner_no'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><label for="month_prod" class="control-label"><?php echo _l('visit_planner_month_prod'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['month_prod'] ?>&nbsp;<?php echo _l('visit_planner_lmt'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><label for="clientid" class="control-label"><?php echo _l('visit_planner_sintering_panel'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['sintering_panel'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><label for="kw_rating_sp" class="control-label"><?php echo _l('visit_planner_kw_rating_sp'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['kw_rating_sp'] ?>&nbsp;<?php echo _l('visit_planner_kw'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><label for="frequency" class="control-label"><?php echo _l('visit_planner_frequency'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['frequency'] ?>&nbsp;<?php echo _l('visit_planner_Hz'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><label for="asbestos" class="control-label"><?php echo _l('visit_planner_asbestos'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['asbestos'] ?>&nbsp;<?php echo _l('visit_planner_after_coil_coat'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><label for="maximum_tapping_temperature" class="control-label"><?php echo _l('visit_planner_maximum_tapping_temperature'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['maximum_tapping_temperature'] ?>&nbsp;<?php echo _l('visit_planner_maximum_c'); ?></td>
                                                <td><label for="diagram_a" class="control-label"><?php echo _l('visit_planner_A'); ?></label></td>
                                                <td><?= $customer_visit['diagram_a'] ?></td>
                                                <td><label for="diagram_e" class="control-label"><?php echo _l('visit_planner_E'); ?></label></td>
                                                <td><?= $customer_visit['diagram_e'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><label for="average_tapping_temperature" class="control-label"><?php echo _l('visit_planner_average_tapping_temperature'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['average_tapping_temperature'] ?>&nbsp;<?php echo _l('visit_planner_maximum_c'); ?></td>
                                                <td><label for="diagram_b" class="control-label"><?php echo _l('visit_planner_B'); ?></label></td>
                                                <td><?= $customer_visit['diagram_b'] ?></td>
                                                <td><label for="diagram_f" class="control-label"><?php echo _l('visit_planner_F'); ?></label></td>
                                                <td><?= $customer_visit['diagram_f'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><label for="avg_power_consumption" class="control-label"><?php echo _l('visit_planner_avg_power_consumption'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['avg_power_consumption'] ?>&nbsp;<?php echo _l('visit_planner_kwh'); ?></td>
                                                <td><label for="diagram_c" class="control-label"><?php echo _l('visit_planner_C'); ?></label></td>
                                                <td><?= $customer_visit['diagram_c'] ?></td>
                                                <td><label for="diagram_g" class="control-label"><?php echo _l('visit_planner_G'); ?></label></td>
                                                <td><?= $customer_visit['diagram_g'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><label for="avg_power_consumption" class="control-label"><?php echo _l('visit_planner_grades_of_steel_produce'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['grades_of_steel_produce'] ?></td>
                                                <td><label for="diagram_d" class="control-label"><?php echo _l('visit_planner_D'); ?></label></td>
                                                <td><?= $customer_visit['diagram_d'] ?></td>
                                                <td><label for="diagram_h" class="control-label"><?php echo _l('visit_planner_H'); ?></label></td>
                                                <td><?= $customer_visit['diagram_h'] ?></td>
                                            </tr>
                                            <tr style="border-top : 1px solid ;">
                                                <td><label for="present_lining_method" class="control-label"><?php echo _l('visit_planner_present_lining_method'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['present_lining_method'] ?>&nbsp;<?php echo _l('visit_planner_manual'); ?></td>
                                                <td><label for="type_of_scrap" class="control-label"><?php echo _l('visit_planner_type_of_scrap'); ?></label></td>
                                                <td colspan="3"><?= $customer_visit['planner_type_of_scrap'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><label for="charge_mix_ratio" class="control-label"><?php echo _l('visit_planner_charge_mix_ratio'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['charge_mix_ratio'] ?></td>
                                                <td><label for="cpc" class="control-label"><?php echo _l('visit_planner_cpc'); ?></label></td>
                                                <td colspan="3"><?= $customer_visit['cpc'] ?>&nbsp;<label for="diagram_g" class="control-label"><?php echo _l('visit_planner_kg_heat'); ?></label></td>
                                            </tr>
                                            <tr>
                                                <td><label for="met_cock_or_cock_fines" class="control-label"><?php echo _l('visit_planner_met_cock_or_cock_fines'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['met_cock_or_cock_fines'] ?>&nbsp;<?php echo _l('visit_planner_kg_heat'); ?></td>
                                                <td><label for="slag_generation" class="control-label"><?php echo _l('visit_planner_slag_generation'); ?></label></td>
                                                <td colspan="3"><?= $customer_visit['slag_generation'] ?>&nbsp;<label for="diagram_g" class="control-label"><?php echo _l('visit_planner_kg_heat'); ?></label></td>
                                            </tr>
                                            <tr>
                                                <td><label for="sintering_time" class="control-label"><?php echo _l('visit_planner_sintering_time'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['sintering_time'] ?>&nbsp;<?php echo _l('visit_planner_hrs'); ?></td>
                                                <td><label for="power_consumption_for_sintering_heat" class="control-label"><?php echo _l('visit_planner_power_consumption_for_sintering_heat'); ?></label></td>
                                                <td colspan="3"><?= $customer_visit['power_consumption_for_sintering_heat'] ?>&nbsp;<label for="diagram_g" class="control-label"><?php echo _l('visit_planner_kwh'); ?></label></td>
                                            </tr>
                                            <tr>
                                                <td><label for="c_at_50_of_bath_metal" class="control-label"><?php echo _l('visit_planner_c_at_50_of_bath_metal'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['c_at_50_of_bath_metal'] ?>&nbsp;%</td>
                                                <td><label for="dri_quality" class="control-label"><?php echo _l('visit_planner_dri_quality'); ?></label></td>
                                                <td colspan="3"><?= $customer_visit['dri_quality'] ?>&nbsp;<label for="diagram_g" class="control-label"><?php echo _l('visit_planner_ratio'); ?></label></td>
                                            </tr>
                                            <tr>
                                                <td><label for="c_at_80_of_bath_metal" class="control-label"><?php echo _l('visit_planner_c_at_80_of_bath_metal'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['c_at_80_of_bath_metal'] ?>&nbsp;%</td>
                                                <td><label for="fem_of_dri" class="control-label"><?php echo _l('visit_planner_fem_of_dri'); ?></label></td>
                                                <td colspan="3"><?= $customer_visit['fem_of_dri'] ?>&nbsp;<label for="diagram_g" class="control-label">%</label></td>
                                            </tr>
                                            <tr>
                                                <td><label for="final_c_before_tap" class="control-label"><?php echo _l('visit_planner_final_c_before_tap'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['final_c_before_tap'] ?>&nbsp;%</td>
                                                <td><label for="fet_of_dri" class="control-label"><?php echo _l('visit_planner_fet_of_dri'); ?></label></td>
                                                <td colspan="3"><?= $customer_visit['fet_of_dri'] ?>&nbsp;<label for="diagram_g" class="control-label">%</label></td>
                                            </tr>
                                            <tr>
                                                <td><label for="mill_scale" class="control-label"><?php echo _l('visit_planner_mill_scale'); ?></label></td>
                                                <td colspan="2"><?= $customer_visit['mill_scale'] ?>&nbsp;<?php echo _l('visit_planner_Kg'); ?></td>
                                                <td><label for="feo_of_dri" class="control-label"><?php echo _l('visit_planner_feo_of_dri'); ?></label></td>
                                                <td colspan="3"><?= $customer_visit['feo_of_dri'] ?>&nbsp;<label for="diagram_g" class="control-label">%</label></td>
                                            </tr>
                                            <tr>
                                                <td><label for="opration" class="control-label"><?php echo _l('visit_planner_opration'); ?></label></td>
                                                <td colspan="6"><?= $customer_visit['operation'] ?></td>
                                            </tr>
                                            <tr style="border-top : 1px solid ;">
                                                <td><label for="specific_problem_customer_face" class="control-label"><?php echo _l('visit_planner_specific_problem_customer_face'); ?></label></td>
                                                <td colspan="6"><?= $customer_visit['specific_problem_customer_face'] ?></td>
                                            </tr>
                                            <tr style="border-top : 1px solid ;">
                                                <td><label for="reason_for_lining_breaking" class="control-label"><?php echo _l('visit_planner_reason_for_lining_breaking'); ?></label></td>
                                                <td colspan="6"><?= $customer_visit['reason_for_lining_breaking'] ?></td>
                                            </tr>
                                            <tr style="border-top : 1px solid ;">
                                                <td><label for="specific_erosion_pattern" class="control-label"><?php echo _l('visit_planner_specific_erosion_pattern'); ?></label></td>
                                                <td colspan="6"><?= $customer_visit['specific_erosion_pattern'] ?></td>
                                            </tr>
                                            <tr style="border-top : 1px solid ;">
                                                <td><label for="customer_expectation" class="control-label"><?php echo _l('visit_planner_customer_expectation'); ?></label></td>
                                                <td colspan="6"><?= $customer_visit['customer_expectation'] ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="table-responsive s_table">
                                        <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop dynamic_table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th align="left"><?php echo _l('visit_planner_furnace_capacity'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_kw_rating'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_Furnace_oem'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_tap_to_tap_time'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_present_lining_material'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_supplier'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_consumtion_for_new_lining'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_consumption_for_side'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_new_lining_life'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_side_lining_life'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_total_no_of_side_lining'); ?></th>
                                                <th align="left"><?php echo _l('visit_planner_total_no_of_heats'); ?></th>
                                                <!-- <th align="center"><i class="fa fa-cog"></i></th>-->
                                            </tr>
                                            </thead>
                                            <tbody class="dynamic_form">
                                            <?php if($customer_visit['show_inquiry_form'] == 'yes'){
                                                if(!empty($customer_visit['total_no_of_panels'])){
                                                    $inquiry = $this->db->where_in('customer_visit_id', $customer_visit['id'])->get('tblcustomer_visit_inquiry')->result_array();
                                                    sort($inquiry);
                                                    $j=0;
                                                    for($i=1;$i<=$customer_visit['total_no_of_panels'];$i++){ ?>
                                                        <tr>
                                                            <td><?= $i ?></td>
                                                            <td><?= $inquiry[$j]['furnace_capacity'] ?></td>
                                                            <td><?= $inquiry[$j]['kw_rating'] ?></td>
                                                            <td><?= $inquiry[$j]['Furnace_oem'] ?></td>
                                                            <td><?= $inquiry[$j]['tap_to_tap_time'] ?></td>
                                                            <td><?= $inquiry[$j]['present_lining_material'] ?></td>
                                                            <td><?= $inquiry[$j]['supplier'] ?></td>
                                                            <td><?= $inquiry[$j]['consumtion_for_new_lining'] ?></td>
                                                            <td><?= $inquiry[$j]['consumption_for_side'] ?></td>
                                                            <td><?= $inquiry[$j]['new_lining_life'] ?></td>
                                                            <td><?= $inquiry[$j]['side_lining_life'] ?></td>
                                                            <td><?= $inquiry[$j]['total_no_of_side_lining'] ?></td>
                                                            <td><?= $inquiry[$j]['total_no_of_heats'] ?></td>
                                                        </tr>
                                                        <?php $j++;
                                                    }
                                                }
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <span class="copyright-footer">2024 Copyright Free Demo</span>
            </div>
        </div>
    </div>
</footer>

<script type="text/javascript" id="bootstrap-js" src="<?= base_url() ?>assets/plugins/bootstrap/js/bootstrap.min.js?v=3.1.1"></script>
<script type="text/javascript" id="datatables-js" src="<?= base_url() ?>assets/plugins/datatables/datatables.min.js?v=3.1.1"></script>
<script type="text/javascript" id="jquery-validation-js" src="<?= base_url() ?>assets/plugins/jquery-validation/jquery.validate.min.js?v=3.1.1"></script>
<script type="text/javascript" id="bootstrap-select-js" src="<?= base_url() ?>assets/builds/bootstrap-select.min.js?v=3.1.1"></script>
<script type="text/javascript" id="datetimepicker-js" src="<?= base_url() ?>assets/plugins/datetimepicker/jquery.datetimepicker.full.min.js?v=3.1.1"></script>
<script type="text/javascript" id="chart-js" src="<?= base_url() ?>assets/plugins/Chart.js/Chart.min.js?v=3.1.1"></script>
<script type="text/javascript" id="colorpicker-js" src="<?= base_url() ?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js?v=3.1.1"></script>
<script type="text/javascript" id="lightbox-js" src="<?= base_url() ?>assets/plugins/lightbox/js/lightbox.min.js?v=3.1.1"></script>
<script type="text/javascript" id="common-js" src="<?= base_url() ?>assets/builds/common.js?v=3.1.1"></script>
<script type="text/javascript" id="theme-global-js" src="<?= base_url() ?>assets/themes/perfex/js/global.min.js?v=3.1.1"></script>
<script type="text/javascript" id="sticky-js" src="<?= base_url() ?>assets/plugins/sticky/sticky.js?v=3.1.1"></script>

<style>
    .enquiry_form tr td{
        border: 1px solid black;
    }
    .dynamic_table tr td{
        border: 1px solid black;
    }
    body, html {
       color: black !important;
    }
    .table>tbody>tr>td, .table>tfoot>tr>td {
        color: #000;
    }
    .control-label, label {
        color: #000;
    }
    .tw-text-neutral-500{
        color:black;
    }
    .table.items>thead:first-child>tr:first-child>th{
        border: 1px solid black;
        color: black;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<script>
    function download_pdf() {
        let no = '<?= _l('visit_planner_short') ?> - <?=  sprintf("%06d", $visit_planner['id']); ?>';
        // $('#DownloadPdf').attr('style','display:show');
        var HTML_Width = $("#DownloadPdf").width();
        var HTML_Height = $("#DownloadPdf").height();
        var top_left_margin = 15;
        var PDF_Width = HTML_Width+(top_left_margin*2);
        var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
        var canvas_image_width = HTML_Width;
        var canvas_image_height = HTML_Height;

        var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
        html2canvas($("#DownloadPdf")[0],{allowTaint:true}).then(function(canvas) {
            canvas.getContext('2d');
            var imgData = canvas.toDataURL("image/jpeg", 1.0);
            var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
            for (var i = 1; i <= totalPDFPages; i++) {
                pdf.addPage(PDF_Width, PDF_Height);
                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
            }
            pdf.save(no+".pdf");
        });
        // $('#DownloadPdf').attr('style','display:none');
    }

</script>