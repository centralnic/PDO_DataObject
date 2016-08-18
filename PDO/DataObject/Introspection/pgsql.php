<?php 
/**
 * Object Based Database Query Builder and data store
 *  - Postgresql Introspection Component.
 *
 * For PHP versions  5 and 7
 * 
 * 
 * Copyright (c) 2015 Alan Knowles
 * 
 * This program is free software: you can redistribute it and/or modify  
 * it under the terms of the GNU Lesser General Public License as   
 * published by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU 
 * Lesser General Lesser Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *  
 * @category   Database
 * @package    PDO_DataObject
 * @author     Alan Knowles <alan@roojs.com>
 * @copyright  2016 Alan Knowles
 * @license    https://www.gnu.org/licenses/lgpl-3.0.en.html  LGPL 3
 * @version    1.0
 * @link       https://github.com/roojs/PDO_DataObject
 */
  
  
class_exists('PDO_DataObject_Introspection') ? '' : require_once 'PDO/DataObject/Introspection.php';
// move me to seperate classes...
class PDO_DataObject_Introspection_pgsql extends PDO_DataObject_Introspection
{
    
    function getSpecialQuery($type)
    {
        switch ($type) {
            
            case 'tables':
                
                return "SELECT table_name FROM information_schema.tables" .
                        " WHERE table_type = 'BASE TABLE'" .
                        " AND table_schema = 'public' order by table_name ASC";
            
            case 'tables.all': /// not sure if this really works....
                return 'SELECT c.relname AS "Name"'
                        . ' FROM pg_class c, pg_user u'
                        . ' WHERE c.relowner = u.usesysid'
                        . " AND c.relkind = 'r'"
                        . ' AND NOT EXISTS'
                        . ' (SELECT 1 FROM pg_views'
                        . '  WHERE viewname = c.relname)'
                        . " AND c.relname !~ '^(pg_|sql_)'"
                        . ' UNION'
                        . ' SELECT c.relname AS "Name"'
                        . ' FROM pg_class c'
                        . " WHERE c.relkind = 'r'"
                        . ' AND NOT EXISTS'
                        . ' (SELECT 1 FROM pg_views'
                        . '  WHERE viewname = c.relname)'
                        . ' AND NOT EXISTS'
                        . ' (SELECT 1 FROM pg_user'
                        . '  WHERE usesysid = c.relowner)'
                        . " AND c.relname !~ '^pg_'";
            case 'schema.tables':
                return "SELECT schemaname || '.' || tablename"
                        . ' AS "Name"'
                        . ' FROM pg_catalog.pg_tables'
                        . ' WHERE schemaname NOT IN'
                        . " ('pg_catalog', 'information_schema', 'pg_toast')";
            case 'schema.views':
                return "SELECT schemaname || '.' || viewname from pg_views WHERE schemaname"
                        . " NOT IN ('information_schema', 'pg_catalog')";
            case 'views':
                // Table cols: viewname | viewowner | definition
                return 'SELECT viewname from pg_views WHERE schemaname'
                        . " NOT IN ('information_schema', 'pg_catalog')";
            case 'users':
                // cols: usename |usesysid|usecreatedb|usetrace|usesuper|usecatupd|passwd  |valuntil
                return 'SELECT usename FROM pg_user';
            case 'databases':
                return 'SELECT datname FROM pg_database';
            case 'functions':
            case 'procedures':
                return 'SELECT proname FROM pg_proc WHERE proowner <> 1';
            default:
                return null;
        }
    }

    
    
