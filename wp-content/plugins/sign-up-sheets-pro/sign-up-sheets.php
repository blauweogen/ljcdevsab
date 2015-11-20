<?php
/*
Plugin Name: Sign-up Sheets Pro
Plugin URI: http://www.dlssoftwarestudios.com/sign-up-sheets-wordpress-plugin/
Description: An online sign-up sheet manager where your users/volunteers can sign up for tasks
Version: 2.0.18.1
Author: DLS Software Studios
Author URI: http://www.dlssoftwarestudios.com/
*/

if (!class_exists('DLS_SUS_Data')) require_once dirname(__FILE__).'/data.php';
if (!class_exists('DLS_SUS_Mail')) require_once dirname(__FILE__).'/mail.php';
if (!class_exists('DLS_SUS_Admin')) require_once dirname(__FILE__).'/admin.php';
if (!class_exists('DLS_SUS_Sheet')) require_once dirname(__FILE__).'/sheet.php';
if (!class_exists('DLS_Session')) require_once dirname(__FILE__).'/lib/dls-session.php';
if (!class_exists('DLS_SUS_Recaptchalib') && get_option('dls_sus_recaptcha') === 'true') require_once dirname(__FILE__).'/recaptchalib.php';

if (!class_exists('DLS_Sign_Up_Sheet')):

    class DLS_Sign_Up_Sheet
    {

        public $wpdb;
        private $data;
        private $mail;
        private $admin;

        private $plugin_path;
        private $plugin_prefix;
        private $request_uri;
        public $db_version;
        public $remote_update_path = 'https://www.dlssoftwarestudios.com/dls-wp-plugin-updater.php?plugin=sign-up-sheets-pro';
        private $wp_roles;
        public $detailed_errors = false;

        public $shortcode_count = 0;

        // reCAPTCHA
        private $recaptcha;
        private $public_key;
        private $private_key;
        private $recaptcha_error;

        public function __construct()
        {
            global $wpdb;
            $this->wpdb = $wpdb;
            $this->data = new DLS_SUS_Data();
            $this->mail = new DLS_SUS_Mail();
            $this->admin = new DLS_SUS_Admin();
            if (get_option('dls_sus_recaptcha') === 'true') $this->recaptcha = new DLS_SUS_Recaptchalib;
            $this->plugin_prefix = $this->data->plugin_prefix;

            if (get_option('dls_sus_detailed_errors') === 'true') {
                $this->detailed_errors = true;
                $this->data->detailed_errors = true;
            }

            $this->db_version = $this->data->plugin_version;

            $plugin = plugin_basename(__FILE__);
            $this->plugin_path = dirname(__FILE__).'/';
            $this->request_uri = $_SERVER['REQUEST_URI'] . ((strstr($_SERVER['REQUEST_URI'], '?') === false) ? '?' : '&amp;');

            $this->set_default_options();

            // Set reCAPTCHA keys
            if (get_option('dls_sus_recaptcha') === 'true') {
                $this->public_key = get_option('dls_sus_recaptcha_public_key');
                $this->private_key = get_option('dls_sus_recaptcha_private_key');
            }

            add_shortcode('sign_up_sheet', array(&$this, 'display_sheet'));
            register_activation_hook(__FILE__, array(&$this, 'activate'));
            register_deactivation_hook( __FILE__, array(&$this, 'deactivate'));

            add_action('admin_head', array(&$this->admin, 'head'));
            if (isset($_GET['page']) && (strpos($_GET['page'], $this->plugin_prefix)) !== false) {
                add_action('admin_footer', array(&$this->admin, 'footer'));
            }
            add_action('plugins_loaded', array(&$this, 'update_db_check'));
            add_action('wp_enqueue_scripts', array(&$this, 'add_css_and_js_to_frontend'));
            add_action('admin_enqueue_scripts', array(&$this->admin, 'add_scripts'));
            add_action('admin_menu', array(&$this->admin, 'menu'));
            add_action('dls_sus_send_reminders', array(&$this, 'send_reminders'));
            add_filter("plugin_action_links_$plugin", array(&$this->admin, 'settings_link'));
            add_filter('admin_footer_text', array($this->admin, 'admin_footer_text'), 100);
            add_action('init', array(&$this, 'start_session'));
            add_action('wp_logout', array(&$this, 'end_session'));
            add_action('wp_login', array(&$this, 'end_session'));
            add_action('admin_init', array(&$this, 'dup_plugin_check'));

            add_action('init', array(&$this, 'init_updater'));
        }

        /**
         * Initialize the auto updater
         */
        public function init_updater()
        {
            if (!class_exists('DLS_SUS_Plugin_Update')) require_once dirname(__FILE__).'/plugin-update.php';
            new DLS_SUS_Plugin_Update($this->data->plugin_version, $this->remote_update_path, plugin_basename(__FILE__));
        }

        public function set_default_options()
        {
            if (get_option('dls_sus_hide_address') === false) {
                add_option('dls_sus_hide_address', 'true', null, 'yes');
            }
            if (get_option('dls_sus_status_email_schedule') === false) {
                add_option('dls_sus_status_email_schedule', 'on-change', null, 'yes');
            }
            if (get_option('dls_sus_remember') === false) {
                add_option('dls_sus_remember', 'false', null, 'yes');
            }
            if (get_option('dls_sus_email_message') === false) {
                $message = "This message was sent to confirm that you signed up for...\n\n".
                    "{signup_details}\n\n".
                    "To cancel your sign-up use the removal link at the bottom of this email or contact us at {from_email}\n\n".
                    "Thanks,\n".
                    "{site_name}\n".
                    "{site_url}\n\n" .
                    "Removal Link: <{removal_link}>";
                add_option('dls_sus_email_message', $message, null, 'yes');
            }
            if (get_option('dls_sus_reminder_email_message') === false) {
                $message = "This is just a reminder that you signed up for...\n\n".
                    "{signup_details}\n\n".
                    "Thanks,\n".
                    "{site_name}\n".
                    "{site_url}";
                add_option('dls_sus_reminder_email_message', $message, null, 'yes');
            }
            if (get_option('dls_sus_removed_email_message') === false) {
                $message = "Your sign-up (detailed below) has been removed.\n\n".
                    "{signup_details}\n\n".
                    "Thanks,\n".
                    "{site_name}\n".
                    "{site_url}";
                add_option('dls_sus_removed_email_message', $message, null, 'yes');
            }
        }

        /**
         * Output the volunteer signup form
         *
         * @param array @atts attributes from shortcode call
         * @return string
         */
        function display_sheet($atts)
        {
            extract(shortcode_atts(array(
                'id' => false,
                'list_title' => 'Current Sign-up Sheets',
                'category_id' => false,
                'list_title_is_category' => 'false',
            ), $atts));

            $return = null;
            $force_one_sheet = false;
            $show_backlink = false;
            if (!empty($_GET['sheet_id'])) $id = $_GET['sheet_id']; // ID overrides shortcode id if defined
            if (!empty($_GET['sheet_id']) || !empty($_GET['task_id'])) {
                $force_one_sheet = true;
                $show_backlink = true;
            }
            if (!empty($_GET['task_id'])) {
                $task = $this->data->get_task($_GET['task_id']);
                $id = $task->sheet_id;// TODO: get sheet id from task id
            }
            $this->shortcode_count++;

            if ($id === false && $force_one_sheet === false) {
                // Display all active
                if ($category_id !== false && $list_title_is_category === 'true') {
                    $category = $this->data->get_category($category_id);
                    $list_title = $category->title;
                }
                $return = '<h2>'.$list_title.'</h2>';

                if ($category_id === false) {
                    $sheets = $this->data->get_sheets(false, true);
                } else {
                    $sheets = $this->data->get_sheets(false, true, $category_id);
                }

                $sheets = array_reverse($sheets);
                if (empty($sheets)) {
                    $return .= '<p>No sheets available at this time.</p>';
                } else {
                    $return .= '
                    <table class="dls-sus-sheets" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="column-title">Title</th>
                                <th class="column-date">Date</th>
                                <th class="column-open_spots">Open Spots</th>
                                <th class="column-view_link">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                    foreach ($sheets AS $sheet) {
                        $open_spots = ($this->data->get_sheet_total_spots($sheet->id) - $this->data->get_sheet_signup_count($sheet->id));
                        if ($sheet->end_date == '0000-00-00') {
                            $display_date = 'N/A';
                        } else {
                            $display_date = (($sheet->start_date == $sheet->end_date) ? null : date(get_option('date_format'), strtotime($sheet->start_date)).' - ');
                            $display_date .= date(get_option('date_format'), strtotime($sheet->end_date));
                        }
                        $display_date =
                        $return .= '
                                <tr'.(($open_spots === 0) ? ' class="filled"' : '').'>
                                    <td class="column-title"><a href="'.$this->request_uri.'sheet_id='.$sheet->id.'">'.$sheet->title.'</a></td>
                                    <td class="column-date">'.$display_date.'</td>
                                    <td class="column-open_spots">'.$open_spots.'</td>
                                    <td class="column-view_link">'.(($open_spots > 0) ? '<a href="'.$this->request_uri.'sheet_id='.$sheet->id.'">View &amp; sign-up &raquo;</a>' : '&#10004; Filled').'</td>
                                </tr>
                            ';
                    }
                    $return .= '
                        </tbody>
                    </table>
                ';
                }

            } else {

                // Display Individual Sheet

                if ($force_one_sheet && $this->shortcode_count > 1) return null; // Do not process multiple short codes on one page

                $sheet = new DLS_SUS_Sheet($id);
                if (!$sheet->is_valid() || !empty($sheet->trash)) {
                    $return .= '<p>'.__('Sign-up sheet not found.', $this->plugin_prefix).'</p>';
                    return $return;
                } else {

                    if ($show_backlink) $return .= '<p class="dls-sus-backlink"><a href="'.remove_query_arg(array('sheet_id', 'task_id'), $_SERVER['REQUEST_URI']).'">'.__('&laquo; View all', 'dls-sus').'</a></p>';
                    $return .= '
                    <div class="dls-sus-sheet">
                        <h2>'.$sheet->title.'</h2>
                ';

                    $submitted = (isset($_POST['mode']) && $_POST['mode'] == 'submitted');
                    $err = 0;
                    $success = false;

                    // Process Sign-up Form
                    if ($submitted) {

                        // reCAPTCHA
                        $recaptcha_resp = null;
                        $recaptcha_error = null;

                        # was there a reCAPTCHA response?
                        if (isset($_POST["recaptcha_response_field"])) {
                            $resp = $this->recaptcha->recaptcha_check_answer ($this->private_key,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

                            if ($resp->is_valid) {
                                // Valid
                            } else {
                                # set the error code so that we can display it
                                $this->recaptcha_error = $resp->error;
                            }
                        }

                        // Error Handling

                        if (!empty($sheet->custom_fields['signup'])) {
                            $custom_fields_err = 0;
                            foreach ($sheet->custom_fields['signup'] as $field) {
                                $slug = str_replace('-', '_', $field['slug']);
                                $required = (isset($field['required']) && $field['required'] === 'true') ? true : false;
                                if (!$required) continue;
                                if (
                                    !isset($_POST['signup_'.$slug])
                                    || (is_string($_POST['signup_' . $slug]) && trim($_POST['signup_'.$slug]) == '')
                                    || (is_array($_POST['signup_' . $slug]) && empty($_POST['signup_' . $slug]))
                                ) {
                                    $test = trim($_POST['signup_' . $slug]);
                                    $custom_fields_err++;
                                }
                            }
                        }
                        if (
                            empty($_POST['signup_firstname'])
                            || empty($_POST['signup_lastname'])
                            || empty($_POST['signup_email'])
                            || ($this->data->phone_required($sheet) && $this->data->show_phone($sheet) && empty($_POST['signup_phone']))
                            || ($this->data->address_required($sheet) && $this->data->show_address($sheet) && (empty($_POST['signup_address']) || empty($_POST['signup_city']) || empty($_POST['signup_state']) || empty($_POST['signup_zip'])))
                            || (get_option('dls_sus_recaptcha') !== 'true' && empty($_POST['spam_check']))
                            || (get_option('dls_sus_recaptcha') === 'true' && empty($_POST["recaptcha_response_field"]))
                            || !empty($custom_fields_err)
                        ) {
                            $err++;
                            $return .= '<p class="dls-sus error">'.__('Please complete all required fields.', 'dls-sus').'</p>';
                        } elseif (get_option('dls_sus_recaptcha') !== 'true' && (empty($_POST['spam_check']) || (!empty($_POST['spam_check']) && trim($_POST['spam_check']) != '8'))) {
                            $err++;
                            $return .= '<p class="dls-sus error">'.sprintf(__('Oh dear, 7 + 1 does not equal %s. Please try again.', 'dls-sus'), esc_attr($_POST['spam_check'])).'</p>';
                        } elseif (!isset($_POST['double_signup']) && (get_option('dls_sus_recaptcha') === 'true' && $_POST["recaptcha_response_field"])) {
                            $recaptcha_resp = $this->recaptcha->recaptcha_check_answer ($this->private_key, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                            if (!$resp->is_valid) {
                                $recaptcha_error = $recaptcha_resp->error;
                                $err++;
                                $return .= '<p class="dls-sus error">'.__('Captcha not correct. Please try again.', 'dls-sus').'</p>';
                            }
                        }

                        // Add Signup
                        if (!$err) {
                            try {
                                // Check if already signed up for task by email address
                                if (empty($_POST['double_signup']) && $this->data->isEmailOnTask($_POST['signup_email'], $_GET['task_id'])) {
                                    $return .= '
                                    <p class="dls-sus alert">
                                        '.__('You have already signed up for this task.  Do you want to sign up again?', 'dls-sus').'
                                        <form method="post" action="'.$this->data->get_current_url(true).'">
                                            ';
                                    $prefix = 'signup_';
                                    foreach ($_POST as $key=>$value) {
                                        if (is_array($value)) {
                                            foreach ($value as $v)
                                                $return .= '<input type="hidden" name="'.esc_attr($key).'[]" value="'.esc_attr($v).'" />'."\n";
                                        } else {
                                            $return .= '<input type="hidden" name="'.esc_attr($key).'" value="'.esc_attr($value).'" />'."\n";
                                        }
                                    }

                                    $return .= '
                                            <input type="hidden" name="double_signup" value="1" />
                                            <input type="hidden" name="mode" value="submitted" />
                                            <input type="submit" name="Submit" class="button-primary" value="Yes, sign me up" />
                                            <a href="'.$_SERVER['REQUEST_URI'].'">No, thanks</a>
                                        </form>
                                    </p>
                                ';
                                } else {
                                    $signup_id = $this->data->add_signup($_POST, $_GET['task_id']);
                                    if (isset($_POST['dls_sus_remember']) && $_POST['dls_sus_remember'] === 'true') {
                                        $this->data->remember_signup($signup_id);
                                    }
                                    $success = true;
                                    $return .= '<p class="dls-sus updated">'.__('You have been signed up!', 'dls-sus').'</p>';
                                    $this->mail->send_mail($_POST['signup_email'], $_GET['task_id'], $signup_id, 'signup');
                                }
                            } catch (DLS_SUS_Data_Exception $e) {
                                $err++;
                                $return .= '<p class="dls-sus error">'.__($e->getMessage(), 'dls-sus').'</p>';
                            }
                        }

                    }

                    // Display Sign-up Form
                    if (!$submitted || $err) {
                        if (isset($_GET['task_id'])) {
                            $return .= $this->display_signup_form($_GET['task_id']);
                            return $return;
                        }
                    }

                    // Sheet Details
                    if (!$submitted || $success || $err) {
                        $return .= '
                        '.(($sheet->date && $sheet->date != '0000-00-00') ? '<p>Date: '.date(get_option('date_format'), strtotime($sheet->date)).'</p>' : '' ).'
                        <div class="dls-sus-sheet-details">'.nl2br($sheet->details).'</div>
                        <h3>Sign up below...</h3>
                    ';

                        // Tasks
                        $return .= $sheet->get_tasks_table(array('show_clear'=>false, 'show_signup_link'=>true));
                    }

                    $return .= '</div><!-- .dls-sus-sheet -->';

                }
            }

            return $return;
        }

        /**
         * Display signup form
         *
         * @param int $task_id
         * @return string
         */
        public function display_signup_form($task_id)
        {
            $task = $this->data->get_task($task_id);
            $sheet = new DLS_SUS_Sheet($task->sheet_id);

            // Set remembered fields
            if (get_option('dls_sus_remember') === 'true') {
                $session = DLS_Session::get();
                if (
                    !empty($session[$this->plugin_prefix.'_remember'])
                    && is_array($session[$this->plugin_prefix.'_remember'])
                ) {
                    foreach ($session[$this->plugin_prefix.'_remember'] as $key=>$value) {
                        if (isset($_POST['signup_'.$key])) continue;
                        $_POST['signup_'.$key] = $value;
                    }
                }
            }

            $date_display = null;
            if (!empty($task->date) && $task->date != '0000-00-00') {
                $date_display = ' on <em class="dls-sus-task-date">'.date(get_option('date_format'), strtotime($task->date)).'</em>';
            }

            $return = '
            <h3>'.__('Sign-up below', $this->plugin_prefix).'</h3>
            <p>'.__('You are signing up for...', $this->plugin_prefix).' <em class="dls-sus-task-title">'.$task->title.'</em>'.$date_display.'</p>
            <form name="dls-sus-signup-form" method="post" action="'.$this->data->get_current_url(true).'" class="dls-sus-signup-form">
                <p>
                    <label for="signup_firstname" class="signup_firstname">First Name <span class="dls-sus-required-icon">*</span></label>
                    <input type="text" id="signup_firstname" class="signup_firstname" name="signup_firstname" maxlength="100" value="'.((isset($_POST['signup_firstname'])) ? esc_attr($_POST['signup_firstname']) : '').'" />
                </p>
                <p>
                    <label for="signup_lastname" class="signup_lastname">Last Name <span class="dls-sus-required-icon">*</span></label>
                    <input type="text" id="signup_lastname" class="signup_lastname" name="signup_lastname" maxlength="100" value="'.((isset($_POST['signup_lastname'])) ? esc_attr($_POST['signup_lastname']) : '').'" />
                </p>
                <p>
                    <label for="signup_email" class="signup_email">E-mail <span class="dls-sus-required-icon">*</span></label>
                    <input type="text" id="signup_email" class="signup_email" name="signup_email" maxlength="100" value="'.((isset($_POST['signup_email'])) ? esc_attr($_POST['signup_email']) : '').'" />
                </p>
                ';
            if ($this->data->show_phone($sheet)) {
                $return .= '
                        <p>
                            <label for="signup_phone" class="signup_phone">Phone '.(($this->data->phone_required($sheet)) ? '<span class="dls-sus-required-icon">*</span>' : '').'</label>
                            <input type="text" id="signup_phone" class="signup_phone" name="signup_phone" maxlength="50" value="'.((isset($_POST['signup_phone'])) ? esc_attr($_POST['signup_phone']) : '').'" />
                        </p>
                    ';
            }
            if ($this->data->show_address($sheet)) {
                $return .= '
                        <p>
                            <label for="signup_address" class="signup_address">Address '.(($this->data->address_required($sheet)) ? '<span class="dls-sus-required-icon">*</span>' : '').'</label>
                            <input type="text" id="signup_address" class="signup_address" name="signup_address" maxlength="200" value="'.((isset($_POST['signup_address'])) ? esc_attr($_POST['signup_address']) : '').'" />
                        </p>
                        <p class="dls-sus-city">
                            <label for="signup_city" class="signup_city">City '.(($this->data->address_required($sheet)) ? '<span class="dls-sus-required-icon">*</span>' : '').'</label>
                            <input type="text" id="signup_city" class="signup_city" name="signup_city" maxlength="50" value="'.((isset($_POST['signup_city'])) ? esc_attr($_POST['signup_city']) : '').'" />
                        </p>
                        <p class="dls-sus-state">
                            <label for="signup_state" class="signup_state">State '.(($this->data->address_required($sheet)) ? '<span class="dls-sus-required-icon">*</span>' : '').'</label>
                            <select id="signup_state" class="signup_state" name="signup_state">
                                <option value=""> </option>
                                ';
                $states = $this->data->get_states();
                foreach ($states as $abbr=>$name) {
                    $selected = (isset($_POST['signup_state']) && $_POST['signup_state'] == $abbr) ? ' selected="selected"' : null;
                    $return .= '<option value="'.$abbr.'"'.$selected.'>'.$abbr.'</option>';
                }
                $return .= '
                            </select>
                        </p>
                        <p class="dls-sus-zip">
                            <label for="signup_zip" class="signup_zip">Zip '.(($this->data->address_required($sheet)) ? '<span class="dls-sus-required-icon">*</span>' : '').'</label>
                            <input type="text" id="signup_zip" class="signup_zip" name="signup_zip" maxlength="10" value="'.((isset($_POST['signup_zip'])) ? esc_attr($_POST['signup_zip']) : '').'" />
                        </p>
                    ';
            }

            // Custom Fields
            if (!empty($sheet->custom_fields['signup'])) {
                foreach ($sheet->custom_fields['signup'] as $field) {
                    $slug = str_replace('-', '_', $field['slug']);
                    $required = (isset($field['required']) && $field['required'] === 'true') ? true : false;
                    $return .= '<p><label for="signup_'.$slug.'" class="signup_'.$slug.'">'.$field['name'].($required ? ' *' : null).'</label>';
                    switch ($field['type']) {
                        case 'text':
                            $return .= '<input type="text" id="signup_'.$slug.'" class="signup_'.$slug.'" name="signup_'.$slug.'" maxlength="100" value="'.((isset($_POST['signup_'.$slug])) ? esc_attr($_POST['signup_'.$slug]) : '').'" />';
                            break;
                        case 'textarea':
                            $return .= '<textarea id="signup_'.$slug.'" class="signup_'.$slug.'" name="signup_'.$slug.'" >'.((isset($_POST['signup_'.$slug])) ? esc_attr($_POST['signup_'.$slug]) : '').'</textarea>';
                            break;
                        case 'radio':
                        case 'checkbox':
                            $i=0;
                            $options = $this->data->options_string_to_array($field['options']);
                            if (!empty($options)) {
                                foreach ($options AS $k => $v) {
                                    $selected = (isset($_POST['signup_' . $slug]) && in_array($v, $_POST['signup_' . $slug])) ? ' checked="checked"' : '';
                                    $return .= '<input type="' . $field['type'] . '" name="signup_' . $slug . (($field['type'] == 'checkbox') ? '[]' : null) . '" value="' . $k . '"' . $selected . ' id="signup_' . $slug . '-' . $i . '">';
                                    $return .= '<label for="signup_' . $slug . '-' . $i . '" class="dls-sus-inline-label">' . $v . '</label><br />';
                                    $i++;
                                }
                                reset($options);
                            }
                            break;
                        case 'dropdown':
                            $options = $this->data->options_string_to_array($field['options']);
                            $return .= '<select id="signup_'.$slug.'" name="signup_'.$slug.'">';
                            if (!empty($options)) {
                                foreach ($options AS $k => $v) {
                                    $selected = (isset($_POST['signup_' . $slug]) && $_POST['signup_' . $slug] == $v) ? ' selected="selected"' : '';
                                    $return .= '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
                                }
                                reset($options);
                            }
                            $return .= '</select>';
                            break;
                    }
                    $return .= '</p>';
                }
                reset($sheet->custom_fields['signup']);
            }

            // Captcha
            if (get_option('dls_sus_recaptcha') === 'true') {
                // reCAPTCHA
                $theme = get_option('dls_sus_recaptcha_theme');
                if (!empty($theme)) {
                    $return .= '
                            <script type="text/javascript">
                                var RecaptchaOptions = {
                                    theme : "'.$theme.'"
                                };
                            </script>
                        ';
                }
                $return .= $this->recaptcha->recaptcha_get_html($this->public_key, $this->recaptcha_error);
            } else {
                // Simple Captcha
                $return .= '
                        <p>
                            <label for="spam_check" class="spam_check">'.__('Answer the following: 7 + 1 = __', $this->plugin_prefix).' <span class="dls-sus-required-icon">*</span></label>
                            <input type="text" id="spam_check" class="spam_check" name="spam_check" size="4" value="'.((isset($_POST['spam_check'])) ? esc_attr($_POST['spam_check']) : '').'" />
                        </p>
                    ';
            }

            // Remember Me
            if (get_option('dls_sus_remember') === 'true') {
                $return .= '
                        <p class="dls-sus-remember">
                            <input type="checkbox" id="dls_sus_remember" name="dls_sus_remember" value="true"'.((isset($_POST['dls_sus_remember'])) ? ' selected="selected"' : null).' />
                            <label for="dls_sus_remember">'.__('Remember me <span class="dls-sus-note">(saves your information for future signup forms during this visit on our site)</span>', 'dls-sus').'</label>
                        </p>
                    ';
            }

            $return .= '
                <p class="submit">
                    <input type="hidden" name="signup_task_id" value="'.esc_attr($_GET['task_id']).'" />
                    <input type="hidden" name="mode" value="submitted" />
                    <input type="submit" name="Submit" class="button-primary" value="'.__('Sign me up!', 'dls-sus').'" />
                    or <a href="'.remove_query_arg(array('task_id'), $_SERVER['REQUEST_URI']).'" class="dls-sus-backlink-from-task">'.__('&laquo; go back to the Sign-Up Sheet', 'dls-sus').'</a>
                </p>
                <p><span class="dls-sus-required-icon">*</span> = '.__('required', 'dls-sus').'</p>
            </form>
        ';
            $return .= '</div><!-- .dls-sus-sheet -->';
            return $return;
        }

        /**
         * Send reminders
         */
        function send_reminders()
        {
            $signups_to_remind = $this->data->get_signups_that_need_reminding();
            foreach ($signups_to_remind as $signup) {
                if ($this->mail->send_reminder_email($signup)) {
                    $this->data->set_signup_as_reminded($signup->signup_id);
                }
            }
        }

        /**
         *  Start session
         */
        function start_session()
        {
            DLS_Session::start();
        }

        /**
         *  Cleanup session
         */
        function end_session()
        {
            DLS_Session::kill();
        }

        /**
         * Enqueue plugin css and js files
         */
        function add_css_and_js_to_frontend()
        {
            wp_register_style('dls-sus-style', plugins_url('css/style.css', __FILE__), array(), $this->data->plugin_version);
            wp_enqueue_style('dls-sus-style');
        }

        public function update_db_check()
        {
            if ($this->data->plugin_version != get_option('dls_sus_db_version')) $this->update_db();
        }

        private function update_db()
        {
            // Change deprecated database items
            $this->wpdb->query("SHOW TABLES LIKE '{$this->data->tables['field']['name']}'");
            $field_table_exists = ($this->wpdb->num_rows > 0) ? true : false;
            $this->wpdb->query("SHOW TABLES LIKE '{$this->wpdb->prefix}dls_sus_signup_fields'");
            $signup_field_table_exists = ($this->wpdb->num_rows > 0) ? true : false;
            if (!$field_table_exists && $signup_field_table_exists) $this->wpdb->query("RENAME TABLE {$this->wpdb->prefix}dls_sus_signup_fields TO {$this->data->tables['field']['name']}");

            $this->wpdb->query("SHOW COLUMNS FROM {$this->data->tables['field']['name']} LIKE 'signup_id'");
            $signup_id_col_exists = ($this->wpdb->num_rows > 0) ? true : false;
            if ($signup_id_col_exists) $this->wpdb->query("ALTER TABLE {$this->data->tables['field']['name']} CHANGE signup_id entity_id INT");

            // Database Tables
            $sql = "CREATE TABLE {$this->data->tables['sheet']['name']} (
            id INT NOT NULL AUTO_INCREMENT,
            title VARCHAR(200) NOT NULL,
            details LONGTEXT NOT NULL,
            date DATE NOT NULL,
            trash BOOL NOT NULL DEFAULT FALSE,
            UNIQUE KEY id (id)
        );";
            $sql .= "CREATE TABLE {$this->data->tables['task']['name']} (
            id INT NOT NULL AUTO_INCREMENT,
            sheet_id INT NOT NULL,
            title VARCHAR(200) NOT NULL,
            qty INT NOT NULL DEFAULT 1,
            position INT NOT NULL,
            date DATE NOT NULL,
            UNIQUE KEY id (id)
        );";
            $sql .= "CREATE TABLE {$this->data->tables['signup']['name']} (
            id INT NOT NULL AUTO_INCREMENT,
            task_id INT NOT NULL,
            firstname VARCHAR(100) NOT NULL,
            lastname VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(50),
            address VARCHAR(200),
            city VARCHAR(50),
            state VARCHAR(2),
            zip VARCHAR(10),
            date_created DATETIME,
            removal_token VARCHAR(40) NOT NULL,
            reminded DATETIME COMMENT 'Time in GMT',
            UNIQUE KEY id (id)
        );";
            $sql .= "CREATE TABLE {$this->data->tables['category']['name']} (
            id INT NOT NULL AUTO_INCREMENT,
            title VARCHAR(200) UNIQUE NOT NULL,
            UNIQUE KEY id (id)
        );";
            $sql .= "CREATE TABLE {$this->data->tables['sheet_category']['name']} (
            id INT NOT NULL AUTO_INCREMENT,
            sheet_id INT NOT NULL,
            category_id INT NOT NULL,
            UNIQUE KEY id (id)
        );";
            $sql .= "CREATE TABLE {$this->data->tables['field']['name']} (
            id INT NOT NULL AUTO_INCREMENT,
            entity_type VARCHAR(10) NOT NULL DEFAULT 'signup',
            entity_id INT NOT NULL,
            slug VARCHAR(200),
            value LONGTEXT,
            UNIQUE KEY id (id),
            UNIQUE KEY entity_slug (entity_type,entity_id,slug)
        );";
            $sql .= "CREATE TABLE {$this->data->tables['sheet_field']['name']} (
            id INT NOT NULL AUTO_INCREMENT,
            sheet_id INT NOT NULL,
            slug VARCHAR(200),
            value LONGTEXT,
            UNIQUE KEY id (id),
            UNIQUE KEY sheet_slug (sheet_id,slug)
        );";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            $this->wpdb->update(
                $this->data->tables['field']['name'],
                array('entity_type' => 'signup'),
                array('entity_type' => ''),
                array('%s'),
                array('%s')
            );

            update_option("dls_sus_db_version", $this->db_version);
        }

        /**
         * Duplicate plugin check
         */
        public function dup_plugin_check()
        {
            if (is_plugin_active('sign-up-sheets/sign-up-sheets.php')) {
                deactivate_plugins(plugin_basename(__FILE__));
                add_action('admin_notices', array($this, 'dup_plugin_admin_notice'));
            }
        }

        /**
         * Duplicate plugin admin notice
         */
        function dup_plugin_admin_notice()
        {
            echo '
            <div id="'.$this->plugin_prefix.'-dup-plugin" class="error">
                <p>More than one Sign-up Sheets plugin was found.  Please only activate one at a time.</p>
            </div>
        ';
        }

        /**
         * Activate the plugin
         */
        public function activate()
        {
            $this->update_db();

            // Add custom role and capability
            add_role('signup_sheet_manager', 'Sign-up Sheet Manager');
            $this->data->set_manage_signup_sheets();

            // Crons
            if (get_option('dls_sus_reminder_email') === 'true' && wp_get_schedule('dls_sus_send_reminders') === false) wp_schedule_event(current_time('timestamp'), 'hourly', 'dls_sus_send_reminders');
        }

        /**
         * Deactivate the plugin
         */
        public function deactivate()
        {
            // Remove custom role and capability
            $role = get_role('signup_sheet_manager');
            if (is_object($role)) {
                $role->remove_cap('read');
                remove_role('signup_sheet_manager');
            }

            global $wp_roles;
            $all_roles = $wp_roles->get_names();
            foreach ($all_roles as $k=>$v) {
                $role = get_role($k);
                if (is_object($role)) $role->remove_cap('manage_signup_sheets');
            }

            // Crons
            wp_clear_scheduled_hook('dls_sus_send_reminders');
        }

    }

    $dls_sus = new DLS_Sign_Up_Sheet();

endif;