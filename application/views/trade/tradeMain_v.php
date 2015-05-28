<!DOCTYPE html>
<html>
    <head> 
        <meta charset="utf-8" />                
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="width=device-width, initial-scal=1, user-scalable=no" />
        <title>ironman</title>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" charset="utf-8">                      
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css" charset="utf-8">                
        <link href="/ironman/include/css/jquery.nouislider.css" rel="stylesheet" charset="utf-8">          <!--  모달내의 슬라이더   -->
        <link href="/ironman/include/css/jquery.nouislider.pips.min.css" rel="stylesheet" charset="utf-8">          <!--  없어도 되나?  모달내의 슬라이더   -->      
        
        <script src="http://code.jquery.com/jquery-1.11.0.min.js" charset="utf-8"></script>            
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" charset="utf-8"></script>    <!--  모달  -->   
        <script type="text/javascript" src="/ironman/include/js/jssor.slider.mini.js" charset="utf-8"></script>       <!-- carousel -->
        <script src="/ironman/include/js/jquery.nouislider.all.js" charset="utf-8"></script>     <!--  모달내의 슬라이더   -->
        
        
        
        <script>
        $(document).ready(function(){                 
               
            // 모달의 입력값들을 검증하고 submit   
            $("#btn_save").click(function() { 
                
                if($('#iput_jongmok').val() == '')
                {
                    alert("종목명 입력해야지 성진아~");
                    $('#iput_jongmok').focus();
                    return false;
                }
                else if($('#iput_code').val() == '')
                {
                    alert("코드번호는 왜 빼먹니, 언릉 입력해 어여");
                    $('#iput_code').focus();
                    return false;
                }
                else if($('#iput_price').val() == '')
                {
                    alert("현재 확인된 가격을 넣어, 정확하게 알지?");
                    $('#iput_price').focus();
                    return false;
                }
                else
                {
                    $('#write_action').submit();
                }                
            });
            
            
            
            
            $('button').click(function() { 
       
                 // 각종목에서 del 버튼을 누르면  
                if( $(this).attr('id') == 'btn_del' )
                {                   
                    var result_d = confirm('제거하시겠습니까?');
            
                    if(result_d) {
                       //yes
                        var delNum = $(this).attr("value");    // 삭제할 종목의 db 테이블의 num
                        
                        url = "/<?php echo ROOT_SEGMENT;?>/tradeMain/hideJongmok/" + delNum;
                        
                        $(location).attr('href', url);
                    } else {
                        //no
                        return 0;
                    }
                }  // del 버튼 end
                
                // 프린트 찍기 버튼을 누르면
                if( $(this).attr('id') == 'btn_print' )
                {
                    var result_p = confirm('시세정보 받아오시겠습니까?');
                    
                    if(result_p) {
                       //yes                    
                       url = "/<?php echo ROOT_SEGMENT;?>/tradeMain/getSise/";
                       $(location).attr('href', url);
                    } else {
                        //no
                        return 0;
                    }
                    
                }
            });
            
            
            
            
            // 각 시세정보의 x 를 클릭하여 삭제 하려고 할 때, 
            $('span').click(function() { 
                
                if( $(this).attr('id') == 'exButton_delsise' )
                {
//                    var result_p = confirm('선택하신 시세정보를 삭제하겠습니까?');
                    
//                    if(result_p) {
                       //yes                    

                      // ajax start -----------------------------------------------------------
                       $.ajax({
                           url : "/<?php echo ROOT_SEGMENT;?>/tradeMain/delsise/",
                           type : "POST",
                           data:{
                               "sise_num"       : $(this).attr("value")
                           },
                           dataType : "text",
                           complete : function(xhr, textStatus){
                               if( textStatus == 'success')
                               {
                                   if( xhr.responseText == 2000 )
                                        alert('다시 삭제하세요');
                                   else   // 성공
                                   {
                                       $('#row_num_' + xhr.responseText).remove();
                                       //alert('삭제되었습니다');
                                   }
                               }
                           }
                       });
                       // ajax end  ----------------------------------------------------------
//                    } else {
                        //no
//                        return 0;
//                    }
                }   
            });            
            
            
                
        });
        </script>        
        
        
        
        
        
        
    </head>
    <body>
        <div id="main">
        
          <article id="board_area">
                <header>

                    <div style="width: 10%; height: 60px; float:left;" >
                    </div>                                        
                    <div style="width: 69%; height: 60px; float:left;" >
                        <center><h2>문ssi 분석 툴</h2> <h5>v1.02.00</h5></center>
                    </div>
                    <div style="width: 21%; height: 60px; margin-top: 50px; float:left;" >
                         <button type="button" class="btn btn-default btn-sm" id="btn_add" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                         <button type="button" class="btn btn-default btn-sm" id="btn_print"  data-target=""><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                    </div>

                    <div style="clear:both;"></div>                                            
                </header>
                
                
            
              
              
              
              
