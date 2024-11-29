<?php
class BackupRestore {

    private $connection;
    private $host;
    private $database;
    private $user;
    private $pass;
    private $file_path;
    private $path = 'backup_db'; // Example property

    /**
     * Sets up the default properties
     *
     * @access public
     */
    public function __construct($dbhost, $database, $dbUser, $dbPass, $path = "") {
        // Raise the memory limit and max_execution time
        @ini_set('memory_limit', '32M');
        @set_time_limit(0);

        $this->host = $dbhost;
        $this->database = $database;
        $this->user = $dbUser;
        $this->pass = $dbPass;
        $this->file_path = ($path) ? $path : dirname(__FILE__);
    }

    /**
     * Backup the database
     *
     * @access public
     * @return bool
     */
    public function backup() {
        // Your backup code...
    }

    /**
     * Restore the database from a backup
     *
     * @access public
     * @return string
     */
    public function restore() {
        // Your restore code...
    }

    /**
     * Reads the Database table in $table and creates SQL Statements for recreating structure and data
     *
     * @access private
     * @param string $sql_file
     * @param string $table
     */
    private function make_sql( $sql_file, $table ) {
        // Add SQL statement to drop existing table
        $sql_file .= "\n";
        $sql_file .= "\n";
        $sql_file .= "#\n";
        $sql_file .= "# Delete any existing table " . $this->sql_backquote( $table ) . "\n";
        $sql_file .= "#\n";
        $sql_file .= "\n";
        $sql_file .= "DROP TABLE IF EXISTS " . $this->sql_backquote( $table ) . ";\n";

        /* Table Structure */
        $sql_file .= "\n";
        $sql_file .= "#\n";
        $sql_file .= "# Table structure of table " . $this->sql_backquote( $table ) . "\n";
        $sql_file .= "#\n";
        $sql_file .= "\n";

        // Get table structure
        $query = 'SHOW CREATE TABLE ' . $this->sql_backquote( $table );
        $result = mysql_query( $query );

        if ( $result ) {
            if ( mysql_num_rows( $result ) > 0 ) {
                $sql_create_arr = mysql_fetch_array( $result );
                $sql_file .= $sql_create_arr[1];
            }
            mysql_free_result( $result );
            $sql_file .= ' ;';
        }

        /* Table Contents */
        $query = 'SELECT * FROM ' . $this->sql_backquote( $table );
        $result = mysql_query( $query );

        if ( $result ) {
            $fields_cnt = mysql_num_fields( $result );
            $rows_cnt   = mysql_num_rows( $result );
        }

        // Comment in SQL-file
        $sql_file .= "\n";
        $sql_file .= "#\n";
        $sql_file .= "# Data contents of table " . $table . " (" . $rows_cnt . " records)\n";
        $sql_file .= "#\n";

        // Checks whether the field is an integer or not
        for ( $j = 0; $j < $fields_cnt; $j++ ) {
            $field_set[$j] = $this->sql_backquote( mysql_field_name( $result, $j ) );
            $type = mysql_field_type( $result, $j );
            if ( $type === 'tinyint' || $type === 'smallint' || $type === 'mediumint' || $type === 'int' || $type === 'bigint' || $type === 'timestamp' ) {
                $field_num[$j] = true;
            } else {
                $field_num[$j] = false;
            }
        }

        // Sets the scheme
        $entries = 'INSERT INTO ' . $this->sql_backquote( $table ) . ' VALUES (';
        $search   = array( '\x00', '\x0a', '\x0d', '\x1a' );
        $replace  = array( '\0', '\n', '\r', '\Z' );
        $current_row = 0;
        $batch_write = 0;

        while ( $row = mysql_fetch_row( $result ) ) {
            $current_row++;
            // build the statement
            for ( $j = 0; $j < $fields_cnt; $j++ ) {
                if ( ! isset($row[$j] ) ) {
                    $values[] = 'NULL';
                } elseif ( $row[$j] === '0' || $row[$j] !== '' ) {
                    // a number
                    if ( $field_num[$j] )
                        $values[] = $row[$j];
                    else
                        $values[] = "'" . str_replace( $search, $replace, $this->sql_addslashes( $row[$j] ) ) . "'";
                } else {
                    $values[] = "''";
                }
            }

            $sql_file .= " \n" . $entries . implode( ', ', $values ) . ") ;";

            // write the rows in batches of 100
            if ( $batch_write === 100 ) {
                $batch_write = 0;
                $this->write_sql( $sql_file );
                $sql_file = '';
            }

            $batch_write++;
            unset( $values );
        }

        mysql_free_result( $result );

        // Create footer/closing comment in SQL-file
        $sql_file .= "\n";
        $sql_file .= "#\n";
        $sql_file .= "# End of data contents of table " . $table . "\n";
        $sql_file .= "# --------------------------------------------------------\n";
        $sql_file .= "\n";

        $this->write_sql( $sql_file );
    }

