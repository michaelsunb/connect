<?php
DEFINE("SELECT_VALUE",0);
DEFINE("SELECT_NAME",1);
DEFINE("SELECT_ALL_TOP",1);
DEFINE("SELECT_ALL_BOTTOM",-1);

class Helpers
{
   /**
    * repetitive select creation
    *
    * @param string $id          string id of html select.
    * @param array $options      array from maybe query.
    * @param array $value_name   MUST be 2 arrays with name of
    *                            $options SELECT_VALUE e.g. $row['id']
    *                            and $options SELECT_NAME e.g. $rom['value'].
    * @param int $selected_id    html option to be selected.
    * @param int $add_all        add an 'All' option to the top or bottom.
    *                            Use SELECT_ALL_TOP for top 
    *                            and SELECT_ALL_BOTTOM for bottom.
    * @return string             return html select.
    */
	static public function select($id,$options,$value_name = array(),$selected_id = -1,$add_all = 0)
   {
      
      ?><select id="<?= $id; ?>" name="<?= $id; ?>"><?php
      $html = '';
      foreach($options as $row)
      {
         $is_selected = false;

         if($row[$value_name[SELECT_VALUE]] == $selected_id)
         {
            $is_selected = true;
         }
         
         if($selected_id != -1 && $is_selected)
         {
            $html .= '<option value="'.$row[$value_name[SELECT_VALUE]].'" selected="selected">'.$row[$value_name[SELECT_NAME]].'</option>';
         }
         else
         {
            $html .= '<option value="'.$row[$value_name[SELECT_VALUE]].'">'.$row[$value_name[SELECT_NAME]].'</option>';
         }
      }
      
      if($add_all == SELECT_ALL_TOP)
      {
         $html = '<option value="0">All</option>'.$html;
      }
      elseif($add_all == SELECT_ALL_BOTTOM)
      {
         $html = $html.'<option value="0">All</option>';
      }
      
      echo $html;
      ?></select><?php
   }

   /**
    * Search a row in an array with a key
    *
    * @param array $haystack  The array we'll be searching through.
    * @param string $needle   The value we're comparing.
    * @param string $key      The key value for the row.
    * @return array|null      returns the row or null.
    */
   static public function inArrays($haystack,$needle,$key='id')
   {
      foreach($haystack as $row)
      {
         if($row[$key] == $needle)
         {
            return $row;
         }
      }
      return null;
   }
}