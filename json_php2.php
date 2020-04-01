<?php
/*	here is the logic is a bit different. I am now using the file json_php3.php*/

/*get the content from the Eurostat json file for asylum applications and then save the data in the csv file 'eurostat_date.csv'. 
the csv file should be saved in the root directory where the php file is normally saved so we can easily locate it.

Dimention of the Eurostat API to get values from arrays
"dimension"{
	"citizen"{List}	-- see file Eurostat_dataset_list.txt
	"sex"{list}		-- see file Eurostat_dataset_list.txt
	"unit"{list} 	-- see file Eurostat_dataset_list.txt	
	"age"{list}		-- see file Eurostat_dataset_list.txt
	"geo"{list}	
	"time"{list} 
}
*/
/*	Since we are running the API for specific country of origin, specify the ISO2 country code below. You can check the webiste https://www.iso.org/obp/ui/#search and take the country code from there;*/

$origin_country="AF";
//$url = "http://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/migr_asyunaa?citizen=".$origin_country."&sex=T&precision=1&time=2018&age=TOTAL";
$url = "http://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/migr_asyunaa?citizen=".$origin_country."&precision=1&sex=F&sex=M&unit=PER&age=Y14-15&age=Y16-17&time=2016&time=2017&time=2018";
//echo $url."</br>"; 

$result = json_decode(file_get_contents($url));

$asylum_data =	(array) $result->value;
$country_ISO2 = (array) $result->dimension->geo->category->index;
$country_name = (array) $result->dimension->geo->category->label;

$sex_id = (array) $result->dimension->sex->category->index;
$sex_label = (array) $result->dimension->sex->category->label;

$age_id = (array) $result->dimension->age->category->index;
$age_label = (array) $result->dimension->age->category->label;

$year_index = (array) $result->dimension->time->category->index;
$year_label = (array) $result->dimension->time->category->label;

$api_id	=	$result->id;
$api_size=	$result->size;

/* using the function explodeValues, explode the original array values */
$geo_array = explodeValues($country_name);
$sex_array = explodeValues($sex_label);
$age_array = explodeValues($age_label);
$year_array = explodeValues($year_label);

//var_dump($asylum_data);
$temp = array();

//$asylum_counter = sizeof($country_name)*sizeof($sex_label)*sizeof($age_label);
$asylum_counter = 0;
$flag = 0;

//print "<br> Size of counter: ". $asylum_counter."<br>";

/*	open the file "eurostat_data.csv" 	*/ 
$file = fopen('eurostat_date.csv', 'w');	
/*	Set the column headers for	csv filename */ 
fputcsv($file, array('Index Num', 'Asylum Data', 'Year', 'Age', 'Sex', 'Country of Asylum'));
// geo country loop for 34 country names including TOTAL and EU 28 categories

//print "int val of size of year array".intval(sizeof($year_array))."-</br>";
//print "";
$flag3=0;
$year_flag = '';
$testflag = 0;

for($i=0; $i< intval(sizeof($geo_array)); $i++) 
{
	//print "Geo :".$geo_array[$i]." in Geo array<br>";  

	for($j=0; $j<intval(sizeof($sex_array)); $j++) 
	{
		//print "Geo :".$geo_array[$i]." in Sex array<br>";  

		for($k=0; $k<intval(sizeof($age_array)); $k++)
		{
			//print "Geo :".$geo_array[$i]." in Age array<br>";  

			for($l=0; $l<intval(sizeof($year_array)); $l++)	
			{
				//print "Geo :".$geo_array[$i]." in Year array<br>";  

				for($m = $asylum_counter; $m< intval(sizeof($asylum_data)); $m++)
				{
					//print "Flag- ".$flag."<br>";

					if( $flag3 <3)
					{
						
						/* check the value of each year counter and dont repeat it -----need to improve here the extra commentary */
						//print "---Flag: ".$flag."--M value: --".$m."-- Asylum counter:--".$asylum_counter."</br></br></br>";	
						print "<br>asylum_counter [".$asylum_counter."] Flag3[".$flag3."] Asylum Data[".$asylum_data[$m]."] Year[".$year_array[$l]."] Age[".$age_array[$k]."] Sex[".$sex_array[$j]."] Geo[".$geo_array[$i]."]</br>";						
						array_push($temp, $flag, $asylum_data[$m], $year_array[$l], $age_array[$k], $sex_array[$j], $geo_array[$i],"</br>");
						//$temp  = array($flag, $asylum_data[$m], $year_array[$l], $age_array[$k], $sex_array[$j], $geo_array[$i]);
						//fputcsv($file, array($flag, $asylum_data[$m], $year_array[$l], $age_array[$k], $sex_array[$j], $geo_array[$i])
						
						//$asylum_counter = $asylum_counter+ intval(sizeof($year_array));		
						$asylum_counter++;
					}
					$flag3++;											
					//$flag3 = 0;
					$flag++;
				
				}
				$flag3 = 0;
		
			}
		}
	}
}
print "<p></p>";
//var_dump($temp);
//Close the file
fclose($file);

/* Using the explodeValues function, convert API array into string values for further use*/
function explodeValues($input_rray)
{
	$array_str = implode (',', $input_rray);
	$array_val = explode(',', $array_str);

	return $array_val;
}
?>