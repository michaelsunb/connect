<?
/** Part C */
require_once("MiniTemplator.class.php");
require_once('controller_interface.php');

/** Part E */
require_once('twitteroauth/twitteroauth.php');
/** 
 * Need config file for Twitter's
 * CONSUMER_KEY, CONSUMER_SECRET and
 * OAUTH_CALLBACK
 */
require_once('config.php');

class _session_viewedController implements Controller
{
   /**
    * @var $mini_t  MiniTemplator model.
    */
   private $mini_t;

   /**
    * retrieves the view file, checked by index.php, and use actions
    *
    * @return void.
    */
   public function init()
   {
      $this->mini_t = new MiniTemplator;
      $this->mini_t->readTemplateFromFile($_SERVER['DOCUMENT_ROOT'] . $_SERVER["ASSIGN_PATH"] . 'view_session_viewed.php');
      $this->mini_t->setVariable("ASSIGN_PATH",$_SERVER["ASSIGN_PATH"]);

      $this->indexAction();

      $this->mini_t->generateOutput();
   }

   /**
    * Start Page
    *
    * @return void.
    */
   private function indexAction()
   {
      if(isset($_COOKIE['submit']))
      {
         foreach($_COOKIE as $key=>$value)
         {
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

      if(count($_SESSION['wine_viewed']) > 0)
      {
         $wine_viewed = $_SESSION['wine_viewed'];
      }
      else
      {
         header("HTTP/1.0 404 Not Found");
         header('location:'.$_SERVER["ASSIGN_PATH"].'404.shtml');
         exit;
      }

      /** 
       * If tweet was successful we
       * want to make it known to the
       * user it was a success.
       * Also we need to unset the oauth
       * $_SESSION tokens.
       */
      if(isset($_GET['success']) &&
         isset($_SESSION['oauth_token']) && 
         isset($_SESSION['oauth_token_secret']))
      {
         $tweet_successful = '<span style="color:red;">Tweet was unsuccessful</span>';
         if($_GET['success'] == 1)
         {
            $tweet_successful = '<span style="color:green;">Tweet was successful.</span>';
         }

         $this->mini_t->setVariable('tweet_success',$tweet_successful);
      
         unset($_SESSION['oauth_token']);
         unset($_SESSION['oauth_token_secret']);
      }

      if(isset($_POST['tweet']))
      {
         /** 
          * Let's make a tweet using
          * Abraham Williams'
          * TwitterOAuth
          */
         $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
         /* Get temporary credentials. */
         $temporary_credentials = $connection->getRequestToken(OAUTH_CALLBACK);

         $_SESSION['oauth_token'] =  $temporary_credentials['oauth_token'];
         $_SESSION['oauth_token_secret'] = $temporary_credentials['oauth_token_secret'];

         /* Save temporary credentials to session. */
         $redirect_url = $connection->getAuthorizeURL($temporary_credentials['oauth_token'], FALSE);

         /* Create a TwitterOauth object with consumer/user tokens. */
         header('location:'.$redirect_url);
         exit;
      }

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

      foreach($this->wine_year_results as $row)
      {
         $this->mini_t->setVariable(
               "select_year_lo",'<option value="'.$row["year"].'">'.$row["year"].'</option>');

         $this->mini_t->addBlock("year_lo_select_block");
      }

      foreach($this->wine_year_results as $row)
      {
         $this->mini_t->setVariable(
               "select_year_hi",'<option value="'.$row["year"].'">'.$row["year"].'</option>');

         $this->mini_t->addBlock("year_hi_select_block");
      }

      foreach($this->grape_variety_results as $row)
      {
         $this->mini_t->setVariable(
               "select_grape_variety",'<option value="'.$row["variety_id"].'">'.$row["variety"].'</option>');

         $this->mini_t->addBlock("grape_variety_select_block");
      }

      foreach($this->region_results as $row)
      {
         $this->mini_t->setVariable(
               "select_region",'<option value="'.$row["region_id"].'">'.$row["region_name"].'</option>');

         $this->mini_t->addBlock("region_select_block");
      }

      /** Multiple results. */
      $wines = $model_wine->query_wine_in_id($wine_viewed);

      /** Distinct wine names. */
      foreach($wines as $rows)
      {
         $this->mini_t->setVariable('wine_name', $rows['wine_name']);
         /** Put into block so that we can use foreach loop */
         $this->mini_t->addBlock("wine_name_block");
      }
   }
}