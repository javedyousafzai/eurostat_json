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
/*	Since we are running the API for specific country of origin, specify the ISO2 country code below. You can check the webiste https://www.iso.org/obp/ui/#search and take the country code from there;*/

$origin_country="AF";
$url = "http://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/migr_asyunaa?citizen=".$origin_country."&sex=T&precision=1&time=2018&age=TOTAL";
echo $url; 
$result = json_decode(file_get_contents($url));

$asylum =		(array) $result->value;
$country_ISO2 = (array) $result->dimension->geo->category->index;
$country_name = (array) $result->dimension->geo->category->label;

$final_output = array();
/*	open the file "eurostat_data.csv" 	*/ 
$file = fopen('eurostat_date.csv', 'w');	

/*	Set the column headers for	csv filename */ 
fputcsv($file, array('ISO 2 Country Code', 'Asylum Country', 'Asylum applications'));
$counter=0;
foreach ($asylum as $key=>$val)
	{
		foreach($country_ISO2 as $key1 => $val2)
		{
			if ($key == $val2)
			{
				foreach($country_name as $key2 => $val2)
				{
					if($key1 == $key2)
					{
						/* fields to write to the csv file;
						$key = index against each country code;
						$key1 = ISO2 country code
						$val2 = Full country name
						$vale = actual number of asylum application for each EU MS
						*/
						fputcsv($file, array($key, $key1, $val2, $val,));							
					}
					
				}
			}
		}
	}
// Close the file
fclose($file);

?>