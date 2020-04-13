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
						
						<?
						
					</header>
				<!-- Annual Asylum block -->
					<div class="row aln-center">
						<div class="col-4">
							<section class="box style1">
								<h3>Asylum applicants considered to be unaccompanied minors by citizenship, age and sex Annual data</h3>
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
		}	*/

		/*	open the file "eurostat_data.csv" 	*/ 
		$file = fopen('eurostat_uasc_data.csv', 'w');	

		/*	Set the column headers for	csv filename */ 
		fputcsv($file, array('Index Num', 'Country of Origin', 'Country of Origin-ISO2', 'Asylum Data', 'Year', 'Age', 'Sex', 'Country of Asylum'));
		?>

		<table>
			<tr><td>Index</td> 
				<td>Country of Origin</td> 
				<td>Country of Origin-ISO2</td> 
				<td>Asylum Data</td> 
				<td>Year</td> 
				<td>Age</td> 
				<td>Sex</td> 
				<td>Country of Asylum</td></tr>
<?php				

		/*	Since we are running the API for specific country of origin, specify the ISO2 country code below. You can check the webiste https://www.iso.org/obp/ui/#search and take the country code from there. for testing the script, we are using Afghanistan as country of origin.  */

		/* here we are using loop to go through number of country of origin stored in json format in the file iso2codes.json in the same root folder 
		168 countries are currently stored in the iso2 json file */
			

				// Get the contents of the JSON file 
				$json = file_get_contents("iso2codes.json");
				$origin_country2 = json_decode($json, true);

		//print sizeof($origin_country2);
		$index=0;
		for ($a=0; $a <sizeof($origin_country2); $a++)	
		{	
			//$url = "http://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/migr_asyunaa?citizen=".$origin_country."&precision=1&sex=F&sex=M&unit=PER&age=Y14-15&age=Y16-17&time=2016&time=2017&time=2018";
			$url = "http://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/migr_asyunaa?citizen=".$origin_country2[$a]["iso2"]."&precision=1&unit=PER&time=2008&time=2009&time=2010&time=2011&time=2012&time=2013&time=2014&time=2015&time=2016&time=2017&time=2018&time=2019";

			//echo $url."</br>"; 

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
			//print "There are ".$asylum_data2." number of records retrieved!";
			$year_flag = 0;

			$geo_flag = 0;
			$sex_flag = 0;
			$thrshld = intval(sizeof($geo_array)) * intval(sizeof($year_array));
			$sex_thrshld = intval(sizeof($geo_array)) * intval(sizeof($year_array)) * intval(sizeof($age_array));

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
					
					if($asylum_val > 0 )
					{
						
						print "<tr>
							<td>".$index."</td>
							<td>".$origin_country2[$a]["name"]."</td>
							<td>".$origin_country2[$a]["iso2"]."</td>
							<td>".$asylum_val."</td>
							<td>".$year_array[$year_flag]."</td>
							<td>".$age_array[$age_flag]."</td>
							<td>".$sex_array[$sex_flag]."</td>
							<td>".$geo_array[$geo_flag]."</td>							
							</tr>";
						//print "<br>".$sex_array[$sex_flag]. "---".$age_array[$age_flag];
						fputcsv($file, array($index, $origin_country2[$a]["name"],$origin_country2[$a]["iso2"], $asylum_val, $year_array[$year_flag], $age_array[$age_flag], $sex_array[$sex_flag], $geo_array[$geo_flag]));
					$index++;
					}
					$year_flag++;
					
					/*	Calculations for Year array */
					if($year_flag == intval(sizeof($year_array)))
					{
						$year_flag=0;
						$geo_flag++;
					}
					
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
			unset($year_index);
			unset($year_label); 
			unset($geo_array); 
			unset($sex_array); 
			unset($age_array); 
			unset($year_array); 
			unset($asylum_data2); 
			unset($year_flag); 

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

		print "</table>";
		print "total records ".$index;
		
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