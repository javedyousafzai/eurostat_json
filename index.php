<!DOCTYPE HTML>
<!--
	Miniport by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
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
						<p>These scripts pull data from eurostat API for asylum applications and decisions in the 32 (28 EU+EFTA) countries. Each dataset pull the data and save it in the csv format.</p>
						<?
						
					</header>
				<!-- Annual Asylum block -->
					<div class="row aln-center">
						<div style="width: 100%;">
							<section class="box style1">
								<h3>Asylum and first time asylum applicants by citizenship, age and sex Annual aggregated data</h3>
						<?php		
						/* if the csv file alredy exists, dispaly it so users can download it. */	
						$filename = "annual_asylum_date.csv";
						if (file_exists($filename)) 
						{
						   print "<p>Download existing file <a href=$filename>$filename</a> exists Or run the this <a href=eurostat_annual_asylum.php>script</a> to download updated data from Eurostat</p>";
						} 
						else {
							    echo "The file ./$filename does not exist";
							}

						?>
							</section>
						</div>
				</div>
				
				<div><p></p></div>

				<!-- Monthly Asylum block -->					
					<div class="row aln-center">
						<div style="width: 100%;">
							<section class="box style1">
								<h3>Asylum and first time asylum applicants by citizenship, age and sex Monthly data</h3>
								<?php		
								/* if the csv file for Monthly asylum data  alredy exists, dispaly it so users can download it. */	
								$monthly_asylum_csv = "eurostat_asylum_monthly.csv";
								if (file_exists($monthly_asylum_csv)) 
								{
								   print "<p>Download existing file <a href=./$monthly_asylum_csv>$monthly_asylum_csv</a> exists Or run the this <a href=eurostat_monthly_asylum.php>script</a> to download updated data from Eurostat</p>";
								} 
								else {
									    echo "The file ./$monthly_asylum_csv does not exist";
									}

								?>
							</section>
						</div>
					</div>
				<div><p></p></div>
				<!-- Quarterly Decisions -->					
					<div class="row aln-center">
						<div style="width: 100%;">
							<section class="box style1">
								<h3>First instance decisions on applications by citizenship, age and sex Quarterly data decisions</h3>
								<p>
							<?php		
								/* if the csv file for Decisions alredy exists, dispaly it so users can download it. */	
								$decisions_csv = "eurostat_quarterly_decisions.csv";
								if (file_exists($decisions_csv)) 
								{
								   print "Download existing file <a href=./$decisions_csv>$decisions_csv</a> exists. ";
								} 
							?>
								To download the data from Eurostat, run the <a href=eurostat_quarterly_decisions.php>script </a></p>
							</section>
						</div>
				</div>

				<div><p></p></div>
				<!-- UASC Asylum applications -->					
					<div class="row aln-center">
						<div style="width: 100%;">
							<section class="box style1">
								<h3>	Asylum applicants considered to be unaccompanied minors by citizenship, age and sex Annual data </h3>
						<p>
							<?php		
								/* if the csv file for Unaccompanied asylum data  exists, dispaly it so users can download it. */	
								$unaccompanied_csv = "eurostat_uasc_data.csv";
								if (file_exists($unaccompanied_csv)) 
								{
								   print "Download existing file <a href=./$unaccompanied_csv>$unaccompanied_csv</a> exists. ";
								} 
							?>
								To download the data from Eurostat, run the <a href=eurostat_uasc_asylum.php>script </a></p>	
							</section>
						</div>
				</div>


			</article>

		<!-- Portfolio -->
		
		<!-- Guidance block -->
			<article id="guidance" class="wrapper styl2">
				<div class="container medium">
					<section class="box style1">
						<p>Provide some guidance on how the data from Eurostat is extracted. How the API works and what type of data is extracted via API. Also provide some examples on the API dimensions e.g. Age, Sex, Geo and Decisons, etc.We co go on to show some more guidelines, but the idea is to make people familize with the work we do.</p>
					</section>
				</div>
			</article>


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