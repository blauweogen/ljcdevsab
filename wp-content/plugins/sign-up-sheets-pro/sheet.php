<?php
/**
 * Sign-up Sheets Admin Class
 */

if (!class_exists('DLS_SUS_Data')) require_once dirname(__FILE__).'/data.php';

class DLS_SUS_Sheet
{

    private $data;
    protected $_data;
    private $detailed_errors = false;
    public $plugin_prefix;
    public $custom_fields = array();
    public $categories = array();

    /**
     * Constructor
     *
     * @param $sheet_id
     */
    public function __construct($sheet_id)
    {
        $this->data = new DLS_SUS_Data();
        $this->plugin_prefix = $this->data->plugin_prefix;

        if (get_option('dls_sus_detailed_errors') === 'true') {
            $this->detailed_errors = true;
            $this->data->detailed_errors = true;
        }

        $this->_data = $this->data->get_sheet($sheet_id);
        $this->_set_custom_fields();
        $this->_set_categories();
    }

    /**
     * Get display code for tasks table (back and front-end)
     *
     * @param array $atts
     * @return string
     */
    public function get_tasks_table($atts=array())
    {
        extract(shortcode_atts(array(
            'show_clear' => false,
            'show_signup_link' => false,
            'admin_table' => false,
        ), $atts));

        $display_all = (get_option('dls_sus_display_all') === 'true' || $admin_table);
        $sheet_expired = ($this->date && $this->date != '0000-00-00' && strtotime($this->date.' 23:59:59') < current_time('timestamp')) ? true : false;
        $show_compact = $this->is_compact() && !$admin_table;
        $display_name = get_option('dls_sus_display_name');
        if ($display_all) $display_name = 'full';

        if (!($tasks = $this->data->get_tasks($this->id))) {
            return '<p>'.__('No tasks were found.', $this->plugin_prefix).'</p>';
        }

        // Custom Fields
        $custom_task_fields_header = null;
        if (!empty($this->custom_fields['task'])) {
            foreach ($this->custom_fields['task'] as $field) {
                $custom_task_fields_header .= '<th>'.$field['name'].'</th>';
            }
            reset($this->custom_fields['task']);
        }

        // Build signup header
        $signup_table_col_header = '
            <th>'.__($this->data->text['task_title_label']['value'], $this->plugin_prefix).'</th>
            '.($this->fields['use_task_dates'] === 'true' ? '<th>'.__('When', $this->plugin_prefix).'</th>' : null).'
            '.$custom_task_fields_header.'
            '.(($show_compact) ? '<th>'.__('Total Spots', $this->plugin_prefix).'</th>' : null).'
            <th>'.(($show_compact) ? __('Available Spots', $this->plugin_prefix) : __('Name', $this->plugin_prefix)).'</th>
        ';
        $display_custom_extra_fields_count = 0;
        if (!$show_compact) {
            if ($display_all) {
                $signup_table_col_header .= '<th>' . __('E-mail', $this->plugin_prefix) . '</th>';
                if ($this->data->show_phone($this)) {
                    $signup_table_col_header .= '<th>' . __('Phone', $this->plugin_prefix) . '</th>';
                }
                if ($this->data->show_address($this)) {
                    $signup_table_col_header .= '
                    <th>' . __('Address', $this->plugin_prefix) . '</th>
                    <th>' . __('City', $this->plugin_prefix) . '</th>
                    <th>' . __('State', $this->plugin_prefix) . '</th>
                    <th>' . __('Zip', $this->plugin_prefix) . '</th>
                ';
                }
            }
            if (!empty($this->custom_fields['signup'])) {
                foreach ($this->custom_fields['signup'] as $field) {
                    if (!$display_all && $field['frontend_results'] !== 'true') continue;
                    $display_custom_extra_fields_count++;
                    $signup_table_col_header .= '<th>'.$field['name'].'</th>';
                }
                reset($this->custom_fields['signup']);
            }
            if ($display_all) {
                $signup_table_col_header .= '<th>' . __('Reminded', $this->plugin_prefix) . ' *</th>';
                if ($show_clear === true) $signup_table_col_header .= '<th></th>';
            }
        }

        // Build Table
        $return = '
            <table class="dls-sus-tasks '.(($admin_table) ? 'wp-list-table widefat' : null).'" cellspacing="0">
                <thead><tr>'.$signup_table_col_header.'</tr></thead>
                <tfoot><tr>'.$signup_table_col_header.'</tr></tfoot>
                <tbody>
        ';

        foreach ($tasks AS $task) {
            $i=1;
            $class['last_task'] = (end($tasks) === $task) ? 'dls-sus-last-task' : null;
            $task_expired = ($task->date && $task->date != '0000-00-00' && strtotime($task->date.' 23:59:59') < current_time('timestamp')) ? true : false;
            $class['task_expired'] = ($sheet_expired || $task_expired) ? 'dls-sus-task-expired' : null;
            $signups = $this->data->get_signups($task->id);

            // Custom Fields
            $custom_task_fields_values = null;
            $custom_task_fields_values_blank = null;
            if (!empty($this->custom_fields['task'])) {
                foreach ($this->custom_fields['task'] as $field) {
                    $slug = str_replace('-', '_', $field['slug']);
                    if (!isset($task->fields[$slug])) $task->fields[$slug] = null;
                    $display_value = (is_array($task->fields[$slug])) ? implode(', ', $task->fields[$slug]) : $task->fields[$slug];
                    $custom_task_fields_values .= '<td>'.$display_value.'</td>';
                    $custom_task_fields_values_blank .= '<td>&nbsp;</td>';
                }
                reset($this->custom_fields['task']);
            }

            if (!$show_compact) {
                foreach ($signups AS $signup) {
                    $class['last_spot'] = ($i == $task->qty) ? 'dls-sus-last-spot' : null;
                    $return .= '
                        <tr class="dls-sus-row dls-sus-filled '.$class['last_task'].' '.$class['last_spot'].' '.$class['task_expired'].' dls-sus-spot-'.$i.'">
                            <td>'.(($i === 1) ? $task->title : '' ).'</td>
                            '.(($this->fields['use_task_dates'] === 'true') ? '<td>'.(($task->date !== '0000-00-00' && $i === 1) ? date(get_option('date_format'), strtotime($task->date)) : null).'</td>' : null).'
                            '.(($i === 1) ? $custom_task_fields_values : $custom_task_fields_values_blank).'
                            <td>#'.$i.': <em>
                    ';
                    switch ($display_name) {
                        case 'anonymous':
                            $return .= __('Filled', $this->plugin_prefix);
                            break;
                        case 'full';
                            $return .= $signup->firstname.' '.$signup->lastname;
                            break;
                        default:
                            $return .= $signup->firstname.' '.substr($signup->lastname, 0, 1).'.';
                    }
                    $return .= '</em></td>';

                    if (!$show_compact) {
                        if ($display_all) {
                            $return .= '<td>' . $signup->email . '</td>';
                            if ($this->data->show_phone($this)) {
                                $return .= '<td>' . $signup->phone . '</td>';
                            }
                            if ($this->data->show_address($this)) {
                                $return .= '
                                <td>' . $signup->address . '</td>
                                <td>' . $signup->city . '</td>
                                <td>' . $signup->state . '</td>
                                <td>' . $signup->zip . '</td>
                            ';
                            }
                        }
                        if (!empty($this->custom_fields['signup'])) {
                            foreach ($this->custom_fields['signup'] as $field) {
                                if (!$display_all && $field['frontend_results'] !== 'true')  continue;
                                $slug = str_replace('-', '_', $field['slug']);
                                $return .= '<td>'.(is_array($signup->fields[$slug]) ? implode(', ', $signup->fields[$slug]) : $signup->fields[$slug]).'</td>';
                            }
                            reset($this->custom_fields['signup']);
                        }
                        if ($display_all) {
                            $return .= '<td>' . (!empty($signup->reminded) ? get_date_from_gmt($signup->reminded, 'Y/m/d H:i:s') : null) . '</td>';
                            if ($show_clear === true) $return .= '<td><span class="delete"><a href="?page=dls-sus-settings_sheets&amp;sheet_id=' . $_GET['sheet_id'] . '&amp;signup_id=' . $signup->id . '&amp;action=clear">Clear Spot</a></span></td>';
                        }
                    }
                    $return .= '</tr>';
                    $i++;
                }
            } else {
                $i = count($task->qty);
            }

            // Remaining empty spots
            $extra_fields = 1;
            if (!$show_compact) {
                if ($display_all) {
                    $extra_fields += 7;
                    if ($this->data->show_phone($this)) $extra_fields++;
                    if ($this->data->show_address($this)) $extra_fields = $extra_fields + 4;
                    if ($show_clear === true) $extra_fields++;
                }
                $extra_fields += $display_custom_extra_fields_count;
            }

            $open_spots = $task->qty - count($signups);
            $open_spots_sep = ($open_spots > 0) ? '&bull;' : null;
            $row_count = ($show_compact) ? 1 : $task->qty;
            for ($i=$i; $i<=$row_count; $i++) {
                $class['last_spot'] = ($i == $task->qty) ? 'dls-sus-last-spot' : null;
                $signup_link = ($show_signup_link && !$sheet_expired && !$task_expired) ? '<a href="' . add_query_arg(array('task_id'=>$task->id)) . '">Sign up &raquo;</a>' : __('(empty)', $this->plugin_prefix);
                if ($sheet_expired || $task_expired) $signup_link .= __(' - sign-ups closed', $this->plugin_prefix);
                $return .= '
                    <tr class="dls-sus-row dls-sus-empty '.$class['last_task'].' '.$class['last_spot'].' '.$class['task_expired'].' dls-sus-spot-'.$i.'">
                        <td>'.(($i === 1) ? $task->title : '' ).'</td>
                        '.(($this->fields['use_task_dates'] === 'true') ? '<td>'.(($task->date !== '0000-00-00' && $i === 1) ? date(get_option('date_format'), strtotime($task->date)) : null).'</td>' : null).'
                        '.(($i === 1) ? $custom_task_fields_values : $custom_task_fields_values_blank).'
                        '.(($show_compact) ? '<td>'.$task->qty.'</td>' : null).'
                        <td colspan="'.$extra_fields.'">
                            ' . (($show_compact) ? ($open_spots).' '.$open_spots_sep : '#'.$i.':') . '
                            ' . ((!$show_compact || $open_spots > 0) ? $signup_link : null) . '
                        </td>
                    </tr>
                ';
            }
        }
        $return .= '
            </tbody>
        </table>
        ';

        return $return;
    }

