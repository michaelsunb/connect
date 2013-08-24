<?php
require_once('db.php');

DEFINE("DEFAULT_START_LIMIT",0);
DEFINE("DEFAULT_END_LIMIT",30);

class connect
{
   private static $instance;
   private $dbconn;

   // singleton to keep mysql connection alive.
   public static function singleton()
   {
      if(!isset(self::$instance))
      {
         $c = __CLASS__;
         self::$instance = new $c;
      }
      return self::$instance;
   }

   // constructor to open connection and select database.
   private function __construct()
   {
      if(!$this->dbconn = mysql_connect(DB_HOST, DB_USER, DB_PW))
      {
         echo 'Could not connect to mysql on ' . DB_HOST . '\n';
         exit;
      }

      if(!mysql_select_db(DB_NAME, $this->dbconn))
      {
         echo 'Could not user database ' . DB_NAME . '\n';
         echo mysql_error() . '\n';
         exit;
      }
   }

   public function __destruct()
   {
      // close connection
      mysql_close($this->dbconn);
      unset($this->dbconn);
   }   
}
