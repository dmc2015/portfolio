# portfolio

home page
portfolio page
about
contact


get arrows to show up bigger and with better color
get links to show up responsiviely
get rid of postpoll
add panels and short descriptions to every project
modify color of panel with a gradient that fits background color
add pading to carousel, i don't like the projects touching
fix extra right margin


google analytics
blog

---------------

Host IPHERE
  HostName IPHERE
  User root
	IdentityFile ~/.ssh/keypair.pem





chmod 400 keypair.pem




sudo apt-get update

sudo apt-get install apache2 php5


cd /var/www


sudo chown ubuntu:ubuntu /var/www/html


<?php phpinfo(); ?>

--------------

OVERFLOW WEBBOX ISSSUE

hi have you worked with flexbox issues before?
an hour ago

I have worked with some
28 minutes ago

do you have time to assist?
28 minutes ago

easiest fixes usually involve falling back to "display:table, table-row, table-cell" so that it degrades to something managable
27 minutes ago

to you have it published somewhere right now?
27 minutes ago

http://52.26.214.120
26 minutes ago

there is extra margin on the right
26 minutes ago

in what browser?
24 minutes ago

chrome or firefox
23 minutes ago

at the contact form area?
23 minutes ago

if you scroll the screen to the right there is white space on any section
22 minutes ago

remove "overflow: hidden;" form ".slick-list"
13 minutes ago

http://52.26.214.120/bower_components/slick.js/slick/slick.css
12 minutes ago

line 22
12 minutes ago

I tried that, it allows me to scroll to the right without stopping.
8 minutes ago

overflow: hidden will force certain box model elements to expand to fit their contents. To counter that you usually set explicit heights/widths or position it with relative/absolute/fixed and set top/left/bottom/right to keep is from becoming larger than its container element
8 minutes ago

ok then go the other route
6 minutes ago

.portfolio {
   background-image: url("../images/bg-2-full.jpg");
  position: relative;
  max-width: 100%;
  overflow: hidden;
}
6 minutes ago

http://52.26.214.120/css/theme.css
6 minutes ago

line 94
6 minutes ago

that worked
3 minutes ago

how did you know it was an issue in the portfolio?
3 minutes ago

looked for the element that was wider than the body element
2 minutes ago

thanks
a minute ago

and the portfolio element was smaller than the slider width-wise so forcing the portfolio element to hide overflow made it stop breaking out of the box model
a minute ago


------------------------

<section ="2"
<h1>About Me</h1>

<h1>Don McLamb</h1>
<p>
Hey, thanks for stopping by to visit my site.
</p>

<p>
I am a recent graduate of General Assembly's WDI program at their
Washington D.C location. Prior to entering the WDI program I worked
in software support for three years and during that time I received
my Masters in CyberSecurity from UMUC December of 2014.

I am passionate about learning new technologies, start ups, building secure apps and apps that can create a more secure web.
<p>

<p><h2>Why I made <h1>Cyberdesigns.io</h1> ?</h2></p>

Cyberdesigns.io was created so that I could continue to develop
and grow my skills as a full-stack web developer through freelance and short-term project work. I have worked in the typical office setting
for sometime now and I am looking to try something different

I intend to continue to develop my portfolio and display my work here
so be sure to check back later and <p id=follow> follow</p> for additional updates.
</section>
