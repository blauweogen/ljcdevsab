<?php
/**
 * Sign-up Sheets Admin Class
 */

if (!class_exists('DLS_SUS_Data')) require_once dirname(__FILE__).'/data.php';
if (!class_exists('DLS_SUS_Mail')) require_once dirname(__FILE__).'/mail.php';
if (!class_exists('DLS_SUS_Sheet')) require_once dirname(__FILE__).'/sheet.php';
if (!class_exists('DLS_SUS_List_Table_Sheets')) require_once dirname(__FILE__).'/list-table-sheets.php';
if (!class_exists('DLS_SUS_List_Table_Categories')) require_once dirname(__FILE__).'/list-table-categories.php';

class DLS_SUS_Admin
{
    
    private $data;
    private $mail;
    private $admin_settings_slug = 'dls-sus-settings';
    private $detailed_errors = false;
    public $plugin_prefix;
    public $table;
    
    public function __construct()
    {
        $this->data = new DLS_SUS_Data();
        $this->mail = new DLS_SUS_Mail();
        $this->plugin_prefix = $this->data->plugin_prefix;
        
        if (get_option('dls_sus_detailed_errors') === 'true') {
            $this->detailed_errors = true;
            $this->data->detailed_errors = true;
        }
    }
    
    /**
     * Enqueue plugin css and js files
     */
    public function head()
    {
        wp_enqueue_style('dls-sus-admin', plugins_url('css/admin.css', __FILE__), array(), $this->data->plugin_version);

        wp_register_script($this->plugin_prefix.'-admin', plugins_url('js/admin.js', __FILE__), array('jquery', 'postbox', 'dls-sus-jquery-comments'), $this->data->plugin_version, true);
        wp_enqueue_script($this->plugin_prefix.'-admin');

        wp_enqueue_script('postbox');

        wp_enqueue_script(
            'dls-sus-jquery-comments',
            plugins_url('js/jquery.comments.js', __FILE__),
            array('jquery'),
            true
        );
    }
    
    /**
     * Add to admin footer
     */
    public function footer()
    {
    }
    
    /**
     * Enqueue admin scripts
     */
    function add_scripts()
    {
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_style( 'jquery.ui.theme', plugins_url( '/css/smoothness/jquery.ui.datepicker.css', __FILE__ ) );
    }
        
    /**
     * Admin Menu
     */
    public function menu()
    {
        add_menu_page('Sign-up Sheets', 'Sign-up Sheets', 'manage_signup_sheets', $this->admin_settings_slug.'_sheets', array(&$this, 'sheet_page'), plugins_url( '/images/admin-icon.png', __FILE__ ), '54.4465525');
        add_submenu_page($this->admin_settings_slug.'_sheets', 'Sign-up Sheets ', 'All Sheets', 'manage_signup_sheets', $this->admin_settings_slug.'_sheets', array(&$this, 'sheet_page'));
        add_submenu_page($this->admin_settings_slug.'_sheets', 'Add New Sheet', 'Add New', 'manage_signup_sheets', $this->admin_settings_slug.'_modify_sheet', array(&$this, 'modify_sheet_page'));
        add_submenu_page($this->admin_settings_slug.'_sheets', 'Categories', 'Categories', 'manage_signup_sheets', $this->admin_settings_slug.'_categories', array(&$this, 'category_page'));
        add_submenu_page($this->admin_settings_slug.'_sheets', 'Sign-up Sheets Settings', __('Settings'), 'manage_options', $this->data->plugin_prefix . '-settings', array(&$this, 'options'));
        add_submenu_page($this->admin_settings_slug.'_sheets', 'Sign-up Sheets Help', __('Help'), 'manage_signup_sheets', $this->data->plugin_prefix . '-help', array(&$this, 'help'));
    }

