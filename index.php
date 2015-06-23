<!doctype html>
<html class="no-js" lang="en">

<head>
	<meta charset="UTF-8"/>
	<meta name = "viewport" content="width=device-width, initial-scale=1.0"/>

	<link rel="stylesheet" href="">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.2/css/foundation.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="css/normalize.css"/> -->

	<script src="foundation-5.5.2/js/vendor/modernizr.js"></script>


	<title>CyberDesigns</title>
	<link rel="stylesheet" type="text/css" href="css/theme.css"/>
	<link rel="stylesheet" type="text/css" href="css/layout.css"/>

	<link rel="stylesheet" type="text/css" href="bower_components/slick.js/slick/slick.css"/>

	<!-- // Add the slick-theme.css if you want default styling -->
	<link rel="stylesheet" type="text/css" href="bower_components/slick.js/slick/slick-theme.css"/>

	<link rel="stylesheet" type="text/css" href="css/slick.css"/>
	<link rel="stylesheet" type="text/css" href="css/contactform.css"/>

	<script src='https://www.google.com/recaptcha/api.js'></script>



	<link rel="stylesheet" type="text/css" href="css/customcontact.css"/>
	<link rel="stylesheet" type="text/css" href="css/skills.css"/>


</head>

<body id="top">
	<div class="contain-to-grid sticky">

		<nav class="top-bar row" data-topbar>

			<ul class="title-area">
				<li class="name">
					<h1><a href="#top">CyberDesigns.io</a></h1>
				</li>
				<li class="toggle-topbar menu-icon">
					<a href="#"><span>Menu</span></a>
				</li>
			</ul>


			<section class ="top-bar-section">
				<ul class="left small-4 main-nav">
					<li>
						<a href="#top">About</a>
					</li>
					<li>
						<a  href="#portfolio">Portfolio</a>
					</li>
					<li>
						<a href="#contact">Contact</a>
					</li>
				</ul>

				<ul class="right small-4">
					<li><a href="https://twitter.com/_D__o__N_"> <img class="social" src="images/twitter.png" alt="Twitter Account"/></a></li>
					<li><a href="https://facebook.com/don.mclamb.16"> <img class="social" src="images/facebook.png" alt="Facebook Account"/></a></li>
					<li><a href="https://linkedin.com/mclambdon"> <img class="social" src="images/linkedin.png" alt="LinkedIn Account"/></a></li>
					<li><a href="https://github.com/dmc2015"><img class="social" src="images/github.png" alt="Github Account"/></a></li>
				</li>
			</ul>
		</section>
	</nav>
</div>




<main>
	<section class="about panel">
		<img class="pro_photo" src="images/professional_photo.jpg" alt="Professional Photo"/>
		<section class="subsection ">
			<h1 class="aboutme">About Me</h1>
			<h1 class="name">Don McLamb</h1>
			<p>
				Hey, thanks for stopping by to visit my site.
			</p>
			<p>
				I am a recent graduate of General Assembly's WDI program at their
				Washington D.C location. Prior to entering the WDI program I worked
				in software support for three years and during that time I received
				my Masters in CyberSecurity from UMUC December of 2014.

				<p>I am passionate about learning new technologies, start ups, building secure apps and apps that can create a more secure web.<p>

					<p>I intend to continue to develop my portfolio and grow my skills as a full-stack web developer through freelance and short-term project work.
						I will display my work here so be sure to check back later and<span class="inline"> follow</span> for additional updates.</p>

						<h2>Skills:</h2>
						<ul id="skills">
							<li>HTML5</p></li>
							<li>CSS3 - Foundations</p></li>
							<li>Ruby - Rails, Devise, Cancancan, Rspec, ActiveRecord</p></li>
							<li>Javascript - jQuery, Backbone, Ajax, Jasmin, JSON</p></li>
							<li>Other Technologies - Microsoft Server 2012, Postgresql, Git, AWS, Heroku</p></li>
						</ul>

					</section>
				</section>



				<section  id="portfolio" class="portfolio row">
					<h1 class="text-center portfolio-heading">Portfolio</h1>

					<ul class="slideshow small-block-grid-3">
						<li class="securitypulse project th">
							<a class="" href="http://securitypulse.herokuapp.com"> <img src="images/securitypulse_1.png"/> </a>
							<p class="text-center panel">A application designed encouraging and supporting a
								  secure web.</p>
							</li>

							<li class="policonnect project th">
								<a class="" href="http://www.policonnect.org"> <img src="images/policonnect_1.png"/></a>
								<p class="text-center panel">A group project to connect policymakers to experts in order to inform policymakers when making decisions.</p>
							</li>

							<!-- <li class="postpoll project th">
							<a class="" href="http://localhost:3000"> <img src="images/postpoll.png"/></a>
						</li> -->

						<li class="writershub project th">
							<a class="" href="https://the-writers-hub.herokuapp.com"> <img src="images/writershub_1.png"/></a>
							<p class="text-center panel">A group project to assist writers in promoting their work and allow readers an opprotunity to read short stories.</p>
						</li>

						<li class="daytracker project th">
							<a class="" href="http://daytracker.meteor.com"> <img src="images/daytracker.png"/></a>
							<p class="text-center panel">Prototype project for Meteor.js, a organizational app.</p>
						</li>

						<!-- <li class="todo project th">
							<a class="" href="https://the-writers-hub.herokuapp.com"> <img src="images/"/></a>
							<p class="text-center panel">A app mobile and web app created with Meteor.js that allows one to track their daily tasks.</p>
						</li> -->

					</ul>
				</section>
				<div id="contact" class="contact-form default-contact">

					<h1 class="text-center contact-padding">Contact Me:</h1>

					<!-- <p class="panel text-center email-contact">
						<a href="mailto:mclamb.donald@gmail.com">Email Me</a>
					</p> -->

					<?php include("custom_form.php"); ?>
					<!-- <div class="g-recaptcha" data-sitekey="6LcJdAgTAAAAAFNtfMQDBij8f1N6k8nCPk24ENv6"></div> -->

				</div>


			</main>


			<footer>
			</footer>



			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
			<script src="foundation-5.5.2/js/foundation.min.js"></script>

			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js"></script>

			<script>
			$(document).foundation();
			</script>

			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.5/slick.min.js"></script>
			<!-- Initializing slick -->
			<script type="text/javascript">
			$(document).ready(function(){
				$('.slideshow').slick({
					dots: true,
					cssEase: 'linear',
					infinite: true,
					speed: 300,
					slidesToShow: 1,
					centerMode: true,
					centerPadding: '40px',
					arrows: true,
					variableWidth: true
				});
			});
			</script>

			<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-64206324-1', 'auto');
			ga('send', 'pageview');

			</script>


			<script type="text/javascript" src="jquery_radiobutton.js"</script>




			</body>
			</html>
