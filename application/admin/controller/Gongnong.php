<?php
namespace app\admin\controller;
use think\Controller;
class Gongnong extends Common
{
	/*
	公历和农历相互转换

	 */

    public $lunarInfo = array(
		array('BaseDays'=>23,'Intercalation'=>3,'BaseWeekday'=>2,'BaseKanChih'=>17,'MonthDays'=>[1,0,0,1,0,0,1,1,0,1,1,1,0]),/*1936*/
		array('BaseDays'=>41,'Intercalation'=>0,'BaseWeekday'=>4,'BaseKanChih'=>23,'MonthDays'=>[1,0,0,1,0,0,1,0,1,1,1,0,1]),
		array('BaseDays'=>30,'Intercalation'=>7,'BaseWeekday'=>5,'BaseKanChih'=>28,'MonthDays'=>[1,1,0,0,1,0,0,1,0,1,1,0,1]),
		array('BaseDays'=>49,'Intercalation'=>0,'BaseWeekday'=>6,'BaseKanChih'=>33,'MonthDays'=>[1,1,0,0,1,0,0,1,0,1,0,1,1]),
		array('BaseDays'=>38,'Intercalation'=>0,'BaseWeekday'=>0,'BaseKanChih'=>38,'MonthDays'=>[1,1,0,1,0,1,0,0,1,0,1,0,1]),/*1940*/
		array('BaseDays'=>26,'Intercalation'=>6,'BaseWeekday'=>2,'BaseKanChih'=>44,'MonthDays'=>[1,1,0,1,1,0,1,0,0,1,0,1,0]),
		array('BaseDays'=>45,'Intercalation'=>0,'BaseWeekday'=>3,'BaseKanChih'=>49,'MonthDays'=>[1,0,1,1,0,1,0,1,0,1,0,1,0]),
		array('BaseDays'=>35,'Intercalation'=>0,'BaseWeekday'=>4,'BaseKanChih'=>54,'MonthDays'=>[0,1,0,1,0,1,1,0,1,0,1,0,1]),
		array('BaseDays'=>24,'Intercalation'=>4,'BaseWeekday'=>5,'BaseKanChih'=>59,'MonthDays'=>[1,0,1,0,1,0,1,0,1,1,0,1,1]),/*1944*/
		array('BaseDays'=>43,'Intercalation'=>0,'BaseWeekday'=>0,'BaseKanChih'=>5,'MonthDays'=>[0,0,1,0,0,1,0,1,1,1,0,1,1]),
		array('BaseDays'=>32,'Intercalation'=>0,'BaseWeekday'=>1,'BaseKanChih'=>10,'MonthDays'=>[1,0,0,1,0,0,1,0,1,1,0,1,1]),
		array('BaseDays'=>21,'Intercalation'=>2,'BaseWeekday'=>2,'BaseKanChih'=>15,'MonthDays'=>[1,1,0,0,1,0,0,1,0,1,0,1,1]),
		array('BaseDays'=>40,'Intercalation'=>0,'BaseWeekday'=>3,'BaseKanChih'=>20,'MonthDays'=>[1,0,1,0,1,0,0,1,0,1,0,1,1]),/*1948*/
		array('BaseDays'=>28,'Intercalation'=>7,'BaseWeekday'=>5,'BaseKanChih'=>26,'MonthDays'=>[1,0,1,1,0,1,0,0,1,0,1,0,1]),
		array('BaseDays'=>47,'Intercalation'=>0,'BaseWeekday'=>6,'BaseKanChih'=>31,'MonthDays'=>[0,1,1,0,1,1,0,0,1,0,1,0,1]),
		array('BaseDays'=>36,'Intercalation'=>0,'BaseWeekday'=>0,'BaseKanChih'=>36,'MonthDays'=>[1,0,1,1,0,1,0,1,0,1,0,1,0]),
		array('BaseDays'=>26,'Intercalation'=>5,'BaseWeekday'=>1,'BaseKanChih'=>41,'MonthDays'=>[0,1,0,1,0,1,0,1,1,0,1,0,1]),/*1952*/
		array('BaseDays'=>44,'Intercalation'=>0,'BaseWeekday'=>3,'BaseKanChih'=>47,'MonthDays'=>[0,1,0,0,1,1,0,1,1,0,1,0,1]),
		array('BaseDays'=>33,'Intercalation'=>0,'BaseWeekday'=>4,'BaseKanChih'=>52,'MonthDays'=>[1,0,1,0,0,1,0,1,1,0,1,1,0]),
		array('BaseDays'=>23,'Intercalation'=>3,'BaseWeekday'=>5,'BaseKanChih'=>57,'MonthDays'=>[0,1,0,1,0,0,1,0,1,0,1,1,1]),
		array('BaseDays'=>42,'Intercalation'=>0,'BaseWeekday'=>6,'BaseKanChih'=>2,'MonthDays'=>[0,1,0,1,0,0,1,0,1,0,1,1,1]),/*1956*/
		array('BaseDays'=>30,'Intercalation'=>8,'BaseWeekday'=>1,'BaseKanChih'=>8,'MonthDays'=>[1,0,1,0,1,0,0,1,0,1,0,1,0]),
		array('BaseDays'=>48,'Intercalation'=>0,'BaseWeekday'=>2,'BaseKanChih'=>13,'MonthDays'=>[1,1,1,0,1,0,0,1,0,1,0,1,0]),
		array('BaseDays'=>38,'Intercalation'=>0,'BaseWeekday'=>3,'BaseKanChih'=>18,'MonthDays'=>[0,1,1,0,1,0,1,0,1,0,1,0,1]),
		array('BaseDays'=>27,'Intercalation'=>6,'BaseWeekday'=>4,'BaseKanChih'=>23,'MonthDays'=>[1,0,1,0,1,1,0,1,0,1,0,1,0]),/*1960*/
		array('BaseDays'=>45,'Intercalation'=>0,'BaseWeekday'=>6,'BaseKanChih'=>29,'MonthDays'=>[1,0,1,0,1,0,1,1,0,1,0,1,0]),
		array('BaseDays'=>35,'Intercalation'=>0,'BaseWeekday'=>0,'BaseKanChih'=>34,'MonthDays'=>[0,1,0,0,1,0,1,1,0,1,1,0,1]),
		array('BaseDays'=>24,'Intercalation'=>4,'BaseWeekday'=>1,'BaseKanChih'=>39,'MonthDays'=>[1,0,1,0,0,1,0,1,0,1,1,1,0]),
		array('BaseDays'=>43,'Intercalation'=>0,'BaseWeekday'=>2,'BaseKanChih'=>44,'MonthDays'=>[1,0,1,0,0,1,0,1,0,1,1,1,0]),/*1964*/
		array('BaseDays'=>32,'Intercalation'=>0,'BaseWeekday'=>4,'BaseKanChih'=>50,'MonthDays'=>[0,1,0,1,0,0,1,0,0,1,1,0,1]),
		array('BaseDays'=>20,'Intercalation'=>3,'BaseWeekday'=>5,'BaseKanChih'=>55,'MonthDays'=>[1,1,1,0,1,0,0,1,0,0,1,1,0]),
		array('BaseDays'=>39,'Intercalation'=>0,'BaseWeekday'=>6,'BaseKanChih'=>0,'MonthDays'=>[1,1,0,1,1,0,0,1,0,1,0,1,0]),
		array('BaseDays'=>29,'Intercalation'=>7,'BaseWeekday'=>0,'BaseKanChih'=>5,'MonthDays'=>[0,1,0,1,1,0,1,0,1,0,1,0,1]),/*1968*/
		array('BaseDays'=>47,'Intercalation'=>0,'BaseWeekday'=>2,'BaseKanChih'=>11,'MonthDays'=>[0,1,0,1,0,1,1,0,1,0,1,0,1]),
		array('BaseDays'=>36,'Intercalation'=>0,'BaseWeekday'=>3,'BaseKanChih'=>16,'MonthDays'=>[1,0,0,1,0,1,1,0,1,1,0,1,0]),
		array('BaseDays'=>26,'Intercalation'=>5,'BaseWeekday'=>4,'BaseKanChih'=>21,'MonthDays'=>[0,1,0,0,1,0,1,0,1,1,1,0,1]),
		array('BaseDays'=>45,'Intercalation'=>0,'BaseWeekday'=>5,'BaseKanChih'=>26,'MonthDays'=>[0,1,0,0,1,0,1,0,1,1,0,1,1]),/*1972*/
		array('BaseDays'=>33,'Intercalation'=>0,'BaseWeekday'=>0,'BaseKanChih'=>32,'MonthDays'=>[1,0,1,0,0,1,0,0,1,1,0,1,1]),
		array('BaseDays'=>22,'Intercalation'=>4,'BaseWeekday'=>1,'BaseKanChih'=>37,'MonthDays'=>[1,1,0,1,0,0,1,0,0,1,1,0,1]),
		array('BaseDays'=>41,'Intercalation'=>0,'BaseWeekday'=>2,'BaseKanChih'=>42,'MonthDays'=>[1,1,0,1,0,0,1,0,0,1,0,1,1]),
		array('BaseDays'=>30,'Intercalation'=>8,'BaseWeekday'=>3,'BaseKanChih'=>47,'MonthDays'=>[1,1,0,1,0,1,0,1,0,0,1,0,1]),/*1976*/
		array('BaseDays'=>48,'Intercalation'=>0,'BaseWeekday'=>5,'BaseKanChih'=>53,'MonthDays'=>[1,0,1,1,0,1,0,1,0,1,0,0,1]),
		array('BaseDays'=>37,'Intercalation'=>0,'BaseWeekday'=>6,'BaseKanChih'=>58,'MonthDays'=>[1,0,1,1,0,1,1,0,1,0,1,0,1]),
		array('BaseDays'=>27,'Intercalation'=>6,'BaseWeekday'=>0,'BaseKanChih'=>3,'MonthDays'=>[1,0,0,1,0,1,1,0,1,1,0,1,0]),
		array('BaseDays'=>46,'Intercalation'=>0,'BaseWeekday'=>1,'BaseKanChih'=>8,'MonthDays'=>[1,0,0,1,0,1,0,1,1,0,1,1,0]),/*1980*/
		array('BaseDays'=>35,'Intercalation'=>0,'BaseWeekday'=>3,'BaseKanChih'=>14,'MonthDays'=>[0,1,0,0,1,0,0,1,1,0,1,1,1]),
		array('BaseDays'=>24,'Intercalation'=>4,'BaseWeekday'=>4,'BaseKanChih'=>19,'MonthDays'=>[1,0,1,0,0,1,0,0,1,0,1,1,1]),
		array('BaseDays'=>43,'Intercalation'=>0,'BaseWeekday'=>5,'BaseKanChih'=>24,'MonthDays'=>[1,0,1,0,0,1,0,0,1,0,1,1,1]),
		array('BaseDays'=>32,'Intercalation'=>10,'BaseWeekday'=>6,'BaseKanChih'=>29,'MonthDays'=>[1,0,1,1,0,0,1,0,0,1,0,1,1]),/*1984*/
		array('BaseDays'=>50,'Intercalation'=>0,'BaseWeekday'=>1,'BaseKanChih'=>35,'MonthDays'=>[0,1,1,0,1,0,1,0,0,1,0,1,0]),
		array('BaseDays'=>39,'Intercalation'=>0,'BaseWeekday'=>2,'BaseKanChih'=>40,'MonthDays'=>[0,1,1,0,1,1,0,1,0,1,0,0,1]),
		array('BaseDays'=>28,'Intercalation'=>6,'BaseWeekday'=>3,'BaseKanChih'=>45,'MonthDays'=>[1,0,1,0,1,1,0,1,1,0,1,0,0]),
		array('BaseDays'=>47,'Intercalation'=>0,'BaseWeekday'=>4,'BaseKanChih'=>50,'MonthDays'=>[1,0,1,0,1,0,1,1,0,1,1,0,1]),/*1988*/
		array('BaseDays'=>36,'Intercalation'=>0,'BaseWeekday'=>6,'BaseKanChih'=>56,'MonthDays'=>[1,0,0,1,0,0,1,1,0,1,1,1,0]),
		array('BaseDays'=>26,'Intercalation'=>5,'BaseWeekday'=>0,'BaseKanChih'=>1,'MonthDays'=>[0,1,0,0,1,0,0,1,0,1,1,1,1]),
		array('BaseDays'=>45,'Intercalation'=>0,'BaseWeekday'=>1,'BaseKanChih'=>6,'MonthDays'=>[0,1,0,0,1,0,0,1,0,1,1,1,0]),
		array('BaseDays'=>34,'Intercalation'=>0,'BaseWeekday'=>2,'BaseKanChih'=>11,'MonthDays'=>[0,1,1,0,0,1,0,0,1,0,1,1,0]),/*1992*/
		array('BaseDays'=>22,'Intercalation'=>3,'BaseWeekday'=>4,'BaseKanChih'=>17,'MonthDays'=>[0,1,1,0,1,0,1,0,0,1,0,1,0]),
		array('BaseDays'=>40,'Intercalation'=>0,'BaseWeekday'=>5,'BaseKanChih'=>22,'MonthDays'=>[1,1,1,0,1,0,1,0,0,1,0,1,0]),
		array('BaseDays'=>30,'Intercalation'=>8,'BaseWeekday'=>6,'BaseKanChih'=>27,'MonthDays'=>[0,1,1,0,1,0,1,1,0,0,1,0,1]),
		array('BaseDays'=>49,'Intercalation'=>0,'BaseWeekday'=>0,'BaseKanChih'=>32,'MonthDays'=>[0,1,0,1,1,0,1,0,1,1,0,0,1]),/*1996*/
		array('BaseDays'=>37,'Intercalation'=>0,'BaseWeekday'=>2,'BaseKanChih'=>38,'MonthDays'=>[1,0,1,0,1,0,1,1,0,1,1,0,1]),
		array('BaseDays'=>27,'Intercalation'=>5,'BaseWeekday'=>3,'BaseKanChih'=>43,'MonthDays'=>[1,0,0,1,0,0,1,1,0,1,1,0,1]),
		array('BaseDays'=>46,'Intercalation'=>0,'BaseWeekday'=>4,'BaseKanChih'=>48,'MonthDays'=>[1,0,0,1,0,0,1,0,1,1,1,0,1]),/*1999*/
		array('BaseDays'=>35,'Intercalation'=>0,'BaseWeekday'=>5,'BaseKanChih'=>53,'MonthDays'=>[1,1,0,0,1,0,0,1,0,1,1,0,1]),/*2000*/
		array('BaseDays'=>23,'Intercalation'=>4,'BaseWeekday'=>0,'BaseKanChih'=>59,'MonthDays'=>[1,1,0,1,0,1,0,0,1,0,1,0,1]),
		array('BaseDays'=>42,'Intercalation'=>0,'BaseWeekday'=>1,'BaseKanChih'=>4,'MonthDays'=>[1,1,0,1,0,1,0,0,1,0,1,0,1]),
		array('BaseDays'=>31,'Intercalation'=>0,'BaseWeekday'=>2,'BaseKanChih'=>9,'MonthDays'=>[1,1,0,1,1,0,1,0,0,1,0,1,0]),
		array('BaseDays'=>21,'Intercalation'=>2,'BaseWeekday'=>3,'BaseKanChih'=>14,'MonthDays'=>[0,1,0,1,1,0,1,0,1,0,1,0,1]),/*2004*/
		array('BaseDays'=>39,'Intercalation'=>0,'BaseWeekday'=>5,'BaseKanChih'=>20,'MonthDays'=>[0,1,0,1,0,1,1,0,1,0,1,0,1]),
		array('BaseDays'=>28,'Intercalation'=>7,'BaseWeekday'=>6,'BaseKanChih'=>25,'MonthDays'=>[1,0,1,0,1,0,1,0,1,1,0,1,1]),
		array('BaseDays'=>48,'Intercalation'=>0,'BaseWeekday'=>0,'BaseKanChih'=>30,'MonthDays'=>[0,0,1,0,0,1,0,1,1,1,0,1,1]),
		array('BaseDays'=>37,'Intercalation'=>0,'BaseWeekday'=>1,'BaseKanChih'=>35,'MonthDays'=>[1,0,0,1,0,0,1,0,1,1,0,1,1]),/*2008*/
		array('BaseDays'=>25,'Intercalation'=>5,'BaseWeekday'=>3,'BaseKanChih'=>41,'MonthDays'=>[1,1,0,0,1,0,0,1,0,1,0,1,1]),
		array('BaseDays'=>44,'Intercalation'=>0,'BaseWeekday'=>4,'BaseKanChih'=>46,'MonthDays'=>[1,0,1,0,1,0,0,1,0,1,0,1,1]),
		array('BaseDays'=>33,'Intercalation'=>0,'BaseWeekday'=>5,'BaseKanChih'=>51,'MonthDays'=>[1,0,1,1,0,1,0,0,1,0,1,0,1]),
		array('BaseDays'=>22,'Intercalation'=>4,'BaseWeekday'=>6,'BaseKanChih'=>56,'MonthDays'=>[1,0,1,1,0,1,0,1,0,1,0,1,0]),/*2012*/
		array('BaseDays'=>40,'Intercalation'=>0,'BaseWeekday'=>1,'BaseKanChih'=>2,'MonthDays'=>[1,0,1,1,0,1,0,1,0,1,0,1,0]),
		array('BaseDays'=>30,'Intercalation'=>9,'BaseWeekday'=>2,'BaseKanChih'=>7,'MonthDays'=>[0,1,0,1,0,1,0,1,1,0,1,0,1]),
		array('BaseDays'=>49,'Intercalation'=>0,'BaseWeekday'=>3,'BaseKanChih'=>12,'MonthDays'=>[0,1,0,0,1,0,1,1,1,0,1,0,1]),
		array('BaseDays'=>38,'Intercalation'=>0,'BaseWeekday'=>4,'BaseKanChih'=>17,'MonthDays'=>[1,0,1,0,0,1,0,1,1,0,1,1,0]),/*2016*/
		array('BaseDays'=>27,'Intercalation'=>6,'BaseWeekday'=>6,'BaseKanChih'=>23,'MonthDays'=>[0,1,0,1,0,0,1,0,1,0,1,1,1]),
		array('BaseDays'=>46,'Intercalation'=>0,'BaseWeekday'=>0,'BaseKanChih'=>28,'MonthDays'=>[0,1,0,1,0,0,1,0,1,0,1,1,0]),
		array('BaseDays'=>35,'Intercalation'=>0,'BaseWeekday'=>1,'BaseKanChih'=>33,'MonthDays'=>[0,1,1,0,1,0,0,1,0,0,1,1,0]),
		array('BaseDays'=>24,'Intercalation'=>4,'BaseWeekday'=>2,'BaseKanChih'=>38,'MonthDays'=>[0,1,1,1,0,1,0,0,1,0,1,0,1]),/*2020*/
		array('BaseDays'=>42,'Intercalation'=>0,'BaseWeekday'=>4,'BaseKanChih'=>44,'MonthDays'=>[0,1,1,0,1,0,1,0,1,0,1,0,1]),
		array('BaseDays'=>31,'Intercalation'=>0,'BaseWeekday'=>5,'BaseKanChih'=>49,'MonthDays'=>[1,0,1,0,1,1,0,1,0,1,0,1,0]),
		array('BaseDays'=>21,'Intercalation'=>2,'BaseWeekday'=>6,'BaseKanChih'=>54,'MonthDays'=>[0,1,0,1,0,1,0,1,1,0,1,0,1]),
		array('BaseDays'=>40,'Intercalation'=>0,'BaseWeekday'=>0,'BaseKanChih'=>59,'MonthDays'=>[0,1,0,0,1,0,1,1,0,1,1,0,1]),/*2024*/
		array('BaseDays'=>28,'Intercalation'=>6,'BaseWeekday'=>2,'BaseKanChih'=>5,'MonthDays'=>[1,0,1,0,0,1,0,1,0,1,1,1,0]),
		array('BaseDays'=>47,'Intercalation'=>0,'BaseWeekday'=>3,'BaseKanChih'=>10,'MonthDays'=>[1,0,1,0,0,1,0,0,1,1,1,0,1]),
		array('BaseDays'=>36,'Intercalation'=>0,'BaseWeekday'=>4,'BaseKanChih'=>15,'MonthDays'=>[1,1,0,1,0,0,1,0,0,1,1,0,1]),
		array('BaseDays'=>25,'Intercalation'=>5,'BaseWeekday'=>5,'BaseKanChih'=>20,'MonthDays'=>[1,1,1,0,1,0,0,1,0,0,1,1,0]),/*2028*/
		array('BaseDays'=>43,'Intercalation'=>0,'BaseWeekday'=>0,'BaseKanChih'=>26,'MonthDays'=>[1,1,0,1,0,1,0,1,0,0,1,0,1]),
		array('BaseDays'=>32,'Intercalation'=>0,'BaseWeekday'=>1,'BaseKanChih'=>31,'MonthDays'=>[1,1,0,1,1,0,1,0,1,0,1,0,0]),
		array('BaseDays'=>22,'Intercalation'=>3,'BaseWeekday'=>2,'BaseKanChih'=>36,'MonthDays'=>[0,1,1,0,1,0,1,1,0,1,0,1,0])

     );

