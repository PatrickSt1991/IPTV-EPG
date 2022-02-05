# IPTV-EPG
Create your own M3U file with EPG from existing M3U file with crap in it.

My IPTV provider provided me with a M3U file with a lot of crap in it, I decided to cleanup my M3U file and create an EPG file with it, cause also the EPG wasn't present (AliExpress).

I use these files as a base: <a href='https://github.com/iptv-org/iptv'>EPG Guide XML</a> in my case i've taken the NL version.

Before you begin make sure to change the database setting values in _config.php.
 - Fill in the database information (host, username, password, database)

<b>How it works:</b>

Open the site and it will load index.html.
Click on the top right on the sliders and fill in the required information when done press the sliders again to go back to index.html.

1. Scan your m3u file for channel names and if you want create a new one or use original.
2. Press the cloud button to grep channels and programmas from the base xml.
3. Press the done button to generate the custom XML EPG file.

<B>NOTE: Fetching the programs and channelscan take a while, cause of all the data that needs to be processed, be patience, or run it via a automated cronjob</b><br/>
<B>NOTE: It takes some power to create the XML file so do use Apache2 / PHP and not NGINX / FPM-PHP.</b><br/>

<B><u>ToDo</u></b>
Create setting to either use original M3U or use modified file.
