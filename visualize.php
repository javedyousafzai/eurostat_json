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
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<link rel="stylesheet" type="text/css" href="tabs/css/demo.css" />
		<link rel="stylesheet" type="text/css" href="tabs/css/tabs.css" />
		<link rel="stylesheet" type="text/css" href="tabs/css/tabstyles.css" />
  		<script src="tabs/js/modernizr.custom.js"></script>
	</head>
	<body>
	
		<!-- Nav -->
			<nav id="nav">
				
				<ul class="container">
					<li><a href="./">Data</a></li>
					<li><a href="#guidance">Guidance</a></li>
					<li><a href="visualize.php">Visualize</a></li>
					<li><a href="#contact">Contact</a></li>
				</ul>
			</nav>

		<!-- inser iframe for the PowerBi visualization -->
			
		<div style="height:5px; margin-bottom: 30px; margin-top: 10px;">
		
		</div>

		<!-- Eurostat API Introduction  -->
			
			<div>

				<section>
					<div class="tabs tabs-style-iconbox">
						<nav>
							<ul>
								<li><a href="#section-iconbox-1" class="icon icon-home"><span>Annual Asylum</span></a></li>
								<li><a href="#section-iconbox-2" class="icon icon-gift"><span>Monthly Asylum</span></a></li>
								<li><a href="#section-iconbox-3" class="icon icon-upload"><span>Decisions</span></a></li>
								<li><a href="#section-iconbox-4" class="icon icon-coffee"><span>Unaccompanied children</span></a></li>
								
							</ul>
						</nav>
						<div class="content-wrap">
							<section id="section-iconbox-1">
								<iframe src="https://app.powerbi.com/view?r=eyJrIjoiZmVhNGVkMGQtNDc3Mi00NzRiLThjY2UtZjE2Y2VmOTAxZmVjIiwidCI6ImU1YzM3OTgxLTY2NjQtNDEzNC04YTBjLTY1NDNkMmFmODBiZSIsImMiOjh9" frameborder="0" border="0" cellspacing="0" style="border-style: none; width: 90%; height: 800px; margin-top: 5px;"></iframe>

							</section>
							<section id="section-iconbox-2"><p>PowerBi Dashboard for Monthly asylum applications</p></section>
							<section id="section-iconbox-3"><p>PowerBi Dashboard for Decisions</p></section>
							<section id="section-iconbox-4"><p>PowerBi Dashboard for Unaccompanied minors</p></section>
							
						</div><!-- /content -->
					</div><!-- /tabs -->
				</section>



					<!--
						<iframe src="https://app.powerbi.com/view?r=eyJrIjoiZmVhNGVkMGQtNDc3Mi00NzRiLThjY2UtZjE2Y2VmOTAxZmVjIiwidCI6ImU1YzM3OTgxLTY2NjQtNDEzNC04YTBjLTY1NDNkMmFmODBiZSIsImMiOjh9" frameborder="0" border="0" cellspacing="0" style="border-style: none; width: 90%; height: 800px; margin-top: 5px;"></iframe>
					-->
			</div>
		
	<script src="tabs/js/cbpFWTabs.js"></script>
	<script>
		(function() {

			[].slice.call( document.querySelectorAll( '.tabs' ) ).forEach( function( el ) {
				new CBPFWTabs( el );
			});

		})();
	</script>		

	</body>
</html>