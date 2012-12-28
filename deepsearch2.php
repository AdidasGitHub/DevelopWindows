<?php

$city = $_POST['where'];  
$passengers = isset($_POST['who']) ? $_POST['who'] : "0";  
$pass2 = isset($_POST['who2']) ? $_POST['who2'] : "400";
$when = isset($_POST['when']) ? $_POST['when'] : "";    
$when2 = isset($_POST['when2']) ? $_POST['when2'] : "";   
$button = $_POST['button'];      
$price = isset($_POST['price']) ? $_POST['price'] : "9999999"; 
$mprice = isset($_POST['mprice']) ? $_POST['mprice'] : "0";
//$pricing = $_POST['pricing'];
$code = $_POST['code'];
$motor = $_POST['motor'];
$sail = $_POST['sail'];
$sports = $_POST['sports'];
$cat = $_POST['cat'];
$mega = $_POST['mega'];
$fishing = $_POST['fishing'];                                           
$event = $_POST['event'];
$wedding = $_POST['wedding'];
$party = $_POST['party'];

$diff = abs(strtotime($when2) - strtotime($when));
$years = floor($diff / (365*60*60*24));
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
$pricing = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

 /* 
echo $city = $_POST['where'];   echo "<br>";
echo  $passengers = $_POST['who'];    echo "<br>";
echo   $pass2 = $_POST['who2'];      echo "<br>";
echo  $when = isset($_REQUEST['when']) ? $_REQUEST['when'] : "";    echo "<br>";
echo  $when2 = isset($_REQUEST['when2']) ? $_REQUEST['when2'] : "";echo "<br>";   
 $button = $_POST['button'];      
 echo $price = $_POST['price']; echo "<br>"; 
  echo $mprice = isset($_POST['mprice']) ? $_POST['mprice'] : "0";   echo "<br>";
echo  $pricing = $_POST['pricing'];      echo "<br>";
echo  $code = $_POST['code'];            echo "<br>";
echo  $motor = $_POST['motor'];            echo "<br>";
echo  $sail = $_POST['sail'];           echo "<br>";
 echo $sports = $_POST['sports'];        echo "<br>";
 echo $cat = $_POST['cat'];           echo "<br>";
 echo $mega = $_POST['mega'];          echo "<br>";
 echo $fishing = $_POST['fishing'];       echo "<br>";                                            
 echo $event = $_POST['event'];          echo "<br>";
 echo $wedding = $_POST['wedding'];         echo "<br>";
  echo $party = $_POST['party'];          echo "<br>";       */

//max displayed per page

$per_page = 4;

//get page

$page = (isset($_POST['page'])) ? (int)$_POST['page'] : 1;
$start = ($page - 1) * $per_page;

$hiprice = 0;
$minprice = 99999999;
$hippl = 0;
$loppl = 99999999;

$joo = $pricing.'day';   

if ($pricing>=7)
{
$joo = 'price';
$pricing = 7;
}
if ($pricing<=0)
{
$joo = '1day';
}

//echo $joo." Prices ".$pricing;

unset($contactwho);
$contactwho = array();

mysql_connect("localhost","s4285_scheduler","myreservations7x");
mysql_select_db("s4285_scheduler") or die (mysql_error());     

if ($motor==3&&$sail==3&&$sports==3&&$cat==3&&$mega==3&&$fishing==3&&$event==3&&$wedding==3&&$party==3)
{
        $q = mysql_query("SELECT * FROM resources WHERE city='$city' ORDER BY $joo DESC") or die (mysql_error());
}
else
{       
        $q = mysql_query("SELECT * FROM resources WHERE city='$city' AND ($motor=motor OR $sail=sail OR $sports=sports OR $cat=cat OR $mega=mega OR $fishing=fishing OR $event=event OR $wedding=wedding OR $party=party) ORDER BY $joo DESC") or die (mysql_error());
}

while ($tpq = mysql_fetch_assoc($q))
        {
                if ($tpq[$joo]>$hiprice)
                {
                $hiprice = $tpq[$joo];
                }
                
                if ($tpq[$joo]<$minprice)
                {
                $minprice = $tpq[$joo];
                }
                
                if ($tpq['max_participants']>$hippl)
                {
                $hippl = $tpq['max_participants'];
                }
                
                if ($tpq['max_participants']<$loppl)
                {
                $loppl = $tpq['max_participants'];
                }
}                
            
