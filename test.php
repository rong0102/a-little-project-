<?php
header("Access-Control-Allow-Origin: *");
$dist = $_POST["dist"];
$date = $_POST["date"];
$context  = stream_context_create(array('http' => array('header' => 'Accept: application/json')));
$url = "https://covid-19.nchc.org.tw/api/covid19?CK=covid-19@nchc.org.tw&querydata=5001&limited=全部縣市" ;
$xml = file_get_contents($url, false, $context);
$xml = json_decode($xml);
#print_r($xml);
print ("<p class='distshow'>縣市:$dist</p>");
print ("<table class='tableshow table table-hover'><tr><td>公布時間</td><td>行政區</td><td>性別</td><td>年齡</td></tr>");
$number=0;
//$a=array(0,0,0,0,0,0,0,0,0,0,0,0,0);
foreach ($xml as $key)
{

     $d = $key->{"a03"};
     $time=$key->{"a02"};
    date_default_timezone_set("Asia/Taipei");
    if((strcmp($time,$date))==0 and (strcmp($d,$dist))==0){ 
        $dists=$key->{"a04"};
        $sex = $key->{"a05"};
        $age = $key->{"a07"};  
        print ("<tr><td>$time</td><td>$dists</td><td>$sex</td><td>$age</td></tr>");
        $number+=1;
    }
    
}
if($age==""){
    print("<script>alert('病例+0喔')</script>");  
}
print ("</table>");


$row = 1;
if (($handle = fopen("./points.csv", "r")) !== FALSE) {
    print ("<br><p class='distshow'>$dist 快篩站</p>");
    echo '<center><table border="1" class="size table table-hover">';

    
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        
        $num = count($data);    
        if ($row == 1) {
            echo '<thead><tr>';
        }else{
            echo '<tr>';
        }
        
        
        for ($c=0; $c < $num; $c++) {
            //echo $data[$c] . "<br />\n";
            if(empty($data[$c])) {
               $value = "&nbsp;";
            }else{
               $value = $data[$c];
            }
            if ($row == 1) {
                echo '<th class="thshow">'.$value.'</th>';
            }else{
                if((strcmp($value,$dist))==0){
                    echo '<td class="tdshow">'.$data[0].'</td>'.'<td class="tdshow">'.$data[1].'</td>'.'<td class="tdshow">'.$data[2].'</td>';
                }
                
            }
        }
       
        if ($row == 1) {
            echo '</tr></thead><tbody>';
        }else{
            echo '</tr>';
        }
        $row++;
    }
   
    echo '</tbody></table></center>';
    fclose($handle);
}
?>

    
