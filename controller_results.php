<?
require_once('controller_interface.php');

class _resultsController implements Controller
{
   /**
    * retrieves the view file, checked by index.php, and use actions
    *
    * @return string return html body contents.
    */
   public function init()
   {
      $this->indexAction();

      /** Store the view file in a internal buffer */
      ob_start();
      require_once($_SERVER['DOCUMENT_ROOT'] . $_SERVER["ASSIGN_PATH"] . 'view_results.php');
      /** Internal buffer copied to a variable string */
      $contents = ob_get_contents();
      /** Discard the buffer contents */
      ob_end_clean();

      return $contents;
   }

   /**
    * Action to show a paginated list of wines.
    *
    * @return void.
    */
   private function indexAction()
   {
      $posts = array();

      if(!isset($_COOKIE['submit']) && 
      (isset($_GET['next']) ||
       isset($_GET['limit']) ||
       isset($_GET['column']) ||
       isset($_GET['wine_id'])))
      {
         $view = 'results.html';
         if(isset($_GET['wine_id']))
         {
            $view = 'wineinfo.html';
         }
         
         foreach($_GET as $key=>$value)
         {
            /** Two component query module. */
            setcookie($key, $value, time()+TEN_MINUTES_IN_SEC);
         }
         setcookie('submit', 'Submit', time()+TEN_MINUTES_IN_SEC);

         header('location:'.$_SERVER["ASSIGN_PATH"].$view);
         exit;
      }
      elseif(isset($_COOKIE['submit']))
      {
         foreach($_COOKIE as $key=>$value)
         {
            $posts[$key] = $value;

            /** 
             * We delete $_COOKIEs by setting
             * the cookie 60 minutes before
             * the current time.
             * We don't want the user to refresh
             * the page.
             */
            setcookie($key, NULL, time() - SIXTY_MINUTES_IN_SEC);
         }
      }
      else
      {
         header('location:'.$_SERVER["ASSIGN_PATH"]."index.html");
         exit;
      }

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

      /** Get $posts requests and check if they are numbers. */
      $this->wine_year_lo = 0;
      if(isset($posts['wine_year_lo']) && preg_match("/^[0-9]+$/", $posts['wine_year_lo']))
      {
         $this->wine_year_lo = $posts['wine_year_lo'];
      }
      $this->wine_year_hi = 0;
      if(isset($posts['wine_year_hi']) && preg_match("/^[0-9]+$/", $posts['wine_year_hi']))
      {
         $this->wine_year_hi = $posts['wine_year_hi'];
      }
      $this->html_year_error = "";
      if($this->wine_year_lo > $this->wine_year_hi)
      {
         $this->html_year_error =
            '<span style="color:red;">Low year must be lower than High year.</span>';
      }
      
      /** Get $posts requests and check if they are numbers. */
      $this->grape_variety = 0;
      if(isset($posts['grape_variety']) && preg_match("/^[0-9]+$/", $posts['grape_variety']))
      {
         $this->grape_variety = $posts['grape_variety'];
      }
      
      /** Get $posts requests and check if they are numbers. */
      $this->min_cost = 0;
      if(isset($posts['min_cost']))
      {
         $min_cost = preg_replace('/^\$/', '', $posts["min_cost"]);
         if(preg_match("/^[0-9.]+$/", $min_cost))
         {
            $this->min_cost = $min_cost;
         }
      }
      
      /** Get $posts requests and check if they are numbers. */
      $this->max_cost = 0;
      if(isset($posts['max_cost']))
      {
         $max_cost = preg_replace('/^\$/', '', $posts["max_cost"]);
         if(preg_match("/^[0-9.]+$/", $max_cost))
         {
            $this->max_cost = $max_cost;
         }
      }

      /** Show error if min ocst is greater than max cost */
      $this->html_cost_error = "";
      if($this->min_cost > $this->max_cost)
      {
         $this->html_cost_error =
            '<span style="color:red;">Min Cost must be lower than Max Cost</span>';
      }

      /** 2 to 9 because region All is 1 and region_id 1 produces no results. */
      $this->region = 0;
      if(isset($posts['region']) && preg_match("/^[2-9]+$/", $posts['region']))
      {
         $this->region = $posts['region'];
      }

      /** Allow if true to do a search query at results action. */
      $allow_search = true;
      $this->winesearch = "";
      if(isset($posts['winesearch']))
      {
         $this->winesearch = $posts['winesearch'];
         /** Allows all letters. */
         if(!preg_match("/^[A-Za-z]+$/", $posts['winesearch']) &&
            $posts['winesearch'] != "")
         {
            /** Don't allow search query because $posts request failed. */
            $allow_search = false;
         }
      }

      $this->winerysearch = "";
      if(isset($posts['winerysearch']))
      {
         $this->winerysearch = $posts['winerysearch'];

         /** Allows spaces as well as all letters. */
         if(!preg_match("/^[A-Za-z ]+$/", $posts['winerysearch']) &&
            $posts['winerysearch'] != "")
         {
            /** Don't allow search query because $posts request failed. */
            $allow_search = false;
         }
      }

      $this->column = DEFAULT_ORDER_COLUMN;
      /** Allow for 2 numbers. */
      if(isset($posts['column']) && preg_match("/^[0-9]{0,2}$/", $posts['column']))
      {
         $this->column = $posts['column'];
      }

      $this->limits = DEFAULT_TOTAL_LIMIT;
      /** Allow for 2 numbers with a maximum of 30. */
      if(isset($posts['limit']) && preg_match("/^[0-9]{0,2}$/", $posts['limit'])
       && $posts['limit'] <= DEFAULT_TOTAL_LIMIT )
      {
         $this->limits = $posts['limit'];
      }

      /** Add above $posts requests to string for html link. */
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

      /**
       * For pagination. Check if next is less or equals to 0 or
       * check if $posts next request has failed number conditions
       * and tehn set to default limits.
       */
      if(!isset($posts['next']) ||
         (isset($posts['next']) && $posts['next'] <= 0) ||
         (isset($posts['next']) && !preg_match("/^[0-9]+$/", $posts['next'])))
      {
         /** Default html links. */
         $this->prev_link = DEFAULT_START_LIMIT;
         $this->next_link = $this->limits;
      }
      /** otherwise put $posts next request into sql and html strings. */
      else
      {
         // User input sql starting from row in table.
         $this->limit_start = $posts['next'];

         // User input html next and previous links
         $this->prev_link = $posts['next'] - $this->limits;
         $this->next_link = $posts['next'] + $this->limits;
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
      if($allow_search)
      {
         $this->wine_results = 
            /**
             * wine_variety model has the sql with lots ofjoins.
             *
             * $this->winesearch    from $posts['winesearch'] request.
             * $this->winerysearch  from $posts['winerysearch'] request.
             * $selectsearch        from select box $posts requests.
             * $this->wine_year_lo  $posts['wine_year_lo'] request.
             * $this->wine_year_hi  $posts['wine_year_hi'] request.
             * $this->min_cost      $posts['min_cost'] request.
             * $this->max_cost      $posts['max_cost'] request..
             * $this->limit_start   $posts['next'] request.
             * $this->limits     from DEFAULT_TOTAL_LIMIT which is 30.
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
      }

      /** Add 'Previous' link if not at the beginning of pagination. */
      $this->html_prv_link = '';
      if($this->limit_start != DEFAULT_START_LIMIT)
      {
         $this->html_prv_link = '<a href="?next='.$this->prev_link.'&amp;'.$this->add_gets.'">&lt;&lt; Previous</a>';
      }

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
   }
}