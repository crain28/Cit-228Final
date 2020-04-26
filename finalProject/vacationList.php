<?php
    $xmlList = simplexml_load_file("vacationList.xml") or die("Error: Cannot create object");
    foreach($xmlList->temperature as $vac){
        $state=$vac->state;
		$duration=$vac->duration;
		$request=$vac->request;
        echo "<div style='width:40%'><p style='color:green;border-bottom:2px green solid;font-weight:900;'>Temperature: " . $temperature . "<br>" .
		"<span style='background-color:white;color:black;'>State: " . $state . "<br>" .
		"<span style='background-color:white;color:black;'>Duration: " . $duration . "<br>" .
        "Request: " . $request . "</span></p></div>";
    }
?>

<div style='width:40%'>
<p style='color:green;border-bottom:2px green solid;font-weight:900;'>ID: 1<br>
<span style='background-color:white;color:black;'>Temperature: Hot<br>
State: Alaska <br>
Duration: 1 Month <br>
Request: Jacket</span></p>

<p style='color:green;border-bottom:2px green solid;font-weight:900;'>ID: 2<br>
<span style='background-color:white;color:black;'>Temperature: Hot<br>
State: Florida <br>
Duration: 2 Weeks <br>
Request: Swim Trunks</span></p>

<p style='color:green;border-bottom:2px green solid;font-weight:900;'>ID: 3<br>
<span style='background-color:white;color:black;'>Temperature: Hot<br>
State: Hawaii <br>
Duration: 1 Week <br>
Request: Surf Board</span></p></div>


