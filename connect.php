<?php
require_once('db.php');

class connect
{
   /**
    * Singleton to hold the class in this variable.
    *
    * @var instance
    */
   private static $instance;
   
   /**
    * database connection.
    *
    * @var dbconn
    */
   private $dbconn;

   /**
    * singleton to keep mysql connection alive.
    */
   public static function singleton()
   {
      if(!isset(self::$instance))
      {
         $c = __CLASS__;
         self::$instance = new $c;
      }
      return self::$instance;
   }

   /**
    * constructor to open connection and select database.
    * Will exit if cannot connect to mysql host or database.
    */
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

   /** Destructor called at the end of the class. */
   public function __destruct()
   {
      // close connection
      mysql_close($this->dbconn);
      unset($this->dbconn);
   }   
}
