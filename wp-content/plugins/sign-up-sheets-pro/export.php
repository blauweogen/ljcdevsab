<?php
/**
* Export Sign-up Sheets to a File
*/
require_once(dirname(__FILE__) . '/../../../wp-load.php');

if (!class_exists('DLS_SUS_Data')) require_once dirname(__FILE__).'/data.php';

class DLS_SUS_Export
{
    
    public $wpdb;
    private $data;
    
    public function __construct()
    {
        if (!current_user_can('manage_signup_sheets'))  {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->data = new DLS_SUS_Data();
    }
    
    /**
     * Create export file with data
     */
    public function create()
    {
        $sheet_id = (!empty($_GET['sheet_id'])) ? $_GET['sheet_id'] : null;
        $data = $this->data->get_all_data($sheet_id);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=sign-up-sheets-".date('Ymd-His').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $all_custom_fields = get_option('dls_sus_custom_fields');
        if (!empty($all_custom_fields)) {
            $custom_field_names = array();
            foreach ($all_custom_fields as $field) {
                $custom_field_names[] = $field['name'];
            }
            reset($all_custom_fields);
        }
        $custom_fields_heading = !empty($custom_field_names) ? '"'.implode('","', $custom_field_names).'",' : null;

        $csv = '"Sheet ID","Sheet Title","Sheet Date","Task ID","Task Title","Task Date","Sign-up ID","Sign-up First Name","Sign-up Last Name","Sign-up Phone","Sign-up Email",'.$custom_fields_heading.'"Reminded"'."\n";
        foreach ($data as $d) {
            $csv .= '"' . 
                $this->clean_csv($d->sheet_id) . '","' . 
                $this->clean_csv($d->sheet_title) . '","' .
                $this->clean_csv(((!empty($d->sheet_date) && $d->sheet_date !== '0000-00-00') ? date('Y-m-d', strtotime($d->sheet_date)) : null)) . '","' .
                $this->clean_csv($d->task_id) . '","' . 
                $this->clean_csv($d->task_title) . '","' .
                $this->clean_csv(((!empty($d->task_date) && $d->task_date !== '0000-00-00') ? date('Y-m-d', strtotime($d->task_date)) : null)) . '","' .
                $this->clean_csv($d->signup_id) . '","' . 
                $this->clean_csv($d->firstname) . '","' . 
                $this->clean_csv($d->lastname) . '","' . 
                $this->clean_csv($d->phone) . '","' . 
                $this->clean_csv($d->email) . '","';
                if (!empty($all_custom_fields)) {
                    foreach ($all_custom_fields as $field) {
                        $slug = str_replace('-', '_', $field['slug']);
                        if (!isset($d->signup_fields[$slug])) $d->signup_fields[$slug] = '';
                        $csv .= $this->clean_csv((is_array($d->signup_fields[$slug]) ? implode(', ', $d->signup_fields[$slug]) : $d->signup_fields[$slug])) . '","';
                    }
                }
                $csv .= $this->clean_csv((!empty($d->reminded) ? get_date_from_gmt($d->reminded, 'Y-m-d H:i:s') : null)) . '"' .
            "\n";
        }
        echo $csv;
    }
    
    /**
     * Clean/escape CSV values
     * 
     * @param   string   input value
     * @return  string   cleaned value
     */
    private function clean_csv($value)
    {
        return str_replace('"', '""', $value);
    }
    
}
$e = new DLS_SUS_Export();
$e->create();