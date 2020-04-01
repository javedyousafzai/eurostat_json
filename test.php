<?php

// Get the contents of the JSON file 
$json = file_get_contents("iso2codes2.json");
$iso2List = json_decode($json, true);

//print sizeof($iso2List);


foreach($iso2List as $key=> $value)
{
	print "<br>".$iso2List[$key]["name"] ."--".$iso2List[$key]["iso2"]."--". $iso2List[$key]["iso3"];
}


/*
 "name" : "South Africa",
  "iso2": "ZA",
  "iso3" : "ZAF",
  "numeric": "710"
*/
/*
for ($a=0; $a <sizeof($array); $a++)	
{
	print "<br>".$array[$a];
}*/

?>