<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Util extends Model
{
    public static function b_fCheckArray($the_a_Array)
    {
        return isset($the_a_Array) && is_array($the_a_Array) && $the_a_Array;
    }

    
    public static function b_fCheckObject($the_o_Object)
    {
        return isset($the_o_Object) && is_object($the_o_Object) && $the_o_Object;
    }

    
    public static function sz_fCurrentDateTime($the_sz_Format = 'Y-m-d H:i:s')
    {
        return date($the_sz_Format, time());
    }

    
    public static function a_fMultiToSingleArray($the_a_MultiArray, $the_sz_Key = 'id', $the_sz_Value = 'name', $the_b_SortByValue = false, $the_b_SortDesc = false) {
        $a_SingleArray = array();
        foreach ($the_a_MultiArray as $a_Single) {
            $a_SingleArray[$a_Single[$the_sz_Key]] = $a_Single[$the_sz_Value];
        }
        return $a_SingleArray;
    }
    
    
    public static function i_fNumberOfDays($i_FromTime,$i_ToTime,$sz_day)
    {
        $i_FromTime = strtotime(date('Y-m-d 00:00:00',$i_FromTime)) ;
        $i_ToTime = strtotime(date('Y-m-d 00:00:00',$i_ToTime)) ;
        $dt = Array ();
        for($i=$i_FromTime; $i<=$i_ToTime;$i=$i+86400) {
                if(date("l",$i) == $sz_day) {
                        $dt[] = date("l Y-m-d ", $i);
                }
        }
        return count($dt);        
    }
    
    /**

     * @Auth: Dienct
     * @Des: Format date time
     * @Since: 8/1/2016     
     */
    public static function sz_DateTimeFormat($date_time){
        $date = date_create($date_time);
        return date_format($date,"m/d/Y H:i:s");
    }
    
    public static function sz_DateFinishFormat($date_time){
        $date = date_create($date_time);
        return date_format($date,"m/d/Y");
    }
}
