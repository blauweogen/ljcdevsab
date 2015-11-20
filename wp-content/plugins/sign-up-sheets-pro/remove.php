<?php
/**
 * Self Removal
 */
require_once '../../../wp-load.php';

if (!class_exists('DLS_SUS_Data')) require_once dirname(__FILE__).'/data.php';
if (!class_exists('DLS_SUS_Mail')) require_once dirname(__FILE__).'/mail.php';

class DLS_SUS_Remove
{
    
    public $wpdb;
    private $data;
    private $mail;
    
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->data = new DLS_SUS_Data();
        $this->mail = new DLS_SUS_Mail();
    }
    
    /**
     * Remove signup
     */
    public function remove()
    {
        try {
            $signup = $this->data->get_signup($_GET['id']);
            if (empty($signup)) throw new DLS_SUS_Data_Exception('No signup record found.');
            $this->data->check_signup_removal_token($_GET['id'], $_GET['t']);
            $this->data->delete_signup($_GET['id']);
            $this->mail->send_mail($signup->email, $signup->task_id, $signup, 'remove');
            echo __('Your signup has successfully been removed');
        } catch (DLS_SUS_Data_Exception $e) {
            echo __($e->getMessage());
        }
    }
    
}
$dls_sus_remove = new DLS_SUS_Remove();
$dls_sus_remove->remove();
