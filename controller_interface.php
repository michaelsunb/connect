<?

interface Controller
{
   /**
    * retrieves the view file, checked by index.php, and use actions
    *
    * @return string             return html body contents.
    */
   public function init();
}