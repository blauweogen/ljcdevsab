<?php

define('AWPCP_TABLE_EXTRA_FIELDS', $wpdb->prefix . "awpcp_extra_fields");


class AWPCP_Extra_Fields_Installer {

    public function install() {
        global $wpdb, $awpcp;

        if ( ! is_null( $awpcp ) ) {
            $plugin_version = AWPCP_EXTRA_FIELDS_MODULE_DB_VERSION;
            $db_version = get_option('awpcp-extra-fields-db-version');

            $table = $wpdb->get_var("SHOW TABLES LIKE '" . AWPCP_TABLE_EXTRA_FIELDS . "'");
            if ($db_version !== false && strcmp($table, AWPCP_TABLE_EXTRA_FIELDS) === 0) {
                return $this->upgrade($db_version, $plugin_version);
            }

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $sql = "CREATE TABLE IF NOT EXISTS " . AWPCP_TABLE_EXTRA_FIELDS . " (
                `field_id` INT(10) NOT NULL AUTO_INCREMENT,
                `field_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
                `field_label` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
                `field_label_view` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
                `field_input_type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
                `field_mysql_data_type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
                `field_options` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `field_validation` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
                `field_privacy` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
                `field_category` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `nosearch` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 0,
                `show_on_listings` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2',
                `weight` INT(10) NOT NULL DEFAULT 0,
                `required` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
                PRIMARY KEY  (`field_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
            dbDelta($sql);

            update_option( 'awpcp-extra-fields-db-version', $plugin_version );
        }
    }

    public function upgrade($oldversion, $newversion) {
        if (version_compare($oldversion, '1.0.0') < 0) {
            $this->upgrade_to_1_0_0($oldversion);
        }
        if (version_compare($oldversion, '1.0.1') < 0) {
            $this->upgrade_to_1_0_1($oldversion);
        }
        if (version_compare($oldversion, '1.0.2') < 0) {
            $this->upgrade_to_1_0_2($oldversion);
        }
        if (version_compare($oldversion, '1.0.3') < 0) {
            $this->upgrade_to_1_0_3($oldversion);
        }
        if (version_compare($oldversion, '2.0.5') < 0) {
            $this->upgrade_to_2_0_5($oldversion);
        }
        if (version_compare($oldversion, '3.0.1') < 0) {
            $this->upgrade_to_3_0_1($oldversion);
        }
        if (version_compare($oldversion, '3.0.3') < 0) {
            $this->upgrade_to_3_0_3($oldversion);
        }
        if (version_compare($oldversion, '3.0.6') < 0) {
            $this->upgrade_to_3_0_6($oldversion);
        }

        update_option('awpcp-extra-fields-db-version', $newversion);
    }

