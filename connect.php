<?php
require_once('db.php');

class assignmentone
{
   private static $instance;
   private $dbconn;
   private $result;

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
   
   // query region_id and region_name from region
   public function query_region()
   {
      $sql = "select region_id, region_name from region";
      $this->result = mysql_query($sql);
      if($this->result)
      {
         while($row = mysql_fetch_assoc($this->result))
         {
            $results[] = $row;
         }
      }
      return $results;
   }

   public function __destruct()
   {
      if(!isset($this->result))
      {
         // free result set memory
         mysql_free_result($this->result);
         unset($this->result);
      }
      
      // close connection
      mysql_close($this->dbconn);
      unset($this->dbconn);
   }   
}
