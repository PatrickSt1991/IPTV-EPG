# IPTV-EPG
Create EPG for your own IPTV list.

Sometimes you want to create you own Electronic Program Guide (EPG), in my case my IPTV provider didn't provided me a EPG so i decided to create my own.

I use these files as a base: <a href='https://github.com/iptv-org/iptv'>EPG Guide XML</a> in my case i've taken the NL version.

Before you begin make sure to change the database setting values in _config.php.
 - Fill in the database information (host, database, username, password)

<b>How it works:</b>

Open the site and it will load index.html.
Click on the top right on the sliders and fill in the required information when done press the sliders again to go back to index.html.

1. Press the magnifing glass to scan your m3u file for channel names.
2. Press the cloud button to grep channels and programmas from the base xml.
3. Press the done button to generate the custom XML EPG file.

Because I only wanted the Dutch channels I added some filters in the script to prevent anything else comming in the file, if you want to modify it please take a look at grep_m3u.php, line 20
"if((strpos($line, 'group-title') == true) && (strpos($line, 'tvg-name="#####') == false)) {"

<B>NOTE: Fetching the programs and channelscan take a while, cause of all the data that needs to be processed, be patience, or run it via a automated cronjob</b><br/>
<B>NOTE: It takes some power to create the XML file so do use Apache2 / PHP and not NGINX / FPM-PHP.</b><br/>
