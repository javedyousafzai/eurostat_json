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
								<h3>Asylum and first time asylum applicants by citizenship, age and sex Monthly data</h3>
								<div class='div_scroll'>

<?php

print "something is not working with actual PHP files";
	?>




		</div>
							</section>
						</div>
				</div>
				
				<div><p></p></div>
<?php
				$filename = "eurostat_asylum_monthly.csv";
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