if ($motor==3&&$sail==3&&$sports==3&&$cat==3&&$mega==3&&$fishing==3&&$event==3&&$wedding==3&&$party==3)
{
        $t = mysql_query("SELECT * FROM resources WHERE $joo >= $mprice AND $joo <= $price AND city='$city' AND max_participants >= $passengers AND max_participants <= $pass2 ORDER BY $joo DESC") or die (mysql_error());
}
else
{       
        $t = mysql_query("SELECT * FROM resources WHERE city='$city' AND $joo >= $mprice AND $joo <= $price AND max_participants >= $passengers AND max_participants <= $pass2 AND ($motor=motor OR $sail=sail OR $sports=sports OR $cat=cat OR $mega=mega OR $fishing=fishing OR $event=event OR $wedding=wedding OR $party=party) ORDER BY $joo DESC") or die (mysql_error());
}

while ($tpain = mysql_fetch_assoc($t))
        {
                $x++;   
                array_push($contactwho,$tpain['email']);       
        }

if ($motor==3&&$sail==3&&$sports==3&&$cat==3&&$mega==3&&$fishing==3&&$event==3&&$wedding==3&&$party==3){
        $type = mysql_query("SELECT * FROM resources WHERE city='$city' AND  $joo >= $mprice AND $joo <= $price AND max_participants >= $passengers AND max_participants <= $pass2 ORDER BY $joo DESC LIMIT $start, $per_page") or die (mysql_error());
}
else{
        $type = mysql_query("SELECT * FROM resources WHERE city LIKE '$city' AND $joo >= $mprice AND $joo <= $price AND max_participants >= $passengers AND max_participants <= $pass2 AND ($motor=motor OR $sail=sail OR $sports=sports OR $cat=cat OR $mega=mega OR $fishing=fishing OR $event=event OR $wedding=wedding OR $party=party) ORDER BY $joo DESC LIMIT $start, $per_page") or die (mysql_error());
}        

$w = ((($per_page * $page) - $per_page)+1);

        while ($fetch = mysql_fetch_assoc($type))
        {      
                $go = $fetch[$joo]/$fetch['max_participants'];
                $maker = "Custom";
                
                  if ($fetch['make']!='')
                  {
                  $maker = $fetch['make'];                
                  }
                        echo "
				          <div id='result_box".(($w != null) ? $w : 0)."' class='result_box'>
				  	      <div id='opener".(($w != null) ? $w : 0)."'> 
						      <div id='price_module'>
						      <p id='full_price'>Total: $".number_format($fetch[$joo])."</p>
						      <p id='new_price'>$".number_format($go)."</p>
						      <p id='price_guest'>per guest</p>
						      <div id='reserve_btn'>Select Yacht</div>
						      </a> </div>
                  <img class='thumb' src='../wp-content/themes/select/images/sample_yacht.jpg' width='185' height='124'/>
  						    <div id='results_yacht_details'>
  							  <h3 id='yacht_name'>".$fetch['name']."</h3>
  							  <p>".$fetch['city']."</p>
  							  <span id='left_col'> 
  							  <li>Type: ".$fetch['name']."</li>
  							  <li>Builder: ".$maker."</li>
  							  <li>Guests: ".$fetch['max_participants']."</li>
  							  </span>
  							  <span id='right_col'></span>
  						    </div>
  					      </div>
					        <div id='dialog".(($w != null) ? $w : 0)."' title='".$fetch['name']."'>
                  <div style='float: left; width: 250px;''>
                  <b>Total Price:</b></p>
                  <p style='font-size:24px;'>$".number_format($fetch[$joo])."</p>
                  <p style='font-size:11px;'>Price per guest: $".number_format($go)."</p>
                  </div>          
                  <div style='float: left; width: 250px; margin-top: 36px;''>
                  <p>Date: Wednesday, 16-Jun-11</p>
                  <p>Depart:  Miami North Harbor 11AM</p>
                  <p>Arrive:  Miami North Harbor 9AM</p>
                  <p>Guests: ".$fetch['max_participants']."</p>
                  <br>
                  </div>
                  <div style='float: left; width: 170px;'>
                  <div class='special'>
                  <button id='special' style='margin-left: 40px;'>Special Requests?</button>
                  </div>
                  <!-- End demo -->
                  </div>
                  <br style='clear:both;'>
                  <div class='slideshow' style='	float:left;  margin-left: -18px;'>
                  <img src='../wp-content/themes/select/images/numba1.jpg' width='345' height='302'  />
                  </div>
                  <div id='details'>
                  <h3>Statistics</h3>
                  <ul>
                    <li>Length: ".(isset($fetch['length']) ? $fetch['length'] : 'N/A')."</li>
                    <li>Beam: ".(isset($fetch['beam']) ? $fetch['beam'] : 'N/A')."</li>
                    <li>Max Speed: ".(isset($fetch['speed']) ? $fetch['speed'] : 'N/A')."</li>
                    <li>Max Engine Power: ".(isset($fetch['engine']) ? $fetch['engine'] : 'N/A')."</li>
                    <li>Fuel Tank: ".(isset($fetch['fuel']) ? $fetch['fuel'] : 'N/A')."</li>
                  </ul>
                  <br>
                  <h3>Accomodations</h3>
                  <ul>
                  <li>Day Guests: ".(isset($fetch['max_participants']) ? $fetch['max_participants'] : 'N/A')."</li>
                  <li>Overnight Guests: ".(isset($fetch['max_sleep']) ? $fetch['max_sleep'] : 'N/A')."</li>
                  <li>Cabins: ".(isset($fetch['cab']) ? $fetch['cab'] : 'N/A')."</li>
                  <li>FWC: ".(isset($fetch['water']) ? $fetch['water'] : 'N/A')."</li>
                  </ul>
                  <br>
                  <h3>Description</h3>
                  <p style='font-size:11px;'>".(isset($fetch['notes']) ? $fetch['notes'] : 'N/A')."</p>
                  </div>    
                  </div>
    				      </div>"; 
              $w++;              
        }
 