 	/* 西曆年每月之日數 */
	public $SolarCal = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];	

	 /* 西曆年每月之累積日數, 平年與閏年 */
	public$SolarDays = [
	  0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334, 365, 396,
	  0, 31, 60, 91, 121, 152, 182, 213, 244, 274, 305, 335, 366, 397 ];	


   /**
     * 将阳历转换为阴历
     * @param year 公历-年
     * @param month 公历-月
     * @param date 公历-日
     */
    public function convertSolarToLunar($year, $month, $date)
    {
		$sm = $month - 1;
		$leap = $this->GetLeap( $year );

 		if ( $sm == 1 ){
 	 		$d = $leap + 28;
 		}else{
 	 		$d = $this->SolarCal[$sm];
 		}
 
		if ( $date < 1 || $date > $d ){
 			return 3;
 		}

		$y = $year - 1936;
		$acc = $this->SolarDays[ $leap*14 +$sm ] + $date;
		$kc = $acc + $this->lunarInfo[$y]['BaseKanChih'];
		$Kan = $kc % 10;
		$Chih =$kc % 12;
		$Age = $kc % 60;

		if ($Age < 22){
 			$Age = 22 - $Age;
		}else{
			$Age = 82 - $Age;
		}
		$Age =$Age + 3;
		if ($Age < 10){
			$Age=$Age+60;
		}

		if($acc <= $this->lunarInfo[$y]['BaseDays'] ){
  			$y--;
  			$LunarYear = $year - 1;
  			$leap = $this->GetLeap($LunarYear );
  			$sm += 12;
  			$acc = $this->SolarDays[$leap*14 + $sm] + $date;
  		}else{
  			$LunarYear = $year;
  		}
  
  		$l1 =$this->lunarInfo[$y]['BaseDays'];
 		for ( $i=0; $i<13; $i++ ) {
  			$l2 = $l1 +$this->lunarInfo[$y]['MonthDays'][$i] + 29;
  			if ( $acc <= $l2 ) {
  				break;
  			}
  			$l1 = $l2;
  		}

		$LunarMonth =$i + 1;
		$LunarDate = $acc - $l1;
		$im = $this->lunarInfo[$y]['Intercalation'];

 		if ( $im != 0 && $LunarMonth > $im ) {
  			$LunarMonth--;
  			if ($LunarMonth == $im ) {
  				$LunarMonth = '闰'.$im;
  			}
  		}

		if($LunarMonth > 12 ) {
			$LunarMonth -= 12;
		}

		$data = array();
		$data['year']=$LunarYear;
		$data['Month']=$LunarMonth;
		if($LunarDate<10){
			$data['day']='0'.$LunarDate;
		}else{
			$data['day']=$LunarDate;
		}
		
 	return $data;

	}


    /**
     * 将阴历转换为阳历
     * @param year 阴历-年
     * @param month 阴历-月
     * @param date 阴历-日
     */
    public function convertLunarToSolar($year, $month, $date)
    {

		$y = $year - 1936; 
        $im = $this->lunarInfo[$y]['Intercalation']; 
        $lm = $month; 
          
        if($im != 0 ){ 
            if($lm > $im){
            	$lm++; 
            }else if($lm == -$im){
            	$lm = $im + 1; 
            }        
        } 
        $lm--; 

        if($date >$this->lunarInfo[$y]['MonthDays'][$lm] + 29 ){
            echo ("农历日期不正确");       	
        }           
      
        $acc = 0;
        for($i=0;$i<$lm;$i++) {
        	$acc+=$this->lunarInfo[$y]['MonthDays'][$i] + 29;
        }
       
        $acc +=$this->lunarInfo[$y]['BaseDays'] + $date;
        $leap =$this->GetLeap($year ); 
             
        for($i=13;$i>=0;$i--) {
            if($acc >$this->SolarDays[$leap*14+$i]){
            	break; 
            } 
                
        }       
        $SolarDate = $acc - $this->SolarDays[$leap*14 + $i]  ;
        
        if($i <= 11){ 
            $SolarYear = $year; 
            $SolarMonth = $i + 1; 
        }else{ 
            $SolarYear = $year + 1; 
            $SolarMonth = $i - 11; 
        } 
        $leap =$this->GetLeap($SolarYear); 
        $y =$SolarYear - 1936;  
        $acc = $this->SolarDays[$leap*14 + $SolarMonth-1] + $SolarDate;
        $weekday = ( $acc +$this->lunarInfo[$y]['BaseWeekday'] ) % 7; 
        $kc = $acc + $this->lunarInfo[$y]['BaseKanChih']; 
        $kan = $kc % 10; 
        $chih = $kc % 12; 

        		$data = array();
		$data['year']=$SolarYear;
		$data['Month']=$SolarMonth;
		if($SolarDate<10){
			$data['day']='0'.$SolarDate;
		}else{
			$data['day']=$SolarDate;
		}
		
 	return $data;
              
	}


 	/* 闰年, 返回 0 平年, 1 闰年 */
	public function GetLeap($year )
 	{
   		if ($year % 400 == 0){
     		return 1;
   		}else if($year % 100 == 0){
     		return 0;
   		}else if($year % 4 == 0){
     		return 1;
   		}else{
     		return 0;
   		}
 	}

}
