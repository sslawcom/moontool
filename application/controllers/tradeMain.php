<?php if(! defined('BASEPATH')) exit('No direct script access allowed');



// github test //
// 현재 v1.0.2 버전의 내용 상태
// v1.0.3 버전에서 작업중. ----------------------
// a내용 추가
// b 내용 추가
// c 내용 추가
// d 내용 추가
// 이상 v1.0.3 내용 변경이었습니다. -----------

// v1.0.3 버전 추가 사항입니다.
// abcdefg





class TradeMain extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        
        $this -> load -> database();
        $this -> load -> model('trademain_m');
        $this -> load -> helper( array('url', 'date'));
       // $this -> load -> helper('form');
    }
    
    public function index() {
        $this -> main();
    }
    
    
    
    
    /**
     * 
     *   메인 페이지 불러오기
     * 
     */
    public function main()
    {
        $data['all_analyzedData'] = $this->trademain_m->get_analyzedData();
        $this -> load -> view('trade/tradeMain_v', $data);
    }    
    








    /**
     * 
     *   시세 값을 네이버에서 가져온다.
     * 
     */
    public function getSise()
    {
/*        
        $code = '028050';
        $resultData = $this -> getNvData($code);
        
        $json = json_decode($resultData, true);
        
        
        echo $json['now'];    // 현재가
*/        
   
        // 우선 현재 보여지는 종목들 중에서 각각의 주식코드번호를 가져온다.
        $jongmokCodeList = $this->trademain_m->get_JongmokCodeList();

    
        // 코드번호 리스트를 기반으로 루프를 돌며, 시세값을 기입한다.
        foreach( $jongmokCodeList as $lt )
        {
            $code = $lt -> code;     // 주식코드번호
            
            $receiveData = $this -> getNvData($code);
            $siseData_json = json_decode($receiveData, true);
            
            $firstPrice  = $lt->price;    // 최초 확인한 가격
            $nowPrice = $siseData_json['now'];     // 네이버에서 받은 현재가
            
            // 가격을 비교해서 up, down, 보합을 결정한다.
            if( $firstPrice > $nowPrice)      // 값이 떨어졌으면,
                $result = 'd';
            else if( $firstPrice < $nowPrice)      // 값이 올랐으면,
                $result = 'u';
            else if( $firstPrice == $nowPrice)      // 값이 같으면,
                $result = 'b';
                        
            $siseData_arr = array (                
                'tb_jongmok_num'   =>   $lt->num,
                'price'   =>   $siseData_json['now'],
                'result'  =>   $result
            );


            
            // 시세값 db 에 삽입
            $result = $this -> trademain_m -> insert_siseData($siseData_arr);
            
            if($result != 1 )
            {
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';                 
                alert('DB 에러발생',  '/'.ROOT_SEGMENT.'/'.$this -> uri -> segment(1));   
            }
        }

       redirect('/'.$this -> uri -> segment(1).'/main');
    }    














    /**
     * 
     *   종목을 새로 추가 한다.
     * 
     */
    public function addJongmok()
    {       
       if( $_POST)
       {
           echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';   
           $this -> load -> helper('alert');
                        
            $add_data_arr = array(
                'jonmok'      =>      $this -> input -> post('iput_jongmok', TRUE),
                'code'          =>      $this -> input -> post('iput_code', TRUE),
                'price'          =>      $this -> input -> post('iput_price', TRUE)
            );
            
            $result = $this -> trademain_m -> insert_jongmokData($add_data_arr);
            
            if($result == 1 )
            {
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';                    
                alert( '추가되었습니다.', '/'.ROOT_SEGMENT.'/'.$this -> uri -> segment(1));    
            }
            else
            { 
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';                 
                alert('DB 에러발생',  '/'.ROOT_SEGMENT.'/'.$this -> uri -> segment(1));   
            } 
        }
        else 
        {
            $this -> main();
        }     
    }  








    /**
     * 
     *   종목 삭제
     *   실제로는 숨긴다.
     */
    public function hideJongmok()
    {
        $delNum = $this -> uri -> segment(3);
        $result = $this -> trademain_m -> hide_jongmokData($delNum);

       echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';   
       $this -> load -> helper('alert');        
        
        if($result == 1 )
        {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';                    
            alert( '삭제되었습니다.', '/'.ROOT_SEGMENT.'/'.$this -> uri -> segment(1));    
        }
        else
        { 
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';                 
            alert('DB 에러발생',  '/'.ROOT_SEGMENT.'/'.$this -> uri -> segment(1));   
        }         
        
    }  












    /**
     * 
     *   시세 삭제
     *   
     */
    public function delsise()
    {
        //$delNum = $this -> uri -> segment(3);
        
        $delNum = $this -> input -> post('sise_num');        
        
        
        $result = $this -> trademain_m -> delete_siseData($delNum);
        
        if( $result )
            echo $delNum;
        else
            echo "2000"; 
        
        
/*        
       echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';   
       $this -> load -> helper('alert');        
        
        if($result == 1 )
        {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';                    
            alert( '삭제되었습니다.', '/'.ROOT_SEGMENT.'/'.$this -> uri -> segment(1));    
        }
        else
        { 
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';                 
            alert('DB 에러발생',  '/'.ROOT_SEGMENT.'/'.$this -> uri -> segment(1));   
        }         
*/
    }  







/*


    public function addJongmok()
    {       
       if( $_POST)
       {
           echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';   
           $this -> load -> helper('alert');
                        
            $add_data_arr = array(
                'jonmok'      =>      $this -> input -> post('iput_jongmok', TRUE),
                'code'          =>      $this -> input -> post('iput_code', TRUE),
                'price'          =>      $this -> input -> post('iput_price', TRUE)
            );
            
            $result = $this -> trademain_m -> insert_jongmokData($add_data_arr);
            
            if($result == 1 )
            {
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';                    
                alert( '추가되었습니다.', '/'.ROOT_SEGMENT.'/'.$this -> uri -> segment(1));    
            }
            else
            { 
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';                 
                alert('DB 에러발생',  '/'.ROOT_SEGMENT.'/'.$this -> uri -> segment(1));   
            } 
        }
        else 
        {
            $this -> main();
        }     
    }  

*/





    public function getNvData($para_code)
    {
        $code = $para_code;        
        $url = 'http://api.finance.naver.com/service/itemSummary.nhn?itemcode='.$code;        
        
        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL,$url); //접속할 URL 주소 
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 인증서 체크같은데 true 시 안되는 경우가 많다. 
        // default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요) 
        curl_setopt ($ch, CURLOPT_SSLVERSION,3); // SSL 버젼 (https 접속시에 필요) 
        curl_setopt ($ch, CURLOPT_HEADER, 0); // 헤더 출력 여부 
        curl_setopt ($ch, CURLOPT_POST, 1); // Post Get 접속 여부 
        curl_setopt ($ch, CURLOPT_POSTFIELDS, "var1=str1&var2=str2"); // Post 값  Get 방식처럼적는다. 
        curl_setopt ($ch, CURLOPT_TIMEOUT, 30); // TimeOut 값 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); // 결과값을 받을것인지 
        $result = curl_exec ($ch); 
        curl_close ($ch); 

        return $result;
    }
}
    