$maxpages = ceil($x / $per_page);

for ($y=1; $y <= $maxpages; $y++)
{
if ($page == $y)
{
echo "<input type='button' style='color:red;' onClick='coolz(".$y.");' value='".$y."'>";      
}
else 
{
echo "<input type='button' style='color:black;' onClick='coolz(".$y.");' value='".$y."'>";
}
}                               

echo "<br><br>";
//print_r($contactwho);      
echo "<br><br>";
echo "<input type='hidden' id='awe' value='".$hiprice."'>";
echo "<input type='hidden' id='awe2' value='".$minprice."'>";
echo "<input type='hidden' id='awe3' value='".$hippl."'>";
echo "<input type='hidden' id='awe4' value='".$loppl."'>";

echo "<hr>";

?> 
<script type="text/javascript">

        getResults(); 
          
</script>

<!--

<div id="form_wrapper_rentals">
            <h2><?php echo $x; ?> Motor Yachts Found</h2>   
            <p class="form_subtitle">in Annapolis, Maryland</p> 
            <p class="form_subtitle">for your corporate event.</p> 
            <br>

            <form action='http://www.selectayacht.com/scheduler2/register.php' method='GET' class="form" name="formz">  
            <p class="form_title">Name</p>
            <p class="form_title_para">Please enter your full name</p>
            <p class="name">
             <input type="text" name="fname" id="web" style="width:132px;" onclick="this.value='';" onfocus="this.select()" onblur="this.value=!this.value?'First name':this.value;" value="First name"  />  
             <input type="text" name="lname" id="web" style="width:132px;" onclick="this.value='';" onfocus="this.select()" onblur="this.value=!this.value?'Last name':this.value;" value="Last name" />  
            </p>  
            </p>  

 <p class="form_title">Email</p>
 <p class="form_title_para">Please enter your e-mail</p>
        <p class="name">
            <input type="text" name="emailaddress" id="web" onclick="this.value='';" onfocus="this.select()" onblur="this.value=!this.value?'sid.tarason@gmail.com':this.value;" value="sid.tarason@gmail.com" />  
         <p class="form_title">Phone Number</p>
 <p class="form_title_para">Please enter your most convenient number</p>
        <p class="name">
            <input type="text" name="phone" id="web" onclick="this.value='';" onfocus="this.select()" onblur="this.value=!this.value?:this.value;"/>  
        </p> 

      <input type='hidden' name='password' value='<?php echo $code;?>'>
      <input type='hidden' name='password2' value='<?php echo $code;?>'>
      <input type='hidden' name='code' value='<?php echo $code;?>'>
      <input type='hidden' name='timezone' value='-4'>
      <input type='hidden' name='lang' value='en_US'>
      <input type='hidden' name='active' value='<?php echo $active;?>'>
      <input type='hidden' name='where' value='<?php echo $city;?>'>
      <input type='hidden' name='who' value='<?php echo $passengers;?>'>
      <input type='hidden' name='when' value='<?php echo $when;?>'>
      <input type='hidden' name='when2' value='<?php echo $when2;?>'>
      <input type='hidden' name='price' value='<?php echo $price;?>'>
      <input type='hidden' name='pricing' value='<?php echo $pricing;?>'>
      <input type='hidden' name='occasion' value='<?php echo $occasion;?>'>
      <input type='hidden' name='crew' value='<?php echo $crew;?>'>
      <input type='hidden' name='finds' value='<?php echo $x;?>'>         
            
      <p class="submit">  
            <input type="submit" name='register' value='Contact All Matched Yacht Charters'>  
      </p>
    </form>
</div>              -->




                         




                    