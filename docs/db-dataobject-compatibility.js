//<script type="text/javascript">

// Auto generated file - created by app.Builder.js- do not edit directly (at present!)

db-dataobject-compatibility = new Roo.XComponent({

 _strings : {
  '7ca2773ac777c886ffeeb73f8d6930d2' :"Detail incompatibilities",
  '24db2fcf915e5bd38b05f238d26a0b75' :"<p>\nPDO_DataObject is based on the original codebase from DB_DataObjects, however some changes have been made \nsince DB_DataObject was originally used on PHP4, and many features and practices have changed since then.\n</p>\n<ul>\n<li>using PDO rather than native drivers and the PEAR DB wrapper\n<li>more strict static methods\n<li>removal of PEAR dependancies, including error handling\n<li>addition of chained methods\n<li>simpler configuration - removed some unused features\n<li>some features - that where not a great idea have been removed, or depricated\n<li>some methods that where very badly named have been renamed.\n</ul>\n",
  '1677feef3f32a6ce37a3f18947514ce5' :"DB_DataObject Compatibility",
  'dbb4340b2845a88dc0a656f243950b83' :" \n\n<h3>Configuration</h3>\n<p>\nPDO_DataObjects uses PDO_DataObjects::config(), rather than PEAR::getStaticProperty()\n</p>\n<p>\nThese configuration items have been removed or renamed\n</p>\n<ul>\n<li>debug_ignore_updates  - remove, you can use the PDO configuration to use a fake PDO class\n<li>sequence_**** - used to enable listing sequence keys for a specific table, just overide sequenceKey in the class\n<li>ignore_sequence_keys  - stoped sequence keys working for a specific table, just overide sequenceKey in the class\n<li>dont_use_pear_sequences - as we do not use pear sequences, this is not relivant\n<li>links_****  - manually list the links for a specific tables -just override the links() method\n<li>ini_**** - enabled setting the location for ini file   - use ::config( [schema_location => [ 'database'  => location ]])\n<li>keep_query_after_fetch - removed - behaviour was inconsistant - and unpredictable... \n<li>disable_null_strings - changed to enable_null_strings and disbled by default\n</ul>\n\n\n\n<h3>PDO related changes</h3>\n<p>\nAs PEAR DB is not used, methods relating to that have been removed, on the assumption that it would be easier to solve a method \nnot found issue, than try and work out why the returned objects are different.\n</p>\n<ul>\n<li>getDatabaseConnection - is now called PDO() - and returns the PDO object.\n<li>getDatabaseResult - is now result() - and returns a PDO_Statement object \n<li>_connect - the internal connector now replaced by PDO()\n</ul>\n\n\n<h3>Badly named methods -  renamed or removed</h3>\n<p>\nThese methods where never well named, and have been changed\n<ul>\n<li>database - has now been renamed as databaseNickname - as it may not actually be the name of the database, \n    (the real database name should be available from ) <code>$do->PDO()->database_name</code>\n<li>table - has been renamed as tableColumns - as that better describes what it returns.\n<li>staticGet - was a dumb idea... it's easy to implement, and using chaining and  load() makes it trivial anyway now\n<li>fetchRow - I've never used this....  just access PDO directly if you need to get this clever\n</ul>\n\n<h3>PHP5 strict rules</h3>\n<p>\nfactory without arguments... used to create a new object of the same type as the caller, but since factory is now static, it \ncan not determine what the caller is.\n\n\n\n<h3>Null strings</h3>\n<p>\nPreviously assigining a object property to the string 'NULL' would result in NULL being used in the built query. This had \nvery unpredictable side effects.  The new standard behaviour is only to treat PHP's null value as database NULL when using the \nset method (it actually set's the property to a PDO_DataObject_Cast value.\n</p>\n\n<h3>Foreign key method - links</h3>\n<p>\npreviously calling it with arguments set the foreign configuration for that table\nIt now only sets it for that instance, use  databaseStructure to modify globallay\n</p>\n\n\n<h3>Loading referenced data</h3>\n<p>\nThere where a number of methods that could be used to load referenced objects,\n so of these loaded objects as properties of the current object.\n  Since this is not a common or recommeded pattern (it's very slow compared to joins) - some have been depricated, and should not be\n  used in new code.\n </p>\n<p>\nAll of these properties have had the 'get' prefix removed removed \n</p>\n<ul>\n<li>getLink has become <a href=\"#pdo-dataobject/link\">link</a>() - second argument can not be an array (not sure why that was ever supported as it just read the first item in the array?!?\n<li>getLinkArray - depricated and renamed - <a href=\"#pdo-dataobject/linkArray\">linkArray</a>\n<li>getLinks - depricated and renamed - <a href=\"#pdo-dataobject/applyLinks\">applyLinks</a>\n</ul>\n\n\n<h3>Debug levels</h3>\n<p>\nThese have been changed slightly - timer information was removed from level(1) - so that tests can work.\n</p>\n\n\n\n<h3>Private properties removed or renamed</h3>\n<p>This is just here for reference, as it's not expected that it would be used.\n</o>\n<ul>\n\n<li>$do->_database_dsn - removed - this was a security hole wating to fail... (Accidental print_r's etc...)\n<li>$do->_DB_resultid removed - results object are now stored as a private property (as they do not reference the original dsn\n<li>$do->_lastError - removed - Not sure if this is ever used...\n\n</ul>"
 },

  part     :  ["docs", "db-dataobject-compatibility" ],
  order    : '001-db-dataobject-compatibility',
  region   : 'center',
  parent   : false,
  name     : "unnamed module",
  disabled : false, 
  permname : '', 
  _tree : function()
  {
   var _this = this;
   var MODULE = this;
   return {
   xtype : 'Body',
   xns : Roo.bootstrap,
   '|xns' : 'Roo.bootstrap',
   items  : [
    {
     xtype : 'Header',
     html : _this._strings['1677feef3f32a6ce37a3f18947514ce5'] /* DB_DataObject Compatibility */,
     level : 1,
     xns : Roo.bootstrap,
     '|xns' : 'Roo.bootstrap'
    },
    {
     xtype : 'Element',
     html : _this._strings['24db2fcf915e5bd38b05f238d26a0b75'] /* 
     <p>     
PDO_DataObject is based on the original codebase from DB_DataObjects, however some changes have been made      
since DB_DataObject was originally used on PHP4, and many features and practices have changed since then.     
</p>     
<ul>     
<li>using PDO rather than native drivers and the PEAR DB wrapper     
<li>more strict static methods     
<li>removal of PEAR dependancies, including error handling     
<li>addition of chained methods     
<li>simpler configuration - removed some unused features     
<li>some features - that where not a great idea have been removed, or depricated     
<li>some methods that where very badly named have been renamed.     
</ul>     

     */ ,
     xns : Roo.bootstrap,
     '|xns' : 'Roo.bootstrap'
    },
    {
     xtype : 'Header',
     html : _this._strings['7ca2773ac777c886ffeeb73f8d6930d2'] /* Detail incompatibilities */,
     level : 2,
     xns : Roo.bootstrap,
     '|xns' : 'Roo.bootstrap'
    },
    {
     xtype : 'Element',
     html : _this._strings['dbb4340b2845a88dc0a656f243950b83'] /* 
           
     
<h3>Configuration</h3>     
<p>     
PDO_DataObjects uses PDO_DataObjects::config(), rather than PEAR::getStaticProperty()     
</p>     
<p>     
These configuration items have been removed or renamed     
</p>     
<ul>     
<li>debug_ignore_updates  - remove, you can use the PDO configuration to use a fake PDO class     
<li>sequence_**** - used to enable listing sequence keys for a specific table, just overide sequenceKey in the class     
<li>ignore_sequence_keys  - stoped sequence keys working for a specific table, just overide sequenceKey in the class     
<li>dont_use_pear_sequences - as we do not use pear sequences, this is not relivant     
<li>links_****  - manually list the links for a specific tables -just override the links() method     
<li>ini_**** - enabled setting the location for ini file   - use ::config( [schema_location => [ 'database'  => location ]])     
<li>keep_query_after_fetch - removed - behaviour was inconsistant - and unpredictable...      
<li>disable_null_strings - changed to enable_null_strings and disbled by default     
</ul>     
     
     
     
<h3>PDO related changes</h3>     
<p>     
As PEAR DB is not used, methods relating to that have been removed, on the assumption that it would be easier to solve a method      
not found issue, than try and work out why the returned objects are different.     
</p>     
<ul>     
<li>getDatabaseConnection - is now called PDO() - and returns the PDO object.     
<li>getDatabaseResult - is now result() - and returns a PDO_Statement object      
<li>_connect - the internal connector now replaced by PDO()     
</ul>     
     
     
<h3>Badly named methods -  renamed or removed</h3>     
<p>     
These methods where never well named, and have been changed     
<ul>     
<li>database - has now been renamed as databaseNickname - as it may not actually be the name of the database,      
    (the real database name should be available from ) <code>$do->PDO()->database_name</code>     
<li>table - has been renamed as tableColumns - as that better describes what it returns.     
<li>staticGet - was a dumb idea... it's easy to implement, and using chaining and  load() makes it trivial anyway now     
<li>fetchRow - I've never used this....  just access PDO directly if you need to get this clever     
</ul>     
     
<h3>PHP5 strict rules</h3>     
<p>     
factory without arguments... used to create a new object of the same type as the caller, but since factory is now static, it      
can not determine what the caller is.     
     
     
     
<h3>Null strings</h3>     
<p>     
Previously assigining a object property to the string 'NULL' would result in NULL being used in the built query. This had      
very unpredictable side effects.  The new standard behaviour is only to treat PHP's null value as database NULL when using the      
set method (it actually set's the property to a PDO_DataObject_Cast value.     
</p>     
     
<h3>Foreign key method - links</h3>     
<p>     
previously calling it with arguments set the foreign configuration for that table     
It now only sets it for that instance, use  databaseStructure to modify globallay     
</p>     
     
     
<h3>Loading referenced data</h3>     
<p>     
There where a number of methods that could be used to load referenced objects,     
 so of these loaded objects as properties of the current object.     
  Since this is not a common or recommeded pattern (it's very slow compared to joins) - some have been depricated, and should not be     
  used in new code.     
 </p>     
<p>     
All of these properties have had the 'get' prefix removed removed      
</p>     
<ul>     
<li>getLink has become <a href="#pdo-dataobject/link">link</a>() - second argument can not be an array (not sure why that was ever supported as it just read the first item in the array?!?     
<li>getLinkArray - depricated and renamed - <a href="#pdo-dataobject/linkArray">linkArray</a>     
<li>getLinks - depricated and renamed - <a href="#pdo-dataobject/applyLinks">applyLinks</a>     
</ul>     
     
     
<h3>Debug levels</h3>     
<p>     
These have been changed slightly - timer information was removed from level(1) - so that tests can work.     
</p>     
     
     
     
<h3>Private properties removed or renamed</h3>     
<p>This is just here for reference, as it's not expected that it would be used.     
</o>     
<ul>     
     
<li>$do->_database_dsn - removed - this was a security hole wating to fail... (Accidental print_r's etc...)     
<li>$do->_DB_resultid removed - results object are now stored as a private property (as they do not reference the original dsn     
<li>$do->_lastError - removed - Not sure if this is ever used...     
     
</ul>
     */ ,
     xns : Roo.bootstrap,
     '|xns' : 'Roo.bootstrap'
    }
   ]
  };  }
});
