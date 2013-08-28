<?php
require_once('db.php');

abstract class ModelAbstract
{
   /**
    * @var db PDO model
    */
   private static $db = null;

   /**
    * connect to connect singleton for open
    * mysql connection
    */
   public function __construct()
   {
      if(!self::$db)
      {
         try
         {
            /** Part D */
            $dsn = DB_ENGINE .':host='. DB_HOST .';dbname='. DB_NAME;
            self::$db = new PDO($dsn, DB_USER, DB_PW);
         }
         catch(PDOException $e)
         { 
            die("PDO CONNECTION ERROR: " . $e->getMessage() . "<br/>");
         }
      }
      return self::$db;  
   }

   /**
    * Protect us from sql injections
    *
    * @param string $sql   query to be checked.
    * @return string|null  return query string if valid
    *                      else return null.
    */
   public function verify_sql($sql)
   {
      if(!preg_match("/;/", $sql))
      {
         return $sql;
      }
      return null;
   }

   /**
    * Common retrieve all results from sql query
    *
    * @param string $sql   sql query.
    * @return array        returns sql multiple results.
    */
   public function retrieve_all($sql)
   {
      $results = array();

      $valid_sql = $this->verify_sql($sql);
      if($valid_sql == null)
      {
         return $results;
      }

      foreach(self::$db->query($valid_sql) as $row)
      {
         $results[] = $row;
      }
      
      return $results;
   } 
}
