# Masterdinger

Masterdinger is an online platform which allows Summoners to see their detailed information about their Mastery Points.
It also uses that data to compare them to other Summoners from all around the world in a Leaderboard.
A Summoner may also choose to compare himself to another Summoner.
</br>
In this documentation we will explain how it works and why it excels at doing its job.
</br>
It is an Entry for Riot's API Challenge 2016 for the category of Usability/Practicality because we believe it has potential to be useful for Leage of Legend's community.
</br>
It supports all of the public League Of Legend's Regions (except PBE, due to the lack of API).

##Installation
######For server side only

Masterdinger.com is going to be running on a Debian 8 Server with PHP 7.0 and Apache 2.4 .
These are the packages needed:
</br>
> php7.0 php7.0-mysql php7.0-curl php7.0-json apache2 php7.0-imagick

PHP 7.0 is not yet available in Debian Jessie's repositories, so I added this one:
> deb http://packages.dotdeb.org jessie all

####Instructions
######Install the packages
1. Enter root shell </br> $ sudo su
2. Add the repository </br> # echo "http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list
3. Get the repository key </br> # wget https://www.dotdeb.org/dotdeb.gpg; apt-key add dotdeb.gpg
4. Update the repositories list </br> # apt-get update
5. Install the packages </br> # apt-get install php7.0 php7.0-mysql php7.0-curl php7.0-json apache2 php7.0-imagick
6. Enable PHP 7.0 </br> # a2enmod php7.0
7. Restart Apache server </br> # service apache2 restart
* Recommended but you can use another tool: Install PHPMyAdmin, you can follow this tutorial https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-phpmyadmin-on-debian-7

######Configuration
8. Go to /var/www </br> # cd /var/www
9. Clone Masterdinger's repository </br> # git clone https://github.com/jgilfn/Masterdinger.git
10. Configure Apache, edit /etc/apache2/ports.conf </br> # nano /etc/apache2/ports.conf </br> Add this to the bottom of the file (adapt for your needs): </br>
~~~
# Masterdinger
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
                ServerName masterdinger.com # Replace this domain by yours
        DocumentRoot /var/www/Masterdinger
        <Directory /var/www/Masterdinger/>
                Options Indexes FollowSymLinks MultiViews
                Require all granted
                AllowOverride All
        </Directory>
        <Directory /var/www/Masterdinger/admin/>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride all
                Require ip 80.255.11.222 # Replace with your IP, so only you can access this folder
        </Directory>
</VirtualHost>
~~~
11. Restart Apache server </br> # service apache2 restart

######DB Configuration - PHPMyAdmin
12. Create a new DB
13. Import the LoL.sql file in this repository
12. Edit the "vars.php" file and add your API key and your DB User/Password

######Notes
The Leaderboard will be automatically generated. It will add and update rows each time a Summoner is searched.
</br>
However you can regenerate it by visiting admin/leaderboard_generate.php - Warning: this may take a lot of time because of the API restriction of 10 requests per second.
</br>
</br>
##Search

In the search page, the Summoner can see how many points does each of his champions own.
Every champion has its own - as we like to call it - 'Champion Card'.
</br>
<center>![Champion Card](/garen.png)</center>
</br>

######Search Options
Between the User information and the Champion Cards, you can see a bar where you can:
* Search for a Champion
* Sort Cards by:
*       > Highest Score
*       > Lowest Score
*       > Last Played
*       > Not Played for longest time (inverted Last Played)
*       > Highest Highest Grade
*       > Lowest Highest Grade
*       > Alphabetically (A-Z)
*       > Alphabetically (Z-A)
* Only show cards which Champions did not receive a chest yet.
* Compare with another Summoner
</br>
<center>![Search Options](/searchingOptions.png)</center>
</br>

####Champion Card

###### Level
At first glance, you can see the Champion's level in the border:

* Level 1 - White gradient
* Level 2 - White gradient
* Level 3 - Bronze gradient
* Level 4 - Silver Gradient
* Level 5 - Gold Gradient

</br>
These borders also have the respective level's icon at the bottom.
</br>

###### Champion Title
Below the champion's name there is his title.
</br>

###### Highest Grade
You may see what is your highest grade with that champion in the left bottom corner of the card.
</br>

###### Chests
On the right bottom corner, you can see if you have already received a chest with that champion. If the icon is not black and white, it means that you have already received a chest with that champion.
</br>

### Hover
![Champion Card](/garen_hover.png)
</br>
If you hover the Champion's icon, you may see some extra information such as:

###### Level Title
Depending on your Champion's main Role and its Mastery Level, you will get a different Level Title.
</br>

###### Mastery Points
Right below the level title, you may see how many Mastery Points that champion has in a human-readable form.
</br>
If you want to see all the points, then you may hover the '+' sign and a tooltip will show you.
</br>

###### Level Progress
If you take a close look, you may see a line around your Champion's icon border which represents a Chart based on how many Mastery Points it has in the current level and how many Mastery Points it misses to reach the next next level.
</br>
Right below the Mastery Points, there is how many points that champion has in the current level and how many points the level has.
</br>
If you want to get even more information, you can hover the '+' button and it will show how many points it is missing to reach next level.

###### When was this champion last played?
For the curious ones, you may also see when was the last match you played with that champion.

</br>

##Leaderboard

In the Leaderboard you can check who has the Highest Score from the **users who already searched their name**.

There are mainly 3 tables:
* Total Points
* Champion Points
* Per Champion Points

<center>![Leaderboard](/leaderboard.png)</center>


######Total Points
Sums the points of every champion from each Summoner and orders the results by highest.
</br>
Default rows displayed is 10. When you click see more it shows 100 sorted in pages of 25.
<center>![Total Points](/totalPoints.png)</center>


######Champion Points
Shows the champions with highest points from all of the Summoners.
</br>
Default rows displayed is 10. When you click see more it shows 100 sorted in pages of 25.
<center>![Champion Points](/championPoints.png)</center>

######Per Champion Points
Creates a table for every Champion and shows the Summoners who have the highest points.
</br>
Default rows displayed is 5. When you click see more it shows 100 sorted in pages of 25.
When you click see more, it will also show different statistics for each champions !WIP!
<center>![Per Champion Points](/perChampionPoints.png)</center>

######Top Header
When you click "See More" in any of these tables, a bigger table will be shown with a table highlighting the 3 top Summoners in three different circles displayed with a different size/border/position depending on their position.
</br>
When you hover the circle, it will show how many points that Summoner's champion has.
</br>
<center>![Leaderboard Top Header](/leaderboardHeader.png)</center>

##Compare

###Inside the Code

##Lore

###404

# Warning
This project was not yet optimized to the Level 6 and Level 7 Update because by the time the challenge started, it was not yet announced and by the time the challenge ends, the API is not going to be publicly available.
After the challenge we will be working on that new update.
