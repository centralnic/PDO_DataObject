<?php


class PDO_DummyStatement {
    
    static $results = array(
        // Dsn
        'mysql:dbname=anotherdb;host=localhost;port=3344' => array(
            
            // QUeries
            'SHOW TABLES' =>  '[
                    {"Tables_in_somedb":"Companies"},
                    {"Tables_in_somedb":"Events"},
                    {"Tables_in_somedb":"Groups"}
                ]',
                
            'DESCRIBE Companies' => '[
                    {"Field":"id","Type":"int(11)","Null":"NO","Key":"PRI","Default":null,"Extra":"auto_increment"},
                    {"Field":"code","Type":"varchar(32)","Null":"NO","Key":"","Default":"","Extra":""},
                    {"Field":"name","Type":"varchar(128)","Null":"YES","Key":"MUL","Default":null,"Extra":""},
                    {"Field":"remarks","Type":"text","Null":"YES","Key":"","Default":null,"Extra":""},
                    {"Field":"owner_id","Type":"int(11)","Null":"NO","Key":"","Default":"0","Extra":""},
                    {"Field":"address","Type":"text","Null":"YES","Key":"","Default":null,"Extra":""},
                    {"Field":"tel","Type":"varchar(32)","Null":"YES","Key":"","Default":null,"Extra":""},
                    {"Field":"fax","Type":"varchar(32)","Null":"YES","Key":"","Default":null,"Extra":""},
                    {"Field":"email","Type":"varchar(128)","Null":"YES","Key":"","Default":null,"Extra":""},
                    {"Field":"isOwner","Type":"int(11)","Null":"YES","Key":"","Default":null,"Extra":""},
                    {"Field":"logo_id","Type":"int(11)","Null":"NO","Key":"","Default":"0","Extra":""},
                    {"Field":"background_color","Type":"varchar(8)","Null":"NO","Key":"","Default":null,"Extra":""},
                    {"Field":"comptype","Type":"varchar(32)","Null":"YES","Key":"","Default":"","Extra":""},
                    {"Field":"url","Type":"varchar(254)","Null":"NO","Key":"","Default":"","Extra":""},
                    {"Field":"main_office_id","Type":"int(11)","Null":"NO","Key":"","Default":"0","Extra":""},
                    {"Field":"created_by","Type":"int(11)","Null":"NO","Key":"","Default":"0","Extra":""},
                    {"Field":"created_dt","Type":"datetime","Null":"NO","Key":"","Default":null,"Extra":""},
                    {"Field":"updated_by","Type":"int(11)","Null":"NO","Key":"","Default":"0","Extra":""},
                    {"Field":"updated_dt","Type":"datetime","Null":"NO","Key":"","Default":null,"Extra":""},
                    {"Field":"passwd","Type":"varchar(64)","Null":"NO","Key":"","Default":"","Extra":""},
                    {"Field":"dispatch_port","Type":"varchar(255)","Null":"NO","Key":"","Default":"","Extra":""},
                    {"Field":"province","Type":"varchar(255)","Null":"NO","Key":"","Default":"","Extra":""},
                    {"Field":"country","Type":"varchar(4)","Null":"NO","Key":"","Default":"","Extra":""}
                    ]',
            
            'DESCRIBE Events' => '[
                    {"Field":"id","Type":"int(11)","Null":"NO","Key":"PRI","Default":null,"Extra":"auto_increment"},
                    {"Field":"person_name","Type":"varchar(128)","Null":"YES","Key":"","Default":null,"Extra":""},
                    {"Field":"event_when","Type":"datetime","Null":"YES","Key":"MUL","Default":null,"Extra":""},
                    {"Field":"action","Type":"varchar(32)","Null":"YES","Key":"MUL","Default":null,"Extra":""},
                    {"Field":"ipaddr","Type":"varchar(16)","Null":"YES","Key":"","Default":null,"Extra":""},
                    {"Field":"on_id","Type":"int(11)","Null":"YES","Key":"MUL","Default":null,"Extra":""},
                    {"Field":"on_table","Type":"varchar(64)","Null":"YES","Key":"MUL","Default":null,"Extra":""},
                    {"Field":"person_id","Type":"int(11)","Null":"YES","Key":"","Default":null,"Extra":""},
                    {"Field":"person_table","Type":"varchar(64)","Null":"YES","Key":"MUL","Default":null,"Extra":""},
                    {"Field":"remarks","Type":"text","Null":"YES","Key":"","Default":null,"Extra":""}
                    ]',
            'DESCRIBE Groups' => '[
                {"Field":"id","Type":"int(11)","Null":"NO","Key":"PRI","Default":null,"Extra":"auto_increment"},
                {"Field":"name","Type":"varchar(64)","Null":"NO","Key":"","Default":"","Extra":""},
                {"Field":"type","Type":"int(11)","Null":"YES","Key":"","Default":"0","Extra":""},
                {"Field":"leader","Type":"int(11)","Null":"NO","Key":"","Default":"0","Extra":""}
            ]',
        ),
        'pgsql://user:nopass/xtuple' => array(
            "SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema = 'public' order by table_name ASC" => '[
                {"table_name":"acalitem"},
                {"table_name":"accnt"},
                {"table_name":"addr"}
            ]',
            // table def for acalitem
            '08e4aa9f52824fa6d6c01dfea04d5efd' => '[
            
            
        )
        // another DB...
    );
    var $result = false;
    var $row = 0;
    
  
    
    
    
    function __construct($db, $query)
    {
        if (!isset(self::$results[$db][$query])) {
            throw new Exception(__CLASS__  . " missing query: DB: $db  QUERY=$query ");
        }
        $this->result = json_decode(self::$results[$db][$query]);
        
    }
    
    function closeCursor()
    {
        echo "Close Cursor\n";
    }
    
    function fetch($method , &$obj=null)
    {
        echo "Fetch Row {$this->row} / " . count($this->result) . "\n";
        
        $row = $this->row  >= count($this->result) ? false : (array)$this->result[$this->row++];
        if ($row === false) {
            return $row;
        }
        if ($method & PDO::FETCH_INTO) {
            foreach($row as $k=>$v) {
                $obj->$k = $v;
            }
        }
        if ($method & PDO::FETCH_ASSOC) {
            return $row;
        }
        throw new Exception("invalid argument for Fetch in dummy PDOStatement");
        
        
    }
    
      
    function rowCount()
    {
        return count( $this->result);
    }
    function columnCount()
    {
        return count(array_keys((array)$this->result[0]));
    }
    function getColumnMeta()
    {
        return false; // pretend we do not support stuff.
    }
    
}