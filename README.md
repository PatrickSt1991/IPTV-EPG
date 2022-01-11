# IPTV-EPG
Create EPG for your own IPTV list.

Sometimes you want to create you own Electronic Program Guide (EPG), in my case my IPTV provider didn't provided me a EPG so i decided to create my own.

I use these files as a base: <a href='https://github.com/iptv-org/iptv'>EPG Guide XML</a> in my case i've taken the NL version.

Before you begin make sure to change the setting values in _config.php.
 - Fill in the database information (host, database, username, password)
 - $m3u_file: Change this to you own m3u file. (m3u file needs to be placed in the same directory as the php script)
 - $CustomEpgFile: The file that will be created, change to you liking
 - $epg_url: The XML EPG Guide that we will be using as a base to create our own XML.

<b>How it works:</b>
Before you begin make sure to update _config.php.
1. Run grep_m3u.php, this file wil fetch all the channels names from the m3u file <u>Pleas note line 20, I only wanted the Dutch channels so I added an extra channel check if you want all the channels replace line 20 with this "if((strpos($line, 'group-title') == true) && (strpos($line, 'tvg-name="#####') == false)) {"

2. Run fetch_epg.php, this file will fetch all the channels and program information from the base XML file, tries to match it with the values from the m3u files and puts it in to the database.
 <B>NOTE: Fetching the programs and channelscan take a while, cause of all the data that needs to be processed, be patience, or run it via a automated cronjob</b>
 
3. Run generate_epg.php, this file will generate the XML file with the channels en programmes with the syntax as it's in the m3u file. <br/>
  <B>NOTE: It takes some power to create the XML file so do use Apache2 / PHP and not NGINX / FPM-PHP.</b><br/>