    /**
     * Write the SQL file
     *
     * @access private
     * @param string $sql
     */
    private function write_sql( $sql ) {
        $sqlname = $this->get_database_dump_filepath();
        if ( is_writable( $sqlname ) || ! file_exists( $sqlname ) ) {
            if ( ! $handle = @fopen( $sqlname, 'a' ) )
                return;
            if ( ! fwrite( $handle, $sql ) )
                return;
            fclose( $handle );
            return true;
        }
    }

    /*********************************** END ********************************/

    /******************* FORMATTING FUNCTIONS FOR CLEAN OUTPUT  *************/

    /**
     * Better addslashes for SQL queries.
     * Taken from phpMyAdmin.
     *
     * @access private
     * @param string $a_string (default: '')
     * @param bool $is_like (default: false)
     */
    private function sql_addslashes( $a_string = '', $is_like = false ) {
        if ( $is_like )
            $a_string = str_replace( '\\', '\\\\\\\\', $a_string );
        else
            $a_string = str_replace( '\\', '\\\\', $a_string );
        $a_string = str_replace( '\'', '\\\'', $a_string );
        return $a_string;
    }

    /**
     * Add backquotes to tables and db-names in SQL queries. Taken from phpMyAdmin.
     *
     * @access private
     * @param mixed $a_name
     */
    private function sql_backquote( $a_name ) {
        if ( ! empty( $a_name ) && $a_name !== '*' ) {
            if ( is_array( $a_name ) ) {
                $result = array();
                reset( $a_name );
                while ( list( $key, $val ) = each( $a_name ) )
                    $result[$key] = '`' . $val . '`';
                return $result;
            } else {
                return '`' . $a_name . '`';
            }
        } else {
            return $a_name;
        }
    }
}
class BackupRestoreClass {
    // Other methods...

    private function remove_accents($string) {
        if (!preg_match('/[\x80-\xff]/', $string)) 
            return $string;

        if (seems_utf8($string)) {
            $chars = array(
                // Decompositions for Latin-1 Supplement
                chr(194).chr(170) => 'a', chr(194).chr(186) => 'o',
                chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
                chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
                chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
                chr(195).chr(134) => 'AE', chr(195).chr(135) => 'C',
                chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
                // more mappings...
            );
            $string = strtr($string, $chars);
        } else {
            // Assume ISO-8859-1 if not UTF-8
            $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
                .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
                .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
                .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
                .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
                .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
                .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
                .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
                .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
                .chr(252).chr(253).chr(255);

            $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";
            $string = strtr($string, $chars['in'], $chars['out']);

            $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
            $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
            $string = str_replace($double_chars['in'], $double_chars['out'], $string);
        }

        return $string;
    }

    private function trailingslashit($string) {
        return $this->untrailingslashit($string) . '/';
    }

    private function untrailingslashit($string) {
        return rtrim($string, '/');
    }
}

// Closing bracket for the class should be here if it's a class
?>