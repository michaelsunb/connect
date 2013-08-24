<?
require_once('model_region.php');
require_once('model_grapevariety.php');
require_once('model_winevariety.php');

class Controller
{
   public function init($actions,$file_name)
   {
      if($actions == 404)
      {
         $actions = 'missing';
      }
      $action = $actions.'Action';
      $this->$action();

      ob_start();
      require_once($_SERVER['DOCUMENT_ROOT'] . "/connect/".$file_name);
		$contents = ob_get_contents();
		ob_end_clean();
      return $contents;
   }

   private $model_wine;
   private $model_region;
   private $model_grape_varity;

   private function commonActions()
   {
      $this->limit_start = DEFAULT_START_LIMIT;

      $this->model_wine = new ModelWineVariety();
      $this->model_region = new ModelRegion();
      $this->model_grape_varity = new ModelGrapeVariety();

      $this->region_results = $this->model_region->query_region();
      $this->grape_variety_results = $this->model_grape_varity->query_grape_variety();

      $this->region = 0;
      if(isset($_GET['region']))
      {
         $this->region = $_GET['region'];
      }

      $this->grape_variety = 0;
      if(isset($_GET['grape_variety']))
      {
         $this->grape_variety = $_GET['grape_variety'];
      }

      $this->winesearch = "";
      if(isset($_GET['winesearch']))
      {
         $this->winesearch = $_GET['winesearch'];
      }
   }

   private function inArrayActions($haystack,$needle,$id='id')
   {
      foreach($haystack as $row)
      {
         if($row[$id] == $needle)
         {
            return $row;
         }
      }
   }

   public function indexAction()
   {
      $this->commonActions();
   }

   public function resultsAction()
   {
      $this->commonActions();

      if(!isset($_GET['next']) ||
         (isset($_GET['next']) && $_GET['next'] <= 0) ||
         (isset($_GET['next']) && !preg_match("/^[0-9]+$/", $_GET['next'])))
      {
         $this->limit_end = DEFAULT_END_LIMIT;
         $this->prev_link = DEFAULT_START_LIMIT;
         $this->next_link = DEFAULT_END_LIMIT;
      }
      else
      {
         $this->limit_start = $_GET['next'];
         $this->limit_end = $_GET['next'] + ADD_TO_LIMIT;

         $this->prev_link = $_GET['next'] - ADD_TO_LIMIT;
         $this->next_link = $this->limit_end;
      }

      $this->wine_results = 
         $this->model_wine->search_wine_name($this->winesearch,$this->limit_start,
            $this->limit_end);
      
      $this->add_gets = 'region='.$this->region.'&amp;grape_variety='.$this->grape_variety.'&amp;winesearch='.$this->winesearch;
      $this->html_nxt_link = '<a href="/connect/">reset search</a><br />';
      $this->html_nxt_link .= '<a href="?'.$this->add_gets.'&amp;winesearch='.$this->winesearch.'">reset pagination</a>';
      if(count($this->wine_results) == DEFAULT_END_LIMIT)
      {
         $this->html_nxt_link = '<a href="?next='.$this->next_link .'&amp;'.$this->add_gets.'">Next &gt;&gt;</a>';
      }

      $this->html_prv_link = '<a href="?next='.$this->prev_link.'&amp;'.$this->add_gets.'">&lt;&lt; Previous</a>';
      if($this->limit_start == DEFAULT_START_LIMIT)
      {
         $this->html_prv_link = '';
      }
   }

   public function missingAction()
   {
      $this->commonActions();
   }

   public function testAction()
   {
		$this->test = "HELLO WORLD!!!";
   }
}