    /**
     * Page: Options/Settings
     */
    function options()
    {
        if (!current_user_can('manage_options') && !current_user_can('manage_signup_sheets'))  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        $display_name_options = array(
            'default' => '"John S." - first name plus first letter of last name',
            'full' => '"John Smith" - full name',
            'anonymous' => '"Filled" - anonymous',
        );

        global $wp_roles;
        $roles = $wp_roles->get_names();
        unset($roles['administrator']);
        unset($roles['signup_sheet_manager']);

        // Sheets Listing
        $sheets = $this->data->get_sheets(false, true, false, 'end_date, s.id DESC');
        $sheet_selection = array('' => __('All', $this->plugin_prefix));//TODO
        foreach ($sheets as $sheet) {
            $sheet_selection[$sheet->id] = '#'.$sheet->id.': ' . $sheet->title . ((!empty($sheet->date) && $sheet->date !== '0000-00-00') ? ' ('.$sheet->date.')' : null);
        }

        $field_types = array(
            'text' => 'text',
            'textarea' => 'textarea',
            'checkbox' => 'checkbox',
            'radio' => 'radio',
            'dropdown' => 'dropdown'
        );
        $signup_fields = array(
            array('Name', 'name', 'text', null),
            array('Slug', 'slug', 'text', null),
            array('Type', 'type', 'dropdown', null, $field_types),
            array('Options', 'options', 'textarea', '*'),
            array('Sheets', 'sheets', 'multiselect', null, $sheet_selection),
            array('Required', 'required', 'checkbox', null),
            array('Results on Frontend', 'frontend_results', 'checkbox', null),
        );
        $task_fields = array(
            array('Name', 'name', 'text', null),
            array('Slug', 'slug', 'text', null),
            array('Type', 'type', 'dropdown', null, $field_types),
            array('Options', 'options', 'textarea', '*'),
            array('Sheets', 'sheets', 'multiselect', null, $sheet_selection),
        );

        // Set next scheduled reminder check
        $reminder_check_utc_timestamp = wp_next_scheduled('dls_sus_send_reminders');
        $display_reminder_check_time = 'NOT SCHEDULED';
        if (!empty($reminder_check_utc_timestamp)) {
	        $timezone_string = get_option( 'timezone_string' );
	        if ( empty( $timezone_string ) ) {
		        $reminder_check_utc_timestamp = $reminder_check_utc_timestamp + (get_option( 'gmt_offset', 0 ) * 60*60);
	        }
            $reminder_check_date = new DateTime(date('Y-m-d H:i:s', $reminder_check_utc_timestamp), new DateTimeZone('UTC'));
	        $timezone_string = get_option( 'timezone_string' );
	        if (!empty($timezone_string)) {
		        $reminder_check_date->setTimezone( new DateTimeZone( $timezone_string ) );
	        }
            $display_reminder_check_time = $reminder_check_date->format('M j, Y g:i a');
        }

        $options = array(
            'Sign-up Sheet',
            array('Front-end Display Names', 'dls_sus_display_name', 'dropdown', 'How the user\'s name should be displayed on the front-end after they sign-up', $display_name_options),
            array('Show All Sheets in Compact Sign-up Mode', 'dls_sus_compact_signups', 'checkbox', 'Show sign-up spots on one line with just # of open spots and a link to sign-up if open.'),
            array('Show All Sign-up Data Fields on Front-end', 'dls_sus_display_all', 'checkbox', 'WARNING: Sign-up sheet table will appear much like the table when sign-ups are viewed via the admin. This option will potentially display personal user information on the frontend like email address and phone.  This option is best used if you are using the [sign_up_sheet] short code within a password protected area. (This also overrides the "Front-end Display Names" option and displays all as full names.)'),
            array('Custom Task Fields', 'dls_sus_custom_task_fields', 'repeater', 'To add more fields, save this page and a new blank row will appear.<br />* Options are for checkbox, radio and dropdown fields.  Put multiple values on new lines.<br /><br /><strong>NOTE: Custom Task Fields are for display only on the frontend. To add custom fields that your users fill out, use the Custom Sign-up Fields in the "Sign-up Form" section below.</strong>', $task_fields),
            'Sign-up Form',
            array('Show "Remember Me" checkbox', 'dls_sus_remember', 'checkbox'),
            array('Set Phone as Optional', 'dls_sus_optional_phone', 'checkbox'),
            array('Set Address as Optional', 'dls_sus_optional_address', 'checkbox'),
            array('Hide Phone Field', 'dls_sus_hide_phone', 'checkbox'),
            array('Hide Address Fields', 'dls_sus_hide_address', 'checkbox'),
            array('Custom Sign-up Fields', 'dls_sus_custom_fields', 'repeater', 'To add more fields, save this page and a new blank row will appear.<br />* Options are for checkbox, radio and dropdown fields.  Put multiple values on new lines.', $signup_fields),
            'reCAPTCHA',
            array('Use reCAPTCHA', 'dls_sus_recaptcha', 'checkbox', '(will override the default simple captcha validation)'),
            array('Public Key', 'dls_sus_recaptcha_public_key', 'text', '(from your account at www.recaptcha.com)'),
            array('Private Key', 'dls_sus_recaptcha_private_key', 'text', '(from your account at www.recaptcha.com)'),
            array('Theme', 'dls_sus_recaptcha_theme', 'dropdown', null, array('red'=>'red', 'white'=>'white', 'blackglass'=>'blackglass', 'clean'=>'clean')),
            'Confirmation E-mail',
            array('Subject', 'dls_sus_email_subject', 'text', '(If blank, defaults to... "'.$this->mail->default_subjects['signup'].'")'),
            array('From E-mail Address', 'dls_sus_email_from', 'text', '(If blank, defaults to WordPress email on file under Settings > General)'),
            array('BCC', 'dls_sus_email_bcc', 'text', '(Comma separate for multiple email addresses)'),
            array('Message', 'dls_sus_email_message', 'textarea', null),
            'Removal Confirmation E-mail',
            array('Message', 'dls_sus_removed_email_message', 'textarea', null),
            'Reminder E-mail',
            array('Enable Reminders', 'dls_sus_reminder_email', 'checkbox', 'Next scheduled reminder check: ' . $display_reminder_check_time . ' <br />* Your site will check hourly to see if there are reminders that need to be sent using the WordPress Cron.<br />* If you just enabled/disabled this, you may need to refresh this page to see the updated "Next scheduled reminder"'),
            array('Reminder Schedule', 'dls_sus_reminder_email_days_before', 'text', 'Number of days before the date on the sign-up sheet that the email should be sent.  Use whole numbers like <code>1</code> to remind 1 day before.  This field is required.'),
            array('Subject', 'dls_sus_reminder_email_subject', 'text', '(If blank, defaults to... "'.$this->mail->default_subjects['reminder'].'")'),
            array('From E-mail Address', 'dls_sus_reminder_email_from', 'text', '(If blank, defaults to WordPress email on file under Settings > General)'),
            array('BCC', 'dls_sus_reminder_email_bcc', 'text', '(Comma separate for multiple email addresses)'),
            array('Message', 'dls_sus_reminder_email_message', 'textarea', null),
            'Status E-mail',
            array('Enable Status E-mail', 'dls_sus_status_email', 'checkbox', 'Shows all signups for a sheet.  Sent when a user adds or removes a signup from the frontend.'),
            array('Subject', 'dls_sus_status_email_subject', 'text', '(If blank, defaults to... "'.$this->mail->default_subjects['status'].'")'),
            array('From E-mail Address', 'dls_sus_status_email_from', 'text', '(If blank, defaults to WordPress email on file under Settings > General)'),
            array('Send to main admin emails', 'dls_sus_status_to_admin', 'checkbox', '("E-mail Address" specified under Settings > General)'),
            array('Send to "Sheet BCC" recipients', 'dls_sus_status_to_sheet_bcc', 'checkbox', '(These address will be added as a recipient only for sheets on which they are assigned.)'),
            'General',
            array('Additional Settings Expanded by Default', 'dls_sus_additional_settings_expanded', 'checkbox', 'This is for the metabox at the bottom of the page when you edit a sheet in the admin'),
            array('User roles that can manage sheets', 'dls_sus_roles', 'checkboxes', '(Note: Administrators and Sign-up Sheet Managers can always manage sheets)', $roles),
            'Text Overrides',
        );

        $text_overrides_fields = array();
        foreach ($this->data->text as $key=>$text) {
            $text_overrides_fields[] = array($text['label'], 'dls_sus_text_'.$key, 'text', 'Default: '.$text['default']);
        }
        $options = array_merge($options, $text_overrides_fields);

        $options = array_merge($options, array (
            'Debug',
            array('Display Detailed Errors', 'dls_sus_detailed_errors', 'checkbox', '(Not recommended for production sites)'),
        ));
        $hidden_field_name = 'submit_hidden';
        
        echo '
            <div class="wrap dls_sus">
                <div id="icon-dls-sus" class="icon32"><br /></div>
                <h2>' . __('Sign-up Sheets', 'dls-sus-menu') . ' <sup class="dls-sus-pro">Pro</sup> ' . __('Settings', 'dls-sus-menu'). '</h2>
                <p style="text-align: right;"><a href="#" class="dls-sus-expand-all-postbox">+Expand All</a></p>
                <form name="dls-sus-form" class="dls-sus-settings meta-box-sortables" method="post" action="">
                    ';

                    $num_saved = 0;
                    foreach ($options AS $key=>$o) {
                        if (!is_array($o)) {
                            if ($key !== 0) echo '</table></div><!-- .inside --></div><!-- .postbox -->';
                            echo '
                                <div class="postbox closed">
                                    <div class="handlediv" title="Click to toggle"><br></div>
                                    <h3 class="hndle"><span>'.$o.'</span></h3>
                                    <div class="inside">
                                        <table class="form-table">
                            ';
                            continue;
                        }
                        if ($key === 0) echo '<table class="form-table">';
                        $opt_label = (isset($o[0])) ? $o[0] : null;
                        $opt_name = (isset($o[1])) ? $o[1] : null;
                        $opt_note = (isset($o[3])) ? $o[3] : null;

                        // Process submitted options form
                        if(isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {
                            $opt_val = (isset($_POST[$opt_name])) ? $_POST[$opt_name] : null;
                            if ($opt_name == 'dls_sus_custom_fields' || $opt_name == 'dls_sus_custom_task_fields') {
                                foreach ($opt_val as $k=>$v) {
                                    if (empty($v['name'])) {
                                        unset($opt_val[$k]);
                                        continue;
                                    }
                                    if (empty($v['slug'])) $opt_val[$k]['slug'] = sanitize_title($v['name']);
                                    else $opt_val[$k]['slug'] = sanitize_title($v['slug']);
                                    if (empty($opt_val[$k]['sheets'])) $opt_val[$k]['sheets'] = array('');

                                }
                            }
                            update_option($opt_name, $opt_val);
                            if ($opt_name == 'dls_sus_roles') $this->data->set_manage_signup_sheets();
                            $num_saved++;
                            if ($num_saved === 1) {
                                if (isset($_POST['dls_sus_reminder_email']) && $_POST['dls_sus_reminder_email'] === 'true') {
                                    if (get_option('dls_sus_reminder_email') !== 'true') {
                                        wp_schedule_event(current_time('timestamp'), 'hourly', 'dls_sus_send_reminders');
                                    }
                                } else {
                                    wp_clear_scheduled_hook('dls_sus_send_reminders');
                                }
                                echo '<div class="updated"><p><strong>'.__('Settings saved.', 'dls-sus-menu').'</strong></p></div>';
                            }
                        }
                        
                        echo '
                            <tr valign="top">
                                <th scope="row">'.__($opt_label.":", 'dls-sus-menu').'</th>
                                <td>
                                    ';

                                    $this->display_field_by_type($o);
                                    
                                    echo '
                                    <span class="description">'.$opt_note.'</span>
                                </tr>
                            </tr>
                        ';

                    }
                    
                    echo '
                    </table></div><!-- .inside --></div><!-- .postbox -->
                    <hr />
                    <p class="submit">
                        <input type="hidden" name="'.$hidden_field_name.'" value="Y">
                        <input type="submit" name="Submit" class="button-primary" value="'.esc_attr('Save Changes').'" />
                    </p>

                </form>
            </div><!-- .wrap -->
        ';
    }

    /**
     * Page: Help
     */
    function help()
    {
        if (!current_user_can('manage_signup_sheets')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        echo '
            <div class="wrap dls_sus">
                <div id="icon-dls-sus" class="icon32"><br /></div>
                <h2>' . __('Sign-up Sheets', 'dls-sus-menu') . ' <sup class="dls-sus-pro">Pro</sup> ' . __('Help', 'dls-sus-menu') . '</h2>
        ';

        echo '
            <h3>Need Help?</h3>
            <ol>
                <li>View the <a href="https://www.dlssoftwarestudios.com/downloads/sign-up-sheets-wordpress-plugin/#dls-faq" target="_blank">Frequently Asked Questions</a></li>
                <li>Check our <a href="https://www.dlssoftwarestudios.com/forums/forum/wordpress/sign-up-sheets/support/" target="_blank">Support Forum</a></li>
                <li>Open a new <a href="https://www.dlssoftwarestudios.com/sign-up-sheets-support-request/" target="_blank">Support Request</a> in our Support Form<br /><em>(you may first be prompted to login or register for an account on our site)</em></li>
            </ol>
        ';

		$theme = wp_get_theme();
	    $all_options = wp_load_alloptions();
	    $sus_options = array();
	    $sus_options_display['oneline'] = null;
	    $sus_options_display['multiline'] = null;
	    if ( ! empty( $all_options ) && is_array( $all_options ) ) {
		    foreach ( $all_options as $key=>$value ) {
				if (strpos($key, 'dls_sus_') !== 0) continue;
			    switch ($key) {
				    // Skip sensitive data
				    case 'dls_sus_recaptcha_public_key':
				    case 'dls_sus_recaptcha_private_key':
					    continue;
					    break;
				    // Multi-line
				    case 'dls_sus_email_message':
				    case 'dls_sus_reminder_email_message':
				    case 'dls_sus_removed_email_message':
				    case 'dls_sus_custom_fields':
				    case 'dls_sus_custom_task_fields':
				    case 'dls_sus_roles':
					    $sus_options['multiline'][ $key ] = $value;
				        $sus_options_display['multiline'] .= "\n# " . str_replace( 'dls_sus_', '', $key ) . "\n" . print_r( maybe_unserialize( $value ), true ) . "\n";
					    break;
				    // One-line
				    default:
					    $sus_options['oneline'][ $key ] = $value;
					    $sus_options_display['oneline'] .= str_replace('dls_sus_', '', $key) . ": $value\n";
			    }
		    }
		    reset( $all_options );
	    }
	    $plugins        = get_plugins();
	    $active_plugins = get_option( 'active_plugins', array() );
	    $plugins_display = null;
	    if (!empty( $plugins ) && is_array( $plugins )) {
		    foreach ( $plugins as $plugin_path => $plugin ) {
			    if ( ! in_array( $plugin_path, $active_plugins ) ) {
				    continue;
			    }
			    if ($plugins_display !== null) $plugins_display .= "\n";
			    $plugins_display .= $plugin['Name'] . ': ' . $plugin['Version'] . " <{$plugin['PluginURI']}>";
		    }
		    reset( $plugins );
	    }

        echo '
            <h3>System Information</h3>
            <p>Details about your system are compiled below.  We may request this information to help us troubleshoot your support request.</p>
            <textarea readonly="readonly" class="dls-sus-system-info" rows="25" onclick="this.focus(); this.select();">### Begin System Information ###

-- General Settings
Site URL:               ' . site_url() . '
Home URL:               ' . home_url() . '
Is Multisite:           ' . ( is_multisite() ? 'Yes' : 'No' ) . '
WordPress Version:      ' . get_bloginfo( 'version' ) . '
Permalink Structure:    ' . get_option( 'permalink_structure' ) . '
Admin Email:            ' . get_option( 'admin_email' ) . '
Date Format:            ' . get_option( 'date_format' ) . '
Time Format:            ' . get_option( 'time_format' ) . '
Time Zone String        ' . get_option( 'timezone_string' ) . '

-- Theme Information
Name:                   ' . $theme->name . '
Version:                ' . $theme->version . '
Author:                 ' . $theme->display( 'Author', false ) . '
Author URI:             ' . $theme->display( 'AuthorURI' ) . '
Parent:                 ' . $theme->parent_theme . '

-- User System and Browser
User Agent String:      ' . $_SERVER['HTTP_USER_AGENT'] . '

-- Active WordPress Plugins
' . $plugins_display . '

-- Sign-up Sheets Settings
' . $sus_options_display['oneline'] . '

-- Sign-up Sheets Additional Settings
' . $sus_options_display['multiline'] . '

### End System Information ###</textarea>
        ';

        echo '</div><!-- .wrap -->';
    }

    public function display_field_by_type($o, $parent_name=null, $value=null)
    {
        $opt_name = (!empty($parent_name)) ? $parent_name.'['.$o[1].']' : $o[1];
        $opt_type = (isset($o[2])) ? $o[2] : null;
        $opt_options = (isset($o[4])) ? $o[4] : array();
        $opt_val = (!empty($value)) ? $value : get_option($opt_name);
        switch ($opt_type) {
            case 'text':
                echo '<input type="text" id="'.$opt_name.'" name="'.$opt_name.'" value="'.esc_attr($opt_val).'" size="20">';
                break;
            case 'checkbox':
                echo '<input type="checkbox" id="'.$opt_name.'" name="'.$opt_name.'" value="true"'.(($opt_val === 'true') ? ' checked="checked"' : '').'>';
                break;
            case 'checkboxes':
                $i=0;
                foreach ($opt_options AS $k=>$v) {
                    $checked = (is_array($opt_val) && in_array($k, $opt_val)) ? ' checked="checked"' : '';
                    echo '<input type="checkbox" name="'.$opt_name.'[]" value="'.$k.'"'.$checked.' id="'.$opt_name.'-'.$i.'">';
                    echo ' <label for="'.$opt_name.'-'.$i.'">'.$v.'</label><br />';
                    $i++;
                }
                break;
            case 'textarea':
                echo '<textarea id="'.$opt_name.'" name="'.$opt_name.'" rows="8" style="width: 100%;">'.esc_attr($opt_val).'</textarea>';
                break;
            case 'dropdown':
                echo '<select id="'.$opt_name.'" name="'.$opt_name.'">';
                foreach ($opt_options AS $k=>$v) {
                    $selected = ($opt_val == $k) ? ' selected="selected"' : '';
                    echo '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
                }
                echo '</select>';
                break;
            case 'multiselect':
                echo '<select multiple="multiple" id="'.$opt_name.'" name="'.$opt_name.'[]">';
                foreach ($opt_options AS $k=>$v) {
                    $selected = (is_array($opt_val) && in_array($k, $opt_val)) ? ' selected="selected"' : '';
                    echo '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
                }
                echo '</select>';
                break;
            case 'repeater':
                echo '</td><tr><td colspan="2"><table class="dls-sus-repeater">';

                echo '<tr>';
                if (!empty($opt_options)) {
                    foreach ($opt_options AS $k=>$v) {
                        $description = (!empty($v[3])) ? ' <span class="description">'.$v[3].'</span>' : null;
                        echo '<th class="'.$opt_name.'_'.$k.'">'.$v[0].$description.'</th>';
                    }
                }
                echo '</tr>';

                if (!empty($opt_val)) {
                    foreach ($opt_val as $val_k=>$val_v) {
                        echo '<tr>';
                        foreach ($opt_options AS $k=>$v) {
                            echo '<td class="'.$opt_name.'_'.$k.'">';
                            if (!isset($opt_val[$val_k][$v[1]])) $opt_val[$val_k][$v[1]] = null;
                            $this->display_field_by_type($v, $opt_name.'['.$val_k.']', $opt_val[$val_k][$v[1]]);
                            echo '</td>';
                        }
                        echo '</tr>';
                    }
                }

                echo '<tr>';
                if (!empty($opt_options)) {
                    foreach ($opt_options AS $k=>$v) {
                        echo '<td class="'.$opt_name.'_'.$k.'">';
                        if (!isset($val_k)) $val_k = 0;
                        $this->display_field_by_type($v, $opt_name.'['.($val_k+1).']');
                        echo '</td>';
                    }
                }
                echo '</tr>';
                echo '</table>';
        }
    }

    /**
     * Sheets
     */
    function sheet_page()
    {
        if (!current_user_can('manage_options') && !current_user_can('manage_signup_sheets'))  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        
        $err = false;
        $success = false;
            
        // Remove signup record
        if (isset($_GET['action']) && $_GET['action'] == 'clear') {
            try {
                $result = $this->data->delete_signup($_GET['signup_id']);
                $success = true;
                if ($result > 0) echo '<div class="updated"><p>Spot has been cleared.</p></div>';
            } catch (DLS_SUS_Data_Exception $e) {
                $err = true;
                echo '<div class="error"><p>Error clearing spot (ID #'.esc_attr($_GET['signup_id']).')</p></div>';
            }
        }
        
        // Set Actions
        $trash = (!empty($_GET['action']) && $_GET['action'] == 'trash');
        $untrash = (!empty($_GET['action']) && $_GET['action'] == 'untrash');
        $delete = (!empty($_GET['action']) && $_GET['action'] == 'delete');
        $copy = (!empty($_GET['action']) && $_GET['action'] == 'copy');
        $view_signups = (!empty($_GET['action']) && $_GET['action'] == 'view_signups');
        $edit = (!$trash && !$untrash && !$delete && !$copy && !empty($_GET['sheet_id']));
        
        echo '<div class="wrap dls_sus">';
        echo '<div id="icon-dls-sus" class="icon32"><br /></div>';
        echo ($edit || $view_signups) ? '<h2>Sheet Details <a href="'.plugins_url( './' , __FILE__ ).'export.php?sheet_id='.$_GET['sheet_id'].'" class="add-new-h2">Export Sheet as CSV</a></h2>' : '<h2>Sign-up Sheets <sup class="dls-sus-pro">Pro</sup>
            <a href="?page='.$this->admin_settings_slug.'_modify_sheet" class="add-new-h2">Add New</a>
            <a href="'.plugins_url( './' , __FILE__ ).'export.php" class="add-new-h2">Export All as CSV</a>
            </h2>
        ';
        
        if ($untrash) {
            try {
                $result = $this->data->update_sheet(array('sheet_trash'=>0), $_GET['sheet_id']);
                echo '<div class="updated"><p>Sheet has been restored.</p></div>';
            } catch (DLS_SUS_Data_Exception $e) {
                echo '<div class="error"><p>Error restoring sheet.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : '').'</p></div>';
            }
        } elseif ($trash) {
            try {
                $result = $this->data->update_sheet(array('sheet_trash'=>1), $_GET['sheet_id']);
                echo '<div class="updated"><p>Sheet has been moved to trash.</p></div>';
            } catch (DLS_SUS_Data_Exception $e) {
                echo '<div class="error"><p>Error moving sheet to trash.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : '').'</p></div>';
            }
        } elseif ($delete) {
            try {
                $result = $this->data->delete_sheet($_GET['sheet_id']);
                echo '<div class="updated"><p>Sheet has been permanently deleted.</p></div>';
            } catch (DLS_SUS_Data_Exception $e) {
                echo '<div class="error"><p>Error permanently deleting sheet.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : '').'</p></div>';
            }
        } elseif ($copy) {
            try {
                $new_id = $this->data->copy_sheet($_GET['sheet_id']);
                echo '<div class="updated"><p>Sheet has been copied to new sheet ID #'.$new_id.' (<a href="?page='.$this->admin_settings_slug.'_modify_sheet&amp;sheet_id='.$new_id.'">Edit</a>).</p></div>';
            } catch (DLS_SUS_Data_Exception $e) {
                echo '<div class="error"><p>Error copying sheet.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : '').'</p></div>';
            }
        }
        
        if ($edit || $view_signups) {
            
            // View Single Sheet
            $sheet = new DLS_SUS_Sheet($_GET['sheet_id']);
            if (!$sheet->is_valid()) {
                echo '<p class="error">'.__('No sign-up sheet found.', $this->plugin_prefix).'</p>';
            } else {
                echo '
                <h3>'.$sheet->title.'</h3>
                
                <p>Date: '.(($sheet->date == '0000-00-00') ? 'N/A' : date(get_option('date_format'), strtotime($sheet->date))).'</p>
                
                <div class="dls-sus-sheet-details">'.nl2br($sheet->details).'</div>
                
                <h4>'.__('Sign-ups', $this->plugin_prefix).'</h4>
                ';
        
                // Tasks
                echo $sheet->get_tasks_table(array('show_clear'=>true, 'admin_table'=>true));
                
            }
            
        } else {
            
            // View All
            $show_trash = (isset($_GET['sheet_status']) && $_GET['sheet_status'] == 'trash');
            $show_all = !$show_trash;
            
            echo '
            <ul class="subsubsub">
                <li class="all"><a href="admin.php?page='.$this->admin_settings_slug.'_sheets"'.(($show_all) ? ' class="current"' : '').'>All <span class="count">('.$this->data->get_sheet_count().')</span></a> |</li>
                <li class="trash"><a href="admin.php?page='.$this->admin_settings_slug.'_sheets&amp;sheet_status=trash"'.(($show_trash) ? ' class="current"' : '').'>Trash <span class="count">('.$this->data->get_sheet_count(true).')</span></a></li>
            </ul>
            <form method="get" action="">
            ';
                    
            // Get and display data
            $this->table = new DLS_SUS_List_Table_Sheets($show_trash);
            $this->table->prepare_items();
            $this->table->display();
            
            echo '</form><!-- #sheet-filter -->';
        }
        
        echo '</div><!-- .wrap -->';
        
    }

    /**
     * Add a Sheet Page
     */
    function modify_sheet_page()
    {
        if (!current_user_can('manage_options') && !current_user_can('manage_signup_sheets'))  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        
        // Set mode vars
        $edit = (empty($_GET['sheet_id'])) ? false : true;
        $add = ($edit) ? false : true;
        $submitted = (isset($_POST['mode']) && $_POST['mode'] == 'submitted');
        $err = 0;
        
        // Process form if submitted
        if($submitted) {
            
            try {
                
                // Sheet
                if ($add) $result = $this->data->add_sheet($_POST);
                else if ($edit) $result = $this->data->update_sheet($_POST, $_GET['sheet_id']);
                $sheet_id = ($add) ? $result : $_GET['sheet_id'];
                echo '<div class="updated"><p><strong>'.__('Sheet saved.', 'dls-sus-menu').'</strong></p></div>';
                
                // Categories
                $sheet_categories = $this->data->get_categories_by_sheet($sheet_id);
                $curr_categories = array();
                foreach ($sheet_categories AS $sheet_category) {
                    $curr_categories[] = $sheet_category->category_id;
                }
                $input_categories = (isset($_POST['categories'])) ? $_POST['categories'] : array();
                $categories_to_add = array_diff((array)$input_categories, (array)$curr_categories);
                $categories_to_delete = array_diff((array)$curr_categories, (array)$input_categories);
                foreach ($categories_to_add AS $category_id) {
                    if (!empty($category_id)) {
                        $add_sheet_category_result = $this->data->add_sheet_category($sheet_id, $category_id);
                        if (is_wp_error($add_sheet_category_result)) {
                            throw new DLS_SUS_Data_Exception($add_sheet_category_result->get_error_message());
                        }
                    }
                }
                foreach ($categories_to_delete AS $category_id) {
                    foreach ($sheet_categories AS $sheet_category) {
                        if ($sheet_category->category_id == $category_id) $this->data->delete_sheet_category($sheet_category->id);
                    }
                }

                // Tasks
                $tasks = $this->data->get_tasks($_GET['sheet_id']);
                $tasks_to_delete = array();
                $tasks_to_update = array();
                $keys_to_process = array();
                foreach ($_POST['task_title'] AS $key=>$value) {
                    $keys_to_process[] = $key;
                }
                
                // Queue for removal: tasks where the fields were emptied out
                for ($i = 0; $i < count($_POST['task_id']); $i++) {
                    if (empty($_POST['task_title'][$i])) {
                        if (!empty($_POST['task_id'][$i])) $tasks_to_delete[] = $_POST['task_id'][$i];
                        continue;
                    } else {
                        $tasks_to_update[] = $_POST['task_id'][$i];
                        $signup_count = count($this->data->get_signups($_POST['task_id'][$i]));
                        if ($signup_count > $_POST['task_qty'][$i]) {
                            $err++;
                            if (!empty($err)) echo '<div class="error"><p><strong>'.__('The number of spots for task "'.$_POST['task_title'].'" cannot be set below '.$signup_count.' because it currently has '.$signup_count.' '.(($signup_count > 1) ? 'people' : 'person').' signed up.  Please clear some spots first before updating this task.').'</strong></p></div>';
                        }
                    }
                }

                // Queue for removal: tasks that are no longer in the list
                foreach ($tasks AS $task) {
                    if (!in_array($task->id, $_POST['task_id'])) {
                        $tasks_to_delete[] = $task->id;
                        $signup_count = count($this->data->get_signups($task->id));
                        if ($signup_count > 0) {
                            $err++;
                            if (!empty($err)) echo '<div class="error"><p><strong>'.__('The task "'.$task->title.'" cannot be removed because it has '.$signup_count.' '.(($signup_count > 1) ? 'people' : 'person').' signed up.  Please clear all spots first before removing this task.').'</strong></p></div>';
                        }
                    }
                }
                
                if (empty($err)) {
                    $i = 0;
                    foreach ($keys_to_process AS $key) {
                        if (empty($_POST['task_title'][$key])) continue;
                        foreach ($this->data->tables['task']['allowed_fields'] AS $field=>$nothing) {
                            if (!isset($_POST['task_'.$field])) continue;
                            $task_data['task_'.$field] = $_POST['task_'.$field][$key];
                            $task_data['task_position'] = $i;
                        }
                        $sheet = new DLS_SUS_Sheet($sheet_id);
                        if (!empty($sheet->custom_fields['task'])) {
                            foreach ($sheet->custom_fields['task'] as $field) {
                                $slug = str_replace('-', '_', $field['slug']);
                                $task_data['task_'.$slug] = $_POST['task_'.$slug][$key];
                            }
                            reset($sheet->custom_fields['task']);
                        }
                        $task_data['task_sheet_id'] = $sheet_id;
                        if (empty($_POST['task_id'][$key])) {
                            if (($result = $this->data->add_task($task_data, $sheet_id)) === false) {
                                $err++;
                            }
                        } else {
                            if (($result = $this->data->update_task($task_data, $_POST['task_id'][$key])) === false) {
                                $err++;
                            }
                        }
                        $i++;
                    }
                    
                    if (!empty($err)) echo '<div class="error"><p><strong>'.__('Error saving '.$err.' task'.(($err > 1) ? 's' : '').'.', 'dls-sus-menu').'</strong></p></div>';
                    
                    // Delete unused tasks
                    foreach ($tasks_to_delete AS $task_id) {
                        if ($this->data->delete_task($task_id) === false) {
                            echo '<div class="error"><p><strong>'.__('Error removing a task.', 'dls-sus-menu').'</strong></p></div>';
                        }
                    }
                }
                
            } catch (DLS_SUS_Data_Exception $e) {
                $err++;
                echo '<div class="error"><p><strong>'.__($e->getMessage()).'</strong></p></div>';
            }
            
        }
        
        // Set field values for form
        $fields = (isset($_POST) && !$add) ? $this->data->stripslashes_full($_POST) : null;
        if ($edit && empty($err)) {
            $sheet_fields = array();
            $task_fields = array();
            $custom_task_fields = array();
            // Pull from DB instead
            $sheet = new DLS_SUS_Sheet($_GET['sheet_id']);
            if ($sheet->is_valid()) {
                $sheet_fields = array();
                foreach($sheet->get_data() AS $k=>$v) $sheet_fields['sheet_'.$k] = $v;
            }
            if ($tasks = $this->data->get_tasks($_GET['sheet_id'])) {
                $task_fields = array();
                foreach ($tasks AS $task) {
                    $task_fields['task_id'][] = $task->id;
                    $task_fields['task_title'][] = $task->title;
                    $task_fields['task_date'][] = $task->date;
                    $task_fields['task_qty'][] = $task->qty;
                    // Custom fields
                    if (!empty($task->fields)) {
                        foreach ($task->fields as $slug=>$value) {
                            $custom_task_fields['task_'.$slug][] = $value;
                        }
                        reset($task->fields);
                    }
                }
                reset($tasks);
            }
            $fields = array_merge((array)$sheet_fields, (array)$task_fields, (array)$custom_task_fields);
        }
        
        // Display Form
        echo '<div class="wrap dls_sus">';
        echo '<div id="icon-dls-sus" class="icon32"><br /></div>';
        echo '<h2>'.(($add) ? 'Add' : 'Edit').' Sign-up Sheet</h2>';
        echo '<div id="poststuff">';
        echo '<div id="post-body" class="metabox-holder columns-1">';
        echo '<div id="post-body-content">';
        $this->display_sheet_form($fields, $sheet);
        echo '</div><!-- #post-body-content -->';
        echo '</div><!-- #post-body -->';
        echo '</div><!-- #poststuff -->';
        echo '</div><!-- .wrap -->';
    }
    
    /**
     * Display the form to add/edit a sheet
     * 
     * @param    array   fields to pass into form, if any
     * @param    DLS_SUS_Sheet $sheet
     */
    private function display_sheet_form($f=array(), $sheet)
    {
        $count = (isset($f['task_title'])) ? count($f['task_title']) : 3;
        if ($count < 3) $count = 3;
        $categories = $this->data->get_categories();
        
        echo '
            <form name="add_sheet" id="dls-sus-modify-sheet" class="meta-box-sortables" method="post" action="">
                <p>
                    <label for="sheet_title">Title:</label>
                    <input type="text" id="sheet_title" name="sheet_title" value="'.((isset($f['sheet_title']) ? esc_attr($f['sheet_title']) : '')).'" size="60">

                    <span class="dls-sus-sheet-date-wrap">
                        <label for="sheet_date">Date:</label>
                        <input type="text" id="sheet_date" name="sheet_date" value="'.((isset($f['sheet_date']) && $f['sheet_date'] != '0000-00-00')  ? date('m/d/Y', strtotime($f['sheet_date'])) : '').'" size="20" class="dls-sus-datepicker">
                    </span>
                    ';

                    $fields = $this->data->get_sheet_field_by('slug', 'use_task_dates');
                    foreach ($fields as $field) {
                        $o = array($field['name'], 'sheetfield_'.$field['slug'], $field['type'], $field['note'], $field['options']);
                        $this->display_field_by_type($o, null, ((isset($f['sheet_fields'][$field['slug']]) ? esc_attr($f['sheet_fields'][$field['slug']]) : '')));
                        echo ' <label for="sheetfield_'.$field['slug'].'">'.$field['name'].'</label> <span class="note">'.$field['note'].'</span>';
                    }
                    reset($fields);

                    echo '
                </p>
                <p>
                    <label for="sheet_details">Details:</label><br />
                    ';
                    wp_editor(html_entity_decode((isset($f['sheet_details']) ? esc_attr($f['sheet_details']) : '')), 'sheet_details', array('textarea_rows'=>8));
                    echo '
                </p>
                <p>
                    <label for="categories">Categories:'.(empty($categories) ? ' <em>'.__('No categories created', $this->plugin_prefix).'</em>' : null).'</label><br />
                    ';
                        if (!empty($categories)) {
                            echo '<select id="categories" name="categories[]" multiple="multiple">';
                            if (!empty($_GET['sheet_id'])) {
                                $sheet_categories = $this->data->get_categories_by_sheet($_GET['sheet_id']);
                                $curr_category_ids = array();
                                foreach ($sheet_categories AS $sheet_category) {
                                    $curr_category_ids[] = $sheet_category->category_id;
                                }
                            }
                            foreach ($categories AS $category) {
                                $selected = (in_array($category->id, (array)$curr_category_ids)) ? ' selected="selected"' : null;
                                if (is_array($sheet_categories)) reset($sheet_categories);
                                echo '<option value="'.$category->id.'"'.$selected.'>'.$category->title.'</option>';
                            }
                            echo '<option value=""> </option>';
                            echo '</select>';
                        }
                echo '</p>';

                echo '
                <h3>Tasks</h3>
                <div class="tasks-wrap">
                <ul class="tasks">
                ';
                $custom_task_fields = $sheet->custom_fields['task'];
                for ($i = 0; $i <= $count; $i++) {
                    if ($i == $count) echo "\n<!--\n";

                    $custom_task_fields_output = null;
                    if (!empty($custom_task_fields)) {
                        foreach ($custom_task_fields as $field) {
                            if (empty($field['slug'])) continue;
                            $slug = str_replace('-', '_', $field['slug']);
                            $custom_task_fields_output .= '&nbsp;&nbsp;&nbsp;'.$field['name'].': ';
                            switch ($field['type']) {
                                case 'text':
                                    $custom_task_fields_output .= '<input type="text" id="task_'.$slug.'" class="task_'.$slug.'" name="task_'.$slug.'['.$i.']" maxlength="100" value="'.((isset($f['task_'.$slug][$i])) ? esc_attr($f['task_'.$slug][$i]) : '').'" />';
                                    break;
                                case 'textarea':
                                    $custom_task_fields_output .= '<textarea id="task_'.$slug.'" class="task_'.$slug.'" name="task_'.$slug.'['.$i.']" >'.((isset($f['task_'.$slug][$i])) ? esc_attr($f['task_'.$slug][$i]) : '').'</textarea>';
                                    break;
                                case 'radio':
                                case 'checkbox':
                                    $options_i=0;
                                    $options = $this->data->options_string_to_array($field['options']);
                                    foreach ($options AS $k=>$v) {
                                        $selected = (isset($f['task_'.$slug][$i]) && is_array($f['task_'.$slug][$i]) && in_array($v, $f['task_'.$slug][$i])) ? ' checked="checked"' : null;
                                        $custom_task_fields_output .= '<input type="'.$field['type'].'" name="task_'.$slug.'['.$i.']'.(($field['type'] == 'checkbox') ? '[]' : null).'" value="'.$k.'"'.$selected.' id="task_'.$slug.'-'.$options_i.'">';
                                        $custom_task_fields_output .= '<label for="task_'.$slug.'-'.$options_i.'" class="dls-sus-inline-label">'.$v.'</label> ';
                                        $options_i++;
                                    }
                                    reset($options);
                                    break;
                                case 'dropdown':
                                    $options = $this->data->options_string_to_array($field['options']);
                                    $custom_task_fields_output .= '<select name="task_'.$slug.'['.$i.']">';
                                    $custom_task_fields_output .= '<option value=""> </option>';
                                    foreach ($options AS $k=>$v) {
                                        $selected = (isset($f['task_'.$slug][$i]) && $f['task_'.$slug][$i] == $v) ? ' selected="selected"' : '';
                                        $custom_task_fields_output .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
                                    }
                                    reset($options);
                                    $custom_task_fields_output .= '</select>';
                                    break;
                            }
                            $custom_task_fields_output .= "\n                            ";
                        }
                        reset($custom_task_fields);
                    }


                    echo '
                        <li id="task-row-'.$i.'" class="task-row">
                            <input type="text" name="task_title['.$i.']" value="'.((isset($f['task_title'][$i]) ? esc_attr($f['task_title'][$i]) : '')).'" size="20">&nbsp;&nbsp;&nbsp;
                            <span class="dls-sus-task-date">Date: <input type="text" name="task_date['.$i.']" value="'.((isset($f['task_date'][$i]) && $f['task_date'][$i] != '0000-00-00') ? date('m/d/Y', strtotime($f['task_date'][$i])) : '').'" size="10" class="dls-sus-datepicker">&nbsp;&nbsp;&nbsp;</span>
                            # of People Needed: <input type="text" name="task_qty['.$i.']" value="'.((isset($f['task_qty'][$i]) ? $f['task_qty'][$i] : '')).'" size="5">
                            '.$custom_task_fields_output.'
                            <input type="hidden" name="task_id['.$i.']" value="'.((isset($f['task_id'][$i]) ? $f['task_id'][$i] : '')).'">
                            <a href="#" class="add-task-after">+</a>
                            <a href="#" class="remove-task">x</a>
                        </li>
                    ';
                    if ($i == $count) echo "\n-->\n";
                }
                echo '</ul></div>';

                $closed = (get_option('dls_sus_additional_settings_expanded') === 'true') ? null : 'closed';
                echo '
                <div id="'.$this->plugin_prefix.'-settings" class="postbox '.$closed.'">
                    <div class="handlediv" title="Click to toggle"><br /></div>
                    <h3><span>'.__('Additional Settings', $this->plugin_prefix).'</span></h3>
                    <div class="inside">
                        <input type="hidden" name="save_input" value="true" />
                        <div class="options" data-layout="default" data-show="true" style="display:none"></div>
                        <div id="'.$this->plugin_prefix.'-extra_stylesheet" class="field field-textarea">
                            <table class="form-table">
                            ';
                            $fields = $this->data->get_sheet_field_by('group', 'settings');
                            foreach ($fields as $field) {
                                echo '
                                    <tr>
                                        <td><label for="sheetfield_'.$field['slug'].'">'.$field['name'].':</label></td>
                                        <td>
                                            ';
                                            $o = array($field['name'], 'sheetfield_'.$field['slug'], $field['type'], $field['note'], $field['options']);
                                            $this->display_field_by_type($o, null, ((isset($f['sheet_fields'][$field['slug']]) ? esc_attr($f['sheet_fields'][$field['slug']]) : '')));
                                            echo '
                                            <span class="note">'.$field['note'].'</span>
                                        </td>
                                    </tr>
                                ';
                            }
                            reset($fields);
                            echo '
                            </table><!-- .form-table -->
                        </div><!-- .field -->
                    </div><!-- .inside -->
                </div><!-- .postbox -->
                ';

                echo '
                <p class="submit">
                    <input type="hidden" name="mode" value="submitted" />
                    <input type="submit" name="Submit" class="button-primary" value="'.esc_attr('Save').'" />
                </p>
            </form>
        ';
    }

    /**
     * Category Page
     */
    function category_page()
    {
        if (!current_user_can('manage_options') && !current_user_can('manage_signup_sheets'))  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        
        $err = false;
        $success = false;
        
        // Set Actons
        $delete = (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'delete');
        $add = (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'add');
        $edited = (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'edited' && !empty($_REQUEST['category_id']));
        $edit = (!$delete && !$add && !$edited && !empty($_REQUEST['category_id']));
        
        echo '<div class="wrap dls_sus">';
        echo '<div id="icon-dls-sus" class="icon32"><br /></div>';
        echo '<h2>Sign-up Sheet Categories</h2>';
        
        if ($edit) {
            try {
                $category = $this->data->get_category($_REQUEST['category_id']);
                if (empty($category)) throw new DLS_SUS_Data_Exception('Category not found.'. (($this->detailed_errors === true) ? '.. Requested category id = '.$_REQUEST['category_id'] : ''));
            } catch (DLS_SUS_Data_Exception $e) {
                $edit = false;
                echo '<div class="error"><p>'.$e->getMessage().'</p></div>';
            }
        } elseif ($edited) {
            try {
                $result = $this->data->update_category($_POST, $_REQUEST['category_id']);
                echo '<div class="updated"><p>Category successfully updated.</p></div>';
            } catch (DLS_SUS_Data_Exception $e) {
                $edited = false;
                $edit = true;
                echo '<div class="error"><p>'.$e->getMessage().'</p></div>';
            }
        }
        
        if ($delete) {
            try {
                $result = $this->data->delete_category($_GET['category_id']);
                echo '<div class="updated"><p>Category has been permanently deleted.</p></div>';
            } catch (DLS_SUS_Data_Exception $e) {
                echo '<div class="error"><p>Error permanently deleting category.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : '').'</p></div>';
            }
        } elseif ($add) {
            try {
                $result = $this->data->add_category($_POST);
                echo '<div class="updated"><p>Category has been added.</p></div>';
            } catch (DLS_SUS_Data_Exception $e) {
                echo '<div class="error"><p>Error adding category.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : '').'</p></div>';
            }
        }
        
        //View All
        $show_trash = (isset($_GET['sheet_status']) && $_GET['sheet_status'] == 'trash');
        $show_all = !$show_trash;
        
        echo '
        <div id="col-container">
        ';
        if (!$edit) {
            echo '
            <div id="col-right">
                <div class="col-wrap">

                <form method="get" action="">
                ';
                
                    // Get and display data
                    $this->table = new DLS_SUS_List_Table_Categories();
                    $this->table->prepare_items();
                    $this->table->display();
                
                echo '
                </form><!-- #sheet-filter -->
                
                </div><!-- .col-wrap -->
            </div><!-- #col-right -->
            ';
        }
        
            echo '
            <div id="col-left">
                <div class="col-wrap">
                
                    <div class="form-wrap">
                        <h3>'.(($edit) ? 'Edit' : 'Add').' New Category</h3>
                        
                        <form id="dls_sus_'.(($edit) ? 'edit' : 'add').'_category" method="post" action="" class="validate">
                            <input type="hidden" name="action" value="'.(($edit) ? 'edited' : 'add').'" />
                            <div class="form-field form-required">
                                <label for="category_title">Name</label>
                                <input name="category_title" id="category_title" type="text" value="'.(isset($category) ? $category->title : null).'" size="40" aria-required="true" />
                                <p>The name is how it appears on your site.</p>
                            </div>
                            <p class="submit"><input type="submit" name="submit" id="submit" class="button" value="'.(($edit) ? 'Save' : 'Add New Category').'" /></p>
                            '.(($edit) ? '<p><a href="?page='.$_REQUEST['page'].'">Cancel</a></p>' : '').'
                        </form><!-- #dls_sus_'.(($edit) ? 'edit' : 'add').'_category -->
                        
                    </div><!-- .form-wrap -->
                
                </div><!-- .col-wrap -->
            </div><!-- #col-left -->
        
        </div><!-- #col-container -->
        
        ';
        
        echo '</div><!-- .wrap -->';
        
    }

    /**
     * Override WordPress Footer
     *
     * @param string $admin_footer_text
     * @return string
     */
    public function admin_footer_text($admin_footer_text)
    {
        if (isset($_REQUEST['page']) && strpos($_REQUEST['page'], 'dls-sus-') === 0) {
            $admin_footer_text = '
                <a href="' . __('https://www.dlssoftwarestudios.com/sign-up-sheets-wordpress-plugin/', 'dls-sus') . '" id="dls-sus-footer-logo" title="' . __('DLS Software Studios', 'dls-sus') . '" target="_blank">' . __('DLS Software Studios', 'dls-sus' ) . '</a>
            ';
        }

        return $admin_footer_text;
    }

    /**
     * Add settings link on plugin page
     *
     * @param array $links
     *
     * @return array
     */
    function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=' . $this->admin_settings_slug . '">Settings</a>';
        array_unshift($links, $settings_link);

        return $links;
    }
    
}