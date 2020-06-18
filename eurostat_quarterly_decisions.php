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
					<li><a href="./">Data</a></li>
					<li><a href="#guidance">Guidance</a></li>
					<li><a href="visualize.php">Visualize</a></li>
					<li><a href="#contact">Contact</a></li>
				</ul>
			
			</nav>

		<!-- Home -->
			

		<!-- Eurostat API Introduction  -->
			<article id="work" class="wrapper style2">
				<div class="container">
					<header>
						<h2>Eurostat Data - API </h2>
																	
					</header>
				<!-- Annual Asylum block -->
					<div class="row aln-center">
						<div class="col-4">
							<section class="box style1">
								<h3>First instance decisions on applications by citizenship, age and sex Quarterly data decisions</h3>
								<div class='div_scroll'>

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

			/*	open the CSV file to write data in it. */ 
			$file = fopen('eurostat_quarterly_decisions.csv', "w");	

			/*	Set the column headers for the csv filename */ 
			fputcsv($file, array('Index Num', 'Country of Origin','Country of Origin-ISO2', 'Decision Data', 'decision Type', 'Year', 'Quarter', 'Age', 'Sex', 'Country of Asylum','HCR Regional Bureau'));

			/* here we are using loop to go through number of country of origin stored in json format in the file iso2codes.json in the same root folder */
			/*	ISO 2 country codes are available on multiple websites; e.g. https://www.nationsonline.org/oneworld/country_code_list.htm */

			// Get the contents of the JSON file 
			$json = file_get_contents("iso2codesnew.json");
			$origin_country = json_decode($json, true);

			$index=0;
			/*	specify the time parameter i.e. the number of quartes for the decision API */
			//$time = "&time=2015Q1&time=2015Q2&time=2015Q3&time=2015Q&time=2016Q1&time=2016Q2&time=2016Q3&time=2016Q4&time=2017Q1&time=2017Q2&time=2017Q3&time=2017Q4&time=2018Q1&time=2018Q2&time=&2018Q3&time=2018Q4&time=2019Q1&time=2019Q2&time=2019Q3&time=2019Q4&time=2020Q1&time=2020Q2&time=2020Q3&time=2020Q4";

			//$time = "&time=2015Q1&time=2015Q2&time=2015Q3&time=2015Q4&time=2016Q1&time=2016Q2&time=2016Q3&time=2016Q4";
			//$time = "&time=2019Q1&time=2019Q2&time=2019Q3&time=2019Q4";
			$time = "&time=2020Q1";

			for ($a=0; $a <sizeof($origin_country); $a++)	
			{	
				$url = "http://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/migr_asydcfstq?citizen=".$origin_country[$a]["iso2"]."&precision=1&sex=T&age=TOTAL".$time;

				//echo $url."</br>"; 

				$result = json_decode(file_get_contents($url));
				$decision_data =	(array) $result->value;

				$sex_id = 		(array) $result->dimension->sex->category->index;
				$sex_label = 	(array) $result->dimension->sex->category->label;

				$age_id = 		(array) $result->dimension->age->category->index;
				$age_label = 	(array) $result->dimension->age->category->label;
				
				$decision_id = 	  	(array) $result->dimension->decision->category->index;
				$decision_label =	(array) $result->dimension->decision->category->label;
				
				$country_ISO2 = (array) $result->dimension->geo->category->index;
				$country_name = (array) $result->dimension->geo->category->label;

				$time_index = 	(array) $result->dimension->time->category->index;
				$time_label = 	(array) $result->dimension->time->category->label;

				/* using the function explodeValues, explode the original array values */
				$geo_array 		= explodeValues($country_name);
				$sex_array 		= explodeValues($sex_label);
				$age_array 		= explodeValues($age_label);
				$decision_array = explodeValues($decision_label);
				$time_array 	= explodeValues($time_label);

				//print_r($decision_array)."<br/>";

				//var_dump($decision_array);

				/*	sometime there are no asylum figures  against a given year - whihc is empty or ':', therefore, we need to get the fix number of values for the main decison array i.e. number of records returned by the API.
				we determine the value of decision array as:  Geo array (35) * time array  (no of quarters specificed in API) * sex array (values in the API)* age array(values in the API) e.g.Decision array values = 35 * 3 * 2 * 2 => 408  */

				$decision_data2 = sizeof($geo_array) * sizeof($time_array) * sizeof($sex_array) * sizeof($age_array) * sizeof($decision_array);
				
				$time_flag = 0;
				
				$age_flag = 0;
				$sex_flag = 0;
				$geo_flag = 0;
				$decision_flag = 0;

				$thrshld_age = intval(sizeof($geo_array)) * intval(sizeof($time_array));
				$thrshld_sex = intval(sizeof($geo_array)) * intval(sizeof($time_array)) * intval(sizeof($age_array));
				//$thrshld_decision = intval(sizeof($geo_array)) * intval(sizeof($time_array)) * intval(sizeof($age_array)) * intval(sizeof($sex_array))* intval(sizeof($decision_array));
				$thrshld_decision = intval(sizeof($geo_array)) * intval(sizeof($time_array));

				//print "<br/> Decison threshold".$thrshld_decision;
				
				$age_counter_flag = 1;
				$sex_counter_flag = 1;
				$decision_counter_flag = 1;

				for ($l=0; $l < $decision_data2; $l++)	
					{
						/*	check wather the value from asylum array is numeric or not)  */
						if (isset($decision_data[$l]))
						{
							$decision_val = $decision_data[$l];
						}
						elseif(empty($decision_data[$l]) )
						{
							$decision_val = 0;	
						}
						else
						{
							$decision_val = 0;	
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

						/*	Calculations for decition type  array */
						if($l == $thrshld_decision * $decision_counter_flag)
						{
							$decision_flag++;	

							if(!isset($decision_array[$decision_flag]))
							{
								$decision_flag = 0;				
							}
							$decision_counter_flag++;
						}

						/*	Calculations for Geo (country of asylum) array */
						if ($geo_flag == 35)
						{
							$geo_flag = 0;
						}

						/* split the value 'time_array' into year and quarter */	
						$year = substr($time_array[$time_flag], 0, 4);  
						$quarter = substr($time_array[$time_flag], -2);
						
						/* In the Out data in csv, we will only write those records which has some data, oterhwise the the data returned via APi will have huge amount of data and will exceed the limits rows offered by Excel i.e. our data should not exceed 1,048,576 rows, but for over 200 origin countries, the data goes so we can keep it lmited with the following If consition/check. */
						if ($decision_val > 0)
						{
							print "<br>".$index."--".
									$origin_country[$a]["name"]."--".
									$origin_country[$a]["iso2"]."--".
									$decision_val."--".
									$decision_array[$decision_flag]."--".					
									$year."--".
									$quarter."--".
									$age_array[$age_flag]."--".
									$sex_array[$sex_flag]."--".
									$geo_array[$geo_flag]."--".
									$origin_country[$a]["unhcr_region"];		
;
									
							/* write the data to csv file */		
							fputcsv($file, array($index, $origin_country[$a]["name"], $origin_country[$a]["iso2"], $decision_val, $decision_array[$decision_flag], $year, $quarter, $age_array[$age_flag], $sex_array[$sex_flag], $geo_array[$geo_flag],$origin_country[$a]["unhcr_region"]));
							$index++;
						}
							$time_flag++;
						/*	Calculations for time array */
						if($time_flag == intval(sizeof($time_array)))
						{
							$time_flag=0;
							$geo_flag++;
						}
						
					}

				/*	Reset all the variables declared above to loop thrugh another instance of country of origin value;*/
				unset($result);
				unset($decision_data);
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
				unset($decision_data2); 
				unset($time_flag); 

				//unset($index);
				unset($geo_flag);
				unset($sex_flag); 
				unset($decision_flag); 
				
				unset($thrshld);
				unset($sex_thrshld); 

				unset($age_flag); 
				unset($age_counter_flag); 
				unset($sex_counter_flag); 
				unset($decision_counter_flag); 
					
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
				</div>
							</section>
						</div>
				</div>
				
				<div><p></p></div>

<?php
				$filename = "eurostat_quarterly_decisions.csv";
				if (file_exists($filename)) 
				{
				   print "<p>The script is executed. The <a href=$filename>$filename</a> can be downloaded for further use.</p>";
				} 
				else {
					    echo "The desired file ./$filename is not generated. Give it another try!";
					}
?>				

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