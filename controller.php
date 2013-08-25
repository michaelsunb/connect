<?
require_once('model_region.php');
require_once('model_grapevariety.php');
require_once('model_wine.php');
require_once('model_winevariety.php');

require_once('helpers.php');

class Controller
{
   /**
    * retrieves the view file, checked by index.php, and use actions
    *
    * @param string $actions     Actions to select which view you want.
    * @param string $file_name   Choose a file from connect directory.
    * @return string             return html body contents.
    */
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

   /**
    * @var $models_[a-z]+  Common models used in the actions.
    */
   private $model_wine;
   private $model_winevariety;
   private $model_region;
   private $model_grape_varity;

   /**
    * Repetitive actions are put in here
    *
    * @return void.
    */
   private function commonActions()
   {
      // Default sql starting from row in table
      $this->limit_start = DEFAULT_START_LIMIT;

      // create new models class.
      $this->model_wine = new ModelWine();
      $this->model_winevariety = new ModelWineVariety();
      $this->model_region = new ModelRegion();
      $this->model_grape_varity = new ModelGrapeVariety();

      /**
       * query all results for their respective models.
       * For the select boxes.
       */
      $this->wine_year_results = $this->model_wine->query_years();
      $this->region_results = $this->model_region->query_region();
      $this->grape_variety_results = $this->model_grape_varity->query_grape_variety();

      // get $_GET requests and check if they are numbers.
      $this->wine_year = 0;
      if(isset($_GET['wine_year']) && preg_match("/^[0-9]+$/", $_GET['wine_year']))
      {
         $this->wine_year = $_GET['wine_year'];
      }
      
      // get $_GET requests and check if they are numbers
      $this->grape_variety = 0;
      if(isset($_GET['grape_variety']) && preg_match("/^[0-9]+$/", $_GET['grape_variety']))
      {
         $this->grape_variety = $_GET['grape_variety'];
      }
      
      // 2 to 9 because region All is 1 and region_id 1 produces no results.
      $this->region = 0;
      if(isset($_GET['region']) && preg_match("/^[2-9]+$/", $_GET['region']))
      {
         $this->region = $_GET['region'];
      }

      // allow if true to do a search query at results action.
      $this->allow_search = true;
      $this->winesearch = "";
      if(isset($_GET['winesearch']))
      {
         $this->winesearch = $_GET['winesearch'];
         // Allows all letters
         if(!preg_match("/^[A-Za-z]+$/", $_GET['winesearch']) &&
            $_GET['winesearch'] != "")
         {
            // don't allow search query because $_GET request failed.
            $this->allow_search = false;
         }
      }

      $this->winerysearch = "";
      if(isset($_GET['winerysearch']))
      {
         $this->winerysearch = $_GET['winerysearch'];

         // Allows spaces as well as all letters
         if(!preg_match("/^[A-Za-z ]+$/", $_GET['winerysearch']) &&
            $_GET['winerysearch'] != "")
         {
            // don't allow search query because $_GET request failed.
            $this->allow_search = false;
         }
      }
      
      // add above $_GET requests to string for html link.
      $this->add_gets = 'region='.$this->region;
      $this->add_gets .= '&amp;wine_year='.$this->wine_year;
      $this->add_gets .= '&amp;grape_variety='.$this->grape_variety;

      // replace whitespace with %20 for w3c standards.
      $this->add_gets .= '&amp;winesearch='.str_replace(' ', '%20', $this->winesearch);
      $this->add_gets .= '&amp;winerysearch='.str_replace(' ', '%20', $this->winerysearch);

      // format html a href link.
      $this->html_nxt_link = '<a href="/connect/index.html">reset search</a><br />';
      $this->html_nxt_link .= '<a href="/connect/results.html?'.$this->add_gets.'">reset pagination</a>';
   }

   /**
    * index action /index, /index.htm, /index.html
    *
    * @return void.
    */
   protected function indexAction()
   {
      $this->commonActions();
   }

   /**
    * results action /results, /results(anything)
    *
    * @return void.
    */
   protected function resultsAction()
   {
      $this->commonActions();

      /**
       * For pagination. Check if next is less or equals to 0 or
       * check if $_GET next request has failed number conditions
       * and tehn set to default limits.
       */
      if(!isset($_GET['next']) ||
         (isset($_GET['next']) && $_GET['next'] <= 0) ||
         (isset($_GET['next']) && !preg_match("/^[0-9]+$/", $_GET['next'])))
      {
         /**
          * Default sql total number of results per page.
          * TODO: change variable name
          */
         $this->limit_end = DEFAULT_TOTAL_LIMIT;
         
         // Default html links
         $this->prev_link = DEFAULT_START_LIMIT;
         $this->next_link = DEFAULT_END_LIMIT;
      }
      // otherwise put $_GET next request into sql and html strings.
      else
      {
         /**
          * Sql total number of results per page.
          * TODO: change variable name and allow 5, 10, 15, 30 results.
          */
         $this->limit_end = DEFAULT_TOTAL_LIMIT;
         
         // User input sql starting from row in table.
         $this->limit_start = $_GET['next'];

         // User input html next and previous links
         $this->prev_link = $_GET['next'] - ADD_TO_LIMIT;
         $this->next_link = $_GET['next'] + ADD_TO_LIMIT;
      }

      /**
       * Select box selected and now adding to sql query.
       * Where $selectsearch[key] is the column name and
       * the value is the user input select.
       */
      $selectsearch = array();
      if($this->wine_year != 0)
      {
         $table_column = '`wine`.`year`';
         $selectsearch[$table_column] = $this->wine_year;
      }
      if($this->region != 0)
      {
         $table_column = '`winery`.`region_id`';
         $selectsearch[$table_column] = $this->region;
      }
      if($this->grape_variety != 0)
      {
         $table_column = '`wine_variety`.`variety_id`';
         $selectsearch[$table_column] = $this->grape_variety;
      }

      $this->wine_results = array();
      // allow if true to do a search query at results action.
      if($this->allow_search)
      {
         $this->wine_results = 
            /**
             * wine_variety model has the sql with lots ofjoins.
             * $this->winesearch from $_GET['winesearch'] request.
             * $this->winerysearch from $_GET['winerysearch'] request.
             * $selectsearch from select box $_GET requests.
             * $this->limit_start $_GET['next'] request.
             * $this->limit_end from DEFAULT_TOTAL_LIMIT which is 30.
             */
            $this->model_winevariety->search_wine_name($this->winesearch,$this->winerysearch,$selectsearch, 
               $this->limit_start, $this->limit_end);
      }

      /**
       * Add 'Next' link if not at the end of pagination.
       * Assumed that the number of results is equal to
       * the total number of results limit set from 
       * the sql query.
       */
      if(count($this->wine_results) == $this->limit_end)
      {
         $this->html_nxt_link = '<a href="?next='.$this->next_link .'&amp;'.$this->add_gets.'">Next &gt;&gt;</a>';
      }

      // Add 'Previous' link if not at the beginning of pagination.
      $this->html_prv_link = '';
      if($this->limit_start != DEFAULT_START_LIMIT)
      {
         $this->html_prv_link = '<a href="?next='.$this->prev_link.'&amp;'.$this->add_gets.'">&lt;&lt; Previous</a>';
      }
   }

   /**
    * results action /404.shtml, /404(anything)
    *
    * @return void.
    */
   protected function missingAction()
   {
      $this->commonActions();
   }
}