<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        ide.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/
?>

<!DOCTYPE html>
<!-- Template by quackit.com -->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>2 Rows, 2 Columns, G</title>
		<style type="text/css">
		
		body {
			margin: 0;
			padding: 0;
			overflow: hidden;
			height: 100%; 
			max-height: 100%; 
			font-family:Sans-serif;
			line-height: 1.5em;
		}
		
		main {
			position: fixed;
			top: 0;
			bottom: 100px; /* Set this to the height of the footer */
			right: 230px; /* Set this to the width of the nav bar */
			overflow: auto; 
			background: #fff;
		}
				
		#footer {
			position: absolute;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 100px; 
			overflow: hidden; /* Disables scrollbars on the header frame. To enable scrollbars, change "hidden" to "scroll" */
			background: #BCCE98;
		}
		
		#nav {
			position: absolute; 
			top: 0; 
			right: 0; 
			bottom: 100px;
			width: 230px;
			overflow: auto; /* Scrollbars will appear on this frame only when there's enough content to require scrolling. To disable scrollbars, change to "hidden", or use "scroll" to enable permanent scrollbars */
			background: #DAE9BC; 		
		}
		
		.innertube {
			margin: 15px; /* Provides padding for the content */
		}
		
		p {
			color: #555;
		}

		nav ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
		}
		
		nav ul a {
			color: darkgreen;
			text-decoration: none;
		}
				
		/*IE6 fix*/
		* html body{
			padding: 0 230px 100px 0; /* Set the second value to the width of the right column and the third value to the height of the footer */
		}
		
		* html main{ 
			height: 100%; 
			width: 100%; 
		}
		
		</style>
		
		<script type="text/javascript">
			/* =============================
			This script generates sample text for the body content. 
			You can remove this script and any reference to it. 
			 ============================= */
			var bodyText=["Remember, you are unique, just like everybody else.", "Too much agreement kills a good chat.", "Get your facts first, then you can distort them as you please.", "I intend to live forever. So far, so good.", "</p><p>A clear conscience is usually a sign of a bad memory.", "What's another word for Thesaurus?", "<h3>Heading</h3><p>Experience is something you don't get until just after you need it."]
			function generateText(sentenceCount){
				for (var i=0; i<sentenceCount; i++)
				document.write(bodyText[Math.floor(Math.random()*7)]+" ")
			}
		</script>		
	
	</head>
	
	<body>
				
		<main>
			<div class="innertube">
				
				<h1>Heading</h1>
				<p><script>generateText(300)</script></p>
				
			</div>
		</main>

		<nav id="nav">
			<div class="innertube">
				<h1>Heading</h1>
				<ul>
					<li><a href="#">Link 1</a></li>
					<li><a href="#">Link 2</a></li>
					<li><a href="#">Link 3</a></li>
					<li><a href="#">Link 4</a></li>
					<li><a href="#">Link 5</a></li>
				</ul>
				<h1>Heading</h1>
				<ul>
					<li><a href="#">Link 1</a></li>
					<li><a href="#">Link 2</a></li>
					<li><a href="#">Link 3</a></li>
					<li><a href="#">Link 4</a></li>
					<li><a href="#">Link 5</a></li>
				</ul>
			</div>
		</nav>	

		<footer id="footer">
			<div class="innertube">
				<p>Footer...</p>
			</div>
		</footer>
		
	</body>
</html>
