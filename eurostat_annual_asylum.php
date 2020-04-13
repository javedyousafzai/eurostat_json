<!DOCTYPE HTML>

<html>
	<head>
		<title>Eurostat Data via API</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">

		<!-- Nav -->
		<!-- Nav -->
			<nav id="nav">
				
				<ul class="container">
					<li><a href="./">Top</a></li>
					<li><a href="#guidance">Guidance</a></li>
					<li><a href="#portfolio">Portfolio</a></li>
					<li><a href="#contact">Contact</a></li>
				</ul>
			
			</nav>

		<!-- Home -->
			

		<!-- Eurostat API Introduction  -->
			<article id="work" class="wrapper style2">
				<div class="container">
					<header>
						<h2>Eurostat Data - API </h2>
						
						<?
						
					</header>
				<!-- Annual Asylum block -->
					<div class="row aln-center">
						<div class="col-4">
							<section class="box style1">
								<h3>Asylum and first time asylum applicants by citizenship, age and sex Annual aggregated data</h3>
								<div class="div_scroll">
	<?php		


		/*get the content from the Eurostat json file for annual asylum applications and then save the data in the csv file 'annual_asylum_date.csv'. 
		the csv file should be saved in the root directory where the php file is normally saved so we can easily locate it.

		Dimention of the Eurostat API to get values from arrays
		"dimension"{
			"citizen"{List}	-- see file Eurostat_dataset_list.txt
			"sex"{list}		-- see file Eurostat_dataset_list.txt
			"unit"{list} 	-- see file Eurostat_dataset_list.txt	
			"age"{list}		-- see file Eurostat_dataset_list.txt
			"geo"{list}	
			"time"{list} 	-- depend on API - for monthly its concatenated with yearand month e.g. '2019M01' 
		}
		*/

		/*	open the file "annual_asylum_date.csv" 	*/ 
		$file = fopen('annual_asylum_date.csv', 'w');	

		/*	Set the column headers for	csv filename */ 
		fputcsv($file, array('Index Num', 'Country of Origin','Country of Origin-ISO2', 'Asylum Data', 'Year', 'Age', 'Sex', 'Country of Asylum'));

		/* here we are using loop to go through number of country of origin stored in json format in the file iso2codes.json in the same root folder */
		/*	ISO 2 country codes are available on multiple websites; e.g. https://www.nationsonline.org/oneworld/country_code_list.htm */

		// Get the contents of the JSON file 
		$json = file_get_contents("iso2codes.json");
		$origin_country2 = json_decode($json, true);

		//$origin_country2=array 	("AF");
		//$origin_country_name = array ("Afghanistan");

		$index=0;

		/*	specify the time parameter i.e. the number of years to get the annual first-time asylum data from the API  */
		$time = "&time=2019";

		//$time = "&time=2018&time=2019";

		for ($a=0; $a <sizeof($origin_country2); $a++)	
		{	
			$url = "http://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/migr_asyappctza?citizen=".$origin_country2[$a]["iso2"]."&precision=1&asyl_app=NASY_APP".$time;
			//echo $url."</br>"; 

			$result = json_decode(file_get_contents($url));
			$annual_asylum_data =	(array) $result->value;

			$sex_id = 		(array) $result->dimension->sex->category->index;
			$sex_label = 	(array) $result->dimension->sex->category->label;

			$age_id = 		(array) $result->dimension->age->category->index;
			$age_label = 	(array) $result->dimension->age->category->label;
			
			$country_ISO2 = (array) $result->dimension->geo->category->index;
			$country_name = (array) $result->dimension->geo->category->label;

			$time_index = 	(array) $result->dimension->time->category->index;
			$time_label = 	(array) $result->dimension->time->category->label;

			/* using the function explodeValues, explode the original array values */
			$geo_array 	= explodeValues($country_name);
			$sex_array 	= explodeValues($sex_label);
			$age_array 	= explodeValues($age_label);
			$time_array 	= explodeValues($time_label);

			//print_r($decision_array)."<br/>";

			//var_dump($decision_array);

			/*	sometime there are no asylum figures  against a given year - whihc is empty or ':', therefore, we need to get the fix number of values for the main decison array i.e. number of records returned by the API.
			we determine the value of decision array as:  Geo array (35) * time array  (no of quarters specificed in API) * sex array (values in the API)* age array(values in the API) e.g.Decision array values = 35 * 3 * 2 * 2 => 408  */

			$annual_asylum_data2 = sizeof($geo_array) * sizeof($time_array) * sizeof($sex_array) * sizeof($age_array);
			
			$time_flag = 0;
			
			$age_flag = 0;
			$sex_flag = 0;
			$geo_flag = 0;
			
			$thrshld_age = intval(sizeof($geo_array)) * intval(sizeof($time_array));
			$thrshld_sex = intval(sizeof($geo_array)) * intval(sizeof($time_array)) * intval(sizeof($age_array));
			
			//print "<br/> Decison threshold".$thrshld_decision;
			
			$age_counter_flag = 1;
			$sex_counter_flag = 1;
			

			for ($l=0; $l < $annual_asylum_data2; $l++)	
				{
					/*	check wather the value from asylum array is numeric or not)  */
					if (isset($annual_asylum_data[$l]))
					{
						$asylum_value = $annual_asylum_data[$l];
					}
					elseif(empty($annual_asylum_data[$l]) )
					{
						$asylum_value = 0;	
					}
					else
					{
						$asylum_value = 0;	
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

					print "<br>".$index."--".
							$origin_country2[$a]["name"]."--".
							$origin_country2[$a]["iso2"]."--".
							$asylum_value."--".
							$time_array[$time_flag]."--".
							$age_array[$age_flag]."--".
							$sex_array[$sex_flag]."--".
							$geo_array[$geo_flag];
							
					fputcsv($file, array($index, $origin_country2[$a]["name"], $origin_country2[$a]["iso2"], $asylum_value, $time_array[$time_flag], $age_array[$age_flag], $sex_array[$sex_flag], $geo_array[$geo_flag]));
					
					$time_flag++;
					
					/*	Calculations for time array */
					if($time_flag == intval(sizeof($time_array)))
					{
						$time_flag=0;
						$geo_flag++;
					}
					$index++;
				}

			/*	Reset all the variables declared above to loop thrugh another instance of country of origin value;*/
			unset($result);
			unset($annual_asylum_data);
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
			unset($annual_asylum_data2); 
			unset($time_flag); 

			//unset($index);
			unset($geo_flag);
			unset($sex_flag); 
				
			unset($thrshld);
			unset($sex_thrshld); 

			unset($age_flag); 
			unset($age_counter_flag); 
			unset($sex_counter_flag); 
			

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
			</div>
							</section>
						</div>
				</div>
				
				<div><p></p></div>

				

			</article>

		<!-- Portfolio -->
		


		<!-- Contact -->
			<article id="contact" class="wrapper style4">
				<div class="container medium">
					
					<footer>
						<ul id="copyright">
							<li>Here we can provide some footer info if we need</li>
							
						</ul>
					</footer>
				</div>
			</article>

		

	</body>
</html>