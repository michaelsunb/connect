<?
/** Part C */
require_once("MiniTemplator.class.php");
require_once('controller_interface.php');

class _resultsController implements Controller
{
   /**
    * @var $mini_t  MiniTemplator model.
    */
   private $mini_t;

   /**
    * retrieves the view file, checked by index.php, and use actions
    *
    * @return string return html body contents.
    */
   public function init()
   {
      $this->mini_t = new MiniTemplator;
      $this->mini_t->readTemplateFromFile($_SERVER['DOCUMENT_ROOT'] . $_SERVER["ASSIGN_PATH"] . 'view_results.php');
      $this->mini_t->setVariable("ASSIGN_PATH",$_SERVER["ASSIGN_PATH"]);

      $this->indexAction();

      $this->mini_t->generateOutput();
   }

   /**
    * Action to show a paginated list of wines.
    *
    * @return void.
    */
   private function indexAction()
   {
      /** Default sql starting from row in table */
      $this->limit_start = DEFAULT_START_LIMIT;

      /** Create new models class. */
      $model_orders = new ModelOrders();
      $model_wine = new ModelWine();
      $model_winevariety = new ModelWineVariety();
      $model_region = new ModelRegion();
      $model_grape_varity = new ModelGrapeVariety();

      /**
       * query all results for their respective models.
       * For the select boxes.
       */
      $this->wine_year_results = $model_wine->query_years();
      $this->region_results = $model_region->query_region();
      $this->grape_variety_results = $model_grape_varity->query_grape_variety();

      /** Get $_GET requests and check if they are numbers. */
      $this->wine_year_lo = 0;
      if(isset($_GET['wine_year_lo']) && preg_match("/^[0-9]+$/", $_GET['wine_year_lo']))
      {
         $this->wine_year_lo = $_GET['wine_year_lo'];
      }
      $this->wine_year_hi = 0;
      if(isset($_GET['wine_year_hi']) && preg_match("/^[0-9]+$/", $_GET['wine_year_hi']))
      {
         $this->wine_year_hi = $_GET['wine_year_hi'];
      }
      $this->html_year_error = "";
      if($this->wine_year_lo > $this->wine_year_hi)
      {
         $this->html_year_error = 
            '<span style="color:red;">Low year must be lower than High year.</span>';
      }
      $this->mini_t->setVariable('html_year_error',$this->html_year_error);

      foreach($this->wine_year_results as $row)
      {
         /** 
          * Check if $_GET year request is equal to
          * the select so we can remember the selected box.
          */
         if($this->wine_year_lo == $row["year"])
         {
            $this->mini_t->setVariable(
               "select_year_lo",'<option value="'.$row["year"].'" selected>'.$row["year"].'</option>');
         }
         else
         {
            $this->mini_t->setVariable(
               "select_year_lo",'<option value="'.$row["year"].'">'.$row["year"].'</option>');
         }
         $this->mini_t->addBlock("year_lo_select_block");
      }

      foreach($this->wine_year_results as $row)
      {
         /** 
          * Check if $_GET year request is equal to
          * the select so we can remember the selected box.
          */
         if($this->wine_year_hi == $row["year"])
         {
            $this->mini_t->setVariable(
               "select_year_hi",'<option value="'.$row["year"].'" selected>'.$row["year"].'</option>');
         }
         else
         {
            $this->mini_t->setVariable(
               "select_year_hi",'<option value="'.$row["year"].'">'.$row["year"].'</option>');
         }
         $this->mini_t->addBlock("year_hi_select_block");
      }
      
      /** Get $_GET requests and check if they are numbers. */
      $this->grape_variety = 0;
      if(isset($_GET['grape_variety']) && preg_match("/^[0-9]+$/", $_GET['grape_variety']))
      {
         $this->grape_variety = $_GET['grape_variety'];
      }
      foreach($this->grape_variety_results as $row)
      {
         /** 
          * Check if $_GET grape_variety request is equal to
          * the select so we can remember the selected box.
          */
         if($this->grape_variety == $row["variety_id"])
         {
            $this->mini_t->setVariable(
               "select_grape_variety",'<option value="'.$row["variety_id"].'" selected>'.$row["variety"].'</option>');
         }
         else
         {
            $this->mini_t->setVariable(
               "select_grape_variety",'<option value="'.$row["variety_id"].'">'.$row["variety"].'</option>');
         }
         $this->mini_t->addBlock("grape_variety_select_block");
      }
      
      /** Get $_GET requests and check if they are numbers. */
      $this->min_cost = 0;
      if(isset($_GET['min_cost']))
      {
         $min_cost = preg_replace('/^\$/', '', $_GET["min_cost"]);
         if(preg_match("/^[0-9.]+$/", $min_cost))
         {
            $this->min_cost = $min_cost;
         }
      }
      $this->mini_t->setVariable("min_cost",$this->min_cost);
      
      /** Get $_GET requests and check if they are numbers. */
      $this->max_cost = 0;
      if(isset($_GET['max_cost']))
      {
         $max_cost = preg_replace('/^\$/', '', $_GET["max_cost"]);
         if(preg_match("/^[0-9.]+$/", $max_cost))
         {
            $this->max_cost = $max_cost;
         }
      }
      $this->mini_t->setVariable("max_cost",$this->max_cost);

      /** Show error if min ocst is greater than max cost */
      $this->html_cost_error = "";
      if($this->min_cost > $this->max_cost)
      {
         $this->html_cost_error =
            '<span style="color:red;">Min Cost must be lower than Max Cost</span>';
      }
      $this->mini_t->setVariable("html_cost_error",$this->html_cost_error);

      /** 2 to 9 because region All is 1 and region_id 1 produces no results. */
      $this->region = 0;
      if(isset($_GET['region']) && preg_match("/^[2-9]+$/", $_GET['region']))
      {
         $this->region = $_GET['region'];
      }
      foreach($this->region_results as $row)
      {
         /** 
          * Check if $_GET region request is equal to
          * the select so we can remember the selected box.
          */
         if($this->region == $row["region_id"])
         {
            $this->mini_t->setVariable(
               "select_region",'<option value="'.$row["region_id"].'" selected>'.$row["region_name"].'</option>');
         }
         else
         {
            $this->mini_t->setVariable(
               "select_region",'<option value="'.$row["region_id"].'">'.$row["region_name"].'</option>');
         }
         $this->mini_t->addBlock("region_select_block");
      }

      /** Allow if true to do a search query at results action. */
      $this->allow_search = true;
      $this->winesearch = "";
      if(isset($_GET['winesearch']))
      {
         $this->winesearch = $_GET['winesearch'];
         /** Allows all letters. */
         if(!preg_match("/^[A-Za-z]+$/", $_GET['winesearch']) &&
            $_GET['winesearch'] != "")
         {
            /** Don't allow search query because $_GET request failed. */
            $this->allow_search = false;
         }
      }
      /** Put $_GET winesearch request back into the view. */
      $this->mini_t->setVariable('winesearch',$this->winesearch);

      $this->winerysearch = "";
      if(isset($_GET['winerysearch']))
      {
         $this->winerysearch = $_GET['winerysearch'];

         /** Allows spaces as well as all letters. */
         if(!preg_match("/^[A-Za-z ]+$/", $_GET['winerysearch']) &&
            $_GET['winerysearch'] != "")
         {
            /** Don't allow search query because $_GET request failed. */
            $this->allow_search = false;
         }
      }
      /** Put $_GET winerysearch request back into the view. */
      $this->mini_t->setVariable('winerysearch',$this->winerysearch);

      $this->column = DEFAULT_ORDER_COLUMN;
      /** Allow for 2 numbers. */
      if(isset($_GET['column']) && preg_match("/^[0-9]{0,2}$/", $_GET['column']))
      {
         $this->column = $_GET['column'];
      }

      $this->limits = DEFAULT_TOTAL_LIMIT;
      /** Allow for 2 numbers with a maximum of 30. */
      if(isset($_GET['limit']) && preg_match("/^[0-9]{0,2}$/", $_GET['limit'])
       && $_GET['limit'] <= DEFAULT_TOTAL_LIMIT )
      {
         $this->limits = $_GET['limit'];
      }

      /** Add above $_GET requests to string for html link. */
      $this->add_gets = 'region='.$this->region;
      $this->add_gets .= '&amp;wine_year_lo='.$this->wine_year_lo;
      $this->add_gets .= '&amp;wine_year_hi='.$this->wine_year_hi;
      $this->add_gets .= '&amp;grape_variety='.$this->grape_variety;
      $this->add_gets .= '&amp;min_cost='.$this->min_cost;
      $this->add_gets .= '&amp;max_cost='.$this->max_cost;

      /** Replace whitespace with %20 for w3c standards. */
      $this->add_gets .= '&amp;winesearch='.str_replace(' ', '%20', $this->winesearch);
      $this->add_gets .= '&amp;winerysearch='.str_replace(' ', '%20', $this->winerysearch);

      /** Add limits to add_gets. */
      $this->add_gets .= '&amp;limit='.$this->limits;

      /** Create url for columns. */
      $this->html_column = '?'.$this->add_gets.'&amp;column=';

      /** Add columns to add_gets. */
      $this->add_gets .= '&amp;column='.$this->column;

      /** Format html a href link. */
      $this->html_nxt_link = '<a href="'.$_SERVER["ASSIGN_PATH"].'index.html">reset search</a><br />';
      $this->html_nxt_link .= '<a href="'.$_SERVER["ASSIGN_PATH"].'results.html?'.$this->add_gets.'">reset pagination</a>';
   
      /**
       * For pagination. Check if next is less or equals to 0 or
       * check if $_GET next request has failed number conditions
       * and tehn set to default limits.
       */
      if(!isset($_GET['next']) ||
         (isset($_GET['next']) && $_GET['next'] <= 0) ||
         (isset($_GET['next']) && !preg_match("/^[0-9]+$/", $_GET['next'])))
      {
         
         /** Default html links. */
         $this->prev_link = DEFAULT_START_LIMIT;
         $this->next_link = $this->limits;
      }
      /** otherwise put $_GET next request into sql and html strings. */
      else
      {
         
         // User input sql starting from row in table.
         $this->limit_start = $_GET['next'];

         // User input html next and previous links
         $this->prev_link = $_GET['next'] - $this->limits;
         $this->next_link = $_GET['next'] + $this->limits;
      }
      
      /**
       * Select box selected and now adding to sql query.
       * Where $selectsearch[key] is the column name and
       * the value is the user input select.
       */
      $selectsearch = array();
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
      /** Allow if true to do a search query at results action. */
      if($this->allow_search)
      {
         $this->wine_results = 
            /**
             * wine_variety model has the sql with lots ofjoins.
             *
             * $this->winesearch    from $_GET['winesearch'] request.
             * $this->winerysearch  from $_GET['winerysearch'] request.
             * $selectsearch        from select box $_GET requests.
             * $this->wine_year_lo  $_GET['wine_year_lo'] request.
             * $this->wine_year_hi  $_GET['wine_year_hi'] request.
             * $this->min_cost      $_GET['min_cost'] request.
             * $this->max_cost      $_GET['max_cost'] request..
             * $this->limit_start   $_GET['next'] request.
             * $this->limits        from DEFAULT_TOTAL_LIMIT which is 30.
             */
            $model_winevariety->search_wine_name($this->winesearch,
               $this->winerysearch,
               $selectsearch, 
               $this->column,
               $this->wine_year_lo,
               $this->wine_year_hi,
               $this->min_cost,
               $this->max_cost,
               $this->limit_start,
               $this->limits);

         /** 
          * Set wine pagination results here instead of
          * the view script because of miniTemplator.
          */
         foreach($this->wine_results as $row)
         {
            $this->mini_t->setVariable('wine_id', $row['wine_id']);
            $this->mini_t->setVariable('wine_name', $row['wine_name']);
            $this->mini_t->setVariable('variety', $row['variety']);
            $this->mini_t->setVariable('year', $row['year']);
            $this->mini_t->setVariable('wine_type', $row['wine_type']);
            $this->mini_t->setVariable('winery_name', $row['winery_name']);
            $this->mini_t->setVariable('region_name', $row['region_name']);
            $this->mini_t->setVariable('on_hand', $row['on_hand']);
            $this->mini_t->setVariable('cost', $row['cost']);
            $this->mini_t->setVariable('total_qty', $row['total_qty']);
            $this->mini_t->setVariable('total_price', $row['total_price']);
            $this->mini_t->addBlock("wine_pagination_block");
         }
      }

      /** 
       * Check the wine pagination results if equal
       * to zero and if so add error to miniTemplator 
       * variable.
       */
      if(count($this->wine_results) == 0)
      {
       $this->mini_t->setVariable('no_records',
         '<p style="color:red;">No records match your search criteria</p>');
      }

      /**
       * Add 'Next' link if not at the end of pagination.
       * Assumed that the number of results is equal to
       * the total number of results limit set from 
       * the sql query.
       */
      if(count($this->wine_results) == $this->limits)
      {
         $this->html_nxt_link = '<a href="?next='.$this->next_link .'&amp;'.$this->add_gets.'">Next &gt;&gt;</a>';
         $this->mini_t->setVariable('html_nxt_link', $this->html_nxt_link);
      }
      $this->mini_t->setVariable('html_nxt_link', $this->html_nxt_link);

      /** 
       * Set html_column from commonAction here because 
       * it's not needed anywhere else.
       */
      $this->mini_t->setVariable('html_column', $this->html_column);

      /** Add 'Previous' link if not at the beginning of pagination. */
      $this->html_prv_link = '';
      if($this->limit_start != DEFAULT_START_LIMIT)
      {
         $this->html_prv_link = '<a href="?next='.$this->prev_link.'&amp;'.$this->add_gets.'">&lt;&lt; Previous</a>';
      }
      $this->mini_t->setVariable('html_prv_link', $this->html_prv_link);

      /** Remove limit link in add_gets because we will add a new one. */
      $add_gets = preg_replace('/\&amp;limit=[0-9]{0,2}/', "", $this->add_gets);
      /** Create limits for pagination table. */
      $this->html_limits = '<a href="'.$_SERVER["ASSIGN_PATH"].'results.html?next='.$this->limit_start.'&amp;'.
         $add_gets.'&amp;limit=5">5</a>, ';
      $this->html_limits .= '<a href="'.$_SERVER["ASSIGN_PATH"].'results.html?next='.$this->limit_start.'&amp;'.
         $add_gets.'&amp;limit=10">10</a>, ';
      $this->html_limits .= '<a href="'.$_SERVER["ASSIGN_PATH"].'results.html?next='.$this->limit_start.'&amp;'.
         $add_gets.'&amp;limit=15">15</a>, ';
      $this->html_limits .= '<a href="'.$_SERVER["ASSIGN_PATH"].'results.html?next='.$this->limit_start.'&amp;'.
         $add_gets.'&amp;limit=30">30</a>';
      $this->mini_t->setVariable('html_limits', $this->html_limits);

      /** Put limits in the view for hidden element */
      $this->mini_t->setVariable('limits', $this->limits);

      $html_session = '<h3></h3>';
      if(isset($_GET['start_session']))
      {
         $_SESSION['start_session'] = false;
         header('location:session_viewed.html');
         exit;
      }
      elseif(isset($_SESSION['start_session']) && $_SESSION['start_session'] == true)
      {
         $html_session = 
         '<h3><a href="results.html?'. $_SERVER['QUERY_STRING'] .'&amp;start_session=true">End Session</a></h3>';

         foreach($this->wine_results as $row)
         {
            $_SESSION['wine_viewed'] []= $row['wine_id'];
         }
      }
      
      if(isset($_SESSION['wine_viewed']) && count($_SESSION['wine_viewed']) > 0)
      {
         $html_session .= '<p><a href="session_viewed.html">View Previous Session</a></p>';
      }

      $this->mini_t->setVariable("html_session",$html_session);
   }
}