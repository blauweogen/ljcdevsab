<?php
/**
 * Sign-up Sheets Mail Class
 */

if (!class_exists('DLS_SUS_Data')) require_once dirname(__FILE__).'/data.php';

class DLS_SUS_Mail
{
    
    private $data;
    public $detailed_errors = false;
    public $default_subjects = array(
        'signup' => 'Thank you for signing up!',
        'remove' => 'Sign-up has been removed',
        'reminder' => 'Sign-up Reminder',
        'status' => 'Sign-up Status Report',
    );
    public $plain_blogname;
    
    public function __construct()
    {
        $this->data = new DLS_SUS_Data();
        
        if (get_option('dls_sus_detailed_errors') === 'true') {
            $this->detailed_errors = true;
            $this->data->detailed_errors = true;
        }

        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $this->plain_blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    }
    
    /**
     * Send email when user signs up
     * 
     * @param string $to
     * @param object|int $task both task object and task id are accepted
     * @param object|int $signup both signup object and signup id are accepted
     * @param string $type of email (signup, remove)
     * @throws DLS_SUS_Data_Exception
     * @return bool
     */
    public function send_mail($to, $task, $signup, $type='signup')
    {
        if (!is_object($task)) $task = $this->data->get_task($task);
        $sheet = new DLS_SUS_Sheet($task->sheet_id);
        if (!is_object($signup)) $signup = $this->data->get_signup($signup);
        
        $from = get_option('dls_sus_email_from');
        if (empty($from)) $from = get_bloginfo('admin_email');

        // Build BCC
        $bcc = explode(',', get_option('dls_sus_email_bcc'));
        $sheet_bcc = !empty($sheet->fields['sheet_bcc']) ? explode(',', $sheet->fields['sheet_bcc']) : array();
        $bcc = array_merge($bcc, $sheet_bcc);
        $bcc = implode(', ', $bcc);

        $headers = array();
        $headers[] = "From: {$this->plain_blogname} <$from>";
        $headers[] = "Reply-To: $from";

        switch ($type) {
            case 'signup':
                if (!empty($bcc)) $headers[] = "BCC: $bcc";
                $subject = get_option('dls_sus_email_subject');
                $message = get_option('dls_sus_email_message');
                break;
            case 'remove':
                if (!empty($bcc)) $headers[] = "BCC: $bcc";
                $message = "Your sign-up (detailed below) has been removed.\n\n".
                    "{signup_details}\n\n".
                    "Thanks,\n".
                    "{$this->plain_blogname}\n".
                    get_bloginfo('url');
                break;
            default:
                throw new DLS_SUS_Data_Exception(__('Email type not valid.'). (($this->detailed_errors === true) ? '.. Input type was "'.$type.'"' : ''));
        }

        $args = array(
            'sheet' => $sheet,
            'task' => $task,
            'signup' => $signup,
            'from' => $from,
        );
        $message = $this->_add_dynamic_content($message, $args);
        
        if (empty($subject)) $subject = $this->default_subjects[$type];

        $result = wp_mail($to, $subject, $message, $headers);
        
        if (!$result) {
            global $ts_mail_errors;
            global $phpmailer;
            if (!isset($ts_mail_errors)) $ts_mail_errors = array();
            if (isset($phpmailer)) $ts_mail_errors[] = $phpmailer->ErrorInfo;
            throw new DLS_SUS_Data_Exception(__('Error sending email.'). (($this->detailed_errors === true) ? '.. '.implode(' --- ', $ts_mail_errors) : ''));
        }

        // Send Status Email
        if (
            get_option('dls_sus_status_email') === 'true'
            && ($type === 'signup' || $type === 'remove')
        ) {
            $this->send_status_email($sheet);
        }

        return $result;
    }