    /**
     * Returns information about a table or a result set
     *
     * @param  string  $table   string containing the name of a table.
     *                           MUST BE QUOTED if required....
     *                          
     
     * @return array  an associative array with the information requested.
     *                 A DB_Error object on failure.
     *
     *
     *  multiple_key
        
     *
     */
    function tableInfo($table)
    {
        
        
        // currently only queries 'public'???
        $schema  ='public';
        if (strpos($table,'.') !== false) {
            list($schema, $table) =explode('.', $table);
        }
         
        $database = $this->do->database();
        
        $records =  $this->do
            ->query("SELECT  
                        f.attnum AS number,  
                        f.attname AS name,  
                        f.attnum,  
                        f.attnotnull AS notnull,  
                        pg_catalog.format_type(f.atttypid,f.atttypmod) AS type,  
                        CASE  
                            WHEN p.contype = 'p' THEN 't'  
                            ELSE 'f'  
                        END AS primarykey,  
                        CASE  
                            WHEN p.contype = 'u' THEN 't'  
                            ELSE 'f'
                        END AS uniquekey,
                        CASE
                            WHEN p.contype = 'f' THEN g.relname
                        END AS foreignkey,
                        CASE
                            WHEN p.contype = 'f' THEN p.confkey
                        END AS foreignkey_fieldnum,
                        CASE
                            WHEN p.contype = 'f' THEN g.relname
                        END AS foreignkey,
                        CASE
                            WHEN p.contype = 'f' THEN p.conkey
                        END AS foreignkey_connnum,
                        CASE
                            WHEN f.atthasdef = 't' THEN d.adsrc
                        END AS default
                    FROM pg_attribute f  
                        JOIN pg_class c ON c.oid = f.attrelid  
                        JOIN pg_type t ON t.oid = f.atttypid  
                        LEFT JOIN pg_attrdef d ON d.adrelid = c.oid AND d.adnum = f.attnum  
                        LEFT JOIN pg_namespace n ON n.oid = c.relnamespace  
                        LEFT JOIN pg_constraint p ON p.conrelid = c.oid AND f.attnum = ANY (p.conkey)  
                        LEFT JOIN pg_class AS g ON p.confrelid = g.oid  
                    WHERE c.relkind = 'r'::char  
                        AND n.nspname = '{$this->do->escape($schema)}'  
                        AND c.relname = '{$this->do->escape($table)}'
                        AND f.attnum > 0 ORDER BY number
            ")
            ->fetchAll(false,false,'toArray');
        
        
        if (PDO_DataObject::config()['portability'] & PDO_DataObject::PORTABILITY_LOWERCASE) {
            $case_func = 'strtolower';
        } else {
            $case_func = 'strval';
        }

        
        $res   = array();

        //print_r($records);
        
        foreach($records as $r) {
            
            $bits = explode('(',$r['type']);
            switch($bits[0]){
                case 'character':
                    $bits[0]  = 'char';
                    break;
                case 'character varying':
                    $bits[0]  = 'varchar';
                    break;
                case 'timestamp without time zone':
                    $bits[0]  = 'datetime';
                    break;
                case 'timestamp with time zone':
                    $bits[0]  = 'timestamptz';
            }
           
            
            $res[] = array(
                'table' => $case_func($table),
                'name'  => $case_func($r['name']),
                'type'  => $bits[0],
                'len'   => isset($bits[1]) ? str_replace(')','', $bits[1])  : '',
                'flags' =>   ($r['notnull'] != '' ? ' not_null' : '').
                        ($r['primarykey'] == 't' ? ' primary' : '').
                        ($r['uniquekey'] == 't' ? ' unique' : '') .
                        ' '. $r['default']
                       
                        
            );
           
        }

        
        return $res;
    }
    /**
     * Returns information about a foriegn keys of a table.
     * Used to generate the links / join .. 
     * 
     * @param  string  $table   string containing the name of a table.
     *                           MUST BE QUOTED if required....
     *                          
     
     * @return array  an associative array (local column) ->  {related_table}:{related_column}
     * 
     * 
        
     *
     */
    function foreignKeys($table)
    {
           
        $fk = array();
        $res = $this->do->query("SELECT
                    pg_catalog.pg_get_constraintdef(r.oid, true) AS condef
                FROM pg_catalog.pg_constraint r,
                     pg_catalog.pg_class c
                WHERE c.oid=r.conrelid
                      AND r.contype = 'f'
                      AND c.relname = '{$this->do->escape($table)}'")
                ->fetchAll(false,false,'toArray');
         
        $treffer = array();
        // this may not work correctly -   see this: http://pear.php.net/bugs/bug.php?id=17049
        
        
        preg_match_all(
            "/FOREIGN KEY \((\w*)\) REFERENCES (\w*)\((\w*)\)/i",
            $r['condef'],
            $treffer,
            PREG_SET_ORDER);
        if (!count($treffer)) {
            return $fk;
        }
        foreach($treffer as $m) {
            $fk[  $m[1]  ]  = $m[2] . ":" . $m[3];
        }
        return $fk;
    }
    
}