<?php              
       foreach($all_analyzedData as $allDatalt )
       {
?>
                <div class="panel panel-default">
                  <!-- Default panel contents -->
                  <div class="panel-heading">
                      <strong><?php echo $allDatalt['jongmokData']->name;?></strong> &nbsp;&nbsp;[<?php echo $allDatalt['jongmokData']->code;?>]
                  </div>

                  
                  <div class="panel-body"> 
                      <div style="width: 87%; float:left;">
                          <p>확인가 : <?php echo number_format($allDatalt['jongmokData']->price);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>(<?php echo date("Y-m-d H:i", strtotime($allDatalt['jongmokData']->date));?>)</em></p>
                      </div>
                      <div style="width: 13%; float:left; " >
                          <button type="button" class="btn btn-default btn-sm" id="btn_del" value="<?php echo $allDatalt['jongmokData']->num;?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Del</button>
                      </div>                      
                  </div>
              
                
                  <!-- Table -->
                  <!-- <table class="table table-striped">  -->
                  <table class="table">    

                    <thead>
                        <tr>
                            <th scope="col" style="text-align: center;">Time</th>
                            <th scope="col" style="text-align: center;">Result</th>                            
                            <th scope="col" style="text-align: center;">Price</th>
                            <th scope="col" style="text-align: center;"></th>                            
                        </tr>
                    </thead>
                    
                    <tbody>
<?php              
               foreach($allDatalt['siseData'] as $siseDatalt )
               {
?>               
                        <tr id="row_num_<?php echo $siseDatalt->num;?>">
                            <td class="col-sm-4" style="text-align: center;"><?php echo $siseDatalt->date;?></td>
<?php
                        $firstPrice = $allDatalt['jongmokData']->price;
                        $nowPrice = $siseDatalt->price; 
                        
                        //$profit =  $nowPrice - $firstPrice;
                        $profit = round(  ( ($nowPrice - $firstPrice) / $firstPrice ) * 100  , 1);
                        
                        $profit_display = '';
                        
                        if( $profit > 0)          // 수익이면,
                            $profit_display = "<span class='label label-danger'>".$profit." %</span>";
                        else if( $profit < 0)   // 손실이면,
                            $profit_display = "<span class='label label-info'>".$profit." %</span>";                        
                        else if( $profit == 0) // 보합이면,
                            $profit_display = "<span class='label label-default'>".$profit." %</span>";                                     
?>                        
                            <td class="col-sm-4" style="text-align: center;"><?php echo $profit_display;?></td>                            
                            <td class="col-sm-4" style="text-align: center;"><?php echo number_format($siseDatalt->price);?></td>  
                            <!-- <td class="col-sm-4" style="text-align: center;"><button type="button" class="btn btn-default btn-xs" id="btn_sise_del" value="<?php echo $siseDatalt->num;?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>   -->                                                             
                            <!-- <td class="col-sm-4" style="text-align: center;"><a href="#"><span class="badge">x</span></a></td>  -->
                            <!-- <td class="col-sm-4" style="text-align: center;"><a href="google.co.kr"><span class="glyphicon glyphicon-remove" id="exButton_delsise"  style="color: DarkGray;"  value='1' aria-hidden="true"></span></a></td>  -->                            
                            <td class="col-sm-4" style="text-align: center;"><span class="glyphicon glyphicon-remove" id="exButton_delsise"  style="color: DarkGray;"  value="<?php echo $siseDatalt->num;?>" aria-hidden="true"></span></td>
                        </tr>
<?php  
               }
?>                 
                    </tbody>
                  </table>
                </div>
                
                <div style="width: 100%; height: 30px; "></div>
<?php 
           }               
?>               
            </article>
                        
        </div>
        
        
        <!-- Modal start-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="myModalLabel" >종목추가</h3>
              </div>
              <div class="modal-body">
                
                    <p>
                        

                    </p>
                                            
                    <form class="form-horizontal" method="post" action="/<?php echo ROOT_SEGMENT;?>/tradeMain/addJongmok" id="write_action">
                          <div class="form-group">
                              <label for="inputEmail3" class="col-sm-2 control-label">종목명</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control" id="iput_jongmok" name="iput_jongmok" placeholder="예: 삼성엔지니어링">   
                            </div>
                          </div>

                          <div class="form-group">
                              <label for="inputEmail3" class="col-sm-2 control-label">코드번호</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control" id="iput_code"   name="iput_code"   placeholder="예: 028050">
                            </div>
                          </div>
                          
                          <div class="form-group">
                              <label for="inputEmail3" class="col-sm-2 control-label">확인가</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control" id="iput_price"   name="iput_price"   placeholder="예: 39500">   
                            </div>
                          </div>
                    </form> 
                                            
                      
                       
                                                                  
                    
                                        
              </div>
              <div class="modal-footer">
                
                <!-- 자동으로 모달 닫기 위해서   data-dismiss="modal"  요 옵션이 필요할지도....   -->
                <!-- <button type="button" class="btn btn-default" id="btn_normal_point" data-dismiss="modal">보통</button>    -->                      
                                      
                <button type="button" class="btn btn-primary" id="btn_save" data-dismiss="modal" >등록</button>
              </div>
            </div>
          </div>
        </div>     
        <!-- Modal end -->            
        
        
        
        
    </body>
</html>        