# IPTV-EPG
Create EPG for your own IPTV list.

Sometimes you want to create you own Electronic Program Guide (EPG), in my case my IPTV provider didn't provided me a EPG so i decided to create my own.

I use these files as a base: <a href='https://github.com/iptv-org/iptv'>EPG Guide XML</a> in my case I've taken the NL version.

That XML file is being fed in to fetch_epg.php that puts al the information in to a database for processing.
In the generate_epg.php all the info thats stored in the DB is being put into a custom XML file that matches my M3U file.

It takes some power to create the XML file so do use Apache2 / PHP and not NGINX / FPM-PHP.
