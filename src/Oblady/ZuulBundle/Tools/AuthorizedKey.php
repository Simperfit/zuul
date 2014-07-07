<?php
/**
 * Description of SSH2
 *
 * @author beleneglorion
 */
namespace Oblady\ZuulBundle\Tools;

use Oblady\ZuulBundle\Entity\Key as Key;

class AuthorizedKey
{
    protected $string;
    protected $options = array();
    protected $type;
    protected $key;
    protected $comment;
    
    protected $valid_options = array(
    "no-port-forwarding" => 1,
    "no-agent-forwarding" => 1,
    "no-x11-forwarding" => 1,
    "no-pty" => 1,
    "no-user-rc" => 1,
    'command' => "s",
    'environment' => "s",
    'from' => "s",
    'permitopen' => "a",
    'tunnel' => "s",
   );


    public function __construct($string = '', Key $obj = null) {

       if(!empty($string))
       {
           $this->parse($string);

       }
       
       if(!is_null($obj))  {
           $this->setKeyObj($obj);
       }
           


   }
   
   public function setKeyObj(Key $obj)
   {
       $this->obj = $obj;
       return $this;
   }
   
   public function getKeyObj()
   {

       return  $this->obj;
   }


   public function parse($string)
   {
       $string = trim($string);
       if(empty($string)) {
           throw new Exception('Invalid empty string to parse');

        }
       // we save the original string
       $this->string =$string;

       $this->parseOptions($string);
       $string = trim($string);

       @list($this->type,$this->key,$this->comment) = explode(' ',$string,3);


   }

   private function parseOptions(&$string)
   {
       $temp = $string;
       $old_temp= '';
       while(false != ($option = $this->getNextOptions($temp)))
       {

           $temp= trim($this->parseOptionValue($option,$temp));
           if($old_temp == $temp)
           {
              throw new \ErrorException('parse options error ('.$temp.')');
           }

           $old_temp = $temp;

       }

       $string = $temp;

   }

    private function parseOptionValue($option,$string)
   {
      $type = $this->valid_options[$option];
      $match = null;
      switch($type)
      {
          case 1:

            $string = preg_replace('/^'.$option.',?/', '',$string,1);
            $this->options[$option] = 1;
            break;
          case 's':
            $pattern =    "/(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/is";
            preg_match($pattern,$string,$match);
            $this->options[$option] = $match[0];
            $pattern =    "/$option=(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/is";
            $string =ltrim(trim(preg_replace($pattern,'',$string,1)),',');

          break;
          case 'a':
            $pattern =    "/(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/is";
            preg_match($pattern,$string,$match);
            if(!isset($this->options[$option])) {
                $this->options[$option] = array();
            }
            $this->options[$option][] = $match[0];
            $pattern =    "/$option=(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/is";
            $string =ltrim(trim(preg_replace($pattern,'',$string,1)),',');

          break;
          default:
             throw new Exception('Invalid options found :'.$option.' in string '.$string);
      }


      return $string;


   }
   /**
    *  return the next options name or false if none availiable
    *
    * @param string $string
    * @return mixed
    */

   private function getNextOptions($string)
   {

       foreach($this->valid_options as $key=>$type)
       {
           if(0 === strpos($string,$key)){
               return $key;
           }

       }

       return false;

   }


  public function __toString() {
      return var_export($this,true);
  }

  
  /**
   * return the original parsed string string used
   * @return string 
   */
  
  public function fullString() {
      return  $this->string;
  }
  
  public function encodedString()
  {
      
      
      return base64_encode($this->string);
      
  }
  
  /**
   * return the ssh key part
   * @return string
   */

  public function getKey()
  {
      return $this->key;

  }



   /**
   * return the ssh type part
   * @return string
   */

  public function getType()
  {
      return $this->type;

  }



   /**
   * return the ssh comment part
   * @return string
   */

  public function getComment()
  {
      return $this->comment;

  }

     /**
   * set the ssh comment part
   * @param  string  $comment
   * @return AuthorizedKey
   */

  public function setComment($comment)
  {
      $this->comment = $comment;
      return $this;
  }

    /**
   * return the ssh comment part
   * @return string
   */

  public function getFingerprint()
  {

      
    return  implode(':',str_split(md5(base64_decode($this->getKey())),2));

  }
  /*
  public function getUser()
  {

      if(!is_null($this->getKeyObj())) {
         return $this->getKeyObj()->getUser();
      }

    return null;
    

  }
*/

  public function getPubKeyFilename()
  {
  	$type = $this->getType();
  	$type = str_replace('ssh-','',$type);
        $returnValue = '';
          switch ($type)
          {

              case 'dss': 
                  $returnValue = '~/.ssh/id_dsa.pub';
              break;
          default:
          case 'rsa': 
                  $returnValue = '~/.ssh/id_rsa.pub';
              break;          
              
          
          }
        
  	return  $returnValue;

  }


}

