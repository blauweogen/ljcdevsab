<?php
/**
 * Database queries and actions
 */
if (!class_exists('DLS_SUS_Sheet')) require_once dirname(__FILE__).'/sheet.php';

class DLS_SUS_Data
{
    
    public $wpdb;
    public $err;
    public $tables = array();
    public $detailed_errors = false;
    public $plugin_version = '2.0.18.1';
    public $plugin_prefix = 'dls-sus';
    public $sheet_fields;
    public $text = array( // Text that can be overridden in Settings
        'task_title_label' => array (
            'label'     => 'Task Title Label',
            'default'   => 'What',
        )
    );
    
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        
        if (get_option('dls_sus_detailed_errors') === 'true') {
            $this->detailed_errors = true;
        }

        $this->set_text_overrides();

        // Set additional sheet fields
        $true_false = array(
            '' => 'Global',
            'true' => 'True',
            'false' => 'False',
        );
        $this->sheet_fields = array(
            array(
                'name' => 'Sheet Specific BCC',
                'slug' => 'sheet_bcc',
                'type' => 'text',
                'group' => 'settings',
                'options' => null,
                'note' => '(comma-separated list of emails to be copied on sign-up confirmations/removals for this sheet)',
            ),
            array(
                'name' => 'Set Phone as Optional',
                'slug' => 'optional_phone',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => '(overrides global setting in Settings > Sign-up Sheets)',
            ),
            array(
                'name' => 'Set Address as Optional',
                'slug' => 'optional_address',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => '(overrides global setting in Settings > Sign-up Sheets)',
            ),
            array(
                'name' => 'Hide Phone Field',
                'slug' => 'hide_phone',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => '(overrides global setting in Settings > Sign-up Sheets)',
            ),
            array(
                'name' => 'Hide Address Fields',
                'slug' => 'hide_address',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => '(overrides global setting in Settings > Sign-up Sheets)',
            ),
            array(
                'name' => 'Compact Sign-ups',
                'slug' => 'compact_signups',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => '(Show sign-up spots on one line with just # of open spots and a link to sign-up if open. Overrides global setting in Settings > Sign-up Sheets.)',
            ),
            array(
                'name' => 'Use task dates instead',
                'slug' => 'use_task_dates',
                'type' => 'checkbox',
                'group' => 'other',
                'options' => null,
                'note' => null,
            ),
        );
        
