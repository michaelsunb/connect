<?php
DEFINE("SELECT_VALUE",0);
DEFINE("SELECT_NAME",1);
DEFINE("SELECT_ALL_TOP",1);
DEFINE("SELECT_ALL_BOTTOM",-1);

class Html
{
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
         $html = '<option value="all">All</option>'.$html;
      }
      elseif($add_all == SELECT_ALL_BOTTOM)
      {
         $html = $html.'<option value="all">All</option>';
      }
      
      echo $html;
      ?></select><?php
   }
}