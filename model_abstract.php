<?php
require_once('connect.php');

abstract class ModelAbstract
{
   /**
    * @var results from executed query
    */
   protected $result = null;

   /**
    * connect to connect singleton for open
    * mysql connection
    */
   public function __construct()
   {
      connect::singleton();
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
      
      $this->result = mysql_query($valid_sql);
      
      if($this->result)
      {
         while($row = mysql_fetch_assoc($this->result))
         {
            $results[] = $row;
         }
      }
      
      return $results;
   }

   /**
    * Common retrieve a single result from sql query
    *
    * @param string $sql   sql query.
    * @return array        returns sql single result.
    */
   public function retrieve_single($sql)
   {
      $row = null;

      $valid_sql = $this->verify_sql($sql);
      if($valid_sql == null)
      {
         return $results;
      }

      $this->result = mysql_query($valid_sql);
      
      if($this->result)
      {
         $row = mysql_fetch_assoc($this->result);
      }
      
      return $row;
   }

   public function __destruct()
   {
      if($this->result != null)
      {
         // free result set memory
         mysql_free_result($this->result);
         unset($this->result);
      }
   }   
}
