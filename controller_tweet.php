<?
/** Part C */
require_once("MiniTemplator.class.php");
require_once('controller_interface.php');

/** Part E */
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

class _tweetController implements Controller
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
      $this->indexAction();
   }

   /**
    * Send tweet to http://www.twitter.com/MichaelsunBaluy
    *
    * @return void.
    */
   private function indexAction()
   {
      $ine_viewed = array();
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

      /* Create a TwitterOauth object with consumer/user tokens. */
      $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], 
         $_SESSION['oauth_token_secret']);
         
      $token_credentials = $connection->getAccessToken($_REQUEST['oauth_verifier']);
      
      unset($_SESSION['oauth_token']);
      unset($_SESSION['oauth_token_secret']);
      
      $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token_credentials['oauth_token'],
         $token_credentials['oauth_token_secret']);

      /** Multiple results. */
      /** Create new models class. */
      $model_wine = new ModelWine();
      $wines = $model_wine->query_wine_in_id($wine_viewed);
      $tweet = '';
      $add_comma = false;
      foreach($wines as $rows)
      {
         if($add_comma)
         {
            $tweet .= ", ";
         }
         $add_comma = true;
         $tweet .= $rows['wine_name'];
      }
      if(strlen($tweet) >= 140)
      {
         $tweet = substr($tweet, 0, 137) . "...";
      }
      $content = $connection->post('statuses/update', array('status' => $tweet));

      header('location:'.$_SERVER["ASSIGN_PATH"].'session_viewed.html');
      exit;
   }
}