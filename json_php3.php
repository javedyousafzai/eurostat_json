<?php

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

/*	Since we are running the API for specific country of origin, specify the ISO2 country code below. You can check the webiste https://www.iso.org/obp/ui/#search and take the country code from there. for testing the script, we are using Afghanistan as country of origin.  */
// Eritrea
$origin_country="ER";

//$url = "http://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/migr_asyunaa?citizen=".$origin_country."&precision=1&sex=F&sex=M&unit=PER&age=Y14-15&age=Y16-17&time=2016&time=2017&time=2018";
$url = "http://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/migr_asyunaa?citizen=".$origin_country."&precision=1&unit=PER&time=2010&time=2011&time=2012";

echo $url."</br>"; 

$result = json_decode(file_get_contents($url));

$asylum_data =	(array) $result->value;
$country_ISO2 = (array) $result->dimension->geo->category->index;
$country_name = (array) $result->dimension->geo->category->label;

$sex_id = 		(array) $result->dimension->sex->category->index;
$sex_label = 	(array) $result->dimension->sex->category->label;

$age_id = 		(array) $result->dimension->age->category->index;
$age_label = 	(array) $result->dimension->age->category->label;

$year_index = 	(array) $result->dimension->time->category->index;
$year_label = 	(array) $result->dimension->time->category->label;

/* using the function explodeValues, explode the original array values */
$geo_array = explodeValues($country_name);
$sex_array = explodeValues($sex_label);
$age_array = explodeValues($age_label);
$year_array = explodeValues($year_label);

/*	sometime there are no asylum figures  against a given year - whihc is empty or ':', therefore, we need to get the fix number of values for asylum array.
we can determine the value of asylum array as - Geo array (34) * year array (no of years specificed in API) * sex array (values in the API)* age array(values in the API)
e.g. Asylum array values = 34*3*2*2 => 408  */

$asylum_data2 = sizeof($geo_array) * sizeof($year_array) * sizeof($sex_array) * sizeof($age_array);
//var_dump($sex_label);
$year_flag = 0;

$index=0;
$geo_flag = 0;
$sex_flag = 0;
$thrshld = intval(sizeof($geo_array)) * intval(sizeof($year_array));
$sex_thrshld = intval(sizeof($geo_array)) * intval(sizeof($year_array)) * intval(sizeof($age_array));

//print "---threshhold--".$thrshld."---<br>";

$age_flag = 0;
$age_counter_flag = 1;
$sex_counter_flag = 1;

/*	open the file "eurostat_data.csv" 	*/ 
$file = fopen('eurostat_date.csv', 'w');	

/*	Set the column headers for	csv filename */ 
fputcsv($file, array('Index Num', 'Asylum Data', 'Year', 'Age', 'Sex', 'Country of Asylum'));

//print sizeof($asylum_data)." -- this is num of asylum vals<br><br>";

for ($l=0; $l < $asylum_data2; $l++)	
	{
		/*	check wather the value from asylum array is numeric or not)  */
		if (isset($asylum_data[$l]))
		{
			$asylum_val = $asylum_data[$l];
		}
		elseif(empty($asylum_data[$l]) )
		{
			$asylum_val = 0;	
		}
		else
		{
			$asylum_val = 0;	
		}

		/*	Calculations for Age array */
		if($l == $thrshld * $age_counter_flag)
		{
			$age_flag++;	

			if(!isset($age_array[$age_flag]))
			{
				$age_flag = 0;				
			}
			$age_counter_flag++;
		}

		/*	Calculations Sex array */
		if($l == $sex_thrshld * $sex_counter_flag)
		{
			$sex_flag++;	

			if(!isset($sex_array[$sex_flag]))
			{
				$sex_flag = 0;				
			}
			$sex_counter_flag++;
		}

		/*	Calculations for Geo (country of asylum) array */
		if ($geo_flag == 35)
		{
			$geo_flag = 0;
		}
		
		//print "<br>".$l."----".$year_array[$year_flag]."--: ".$asylum_val."---".$geo_array[$geo_flag]."---".$sex_array[$sex_flag]. "---".$age_array[$age_flag];
		//print "<br>".$sex_array[$sex_flag]. "---".$age_array[$age_flag];
		

		fputcsv($file, array($index, $asylum_val, $year_array[$year_flag], $age_array[$age_flag], $sex_array[$sex_flag], $geo_array[$geo_flag]));
		$year_flag++;
		
		/*	Calculations for Year array */
		if($year_flag == intval(sizeof($year_array)))
		{
			$year_flag=0;
			$geo_flag++;
		}
		$index++;
	}

print "<p></p>";
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