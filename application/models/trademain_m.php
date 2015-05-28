<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

class TradeMain_m extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    
    
    /**
     *  
     *  저장된 분석정보들을 가져온다.
     */    
    function get_analyzedData( )
    {
        $table_jongmok = 'tb_jongmok';
        $table_sise = 'tb_sise';
        
        
        
        
        
        //---- 전체 목표에서 정보를 가져온다.------------------------------------------v
        
        $analyzedDataArr = array( );        
        
        $sqlString = "SELECT num, code, name, price, date  FROM ".$table_jongmok." WHERE isview = 1";        
        $query = $this -> db -> query($sqlString);
        $result_jongmok = $query -> result();
        
         
         foreach($result_jongmok as $jm)
         {
           $searchNum = $jm->num;
           
            $sqlString = "SELECT num, price, date, result FROM ".$table_sise." WHERE  tb_jongmok_num = '".$searchNum."' ORDER BY date ASC"; 
            $query = $this -> db -> query($sqlString);
            $result_sise = $query -> result();
            
            $jongmokGroupArr = array (
                'jongmokData'      => $jm,
                'siseData'             => $result_sise
            );
             
            $analyzedDataArr[$jm->num] = $jongmokGroupArr;
         }

       // echo $analyzedDataArr[1]['jongmokData'] ->code;
        
        //var_dump($analyzedDataArr);

        
        return $analyzedDataArr;
    }
    
    
    
    
 
    /**
     *  
     *  사용자가 추가하여 입력한 종목정보를 저장한다.
     */    
    function insert_jongmokData( $para_add_data_arr )
    {
        $table_jongmok = 'tb_jongmok';
        
        $jonmok  = $para_add_data_arr['jonmok'];
        $code      = $para_add_data_arr['code'];        
        $price      = $para_add_data_arr['price'];      
        
        //----- 점수 입력하기  -----------------------------------------------------------------------------v
        $sqlString = "INSERT INTO ".$table_jongmok." (code, name, price, date, isview) VALUES( '".$code."', '".$jonmok."', '".$price."', DATE_FORMAT(CURRENT_TIMESTAMP(), '%Y-%m-%d %H:%i:%s'), '1');";        
        $queryResult = $this -> db -> query($sqlString);        
        
        return $queryResult;    // 1 이면 정상
    }
     
 
 
 
 
   
    /**
     *  
     *  각 종목의 코드 번호를 가져온다.
     */    
    function get_JongmokCodeList( )
    {
        $table_jongmok = 'tb_jongmok';
        
        
        //---- 전체 목표에서 정보를 가져온다.------------------------------------------v
       
        $sqlString = "SELECT num, code, name, price, date  FROM ".$table_jongmok." WHERE isview = 1";        
        $query = $this -> db -> query($sqlString);
        $result_jongmok = $query -> result();

        
        return $result_jongmok;
    }
    
    
    

 
 


 
    /**
     *  
     *  시세정보를 저장한다.
     */    
    function insert_siseData( $para_siseData_arr )
    {
        $table_sise = 'tb_sise';
        
        $tb_jongmok_num  = $para_siseData_arr['tb_jongmok_num'];
        $price                   = $para_siseData_arr['price'];        
        $result                  = $para_siseData_arr['result'];      
        
        //----- 점수 입력하기  -----------------------------------------------------------------------------v
        $sqlString = "INSERT INTO ".$table_sise." (tb_jongmok_num, price, date, result) VALUES( ".$tb_jongmok_num.", ".$price.", DATE_FORMAT(CURRENT_TIMESTAMP(), '%Y-%m-%d %H:%i:%s'), '".$result."' );";        
        $queryResult = $this -> db -> query($sqlString);        
        
        return $queryResult;    // 1 이면 정상
    }
      
 
 
 
 
 
 
 
 
 
 


 
    /**
     *  
     *  종목정보를 숨긴다.
     */    
    function hide_jongmokData( $para_delNum )
    {
        $table_jongmok = 'tb_jongmok';
        
        $delNum  = $para_delNum;    // 삭제할 번호
        
        //----- isview 값을 0으로 변경하여 앞으로 안보이게 하기  -----------------------------------------------------------------------------v
        $sqlString = "UPDATE ".$table_jongmok." SET isview= '0' WHERE num= ".$delNum;
        $queryResult = $this -> db -> query($sqlString);        
        
        return $queryResult;    // 1 이면 정상
    }
    
    
    
    
    
    
    
    
 
 
    /**
     *  
     *  시세정보를 삭제한다.
     */    
    function delete_siseData( $para_delNum )
    {
        $table_sise = 'tb_sise';
        
        $delNum  = $para_delNum;    // 삭제할 번호
        
        //----- isview 값을 0으로 변경하여 앞으로 안보이게 하기  -----------------------------------------------------------------------------v
        //$sqlString = "UPDATE ".$table_sise." SET isview= '0' WHERE num= ".$delNum;
        $sqlString = "DELETE FROM ".$table_sise." WHERE num= ".$delNum;
        $queryResult = $this -> db -> query($sqlString);        
        
        return $queryResult;    // 1 이면 정상
    }
       
    
    
    
    
    
    
    
    
    
    
    
}
    
    
    
    