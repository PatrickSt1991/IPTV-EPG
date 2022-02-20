# IPTV-EPG
Create your own M3U file with EPG from an existing M3U file with crap in it.

My IPTV provider provided me with a M3U file with a lot of crap in it, I decided to cleanup my M3U file and create an EPG file with it, cause also the EPG wasn't present (AliExpress).

I use these files as a base: <a href='https://github.com/iptv-org/iptv'>EPG Guide XML</a> in my case i've taken the NL version.

Before you begin make sure to change the database setting values in _config.php.
 - Fill in the database information (host, username, password, database)

<b>How it works:</b>

Open the site and it will load index.html.
Click on the top right on the sliders and fill in the required information when done press the sliders again to go back to index.html.

1. Download your m3u file or upload it to your root.
2. Scan your m3u file for channel names.
3. Press the cloud button to grep channels and programmas from the base xml.
4. Press the done button to generate the custom XML EPG file.

Just look through the settings and pages, you also have the ability to put manually put a name to a channel.

<B>NOTE: Fetching the programs and channelscan take a while, cause of all the data that needs to be processed, be patience, or run it via a automated cronjob</b><br/>
<B>NOTE: It takes some power to create the XML file so do use Apache2 / PHP and not NGINX / FPM-PHP.</b><br/>

<p align="center">
  <a target="_blank" rel="noopener noreferrer" href="https://raw.githubusercontent.com/PatrickSt1991/IPTV-EPG/main/index.png"><img src="https://github.com/PatrickSt1991/IPTV-EPG/blob/main/index.png?raw=true" width="700" style="max-width:100%;"></a>
</p>
<p align="center">
  <a target="_blank" rel="noopener noreferrer" href="https://raw.githubusercontent.com/PatrickSt1991/IPTV-EPG/main/settings.png"><img src="https://github.com/PatrickSt1991/IPTV-EPG/blob/main/settings.png?raw=true" width="700" style="max-width:100%;"></a>
</p>
