<?php
/**
 * Plugin Name: Localandar API
 * Plugin URI: https://www.simtheme.com/localandar-api
 * Description: Get Data from Localander .ics file
 * Author: Shimion B
 * Author URI: https://www.simtheme.com/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Version: 0.1
 * Text Domain: simtheme
 * Domain Path: simtheme.com
 *
 *
 */

define('DRI', plugin_dir_path(__FILE__));

require DRI.'class.iCalReader.php';

class localendar{
    
    protected $today; 
    protected $now; 
    public $data = array(); 
    public $array = [];
    public $formet;
    public $count;
    public $ical = array();
    public function __construct() 
    {
    $this->ical = array();
     $this->today = self::Today();
     $this->now = self::Now();
      $this->array  = array('ID', 'title', 'content', 'stamp', 'start', 'end');
        $this->formet = 'Y-m-d H:i:s';
       $this->count = 5;
    }
    
    
    public function GetData( $data){
     
        $this->data = $data;
      
        
    }
    
    
    
    
    private function SetData(){
       
        if(!empty($this->data) && is_array($this->data)){
             if(!empty($this->data)){
                $i = 0;
                foreach ($this->data as $event) {
                    $i++;
                    if($this->CheckDataSmaller($event['DTSTART'])) break;
                    $return[] = self::Structure($event);
                    if($i >= $this->count ) break;
                    /*echo "SUMMARY: ".$event['SUMMARY']."<br/>";
                    echo "DTSTART: ".date('Y-m-d H:i:s',$ical->iCalDateToUnixTimestamp($event['DTSTART'])).'<br>';
                    echo "DTEND: ".date($event['DTEND'])."<br/>";
                    echo "DTSTAMP: ".date($event['DTSTAMP'])."<br/>";
                    echo "UID: ".$event['UID']."<br/>";
                    echo "DESCRIPTION: ".$event['DESCRIPTION']."<br/>";
                    echo "<hr/>";*/
                }
            

            }
            
            return $return;
    
        }
    
    }
    
    
    public function CheckDataSmaller($date){
        $eventdate = ICal::iCalDateToUnixTimestamp($date);
        if($eventdate <= self::Now()) true;
        
    }
    
    
    
    private function Today(){
        return strtotime('today');
    }
    
    private function Now(){
        return strtotime('now');
    }
    
    
    
    public function Randerd(){
            return self::SetData();
    }
    
    
    
    private function Structure($data){
        $output = array();
        if( !empty($this->array)){
            foreach($this->array as $arr){
                $output[$arr] = self::SetValueByName($arr, $data);
            }
        }
        
        
        return $output;
    }
    
    
    private function SetValueByName($name, $value){
         if($name == 'ID') 
             return $value['UID'];
         if($name == 'title') 
             return $value['SUMMARY'];
         if($name == 'content') 
             return $value['DESCRIPTION'];
         if($name == 'start') 
             return !empty($value['DTSTART']) ? self::TData($value['DTSTART']) : '';
         if($name == 'end') 
             return !empty($value['DTEND']) ? self::TData($value['DTEND']) : '';
         if($name == 'stamp') 
             return !empty($value['DTSTAMP']) ? self::TData($value['DTSTAMP']) : '';
    }
    
    
    
    
    private function TData($date){
        return date($this->formet, ICal::iCalDateToUnixTimestamp($date));
    }
    
    
    
}


function Localander(){
    $ical = new ICal(DRI.'southwestcpr.ics');  
    $app = new localendar();
    $events =  $ical->events();
    $app->GetData($events);
    return $app->Randerd();
    
    
}





?>
