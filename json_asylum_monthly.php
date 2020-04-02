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

/*	open the file "eurostat_data.csv" 	*/ 
$file = fopen('eurostat_asylum_monthly.csv', 'w');	

/*	Set the column headers for	csv filename */ 
fputcsv($file, array('Index Num', 'Country of Origin','Country of Origin-ISO2', 'Asylum Data', 'Year', 'Month', 'Age', 'Sex', 'Country of Asylum'));

/* here we are using loop to go through number of country of origin stored in json format in the file iso2codes.json in the same root folder */
/*	ISO 2 country codes are available on multiple websites; e.g. https://www.nationsonline.org/oneworld/country_code_list.htm */

// Get the contents of the JSON file 
$json = file_get_contents("iso2codes.json");
$origin_country2 = json_decode($json, true);

//$origin_country2=array("ER");

//$origin_country_name = array ("Eritrea");


$index=0;

for ($a=0; $a <sizeof($origin_country2); $a++)	
//foreach($origin_country2 as $a=> $val)
{	
	$url = "http://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/migr_asyappctzm?citizen=".$origin_country2[$a]["iso2"]."&precision=1&asyl_app=NASY_APP&time=2019M01&time=2019M02&time=2019M03";

//	echo $url."</br>"; 

	$result = json_decode(file_get_contents($url));
	$asylum_data =	(array) $result->value;
	$country_ISO2 = (array) $result->dimension->geo->category->index;
	$country_name = (array) $result->dimension->geo->category->label;

	$sex_id = 		(array) $result->dimension->sex->category->index;
	$sex_label = 	(array) $result->dimension->sex->category->label;

	$age_id = 		(array) $result->dimension->age->category->index;
	$age_label = 	(array) $result->dimension->age->category->label;

	$time_index = 	(array) $result->dimension->time->category->index;
	$time_label = 	(array) $result->dimension->time->category->label;


	/* using the function explodeValues, explode the original array values */
	$geo_array = explodeValues($country_name);
	$sex_array = explodeValues($sex_label);
	$age_array = explodeValues($age_label);
	$time_array = explodeValues($time_label);

	/*	sometime there are no asylum figures  against a given year - whihc is empty or ':', therefore, we need to get the fix number of values for asylum array.
	we can determine the value of asylum array as - Geo array (34) * year array (no of years specificed in API) * sex array (values in the API)* age array(values in the API) e.g. Asylum array values = 34*3*2*2 => 408  */

	$asylum_data2 = sizeof($geo_array) * sizeof($time_array) * sizeof($sex_array) * sizeof($age_array);
	$time_flag = 0;
	
	$geo_flag = 0;
	$sex_flag = 0;
	$thrshld_age = intval(sizeof($geo_array)) * intval(sizeof($time_array));
	$thrshld_sex = intval(sizeof($geo_array)) * intval(sizeof($time_array)) * intval(sizeof($age_array));

	$age_flag = 0;
	$age_counter_flag = 1;
	$sex_counter_flag = 1;

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
			if($l == $thrshld_age * $age_counter_flag)
			{
				$age_flag++;	

				if(!isset($age_array[$age_flag]))
				{
					$age_flag = 0;				
				}
				$age_counter_flag++;
			}

			/*	Calculations Sex array */
			if($l == $thrshld_sex * $sex_counter_flag)
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

			/* split the value 'time_array' into year and month */	
			$year = substr($time_array[$time_flag], 0, 4);  
			$month = substr($time_array[$time_flag], -2);
			
			print "<br>".$origin_country2[$a]["iso2"]."--".
						$origin_country2[$a]["name"]."---".
						$l."----".
						$year."---".
						$month."--: ".
						$asylum_val."---".
						$geo_array[$geo_flag]."---".
						$sex_array[$sex_flag]. "---".
						$age_array[$age_flag];
			
			fputcsv($file, array($index, $origin_country2[$a]["iso2"], $origin_country2[$a]["name"], $asylum_val, $year, $month, $age_array[$age_flag], $sex_array[$sex_flag], $geo_array[$geo_flag]));
			$time_flag++;
			
			/*	Calculations for Year array */
			if($time_flag == intval(sizeof($time_array)))
			{
				$time_flag=0;
				$geo_flag++;
			}
			$index++;
		}

	/*	Reset all the variables declared above to loop thrugh another instance of country of origin value;*/
	unset($result);
	unset($asylum_data);
	unset($country_ISO2);
	unset($country_name);
	unset($sex_id);
	unset($sex_label);
	unset($age_id);
	unset($age_label);
	unset($time_index);
	unset($time_label); 
	unset($geo_array); 
	unset($sex_array); 
	unset($age_array); 
	unset($time_array); 
	unset($asylum_data2); 
	unset($time_flag); 

	//unset($index);
	unset($geo_flag);
	unset($sex_flag); 
	unset($thrshld);
	unset($sex_thrshld); 

	unset($age_flag); 
	unset($age_counter_flag); 
	unset($sex_counter_flag); 
	
	//print "<p></p>";
	

} 
//Close the file
fclose($file);

// country of origin

/*  Using the explodeValues function, convert API array into string values for further use  */
	function explodeValues($input_rray)
	{
		$array_str = implode (',', $input_rray);
		$array_val = explode(',', $array_str);
		return $array_val;
	}

?>