    private function _add_dynamic_content($message, $args)
    {
        // Set sign-up date
        $signup_date = null;
        if ($args['sheet']->fields['use_task_dates'] === 'true' && $args['task']->date !== '0000-00-00') {
            $signup_date = $args['task']->date;
        } elseif ($args['sheet']->date != '0000-00-00') {
            $signup_date = $args['sheet']->date;
        }

        $signup_details = (!empty($signup_date) ? "Date: ".date(get_option('date_format'), strtotime($signup_date))."\n" : null)."Event: {$args['sheet']->title}\n{$this->data->text['task_title_label']['value']}: {$args['task']->title}";
        //TODO TODO: custom task fields
        if (!empty($args['sheet']->custom_fields['task'])) {
            foreach ($args['sheet']->custom_fields['task'] as $field) {
                $slug = str_replace('-', '_', $field['slug']);
                $display_value = (is_array($args['task']->fields[$slug])) ? implode(', ', $args['task']->fields[$slug]) : $args['task']->fields[$slug];
                $signup_details .= "\n".$field['name'].': '.$display_value;
            }
            reset($args['sheet']->custom_fields['task']);
        }


        // Replace
        $message = str_replace('{signup_details}', $signup_details, $message);
        $message = str_replace('{from_email}', $args['from'], $message);
        $message = str_replace('{site_name}', $this->plain_blogname, $message);
        $message = str_replace('{site_url}', get_bloginfo('url'), $message);
        $message = str_replace('{removal_link}', plugins_url('remove.php', __FILE__)."?id={$args['signup']->id}&t={$args['signup']->removal_token}", $message);
        $message = str_replace('{signup_firstname}', $args['signup']->firstname, $message);
        $message = str_replace('{signup_lastname}', $args['signup']->lastname, $message);
        $message = str_replace('{signup_email}', $args['signup']->email, $message);
        return $message;
    }
    
    /**
     * Send reminder email
     * 
     * @param object $signup
     * @return bool Whether the email contents were sent successfully.
     */
    public function send_reminder_email($signup)
    {
        $task = $this->data->get_task($signup->task_id);
        $sheet = new DLS_SUS_Sheet($task->sheet_id);
        $subject = get_option('dls_sus_reminder_email_subject');
        if (empty($subject)) $subject = $this->default_subjects['reminder'];
        $to = $signup->email;
        $from = get_option('dls_sus_reminder_email_from');
        if (empty($from)) $from = get_bloginfo('admin_email');
        $bcc = get_option('dls_sus_reminder_email_bcc');
        
        $headers = array();
        $headers[] = "From: {$this->plain_blogname} <$from>";
        $headers[] = "Reply-To: $from";
        if (!empty($bcc)) $headers[] = "BCC: $bcc";

        $args = array(
            'sheet' => $sheet,
            'task' => $task,
            'signup' => $signup,
            'from' => $from,
        );
        $message = $this->_add_dynamic_content(get_option('dls_sus_reminder_email_message'), $args);
            
        return wp_mail($to, $subject, $message, $headers);
    }

    /**
     * Send status email
     *
     * @param object $sheet
     * @return bool Whether the email contents were sent successfully.
     */
    public function send_status_email($sheet)
    {
        $subject = get_option('dls_sus_status_email_subject');
        if (empty($subject)) $subject = $this->default_subjects['status'];

        $from = get_option('dls_sus_status_email_from');
        if (empty($from)) $from = get_bloginfo('admin_email');

        $headers = array();
        $headers[] = "Content-type: text/html";
        $headers[] = "From: {$this->plain_blogname} <$from>";
        $headers[] = "Reply-To: $from";

        // Build recipients
        $to_admin = (get_option('dls_sus_status_to_admin') === 'true') ? array(get_bloginfo('admin_email')) : array();
        $sheet_bcc = !empty($sheet->fields['sheet_bcc']) ? explode(',', $sheet->fields['sheet_bcc']) : array();
        $to = array_merge($to_admin, $sheet_bcc);

        // Tasks
        $message ="<p><strong>Event: {$sheet->title}</strong></p>";
        if (!($tasks = $this->data->get_tasks($sheet->id))) {
            $message .= '<p>No tasks were found.</p>';
        } else {
            $message .= '
                <table class="dls-sus-tasks" cellspacing="0" cellpadding="5" border="1">
                    <thead>
                        <tr>
                            <th>'.$this->data->text['task_title_label']['value'].'</th>
                            <th>Available Spots</th>
                        </tr>
                    </thead>
                    <tbody>
                    ';
                    foreach ($tasks AS $task) {
                        $message .= '
                            <tr>
                                <td valign="top">'.$task->title.'</td>
                                <td valign="top">
                                ';

                                $i=1;
                                $signups = $this->data->get_signups($task->id);
                                foreach ($signups AS $signup) {
                                    if ($i != 1) $message .= '<br />';
                                    $message .= '#'.$i.': <em>';
                                    $message .= $signup->firstname.' '.$signup->lastname;
                                    $message .= ' &lt;'.$signup->email.'&gt;';
                                    $message .= '</em>';
                                    $i++;
                                }
                                for ($i=$i; $i<=$task->qty; $i++) {
                                    if ($i != 1) $message .= '<br />';
                                    $message .= '#'.$i.': ____________________';
                                }

                                $message .= '
                                </td>
                            </tr>
                        ';
                    }
                    $message .= '
                    </tbody>
                </table>
            ';
        }

        return wp_mail($to, $subject, $message, $headers);
    }
    
}