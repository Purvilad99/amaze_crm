<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Visit_planner_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update_visit_planner($id,$data)
    {
        $i = [];

        $i['subject'] = $data['subject'];
        $i['type'] = $data['type'];
        $i['datetime'] = $data['datetime'];
        $i['rel_type'] = $data['rel_type'];
        $i['leadid'] = $data['leadid'];
        $i['clientid'] = $data['clientid'];
        $i['contacts'] = (!empty($data['contacts'])) ? implode(',',$data['contacts']) : '';
//        $i['location'] = '';
        $i['tags'] = $data['tags'];
//        $i['nxt_followup_date'] = $data['nxt_followup_date'];
        $i['assigned'] = $data['assigned'];
        $i['visit_planner_company_name'] = $data['visit_planner_company_name'];
        $i['visit_planner_gstno'] = $data['visit_planner_gstno'];
        $i['visit_planner_to'] = $data['visit_planner_to'];
        $i['visit_planner_address'] = $data['visit_planner_address'];
        $i['visit_planner_city'] = $data['visit_planner_city'];
        $i['visit_planner_state'] = $data['visit_planner_state'];
        $i['visit_planner_country'] = $data['visit_planner_country'];
        $i['visit_planner_zip'] = $data['visit_planner_zip'];
        $i['visit_planner_email'] = $data['visit_planner_email'];
        $i['visit_planner_phone'] = $data['visit_planner_phone'];

        /*$i['show_inquiry_form'] = $data['show_inquiry_form'];
        $i['end_product'] = $data['end_product'];
        $i['month_prod'] = $data['month_prod'];
        $i['total_no_of_panels'] = $data['total_no_of_panels'];
        $i['sintering_panel'] = $data['sintering_panel'];
        $i['kw_rating_sp'] = $data['kw_rating_sp'];
        $i['frequency'] = $data['frequency'];
        $i['asbestos'] = $data['asbestos'];
        $i['maximum_tapping_temperature'] = $data['maximum_tapping_temperature'];
        $i['diagram_a'] = $data['diagram_a'];
        $i['diagram_e'] = $data['diagram_e'];
        $i['average_tapping_temperature'] = $data['average_tapping_temperature'];
        $i['diagram_b'] = $data['diagram_b'];
        $i['diagram_f'] = $data['diagram_f'];
        $i['avg_power_consumption'] = $data['avg_power_consumption'];
        $i['diagram_c'] = $data['diagram_c'];
        $i['diagram_g'] = $data['diagram_g'];
        $i['grades_of_steel_produce'] = $data['grades_of_steel_produce'];
        $i['diagram_d'] = $data['diagram_d'];
        $i['diagram_h'] = $data['diagram_h'];
        $i['present_lining_method'] = $data['present_lining_method'];
        $i['planner_type_of_scrap'] = $data['planner_type_of_scrap'];
        $i['charge_mix_ratio'] = $data['charge_mix_ratio'];
        $i['cpc'] = $data['cpc'];
        $i['met_cock_or_cock_fines'] = $data['met_cock_or_cock_fines'];
        $i['slag_generation'] = $data['slag_generation'];
        $i['sintering_time'] = $data['sintering_time'];
        $i['power_consumption_for_sintering_heat'] = $data['power_consumption_for_sintering_heat'];
        $i['c_at_50_of_bath_metal'] = $data['c_at_50_of_bath_metal'];
        $i['dri_quality'] = $data['dri_quality'];
        $i['c_at_80_of_bath_metal'] = $data['c_at_80_of_bath_metal'];
        $i['fem_of_dri'] = $data['fem_of_dri'];
        $i['final_c_before_tap'] = $data['final_c_before_tap'];
        $i['fet_of_dri'] = $data['fet_of_dri'];
        $i['mill_scale'] = $data['mill_scale'];
        $i['feo_of_dri'] = $data['feo_of_dri'];
        $i['operation'] = $data['operation'];
        $i['specific_problem_customer_face'] = $data['specific_problem_customer_face'];
        $i['reason_for_lining_breaking'] = $data['reason_for_lining_breaking'];
        $i['specific_erosion_pattern'] = $data['specific_erosion_pattern'];
        $i['customer_expectation'] = $data['customer_expectation'];*/

        $this->db->where('id', $id)->update(db_prefix() . 'visit_planner', $i);

        /*if(!empty($data['total_no_of_panels'])){

            $items = $this->db->where('visit_planner_id',$id)->get('visit_planner_inquiry')->result_array();
            if(!empty($items)){
                $this->db->where('visit_planner_id', $id);
                $this->db->delete(db_prefix() . 'visit_planner_inquiry');
            }

            for($k=1;$k<=$data['total_no_of_panels'];$k++){
                $d['visit_planner_id'] = $id;
                $d['furnace_capacity'] = $data['furnace_capacity_'.$k];
                $d['kw_rating'] = $data['kw_rating_'.$k];
                $d['Furnace_oem'] = $data['Furnace_oem_'.$k];
                $d['tap_to_tap_time'] = $data['tap_to_tap_time_'.$k];
                $d['present_lining_material'] = $data['present_lining_material_'.$k];
                $d['supplier'] = $data['supplier_'.$k];
                $d['consumtion_for_new_lining'] = $data['consumtion_for_new_lining_'.$k];
                $d['consumption_for_side'] = $data['consumption_for_side_'.$k];
                $d['new_lining_life'] = $data['new_lining_life_'.$k];
                $d['side_lining_life'] = $data['side_lining_life_'.$k];
                $d['total_no_of_side_lining'] = $data['total_no_of_side_lining_'.$k];
                $d['total_no_of_heats'] = $data['total_no_of_heats_'.$k];

                $this->db->insert(db_prefix() . 'visit_planner_inquiry', $d);
            }
        }*/

        return true;
    }

    public function delete_file_records($id,$img){
        $this->db->where('visit_planner_id',$id);
        $this->db->where('file',$img);
        $this->db->delete(db_prefix() . 'visit_planner_attachment');
        return true;
    }

    public function update_file_records($id,$formArrayImage){
        $this->db->where('visit_planner_id',$id);
        $this->db->insert(db_prefix() . 'visit_planner_attachment',$formArrayImage);
        return true;
    }

    public function add_visit_planner($data){
        $i['subject'] = $data['subject'];
        $i['type'] = $data['type'];
        $i['datetime'] = $data['datetime'];
        $i['rel_type'] = $data['rel_type'];
        $i['leadid'] = $data['leadid'];
        $i['clientid'] = $data['clientid'];
        $i['contacts'] = (!empty($data['contacts'])) ? implode(',',$data['contacts']) : '';
//        $i['location'] = '';
        $i['tags'] = $data['tags'];
//        $i['nxt_followup_date'] = $data['nxt_followup_date'];
        $i['assigned'] = $data['assigned'];
        $i['visit_planner_company_name'] = $data['visit_planner_company_name'];
        $i['visit_planner_to'] = $data['visit_planner_to'];
        $i['visit_planner_gstno'] = $data['visit_planner_gstno'];
        $i['visit_planner_address'] = $data['visit_planner_address'];
        $i['visit_planner_city'] = $data['visit_planner_city'];
        $i['visit_planner_state'] = $data['visit_planner_state'];
        $i['visit_planner_country'] = $data['visit_planner_country'];
        $i['visit_planner_zip'] = $data['visit_planner_zip'];
        $i['visit_planner_email'] = $data['visit_planner_email'];
        $i['visit_planner_phone'] = $data['visit_planner_phone'];

        /*$i['show_inquiry_form'] = $data['show_inquiry_form'];
        $i['end_product'] = $data['end_product'];
        $i['month_prod'] = $data['month_prod'];
        $i['total_no_of_panels'] = $data['total_no_of_panels'];
        $i['sintering_panel'] = $data['sintering_panel'];
        $i['kw_rating_sp'] = $data['kw_rating_sp'];
        $i['frequency'] = $data['frequency'];
        $i['asbestos'] = $data['asbestos'];
        $i['maximum_tapping_temperature'] = $data['maximum_tapping_temperature'];
        $i['diagram_a'] = $data['diagram_a'];
        $i['diagram_e'] = $data['diagram_e'];
        $i['average_tapping_temperature'] = $data['average_tapping_temperature'];
        $i['diagram_b'] = $data['diagram_b'];
        $i['diagram_f'] = $data['diagram_f'];
        $i['avg_power_consumption'] = $data['avg_power_consumption'];
        $i['diagram_c'] = $data['diagram_c'];
        $i['diagram_g'] = $data['diagram_g'];
        $i['grades_of_steel_produce'] = $data['grades_of_steel_produce'];
        $i['diagram_d'] = $data['diagram_d'];
        $i['diagram_h'] = $data['diagram_h'];
        $i['present_lining_method'] = $data['present_lining_method'];
        $i['planner_type_of_scrap'] = $data['planner_type_of_scrap'];
        $i['charge_mix_ratio'] = $data['charge_mix_ratio'];
        $i['cpc'] = $data['cpc'];
        $i['met_cock_or_cock_fines'] = $data['met_cock_or_cock_fines'];
        $i['slag_generation'] = $data['slag_generation'];
        $i['sintering_time'] = $data['sintering_time'];
        $i['power_consumption_for_sintering_heat'] = $data['power_consumption_for_sintering_heat'];
        $i['c_at_50_of_bath_metal'] = $data['c_at_50_of_bath_metal'];
        $i['dri_quality'] = $data['dri_quality'];
        $i['c_at_80_of_bath_metal'] = $data['c_at_80_of_bath_metal'];
        $i['fem_of_dri'] = $data['fem_of_dri'];
        $i['final_c_before_tap'] = $data['final_c_before_tap'];
        $i['fet_of_dri'] = $data['fet_of_dri'];
        $i['mill_scale'] = $data['mill_scale'];
        $i['feo_of_dri'] = $data['feo_of_dri'];
        $i['operation'] = $data['operation'];
        $i['specific_problem_customer_face'] = $data['specific_problem_customer_face'];
        $i['reason_for_lining_breaking'] = $data['reason_for_lining_breaking'];
        $i['specific_erosion_pattern'] = $data['specific_erosion_pattern'];
        $i['customer_expectation'] = $data['customer_expectation'];*/

        $this->db->insert(db_prefix() . 'visit_planner', $i);
        $insert_id = $this->db->insert_id();

        /*if(!empty($data['total_no_of_panels'])){
            for($k=1;$k<=$data['total_no_of_panels'];$k++){
                $d['visit_planner_id'] = $insert_id;
                $d['furnace_capacity'] = $data['furnace_capacity_'.$k];
                $d['kw_rating'] = $data['kw_rating_'.$k];
                $d['Furnace_oem'] = $data['Furnace_oem_'.$k];
                $d['tap_to_tap_time'] = $data['tap_to_tap_time_'.$k];
                $d['present_lining_material'] = $data['present_lining_material_'.$k];
                $d['supplier'] = $data['supplier_'.$k];
                $d['consumtion_for_new_lining'] = $data['consumtion_for_new_lining_'.$k];
                $d['consumption_for_side'] = $data['consumption_for_side_'.$k];
                $d['new_lining_life'] = $data['new_lining_life_'.$k];
                $d['side_lining_life'] = $data['side_lining_life_'.$k];
                $d['total_no_of_side_lining'] = $data['total_no_of_side_lining_'.$k];
                $d['total_no_of_heats'] = $data['total_no_of_heats_'.$k];

                $this->db->insert(db_prefix() . 'visit_planner_inquiry', $d);
            }
        }*/

        /*if(!empty($data['file'])){
            for($i=0;$i<count($data['file']);$i++){
                $formArrayImage['visit_planner_id'] = $insert_id;
                $formArrayImage['file'] = $data['file'][$i];
                if(!empty( $formArrayImage['file'])){
                    $this->db->insert(db_prefix() . 'visit_planner_attachment', $formArrayImage);
                }
            }
        }*/

        return $insert_id;
    }

    public function delete($id){
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'visit_planner');
        return true;
    }

    public function get($id = '', $where = [])
    {
        $data = $this->db->where('id',$id)->get(db_prefix() . 'visit_planner')->row_array();
        return $data;
    }

    public function get_all_data(){
        $data = $this->db->get(db_prefix() . 'visit_planner')->result();
        return $data;
    }

    public function get_all_customer_data($id){
        $data = $this->db->where('visit_planner_id',$id)->get(db_prefix() . 'customer_visit')->result();
        return $data;
    }

    public function delete_items($id){
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'visit_planner');
        return true;
    }

    function getContactByClient($client_id)
    {
        $this->db->where('userid', $client_id);
        $data = $this->db->get('tblcontacts')->result_array();
        return $data;
    }

    public function add_customer_visit($data){
        $i['visit_planner_id'] = $data['visit_planner_id'];
        $i['subject'] = $data['subject'];
        $i['type'] = $data['type'];
        $i['datetime'] = $data['datetime'];
        $i['rel_type'] = $data['rel_type'];
        $i['leadid'] = $data['leadid'];
        $i['clientid'] = $data['clientid'];
        $i['contacts'] = (!empty($data['contacts'])) ? implode(',',$data['contacts']) : '';
//        $i['location'] = '';
        $i['product_explain'] = $data['product_explain'];
        $i['p_other'] = $data['p_other'];
        $i['opportunity'] = $data['opportunity'];
        $i['o_other'] = $data['o_other'];
        $i['tags'] = $data['tags'];
        $i['nxt_followup_date'] = $data['nxt_followup_date'];

        $i['show_inquiry_form'] = $data['show_inquiry_form'];
        $i['end_product'] = $data['end_product'];
        $i['month_prod'] = $data['month_prod'];
        $i['total_no_of_panels'] = $data['total_no_of_panels'];
        $i['sintering_panel'] = $data['sintering_panel'];
        $i['kw_rating_sp'] = $data['kw_rating_sp'];
        $i['frequency'] = $data['frequency'];
        $i['asbestos'] = $data['asbestos'];
        $i['maximum_tapping_temperature'] = $data['maximum_tapping_temperature'];
        $i['diagram_a'] = $data['diagram_a'];
        $i['diagram_e'] = $data['diagram_e'];
        $i['average_tapping_temperature'] = $data['average_tapping_temperature'];
        $i['diagram_b'] = $data['diagram_b'];
        $i['diagram_f'] = $data['diagram_f'];
        $i['avg_power_consumption'] = $data['avg_power_consumption'];
        $i['diagram_c'] = $data['diagram_c'];
        $i['diagram_g'] = $data['diagram_g'];
        $i['grades_of_steel_produce'] = $data['grades_of_steel_produce'];
        $i['diagram_d'] = $data['diagram_d'];
        $i['diagram_h'] = $data['diagram_h'];
        $i['present_lining_method'] = $data['present_lining_method'];
        $i['planner_type_of_scrap'] = $data['planner_type_of_scrap'];
        $i['charge_mix_ratio'] = $data['charge_mix_ratio'];
        $i['cpc'] = $data['cpc'];
        $i['met_cock_or_cock_fines'] = $data['met_cock_or_cock_fines'];
        $i['slag_generation'] = $data['slag_generation'];
        $i['sintering_time'] = $data['sintering_time'];
        $i['power_consumption_for_sintering_heat'] = $data['power_consumption_for_sintering_heat'];
        $i['c_at_50_of_bath_metal'] = $data['c_at_50_of_bath_metal'];
        $i['dri_quality'] = $data['dri_quality'];
        $i['c_at_80_of_bath_metal'] = $data['c_at_80_of_bath_metal'];
        $i['fem_of_dri'] = $data['fem_of_dri'];
        $i['final_c_before_tap'] = $data['final_c_before_tap'];
        $i['fet_of_dri'] = $data['fet_of_dri'];
        $i['mill_scale'] = $data['mill_scale'];
        $i['feo_of_dri'] = $data['feo_of_dri'];
        $i['operation'] = $data['operation'];
        $i['specific_problem_customer_face'] = $data['specific_problem_customer_face'];
        $i['reason_for_lining_breaking'] = $data['reason_for_lining_breaking'];
        $i['specific_erosion_pattern'] = $data['specific_erosion_pattern'];
        $i['customer_expectation'] = $data['customer_expectation'];

        $this->db->insert(db_prefix() . 'customer_visit', $i);
        $insert_id = $this->db->insert_id();

        if(!empty($data['total_no_of_panels'])){
            for($k=1;$k<=$data['total_no_of_panels'];$k++){
                $d['customer_visit_id'] = $insert_id;
                $d['furnace_capacity'] = $data['furnace_capacity_'.$k];
                $d['kw_rating'] = $data['kw_rating_'.$k];
                $d['Furnace_oem'] = $data['Furnace_oem_'.$k];
                $d['tap_to_tap_time'] = $data['tap_to_tap_time_'.$k];
                $d['present_lining_material'] = $data['present_lining_material_'.$k];
                $d['supplier'] = $data['supplier_'.$k];
                $d['consumtion_for_new_lining'] = $data['consumtion_for_new_lining_'.$k];
                $d['consumption_for_side'] = $data['consumption_for_side_'.$k];
                $d['new_lining_life'] = $data['new_lining_life_'.$k];
                $d['side_lining_life'] = $data['side_lining_life_'.$k];
                $d['total_no_of_side_lining'] = $data['total_no_of_side_lining_'.$k];
                $d['total_no_of_heats'] = $data['total_no_of_heats_'.$k];

                $this->db->insert(db_prefix() . 'customer_visit_inquiry', $d);
            }
        }

        if(!empty($data['file'])){
            for($i=0;$i<count($data['file']);$i++){
                $formArrayImage['customer_visit_id'] = $insert_id;
                $formArrayImage['file'] = $data['file'][$i];
                if(!empty( $formArrayImage['file'])){
                    $this->db->insert(db_prefix() . 'customer_visit_attachment', $formArrayImage);
                }
            }
        }

        return $insert_id;
    }

    public function update_customer_visit($id,$data)
    {
        $i = [];
        $i['visit_planner_id'] = $data['visit_planner_id'];
        $i['subject'] = $data['subject'];
        $i['type'] = $data['type'];
        $i['datetime'] = $data['datetime'];
        $i['rel_type'] = $data['rel_type'];
        $i['leadid'] = $data['leadid'];
        $i['clientid'] = $data['clientid'];
        $i['contacts'] = (!empty($data['contacts'])) ? implode(',',$data['contacts']) : '';
        $i['tags'] = $data['tags'];
        $i['product_explain'] = $data['product_explain'];
        $i['p_other'] = $data['p_other'];
        $i['opportunity'] = $data['opportunity'];
        $i['o_other'] = $data['o_other'];
        $i['nxt_followup_date'] = $data['nxt_followup_date'];

        $i['show_inquiry_form'] = $data['show_inquiry_form'];
        $i['end_product'] = $data['end_product'];
        $i['month_prod'] = $data['month_prod'];
        $i['total_no_of_panels'] = $data['total_no_of_panels'];
        $i['sintering_panel'] = $data['sintering_panel'];
        $i['kw_rating_sp'] = $data['kw_rating_sp'];
        $i['frequency'] = $data['frequency'];
        $i['asbestos'] = $data['asbestos'];
        $i['maximum_tapping_temperature'] = $data['maximum_tapping_temperature'];
        $i['diagram_a'] = $data['diagram_a'];
        $i['diagram_e'] = $data['diagram_e'];
        $i['average_tapping_temperature'] = $data['average_tapping_temperature'];
        $i['diagram_b'] = $data['diagram_b'];
        $i['diagram_f'] = $data['diagram_f'];
        $i['avg_power_consumption'] = $data['avg_power_consumption'];
        $i['diagram_c'] = $data['diagram_c'];
        $i['diagram_g'] = $data['diagram_g'];
        $i['grades_of_steel_produce'] = $data['grades_of_steel_produce'];
        $i['diagram_d'] = $data['diagram_d'];
        $i['diagram_h'] = $data['diagram_h'];
        $i['present_lining_method'] = $data['present_lining_method'];
        $i['planner_type_of_scrap'] = $data['planner_type_of_scrap'];
        $i['charge_mix_ratio'] = $data['charge_mix_ratio'];
        $i['cpc'] = $data['cpc'];
        $i['met_cock_or_cock_fines'] = $data['met_cock_or_cock_fines'];
        $i['slag_generation'] = $data['slag_generation'];
        $i['sintering_time'] = $data['sintering_time'];
        $i['power_consumption_for_sintering_heat'] = $data['power_consumption_for_sintering_heat'];
        $i['c_at_50_of_bath_metal'] = $data['c_at_50_of_bath_metal'];
        $i['dri_quality'] = $data['dri_quality'];
        $i['c_at_80_of_bath_metal'] = $data['c_at_80_of_bath_metal'];
        $i['fem_of_dri'] = $data['fem_of_dri'];
        $i['final_c_before_tap'] = $data['final_c_before_tap'];
        $i['fet_of_dri'] = $data['fet_of_dri'];
        $i['mill_scale'] = $data['mill_scale'];
        $i['feo_of_dri'] = $data['feo_of_dri'];
        $i['operation'] = $data['operation'];
        $i['specific_problem_customer_face'] = $data['specific_problem_customer_face'];
        $i['reason_for_lining_breaking'] = $data['reason_for_lining_breaking'];
        $i['specific_erosion_pattern'] = $data['specific_erosion_pattern'];
        $i['customer_expectation'] = $data['customer_expectation'];

        $this->db->where('id', $id)->update(db_prefix() . 'customer_visit', $i);

        if(!empty($data['total_no_of_panels'])){

            $items = $this->db->where('customer_visit_id',$id)->get('customer_visit_inquiry')->result_array();
            if(!empty($items)){
                $this->db->where('customer_visit_id', $id);
                $this->db->delete(db_prefix() . 'customer_visit_inquiry');
            }

            for($k=1;$k<=$data['total_no_of_panels'];$k++){
                $d['customer_visit_id'] = $id;
                $d['furnace_capacity'] = $data['furnace_capacity_'.$k];
                $d['kw_rating'] = $data['kw_rating_'.$k];
                $d['Furnace_oem'] = $data['Furnace_oem_'.$k];
                $d['tap_to_tap_time'] = $data['tap_to_tap_time_'.$k];
                $d['present_lining_material'] = $data['present_lining_material_'.$k];
                $d['supplier'] = $data['supplier_'.$k];
                $d['consumtion_for_new_lining'] = $data['consumtion_for_new_lining_'.$k];
                $d['consumption_for_side'] = $data['consumption_for_side_'.$k];
                $d['new_lining_life'] = $data['new_lining_life_'.$k];
                $d['side_lining_life'] = $data['side_lining_life_'.$k];
                $d['total_no_of_side_lining'] = $data['total_no_of_side_lining_'.$k];
                $d['total_no_of_heats'] = $data['total_no_of_heats_'.$k];

                $this->db->insert(db_prefix() . 'customer_visit_inquiry', $d);
            }
        }

        return true;
    }

    public function cv_delete($id){
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'customer_visit');

        $this->db->where('customer_visit_id', $id);
        $this->db->delete(db_prefix() . 'customer_visit_inquiry');

        $this->db->where('customer_visit_id', $id);
        $this->db->delete(db_prefix() . 'customer_visit_attachment');
        return true;
    }

    public function cv_get($id = '', $where = [])
    {
        $data = $this->db->where('id',$id)->get(db_prefix() . 'customer_visit')->row_array();
        return $data;
    }
}
?>