        // Set table names
        $this->tables = array(
            'sheet' => array(
                'name' => $this->wpdb->prefix.'dls_sus_sheets',
                'allowed_fields' => array(
                    'title' => false,
                    'details' => false,
                    'date' => false,
                    'trash' => false,
                ),
            ),
            'task' => array(
                'name' => $this->wpdb->prefix.'dls_sus_tasks',
                'allowed_fields' => array(
                    'sheet_id' => false,
                    'title' => false,
                    'qty' => false,
                    'position' => false,
                    'date' => false,
                ),
            ),
            'signup' => array(
                'name' => $this->wpdb->prefix.'dls_sus_signups',
                'allowed_fields' => array(
                    'task_id' => false,
                    'firstname' => false,
                    'lastname' => false,
                    'email' => false,
                    'phone' => false,
                    'address' => false,
                    'city' => false,
                    'state' => false,
                    'zip' => false,
                    'removal_token' => false,
                ),
            ),
            'category' => array(
                'name' => $this->wpdb->prefix.'dls_sus_categories',
                'allowed_fields' => array(
                    'title' => false,
                ),
            ),
            'sheet_category' => array(
                'name' => $this->wpdb->prefix.'dls_sus_sheets_categories',
                'allowed_fields' => array(
                    'sheet_id' => false,
                    'category_id' => false,
                ),
            ),
            'field' => array(
                'name' => $this->wpdb->prefix.'dls_sus_fields',
                'allowed_fields' => array(
                    'entity_type' => false, // Ex: signup, sheet, task
                    'entity_id' => false,
                    'slug' => false,
                    'value' => false,
                ),
            ),
            'sheet_field' => array(
                'name' => $this->wpdb->prefix.'dls_sus_sheet_fields',
                'allowed_fields' => array(
                    'sheet_id' => false,
                    'slug' => false,
                    'value' => false,
                ),
            ),
        );

    }

    /**
     * Set text override values
     */
    public function set_text_overrides()
    {
        foreach ($this->text as $key=>$text) {
            $override = get_option('dls_sus_text_'.$key, false);
            if ($override === false || $override === '') {
                $this->text[$key]['value'] = $this->text[$key]['default'];
            } else {
                $this->text[$key]['value'] = $override;
            }
        }
        reset($this->text);
    }
     
    /**
     * Get all Sheets
     * 
     * @param     bool     $trash get just trash
     * @param     bool     $active_only get only active sheets or those without a set date
     * @param     id|bool  $category_id
     * @param     string   $sort
     * @return    mixed    array of sheets
     */
    public function get_sheets($trash=false, $active_only=false, $category_id=false, $sort='end_date DESC, s.id DESC')
    {
        if ($category_id !== false) $category_id = (int) $category_id;
        $sort_string = (!empty($sort)) ? "ORDER BY $sort" : "";

        $this->wpdb->query('SET SESSION SQL_BIG_SELECTS = 1');
        $sql = "
            SELECT
                s.*
                , IF(use_task_dates.`value` IS TRUE, TRUE, FALSE) AS use_task_dates
                , IF(use_task_dates.`value` IS TRUE, t_start_date.date, s.date) AS start_date
                , IF(use_task_dates.`value` IS TRUE, t_end_date.date, s.date) AS end_date
            FROM " . $this->tables['sheet']['name'] . " s
            " . ((!empty($category_id)) ? " INNER JOIN " . $this->tables['sheet_category']['name'] . " c ON s.id = c.sheet_id AND c.category_id = {$category_id}" : "") . "

            -- Use Task Dates
            LEFT OUTER JOIN (
                SELECT sheet_id, IF(`value` = 'true', TRUE, FALSE) AS `value` FROM " . $this->tables['sheet_field']['name'] . " WHERE slug = 'use_task_dates' GROUP BY sheet_id, slug
            ) use_task_dates ON use_task_dates.sheet_id = s.id

            -- Start Date
            LEFT OUTER JOIN (
                SELECT sheet_id, MIN(`DATE`) AS `date` FROM " . $this->tables['task']['name'] . " WHERE `DATE` <> '0000-00-00' GROUP BY `sheet_id`
            ) t_start_date ON s.id = t_start_date.sheet_id

            -- End Date
            LEFT OUTER JOIN (
                SELECT sheet_id, MAX(`DATE`) AS `date` FROM " . $this->tables['task']['name'] . " WHERE `DATE` <> '0000-00-00' GROUP BY `sheet_id`
            ) t_end_date ON s.id = t_end_date.sheet_id

            WHERE
                s.trash = " . (($trash) ? "TRUE" : "FALSE") . "
                " . (($active_only) ? "
                    AND (
                        (IF(use_task_dates.`value` IS TRUE, TRUE, FALSE) = FALSE AND (s.date >= DATE_FORMAT(NOW(), '%Y-%m-%d') OR s.date = '0000-00-00'))
                        OR (IF(use_task_dates.`value` IS TRUE, TRUE, FALSE) = TRUE AND IF(use_task_dates.`value` IS TRUE, t_end_date.date, s.date) >= DATE_FORMAT(NOW(), '%Y-%m-%d'))
                    )
                " : "") . "
            $sort_string
        ";
        $results = $this->wpdb->get_results($sql);
        foreach ($results as $key=>$result) $results[$key]->fields = $this->get_sheet_fields($result->id);
        $results = $this->stripslashes_full($results);
        return $results;
    }
     
    /**
     * Get one sheet by id
     *
     * @param     int      $id
     * @return    object   sheet object
     */
    public function get_sheet($id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM ".$this->tables['sheet']['name']." WHERE id = %d" , $id));
        foreach ($results as $key=>$result) $results[$key]->fields = $this->get_sheet_fields($result->id);
        $results = $this->stripslashes_full($results);
        return $results[0];
    }
    
    /**
     * Get number of sheets
     */
    public function get_sheet_count($trash=false)
    { 
        $results = $this->wpdb->get_results("
            SELECT COUNT(*) AS count 
            FROM ".$this->tables['sheet']['name']." 
            WHERE trash = ".(($trash) ? "TRUE" : "FALSE")."
        ");
        $results = $this->stripslashes_full($results);
        return $results[0]->count;
    }
    
    /**
     * Get tasks by sheet
     * 
     * @param     int      $sheet_id
     * @return    mixed    array of tasks
     */
    public function get_tasks($sheet_id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM ".$this->tables['task']['name']." WHERE sheet_id = %d ORDER BY position, id" , $sheet_id));
        foreach ($results as $key=>$result) $results[$key]->fields = $this->get_fields('task', $result->id);
        $results = $this->stripslashes_full($results);
        return $results;
    }
     
    /**
     * Get single task
     * 
     * @param     int      $id task id
     * @return    mixed    task
     */
    public function get_task($id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM ".$this->tables['task']['name']." WHERE id = %d" , $id));
        foreach ($results as $key=>$result) $results[$key]->fields = $this->get_fields('task', $result->id);
        $results = $this->stripslashes_full($results);
        return $results[0];
    }
    
    /**
     * Get signups by task
     * 
     * @param    int      $task_id
     * @return   mixed    signups
     */
    public function get_signups($task_id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM ".$this->tables['signup']['name']." WHERE task_id = %d ORDER BY id" , $task_id));
        foreach ($results as $key=>$result) $results[$key]->fields = $this->get_fields('signup', $result->id);
        $results = $this->stripslashes_full($results);
        return $results;
    }
     
    /**
     * Get single signup
     * 
     * @param     int      $id
     * @return    mixed    signup
     * @throws    DLS_SUS_Data_Exception
     */
    public function get_signup($id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM ".$this->tables['signup']['name']." WHERE id = %d" , $id));
        foreach ($results as $key=>$result) $results[$key]->signup_fields = $this->get_fields('signup', $result->id);
        $results = $this->stripslashes_full($results);
        if (mysql_errno() > 0) {
            throw new DLS_SUS_Data_Exception('Error getting signup record.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        }
        return $results[0];
    }

    /**
     * Get custom fields
     *
     * @param string $entity_type (ex: signup, sheet, task)
     * @param int $entity_id
     * @return mixed
     */
    public function get_fields($entity_type, $entity_id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM ".$this->tables['field']['name']." WHERE entity_type = %s AND entity_id = %d" , $entity_type, $entity_id));
        $fields = array();
        foreach ($results as $result) {
            $fields[$result->slug] = maybe_unserialize($result->value);
        }
        return $fields;
    }

    /**
     * Get custom sheet fields
     *
     * @param    int      $sheet_id
     * @return   mixed    signups
     */
    public function get_sheet_fields($sheet_id)
    {
        $fields = array();

        // Set default sheet fields
        foreach ($this->sheet_fields as $sheet_fields) {
            $fields[$sheet_fields['slug']] = null;
        }
        reset($this->sheet_fields);

        // Get sheet field data
        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM ".$this->tables['sheet_field']['name']." WHERE sheet_id = %d" , $sheet_id));
        foreach ($results as $result) {
            $fields[$result->slug] = maybe_unserialize($result->value);
        }

        return $fields;
    }
     
    /**
     * Get all signups that need reminding
     * 
     * @return    mixed    signups
     */
    public function get_signups_that_need_reminding()
    {
        $days_before = get_option('dls_sus_reminder_email_days_before');
        $days_before = ($days_before === false) ? 1 : (int) $days_before;
        $now_date = date('Y-m-d', current_time('timestamp'));
        $date = date('Y-m-d', strtotime($now_date." + $days_before days"));
        
        $sql = $this->wpdb->prepare("
            SELECT
                signup.id AS signup_id
                , signup.firstname
                , signup.lastname
                , signup.email
                , task.id AS task_id
                , task.title AS task_title
                , sheet.id AS sheet_id
                , sheet.title AS sheet_title
                , sheet.date AS sheet_date
                , IF(use_task_dates.`value` IS TRUE, task.date, sheet.date) AS signup_date
            FROM ".$this->tables['sheet']['name']." sheet

                -- Use Task Dates
                LEFT OUTER JOIN (
                    SELECT sheet_id, IF(`value` = 'true', TRUE, FALSE) AS `value` FROM ".$this->tables['sheet_field']['name']." WHERE slug = 'use_task_dates' GROUP BY sheet_id, slug
                ) use_task_dates ON use_task_dates.sheet_id = sheet.id

                INNER JOIN ".$this->tables['task']['name']." task ON sheet.id = task.sheet_id
                INNER JOIN ".$this->tables['signup']['name']." signup ON task.id = signup.task_id
            WHERE sheet.trash = 0
                AND IF(use_task_dates.`value` IS TRUE, task.date, sheet.date) = %s
                AND signup.reminded IS NULL
        ", $date);
        
        $results = $this->wpdb->get_results($sql);
        $results = $this->stripslashes_full($results);
        return $results;
    }

    /**
     * Set signup as reminded
     *
     * @param   int     $id
     * @return  mixed
     * @throws  DLS_SUS_Data_Exception
     */
    public function set_signup_as_reminded($id)
    {
        $result = $this->wpdb->update($this->tables['signup']['name'], array('reminded' => current_time('mysql', 1)), array('id' => $id), array('%s'), array('%d'));
        if ($result === false) throw new DLS_SUS_Data_Exception('Error setting signup as reminded.'. (($this->detailed_errors === true) ? '... '.print_r(mysql_error(), true) : ''));
        return $result;
    }
     
    /**
     * Get all categories
     * 
     * @return    mixed    signup
     * @throws    DLS_SUS_Data_Exception
     */
    public function get_categories()
    {
        $results = $this->wpdb->get_results("SELECT * FROM ".$this->tables['category']['name']);
        $results = $this->stripslashes_full($results);
        if (mysql_errno() > 0) {
            throw new DLS_SUS_Data_Exception('Error getting category records.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        }
        return $results;
    }
     
    /**
     * Get all category by id
     * 
     * @param     int           $id
     * @return    mixed|bool    signup
     * @throws    DLS_SUS_Data_Exception
     */
    public function get_category($id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM ".$this->tables['category']['name']." WHERE id = %d" , $id));
        $results = $this->stripslashes_full($results);
        if (mysql_errno() > 0) {
            throw new DLS_SUS_Data_Exception('Error getting category record.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        }
        return $results[0];
    }
     
    /**
     * Get all categories by sheet
     *
     * @param     int           $sheet_id
     * @return    mixed|bool    signup
     * @throws    DLS_SUS_Data_Exception
     */
    public function get_categories_by_sheet($sheet_id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("
            SELECT sc.* FROM ".$this->tables['sheet_category']['name']." sc 
            LEFT JOIN ".$this->tables['category']['name']." c ON sc.category_id = c.id
            WHERE sc.sheet_id = %d
        ", $sheet_id));
        $results = $this->stripslashes_full($results);
        if (mysql_errno() > 0) {
            throw new DLS_SUS_Data_Exception('Error getting category records.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        }
        return $results;
    }

    /**
     * Get all data
     *
     * @param   int|null    $sheet_id
     * @param   bool        $trash
     * @return  mixed       array of signups
     */
    public function get_all_data($sheet_id=null, $trash=false)
    {
        $this->wpdb->query('SET SESSION SQL_BIG_SELECTS = 1');
        $results = $this->wpdb->get_results($this->wpdb->prepare("
            SELECT
                sheet.id AS sheet_id
                , sheet.title AS sheet_title
                , sheet.details AS sheet_details
                , sheet.date AS sheet_date
                , sheet.trash AS sheet_trash
                , task.id AS task_id
                , task.title AS task_title
                , task.date AS task_date
                , task.qty AS task_qty
                , task.position AS task_position
                , signup.id AS signup_id
                , firstname
                , lastname
                , email
                , phone
                , reminded
            FROM  ".$this->tables['sheet']['name']." sheet
            LEFT JOIN ".$this->tables['task']['name']." task ON sheet.id = task.sheet_id
            LEFT JOIN ".$this->tables['signup']['name']." signup ON task.id = signup.task_id
            WHERE 1=1
            " . (!empty($sheet_id) ? " AND sheet.id = %s " : null) . "
            AND sheet.trash = ".(($trash) ? "TRUE" : "FALSE")."
            ORDER BY sheet_id, task_position, signup_id
        ", $sheet_id));

        foreach ($results as $key=>$result) $results[$key]->sheet_fields = $this->get_sheet_fields($result->sheet_id);
        foreach ($results as $key=>$result) $results[$key]->signup_fields = $this->get_fields('signup', $result->signup_id);
        $results = $this->stripslashes_full($results);

        return $results;
    }
    
    /**
    * Get number of signups on a specific sheet
    * 
    * @param    int    $id sheet id
    */
    public function get_sheet_signup_count($id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("
            SELECT COUNT(*) AS count FROM ".$this->tables['task']['name']." t
            RIGHT OUTER JOIN ".$this->tables['signup']['name']." s ON t.id = s.task_id 
            WHERE t.sheet_id = %d
        ", $id));
        return $results[0]->count;
    }
    
    /**
    * Get number of total spots on a specific sheet
    * 
    * @param    int    $id sheet id
    */
    public function get_sheet_total_spots($id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("
            SELECT SUM(qty) AS total FROM ".$this->tables['task']['name']." t
            WHERE t.sheet_id = %d
        ", $id));
        return $results[0]->total;
    }

    /**
     * Add a new sheet
     *
     * @param array $fields array of fields and values to insert
     * @return mixed
     * @throws DLS_SUS_Data_Exception|Exception
     */
    public function add_sheet($fields)
    {
        $clean_fields = $this->clean_array($fields, 'sheet_');
        $clean_fields = array_intersect_key($clean_fields, $this->tables['sheet']['allowed_fields']);
        if (isset($clean_fields['date'])) {
            if ($clean_fields['date'] == '') $clean_fields['date'] = '0000-00-00';
            if ($clean_fields['date'] != '0000-00-00') $clean_fields['date'] = date('Y-m-d', strtotime($clean_fields['date']));
        }
        $result = $this->wpdb->insert($this->tables['sheet']['name'], $clean_fields);
        $sheet_id = $this->wpdb->insert_id;
        if ($result === false) throw new DLS_SUS_Data_Exception('Error adding sheet.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));

        // Sheet Fields
        if (is_wp_error($sheet_field_result = $this->add_update_sheet_fields($fields, $this->wpdb->insert_id))) {
            throw new DLS_SUS_Data_Exception($sheet_field_result->get_error_message());
        }

        return ($result !== false) ? $sheet_id : $result;
    }

    /**
     * Add a new task
     *
     * @param array $fields array of fields and values to insert
     * @param int $sheet_id
     * @return mixed
     * @throws DLS_SUS_Data_Exception
     */
    public function add_task($fields, $sheet_id)
    {
        $clean_fields = $this->clean_array($fields, 'task_');
        $clean_fields = array_intersect_key($clean_fields, $this->tables['task']['allowed_fields']);
        $clean_fields['sheet_id'] = $sheet_id;
        if ($clean_fields['qty'] < 2) $clean_fields['qty'] = 1;
        if (isset($clean_fields['date'])) {
            if ($clean_fields['date'] == '') $clean_fields['date'] = '0000-00-00';
            if ($clean_fields['date'] != '0000-00-00') $clean_fields['date'] = date('Y-m-d', strtotime($clean_fields['date']));
        }
        $result = $this->wpdb->insert($this->tables['task']['name'], $clean_fields);
        if ($result === false) throw new DLS_SUS_Data_Exception('Error adding task.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));

        $task_id = $this->wpdb->insert_id;
        $sheet = new DLS_SUS_Sheet($sheet_id);

        // Add custom fields
        $custom_fields = array();
        if (!empty($sheet->custom_fields['task'])) {
            foreach ($sheet->custom_fields['task'] as $key=>$field) {
                $slug = str_replace('-', '_', $field['slug']);
                if (isset($fields['task_'.$slug])) {
                    $custom_fields[] = array(
                        'entity_type' => 'task',
                        'entity_id' => $task_id,
                        'slug' => $slug,
                        'value' => maybe_serialize($fields['task_'.$slug])
                    );
                }
            }
            reset($sheet->custom_fields['task']);
        }
        foreach ($custom_fields as $row) {
            $result = $this->wpdb->insert($this->tables['field']['name'], $row);
            if ($result === false && $this->detailed_errors === true) {
                throw new DLS_SUS_Data_Exception('Error adding custom fields to task.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
                return false;
            }
        }

        return $result;
    }

    /**
     * Add a new signup to a task
     *
     * @param array $fields array of fields and values to insert
     * @param int $task_id
     * @return bool
     * @throws DLS_SUS_Data_Exception
     */
    public function add_signup($fields, $task_id)
    {
        $clean_fields = $this->clean_array($fields, 'signup_');
        $clean_fields = array_intersect_key($clean_fields, $this->tables['signup']['allowed_fields']);
        $clean_fields['task_id'] = $task_id;
        $clean_fields['date_created'] = date('Y-m-d H:i:s');
        $clean_fields['removal_token'] = $this->generate_token();
        
        // Check if signup spots are filled
        $task = $this->get_task($task_id);
        $sheet = new DLS_SUS_Sheet($task->sheet_id);
        $signups = $this->get_signups($task_id);
        if (count($signups) >= $task->qty) {
            throw new DLS_SUS_Data_Exception('Error adding signup.  All spots are filled.'. (($this->detailed_errors === true) ? ' Current Signups: '.count($signups).', Total Spots:'.$task->qty : ''));
            return false;
        }

        // Add main signup
        $result = $this->wpdb->insert($this->tables['signup']['name'], $clean_fields);
        if ($result === false) {
            throw new DLS_SUS_Data_Exception('Error adding signup.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
            return false;
        }
        $signup_id = $this->wpdb->insert_id;

        // Add custom fields
        $custom_fields = array();
        if (!empty($sheet->custom_fields['signup'])) {
            foreach ($sheet->custom_fields['signup'] as $key=>$field) {
                $slug = str_replace('-', '_', $field['slug']);
                if (isset($fields['signup_'.$slug])) {
                    $custom_fields[] = array(
                        'entity_type' => 'signup',
                        'entity_id' => $signup_id,
                        'slug' => $slug,
                        'value' => maybe_serialize($fields['signup_'.$slug])
                    );
                }
            }
            reset($sheet->custom_fields['signup']);
        }
        foreach ($custom_fields as $row) {
            $result = $this->wpdb->insert($this->tables['field']['name'], $row);
            if ($result === false && $this->detailed_errors === true) {
                throw new DLS_SUS_Data_Exception('Error adding custom fields to signup.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
                return false;
            }
        }

        return $signup_id;
    }
    
    /**
     * Add a category
     * 
     * @param   array       array of fields and values to insert
     * @return  int|bool
     * @throws  DLS_SUS_Data_Exception
     */
    public function add_category($fields) {
        $clean_fields = $this->clean_array($fields, 'category_');
        $clean_fields = array_intersect_key($clean_fields, $this->tables['category']['allowed_fields']);
        $result = $this->wpdb->insert($this->tables['category']['name'], $clean_fields);
        if ($result === false) throw new DLS_SUS_Data_Exception('Error adding category.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        return $result;
    }
    
    /**
     * Assign a category to a sheet
     * 
     * @param   int         sheet id
     * @param   int         category id
     * @return  int|false|WP_Error
     */
    public function add_sheet_category($sheet_id, $category_id) {
        $clean_fields = array(
            'sheet_id' => $sheet_id,
            'category_id' => $category_id,
        );
        $result = $this->wpdb->insert($this->tables['sheet_category']['name'], $clean_fields);
        if ($result === false) return $this->err('dlssus_add_sheet_category', 'Error assigning category to sheet.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        return $result;
    }
    
    /**
     * Update a sheet
     * 
     * @param    array    $fields array of fields and values to update
     * @param    int      $id
     * @return   mixed    number of rows update or false if fails
     * @throws   DLS_SUS_Data_Exception|Exception
     */
    public function update_sheet($fields, $id)
    {
        // Main Sheet
        $clean_fields = $this->clean_array($fields, 'sheet_');
        $clean_fields = array_intersect_key($clean_fields, $this->tables['sheet']['allowed_fields']);
        if (isset($clean_fields['date'])) {
            if ($clean_fields['date'] == '') $clean_fields['date'] = '0000-00-00';
            if ($clean_fields['date'] != '0000-00-00') $clean_fields['date'] = date('Y-m-d', strtotime($clean_fields['date']));
        }
        $result = $this->wpdb->update($this->tables['sheet']['name'], $clean_fields, array('id' => $id), null, array('%d'));
        if ($result === false) throw new DLS_SUS_Data_Exception('Error updating sheet.'. (($this->detailed_errors === true) ? '... '.print_r(mysql_error(), true) : ''));

        // Sheet Fields
        if (is_wp_error($sheet_field_result = $this->add_update_sheet_fields($fields, $id))) {
            throw new DLS_SUS_Data_Exception($sheet_field_result->get_error_message());
        }

        return $result;
    }

    /**
     * Add or Update Sheet Field records
     *
     * @param array $fields
     * @param int $sheet_id
     * @return mixed|WP_Error
     */
    public function add_update_sheet_fields($fields, $sheet_id, $skip_clean=false)
    {
        if ($skip_clean) {
            $clean_sheet_fields = $fields;
        } else {
            $clean_sheet_fields = $this->clean_array($fields, 'sheetfield_');
            $sheet_fields_keys = array();
            foreach ($this->sheet_fields as $field) {
                $sheet_fields_keys[$field['slug']] = null;
                // Add missing elements (especially for unchecked checkboxes where is the key isn't passed in the post)
                if (!isset($clean_sheet_fields[$field['slug']])) $clean_sheet_fields[$field['slug']] = null;
            }
            reset($this->sheet_fields);
            $clean_sheet_fields = array_intersect_key($clean_sheet_fields, $sheet_fields_keys);
        }

        foreach ($clean_sheet_fields as $key=>$value) {
            $data = array(
                'sheet_id' => $sheet_id,
                'slug' => $key,
                'value' => $value,
            );
            if ($value === null || $value === '') {
                unset($data['value']);
                $result = $this->wpdb->delete($this->tables['sheet_field']['name'], $data, array('%d', '%s'));
            } else {
                $result = $this->wpdb->replace($this->tables['sheet_field']['name'], $data, array('%d', '%s', '%s'));
            }
            if ($result === false) return $this->err('dlssus_update_sheet_fields', 'Error updating sheet field.'. (($this->detailed_errors === true) ? '... '.print_r(mysql_error(), true) : ''));
        }

        return $result;
    }
    
    /**
     * Update a task
     * 
     * @param    array    $fields array of fields and values to update
     * @param    int      $id task id
     * @return   mixed    number of rows update or false if fails
     * @throws   DLS_SUS_Data_Exception
     */
    public function update_task($fields, $id)
    {
        // Clean Data
        $clean_fields = $this->clean_array($fields, 'task_');
        $clean_fields = array_intersect_key($clean_fields, $this->tables['task']['allowed_fields']);
        if (isset($clean_fields['date'])) {
            if ($clean_fields['date'] == '') $clean_fields['date'] = '0000-00-00';
            if ($clean_fields['date'] != '0000-00-00') $clean_fields['date'] = date('Y-m-d', strtotime($clean_fields['date']));
        }
        if ($clean_fields['qty'] < 2) $clean_fields['qty'] = 1;
        
        // Error Handling
        $signup_count = count($this->get_signups($id));
        if ($signup_count > $clean_fields['qty']) throw new DLS_SUS_Data_Exception('Could not update the number of people needed on task "'.$clean_fields['title'].'" to be "'.$clean_fields['qty'].'" because the number of signups is already "'.$signup_count.'".  You will need to clear spots before adjusting this number.');
        
        // Process
        $result = $this->wpdb->update($this->tables['task']['name'], $clean_fields, array('id' => $id), null, array('%d'));
        if ($result === false) throw new DLS_SUS_Data_Exception('Error updating task.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));

        $sheet = new DLS_SUS_Sheet($fields['task_sheet_id']);

        // Update custom fields
        $custom_fields = array();
        if (!empty($sheet->custom_fields['task'])) {
            foreach ($sheet->custom_fields['task'] as $field) {
                $slug = str_replace('-', '_', $field['slug']);
                if (!isset($fields['task_'.$slug])) continue;
                $custom_fields[] = array(
                    'entity_type' => 'task',
                    'entity_id' => $id,
                    'slug' => $slug,
                    'value' => maybe_serialize($fields['task_'.$slug])
                );
            }
            reset($sheet->custom_fields['task']);
        }
        foreach ($custom_fields as $row) {
            $result = $this->wpdb->replace($this->tables['field']['name'], $row, array('%s', '%d', '%s', '%s'));
            if ($result === false && $this->detailed_errors === true) {
                throw new DLS_SUS_Data_Exception('Error adding custom fields to task.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
                return false;
            }
        }

        return $result;
    }
    
    /**
     * Update a category
     * 
     * @param    array    array of fields and values to update
     * @param    int      id
     * @return   mixed    number of rows update or false if fails
     * @throws   DLS_SUS_Data_Exception
     */
    public function update_category($fields, $id)
    {
        $clean_fields = $this->clean_array($fields, 'category_');
        $clean_fields = array_intersect_key($clean_fields, $this->tables['category']['allowed_fields']);
        $result = $this->wpdb->update($this->tables['category']['name'], $clean_fields, array('id' => $id), null, array('%d'));
        if ($result === false) throw new DLS_SUS_Data_Exception('Error updating category.'. (($this->detailed_errors === true) ? '... '.print_r(mysql_error(), true) : ''));
        return $result;
    }
    
    /**
     * Delete a sheet and all associated tasks and signups
     * 
     * @param    int    $id     sheet id
     * @return   bool
     * @throws   DLS_SUS_Data_Exception
     */
    public function delete_sheet($id)
    {
        try {
            
            // Delete sheet_category links
            $this->delete_sheet_category($id, 'sheet');
        
            // Delete Signups
            $tasks = $this->get_tasks($id);
            foreach ($tasks AS $task) {
                if ($this->wpdb->query($this->wpdb->prepare("DELETE FROM ".$this->tables['signup']['name']." WHERE task_id = %d" , $task->id)) === false) {
                    throw new DLS_SUS_Data_Exception('Error deleting signups from task #'.$task->id.' on sheet.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
                }
            }
            
            // Delete Tasks
            if ($this->wpdb->query($this->wpdb->prepare("DELETE FROM ".$this->tables['task']['name']." WHERE sheet_id = %d" , $id)) === false) {
                throw new DLS_SUS_Data_Exception('Error deleting tasks on sheet.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
            }
            
            // Delete Sheet
            if ($this->wpdb->query($this->wpdb->prepare("DELETE FROM ".$this->tables['sheet']['name']." WHERE id = %d" , $id)) === false) {
                throw new DLS_SUS_Data_Exception('Error deleting sheet.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
            }
            
        } catch (DLS_SUS_Data_Exception $e) {
            throw new DLS_SUS_Data_Exception($e->getMessage());
        }
        
        return true;
    }
    
    /**
     * Delete a task
     * 
     * @param    int     $id
     * @return   array
     * @throws   DLS_SUS_Data_Exception
     */
    public function delete_task($id)
    {
        $result = $this->wpdb->query($this->wpdb->prepare("DELETE FROM ".$this->tables['task']['name']." WHERE id = %d" , $id));
        if ($result === false) throw new DLS_SUS_Data_Exception('Error deleting task.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        return $result;
    }
    
    /**
     * Delete a signup
     * 
     * @param    int     $id
     * @return   array
     * @throws   DLS_SUS_Data_Exception
     */
    public function delete_signup($id)
    {
        $result = $this->wpdb->query($this->wpdb->prepare("DELETE FROM ".$this->tables['signup']['name']." WHERE id = %d" , $id));
        if ($result === false) throw new DLS_SUS_Data_Exception('Error deleting signup.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM ".$this->tables['field']['name']." WHERE entity_type = 'signup' AND entity_id = %d" , $id));
        return $result;
    }
    
    /**
     * Delete a category
     * 
     * @param    int     $id
     * @return   array
     * @throws   DLS_SUS_Data_Exception
     */
    public function delete_category($id)
    {
        try {
            $this->delete_sheet_category($id, 'category');
            $result = $this->wpdb->query($this->wpdb->prepare("DELETE FROM ".$this->tables['category']['name']." WHERE id = %d" , $id));
            if ($result === false) throw new DLS_SUS_Data_Exception('Error deleting category.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        } catch (DLS_SUS_Data_Exception $e) {
            throw new DLS_SUS_Data_Exception($e);
        }
        return $result;
    }
    
    /**
     * Delete a sheet_category
     * 
     * @param    int     $id
     * @param    string  $type of id (null, sheet, category) - null = main auto increment id
     * @return   array
     * @throws   DLS_SUS_Data_Exception
     */
    public function delete_sheet_category($id, $type=null)
    {
        $allowed_types = array(null, 'sheet', 'category');
        if (!in_array($type, $allowed_types)) {
            throw new DLS_SUS_Data_Exception('Error removing category from sheet (id type not allowed).'. (($this->detailed_errors === true) ? '.. Type = '.$type : ''));
        }
        if (!is_null($type)) $type = $type.'_';
        $result = $this->wpdb->query($this->wpdb->prepare("DELETE FROM ".$this->tables['sheet_category']['name']." WHERE {$type}id = %d" , $id));
        if ($result === false) throw new DLS_SUS_Data_Exception('Error removing category from sheet.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        return $result;
    }
    
    /**
     * Copy a sheet and all tasks to a new sheet for editing
     * 
     * @param    int     $id sheet id
     * @return   int     new sheet id
     * @throws   DLS_SUS_Data_Exception
     */
    public function copy_sheet($id)
    {
        $new_fields = array();
        
        $sheet = new DLS_SUS_Sheet($id);

        // Add new sheet
        foreach ($this->tables['sheet']['allowed_fields'] AS $field=>$nothing) {
            $new_fields['sheet_'.$field] = $sheet->$field.(($field == 'title') ? " (Copy)" : "");
        }
        try {
            if ($this->add_sheet($new_fields) === false) return false;
        } catch (DLS_SUS_Data_Exception $e) {
            throw new DLS_SUS_Data_Exception($e);
        }

        $new_sheet_id = $this->wpdb->insert_id;

        // Add sheet fields to new sheet
        if (!empty($sheet->fields) && is_array($sheet->fields)) {
            if (is_wp_error($sheet_field_result = $this->add_update_sheet_fields($sheet->fields, $new_sheet_id, true))) {
                throw new DLS_SUS_Data_Exception($sheet_field_result->get_error_message());
            }
        }

        // Add tasks
        $tasks = $this->get_tasks($id);
        foreach ($tasks AS $task) {
            $new_fields = array();
            $task = (array)$task;
            foreach ($this->tables['task']['allowed_fields'] AS $field=>$nothing) {
                $new_fields['task_'.$field] = $task[$field];
            }
            try {
                if ($this->add_task($new_fields, $new_sheet_id) === false) return false;
            } catch (DLS_SUS_Data_Exception $e) {
                throw new DLS_SUS_Data_Exception($e);
            }
        }

        // Add categories
        if (!empty($sheet->categories) && is_array($sheet->categories)) {
            foreach ($sheet->categories as $category) {
                if (is_wp_error($result = $this->add_sheet_category($new_sheet_id, $category['category_id']))) {
                    throw new DLS_SUS_Data_Exception($result->get_error_message());
                }
            }
            reset($sheet->categories);
        }
        
        return $new_sheet_id;
    }
     
    /**
     * Check if an email is already listed on a signup for a task
     * 
     * @param    string    $email address
     * @param    int       $task_id
     * @return   bool
     * @throws   DLS_SUS_Data_Exception
     */
    public function isEmailOnTask($email, $task_id)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare("
            SELECT * FROM ".$this->tables['signup']['name']."
            WHERE task_id = %d
                AND email = %s
        ", $task_id, $email));
        $results = $this->stripslashes_full($results);
        if (mysql_errno() > 0) {
            throw new DLS_SUS_Data_Exception('Error checking if email exists for task.'. (($this->detailed_errors === true) ? '.. '.print_r(mysql_error(), true) : ''));
        }
        return (empty($results)) ? false : true;
    }

    /**
     * Add/remove manage_signup_sheets to all roles that need it
     */
    public function set_manage_signup_sheets()
    {
        global $wp_roles;
        $all_roles = $wp_roles->get_names();
        $manager_roles = get_option('dls_sus_roles');
        if (!is_array($all_roles)) $all_roles = array();
        $manager_roles[] = 'administrator';
        $manager_roles[] = 'signup_sheet_manager';

        foreach ($all_roles as $k=>$v) {
            $role = get_role($k);
            if (is_object($role)) {
                if (in_array($k, $manager_roles)) {
                    $role->add_cap('manage_signup_sheets');
                    if ($k == 'signup_sheet_manager') $role->add_cap('read');
                } else {
                    $role->remove_cap('manage_signup_sheets');
                }
            }
        }
    }

    public function remember_signup($signup_id)
    {
        $signup = $this->get_signup($signup_id);
        if (isset($_POST['dls_sus_remember']) && $_POST['dls_sus_remember'] === 'true') {
            $session = DLS_Session::start();
            $session_data[$this->plugin_prefix.'_remember'] = array(
                'firstname' => $signup->firstname,
                'lastname' => $signup->lastname,
                'email' => $signup->email,
                'phone' => $signup->phone,
                'address' => $signup->address,
                'city' => $signup->city,
                'state' => $signup->state,
                'zip' => $signup->zip,
            );
            $session->set($session_data);

        }
    }

    /**
     * Filter sheet field array by a key and value
     *
     * @param string $key
     * @param string $value
     * @return array
     */
    public function get_sheet_field_by($key, $value)
    {
        $new_fields = array();
        foreach ($this->sheet_fields as $field) {
            if (!isset($field[$key]) || $field[$key] != $value) continue;
            $new_fields[] = $field;
        }
        reset($this->sheet_fields);
        return $new_fields;
    }

    /**
     * Should the phone be displayed on the sheet?
     *
     * @param object $sheet
     * @return bool
     */
    public function show_phone($sheet)
    {
        return (
            (isset($sheet->fields['hide_phone']) && $sheet->fields['hide_phone'] === 'false')
            || (empty($sheet->fields['hide_phone']) && get_option('dls_sus_hide_phone') !== 'true')
        ) ? true : false;
    }

    /**
     * Should the address be displayed on the sheet?
     *
     * @param object $sheet
     * @return bool
     */
    public function show_address($sheet)
    {
        return (
            (isset($sheet->fields['hide_address']) && $sheet->fields['hide_address'] === 'false')
            || (empty($sheet->fields['hide_address']) && get_option('dls_sus_hide_address') !== 'true')
        ) ? true : false;
    }

    /**
     * Should the phone be displayed on the sheet?
     *
     * @param object $sheet
     * @return bool
     */
    public function phone_required($sheet)
    {
        return (
            (isset($sheet->fields['optional_phone']) && $sheet->fields['optional_phone'] === 'false')
            || (empty($sheet->fields['optional_phone']) && get_option('dls_sus_optional_phone') !== 'true')
        ) ? true : false;
    }

    /**
     * Should the address be displayed on the sheet?
     *
     * @param object $sheet
     * @return bool
     */
    public function address_required($sheet)
    {
        return (
            (isset($sheet->fields['optional_address']) && $sheet->fields['optional_address'] === 'false')
            || (empty($sheet->fields['optional_address']) && get_option('dls_sus_optional_address') !== 'true')
        ) ? true : false;
    }
    
    /**
     * Get list of states and abbreviations
     * 
     * @return   array   states
     */
    public function get_states()
    {
        $states = array(
            'AL' => "Alabama",
            'AK' => "Alaska",
            'AS' => "American Samoa",
            'AZ' => "Arizona",
            'AR' => "Arkansas",
            'CA' => "California",
            'CO' => "Colorado",
            'CT' => "Connecticut",
            'DE' => "Delaware",
            'DC' => "District Of Columbia",
            'FL' => "Florida",
            'FM' => "Federated States of Micronesia",
            'GA' => "Georgia",
            'GU' => "Guam",
            'HI' => "Hawaii",
            'ID' => "Idaho",
            'IL' => "Illinois",
            'IN' => "Indiana",
            'IA' => "Iowa",
            'KS' => "Kansas",
            'KY' => "Kentucky",
            'LA' => "Louisiana",
            'ME' => "Maine",
            'MD' => "Maryland",
            'MA' => "Massachusetts",
            'MH' => "Marshall Islands",
            'MI' => "Michigan",
            'MN' => "Minnesota",
            'MP' => "Northern Mariana Islands",
            'MS' => "Mississippi",
            'MO' => "Missouri",
            'MT' => "Montana",
            'NE' => "Nebraska",
            'NV' => "Nevada",
            'NH' => "New Hampshire",
            'NJ' => "New Jersey",
            'NM' => "New Mexico",
            'NY' => "New York",
            'NC' => "North Carolina",
            'ND' => "North Dakota",
            'OH' => "Ohio",
            'OK' => "Oklahoma",
            'OR' => "Oregon",
            'PA' => "Pennsylvania",
            'PR' => "Puerto Rico",
            'PW' => "Palau",
            'RI' => "Rhode Island",  
            'SC' => "South Carolina",  
            'SD' => "South Dakota",
            'TN' => "Tennessee",  
            'TX' => "Texas",
            'UT' => "Utah",
            'VT' => "Vermont",
            'VA' => "Virginia",
            'VI' => "Virgin Islands",
            'WA' => "Washington",
            'WV' => "West Virginia",
            'WI' => "Wisconsin",
            'WY' => "Wyoming"
        );
        return $states;
    }

    /**
     * Remove prefix from keys of an array and return records that were cleaned
     *
     * @param array $input
     * @param bool|string $prefix
     * @return array|bool records that were cleaned or false on error
     */
    public function clean_array($input=array(), $prefix=false)
    {
        if (!is_array($input)) return false;
        $clean_fields = array();
        foreach ($input AS $k=>$v) {
            if ($prefix === false || (substr($k, 0, strlen($prefix)) == $prefix)) {
                $clean_fields[str_replace($prefix, '', $k)] = ($prefix == 'signup_') ? sanitize_text_field($v) : $v;
            }
        }
        return $clean_fields;
    }
    
    /**
     * Remove slashes from strings, arrays and objects
     * 
     * @param    mixed   $input data
     * @return   mixed   cleaned input data
     */
    public function stripslashes_full($input)
    {
        if (is_array($input)) {
            $input = array_map(array('DLS_SUS_Data', 'stripslashes_full'), $input);
        } elseif (is_object($input)) {
            $vars = get_object_vars($input);
            foreach ($vars as $k=>$v) {
                $input->{$k} = $this->stripslashes_full($v);
            }
        } else {
            $input = stripslashes($input);
        }
        return $input;
    }

    /**
     * Convert options listed in string format into an array
     * Example...
     *   chicago:Chicago
     *   new_york:New York
     * Converts to...
     *   array (
     *       'chicago' => 'Chicago',
     *       'new_york' => 'New York'
     *   );
     *
     * @param string $string options as string
     * @return array options as array
     */
    public function options_string_to_array($string)
    {
        $options = array();
        if (!empty($string)) {
            $exploded_string = explode("\r\n", $string);
            foreach ($exploded_string as $option) {
                $o = explode(' : ', $option, 2);
                $options[$o[0]] = (isset($o[1])) ? $o[1] : $o[0];
            }
        }
        return $options;
    }
    
    /**
     * Generate Token
     * 
     * @return  string  random token
     */
    public function generate_token()
    {
        return sha1(uniqid(mt_rand(), true));
    }

    /**
     * Validate Signup Removal Token
     *
     * @param int $signup_id
     * @param string $token
     * @return bool
     * @throws DLS_SUS_Data_Exception
     */
    public function check_signup_removal_token($signup_id, $token)
    {
        $signup = $this->get_signup($signup_id);
        
        if (trim($token) != $signup->removal_token) {
            throw new DLS_SUS_Data_Exception('Invalid token.'. (($this->detailed_errors === true) ? ' Actual Token: '.$signup->removal_token.', Input Token: '.sanitize_text_field($token) : ''));
        } else {
            return true;
        }
    }

    /**
     * Get current URL
     *
     * @param bool $htmlspecialchars escape html chars using htmlspecialchars()
     * @return string url
     */
    public function get_current_url($htmlspecialchars=false)
    {
        if (!isset($_SERVER['REQUEST_URI'])) return null;
        $url = $_SERVER['REQUEST_URI'];
        return ($htmlspecialchars) ? htmlspecialchars($url, ENT_QUOTES) : $url;
    }

    /**
     * Build WP_Error
     *
     * @param string $code
     * @param string $message
     * @return WP_Error
     */
    public function err($code, $message)
    {
        if (!empty($this->err) && is_wp_error($this->err)) {
            $this->err->add($code, $message);
        } else {
            $this->err = new WP_Error($code, $message);
        }
        return $this->err;
    }
    
}

/**
 * Data Exception Class
 */
if (!class_exists('DLS_SUS_Data_Exception')) {
    class DLS_SUS_Data_Exception extends Exception{}
}