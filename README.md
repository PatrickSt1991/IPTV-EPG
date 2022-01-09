# IPTV-EPG
Create EPG for your own IPTV list.

Sometimes you want to create you own Electronic Program Guide (EPG), in my case my IPTV provider didn't provided me a EPG so i decided to create my own.

I use these files as a base: <a href='https://github.com/iptv-org/iptv'>EPG Guide XML</a> in my case I've taken the NL version.

Before you begin make sure to change the setting values in _config.php.
 - Fill in the database information (host, database, username, password)
 - $m3u_file: Change this to you own m3u file. (m3u file needs to be placed in the same directory as the php script)
 - $CustomEpgFile: The file that will be created, change to you liking
 - $epg_url: The XML EPG Guide that we will be using as a base to create our own XML.

<h3>How it works:</h3>
That XML file is being fed in to fetch_epg.php that puts al the information in to a database for processing.
In the generate_epg.php all the info thats stored in the DB is being put into a custom XML file that matches my M3U file.

It takes some power to create the XML file so do use Apache2 / PHP and not NGINX / FPM-PHP.