    /**
     * Set custom fields
     */
    private function _set_custom_fields()
    {
        $all_fields['signup'] = get_option('dls_sus_custom_fields');
        $all_fields['task'] = get_option('dls_sus_custom_task_fields');

        // Set default values
        $this->custom_fields['signup'] = array();
        $this->custom_fields['task'] = array();

        foreach ($all_fields as $type=>$fields) {
            if (empty($fields) || !is_array($fields)) break;
            foreach ($fields as $key=>$field) {
                foreach ($field['sheets'] as $sheet_id) {
                    if (!empty($sheet_id) && $sheet_id != $this->id) continue;
                    $this->custom_fields[$type][$key] = $field;
                }
            }
        }
    }

    private function _set_categories()
    {
        $all_categories = $this->data->get_categories_by_sheet($this->id);
        foreach ($all_categories as $category) {
            $this->categories[] = array(
                'id' => $category->id,
                'category_id' => $category->category_id,
                'title' => $this->data->get_category($category->category_id)->title,
            );
        }
    }

    public function get_data()
    {
        return $this->_data;
    }

    /**
     * Should the sheet display in compact signup mode?
     *
     * @return bool
     */
    public function is_compact()
    {
        return (
            (isset($this->fields['compact_signups']) && $this->fields['compact_signups'] === 'true')
                || (empty($this->fields['compact_signups']) && get_option('dls_sus_compact_signups') === 'true')
        ) ? true : false;
    }

    /**
     * Is sheet data valid?
     *
     * @return bool
     */
    public function is_valid()
    {
        return is_object($this->_data);
    }

    /**
     * Magic getter
     *
     * @param string $name
     * @return null
     */
    public function __get($name)
    {
        if (is_object($this->_data) && property_exists($this->_data, $name)) {
            return $this->_data->$name;
        }
        return null;
    }

    /**
     * Magic isset (required for empty to work)
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_data->$name);
    }

}