    private function upgrade_to_1_0_0($version) {
        global $wpdb;

        /* add missing columns */

        if ( ! awpcp_column_exists( AWPCP_TABLE_EXTRA_FIELDS, 'field_privacy' ) ) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_EXTRA_FIELDS . "  ADD `field_privacy` VARCHAR(255) COLLATE utf8_general_ci NOT NULL AFTER `field_validation`");
        }

        if ( ! awpcp_column_exists( AWPCP_TABLE_EXTRA_FIELDS, 'nosearch' ) ) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_EXTRA_FIELDS . "  ADD `nosearch` VARCHAR(255) COLLATE utf8_general_ci ");
            $wpdb->query("UPDATE " . AWPCP_TABLE_EXTRA_FIELDS . "  SET `nosearch` = 0");
        }

        if ( ! awpcp_column_exists( AWPCP_TABLE_EXTRA_FIELDS, 'show_on_listings' ) ) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_EXTRA_FIELDS . "  ADD `show_on_listings` VARCHAR(255) COLLATE utf8_general_ci ");
            // 1 = listings
            // 2 = single, the default for backward compatibility
            // 3 = both
            $wpdb->query("UPDATE " . AWPCP_TABLE_EXTRA_FIELDS . "  SET `show_on_listings` = '2'");
        }

        if ( ! awpcp_column_exists( AWPCP_TABLE_EXTRA_FIELDS, 'field_category' ) ) {
            $wpdb->query("ALTER TABLE " . AWPCP_TABLE_EXTRA_FIELDS . "  ADD `field_category` VARCHAR(255) COLLATE utf8_general_ci AFTER `show_on_listings`");
        }
    }

    private function upgrade_to_1_0_1($version) {
        global $wpdb;

        // serialize extra field options both in Ads table and Extra fields table
        $fields = awpcp_get_extra_fields();
        foreach ($fields as &$field) {
            $name = $field->field_name;
            $options = $field->field_options;

            if (!is_array($options) && !is_serialized($options) && !empty($options)) {
                $options = array_map('trim', explode(',', $options));
                $options = maybe_serialize($options);
                $query = 'UPDATE ' . AWPCP_TABLE_EXTRA_FIELDS . ' SET ';
                $query.= 'field_options = %s WHERE field_id = %d';
                $wpdb->query($wpdb->prepare($query, $options, $field->field_id));
            }

            $query = 'SELECT ad_id, ' . $name . ' FROM ' . AWPCP_TABLE_ADS;
            $results = $wpdb->get_results($query);

            foreach ($results as $row) {
                $value = awpcp_extra_fields_field_value($field, $row->{$name});
                $wpdb->update(AWPCP_TABLE_ADS, array($name => $value), array('ad_id' => $row->ad_id));
            }
        }
    }

    private function upgrade_to_1_0_2($version) {
        global $wpdb;

        // allow Extra Fields custom ordering
        $column = "weight";
        $wpdb->hide_errors();
        $result = $wpdb->query("SELECT `$column` FROM " . AWPCP_TABLE_EXTRA_FIELDS);
        $wpdb->show_errors();

        if ($result === false) {
            $query = "ALTER TABLE %s ADD %s INT(10) NOT NULL DEFAULT 0";
            $wpdb->query( sprintf( $query, AWPCP_TABLE_EXTRA_FIELDS, $column ) );
        }

        // set weight for existing Extra Fields
        $fields = awpcp_get_extra_fields();
        $query = 'UPDATE ' . AWPCP_TABLE_EXTRA_FIELDS . ' SET weight = %d WHERE field_id = %d';
        foreach ($fields as $i => $field) {
            $wpdb->query($wpdb->prepare($query, $i, $field->field_id));
        }
    }

    private function upgrade_to_1_0_3($version) {
        global $wpdb;

        /* Set TEXT as MySQL data type for columns that should store multiple values */
        $fields = awpcp_get_extra_fields();
        $inputs = array('Select Multiple', 'Checkbox');

        $query = "ALTER TABLE " . AWPCP_TABLE_ADS . " CHANGE `%s` `%s` TEXT COLLATE utf8_general_ci NOT NULL";

        foreach ($fields as $i => $field) {
            if (in_array($field->field_input_type, $inputs)) {
                $wpdb->query(sprintf($query, $field->field_name, $field->field_name));
            }
        }
    }

    private function upgrade_to_2_0_5($version) {
        global $wpdb;

        // allow Extra Fields to be optional
        $column = "required";
        $wpdb->hide_errors();
        $result = $wpdb->query("SELECT `$column` FROM " . AWPCP_TABLE_EXTRA_FIELDS);
        $wpdb->show_errors();

        if ($result === false) {
            $query = "ALTER TABLE %s ADD %s TINYINT(1) UNSIGNED NOT NULL DEFAULT 0";
            $wpdb->query(sprintf($query, AWPCP_TABLE_EXTRA_FIELDS, $column));
        }

        // increse capacity of DECIMAL extra fields
        $alter = "ALTER TABLE " . AWPCP_TABLE_ADS . " CHANGE `%s` `%s` FLOAT(12,2) NOT NULL";

        $query = "SELECT field_name FROM " . AWPCP_TABLE_EXTRA_FIELDS . " ";
        $query.= "WHERE field_mysql_data_type = 'FLOAT' ";
        $query.= "AND field_input_type NOT IN ('Checkbox', 'Select Multiple')";

        $fields = $wpdb->get_col($query);

        foreach ($fields as $field) {
            $wpdb->query(sprintf($alter, $field, $field));
        }

        // serialize categories
        $all_categories = $wpdb->get_col( 'SELECT category_id FROM ' . AWPCP_TABLE_CATEGORIES );

        $query = 'SELECT field_id, field_category FROM ' . AWPCP_TABLE_EXTRA_FIELDS . ' ';
        foreach ( $wpdb->get_results( $query ) as $field ) {
            $categories = (array) maybe_unserialize( $field->field_category );

            if ( in_array( 'root', $categories ) ) {
                $categories = $all_categories;
            }

            $wpdb->update( AWPCP_TABLE_EXTRA_FIELDS,
                           array( 'field_category' => maybe_serialize( $categories ) ),
                           array( 'field_id' => $field->field_id ) );
        }
    }

    private function upgrade_to_3_0_1($version) {
        global $wpdb;

        $query = "SELECT field_name, field_mysql_data_type FROM " . AWPCP_TABLE_EXTRA_FIELDS;
        $fields = $wpdb->get_results( $query );

        $alter = 'ALTER TABLE ' . AWPCP_TABLE_ADS . ' CHANGE `%1$s` `%1$s` %2$s';

        foreach ( $fields as $field ) {
            if ( $field->field_mysql_data_type == 'INT' ) {
                $change = 'INT(10) DEFAULT NULL';
            } else if ( $field->field_mysql_data_type == 'FLOAT' ) {
                $change = 'FLOAT(12,2) DEFAULT NULL';
            } else if ( $field->field_mysql_data_type == 'VARCHAR' ) {
                $change = 'VARCHAR(500) COLLATE utf8_general_ci';
            } else if ( $field->field_mysql_data_type == 'TEXT' ) {
                $change = 'TEXT COLLATE utf8_general_ci';
            }

            $wpdb->query( sprintf( $alter, $field->field_name, $change ) );
        }
    }

    private function upgrade_to_3_0_3($version) {
        global $wpdb;

        $query = 'ALTER TABLE ' . AWPCP_TABLE_EXTRA_FIELDS . ' CHANGE `field_category` ';
        $query.= '`field_category` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL';

        $wpdb->query( $query );
    }

    private function upgrade_to_3_0_6($version) {
        global $wpdb;

        $fields = $wpdb->get_results( 'SELECT * FROM ' . AWPCP_TABLE_EXTRA_FIELDS );
        $properties = array( 'field_options', 'field_category' );

        foreach ($fields as $field) {
            $data = array();

            foreach ( $properties as $property ) {
                $value = maybe_unserialize( trim( awpcp_get_property( $field, $property ) ) );

                if ( !is_array( $value ) && strlen( $value ) === 0 ) {
                    $value = array();
                } else if ( !is_array( $value ) ) {
                    $value = array( $value );
                }

                // remove empty strings from array
                $value = array_filter( $value, 'strlen' );

                $data[ $property ] = maybe_serialize( $value );
            }

            $wpdb->update( AWPCP_TABLE_EXTRA_FIELDS, $data, array( 'field_id' => $field->field_id ) );
        }
    }
}
