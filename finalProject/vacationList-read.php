<?php
$xmlList = simplexml_load_file("vacationList.xml") or die("Error: Cannot create object");
echo $xmlList->fun->temperature . "<br>";
echo $xmlList->fun->state . "<br>";
echo $xmlList->fun->duration . "<br>";
echo $xmlList->fun->request . "<br>";
?>