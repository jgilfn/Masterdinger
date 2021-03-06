# Masterdinger
## http://masterdinger.com

Masterdinger is an online platform which allows Summoners to see their detailed information about their Mastery Points.
It also uses that data to compare them to other Summoners from all around the world in a Leaderboard.
A Summoner may also choose to compare himself to another Summoner.
</br>
In this documentation we will explain how it works and why it excels at doing its job.
</br>
It is an Entry for Riot's API Challenge 2016 for the category of Usability/Practicality because we believe it has potential to be useful for Leage of Legend's community.
</br>
It supports all of the public League Of Legend's Regions (except PBE, due to the lack of API).
</br>
We plan to keep this platform online forever, so the whole community may interact with it, as we receive feedback and keep developing new features.
</br>

##Index
* [Installation] (#installation)
  * [Instructions] (#instructions)
    * [Install the packages] (#install-the-packages)
    * [Configuration] (#configuration)
    * [DB Configuration] (#db-configuration---phpmyadmin)
      * [DB Structure] (#db-structure) 
    * [Notes] (#notes)
 
* [Homepage] (#homepage)

* [Search] (#search)
  * [Search Options] (#search-options)
  * [Champion Card] (#champion-card)
    * [Level] (#level)
    * [Champion Title] (#champion-title)
    * [Highest Grade] (#highest-grade)
    * [Chests] (#chests)
    * [Hover] (#hover)
      * [Level Title] (#level-title)
      * [Mastery Points] (#mastery-points)
      * [Level Progress] (#level-progress)
      * [Last Played] (#last-played)
 
* [Leaderboard] (#leaderboard)
  * [Total Points] (#total-points)
  * [Champion Points] (#champion-points)
    * [Global Statistics] (#global-statistics)
  * [Per Champion Points] (#per-champion-points)
    * [Champion Statistics] (#champion-statistics)
  * [Top Header] (#top-header)
 
* [Compare] (#compare)

* Extra
  *  [Easter Eggs] (#easter-eggs)
  *  [Inside the Code] (#inside-the-code)
  *  [Challenges Faced] (#challenges-faced)
    * Search Page
      * [Design] (#search-page-design)
      * [System] (#search-page-system)
    * [Leaderboard System] (#leaderboard-system)
    * [English Language] (#english-language)
 * [What's next?] (#whats-next)


##Installation
######For server side only

Masterdinger.com is running on a Debian 8 Server with PHP 7.0 and Apache 2.4 .
These are the packages needed:
</br>
> php7.0 php7.0-mysql php7.0-curl php7.0-json php7.0-imagick apache2

PHP 7.0 is not yet available in Debian Jessie's repositories, so I added this one:
> deb http://packages.dotdeb.org jessie all

####Instructions
#####Install the packages
1. Enter root shell </br> $ sudo su
2. Add the repository </br> # echo "http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list
3. Get the repository key </br> # wget https://www.dotdeb.org/dotdeb.gpg; apt-key add dotdeb.gpg
4. Update the repositories list </br> # apt-get update
5. Install the packages </br> # apt-get install php7.0 php7.0-mysql php7.0-curl php7.0-json apache2 php7.0-imagick
6. Enable PHP 7.0 </br> # a2enmod php7.0
7. Restart Apache server </br> # service apache2 restart
* Recommended but you can use another tool: Install PHPMyAdmin, you can follow this tutorial https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-phpmyadmin-on-debian-7

#####Configuration
8. Go to /var/www </br> # cd /var/www
9. Clone Masterdinger's repository </br> # git clone https://github.com/jgilfn/Masterdinger.git
10. Configure Apache, edit /etc/apache2/ports.conf </br> # nano /etc/apache2/ports.conf </br> Add this to the bottom of the file (adapt for your needs, edit the domain and the admin ip): </br>
~~~
# Masterdinger
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
                ServerName <insert your domain here>
        DocumentRoot /var/www/Masterdinger
        <Directory /var/www/Masterdinger/>
                Options Indexes FollowSymLinks MultiViews
                Require all granted
                AllowOverride All
        </Directory>
        <Directory /var/www/Masterdinger/admin/>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride all
                Require ip <insert admin IP here>
        </Directory>
</VirtualHost>
~~~
11. Restart Apache server </br> # service apache2 restart

#####DB Configuration - PHPMyAdmin
12. Create a new DB
13. Import the /db/db-structure.sql file in this repository
14. Edit the "vars.php" file and add your API key and your DB User/Password/Name

</br>
You can see the database structure in the /db folder or in the pictures below.

######DB Structure
######Summoners Table
<center>![Summoners Table](/Documentation/DBSummoners.png)</center>
######Champions Table
<center>![Champions Table](/Documentation/DBChampions.png)</center>
######Leaderboard Table
<center>![Leaderboard Table](/Documentation/DBLeaderboard.png)</center>
######Versions Table
<center>![Versions Table](/Documentation/DBVersions.png)</center>

######Notes
The Leaderboard will be automatically generated. It will add and update rows each time a Summoner is searched.
</br>
However you can regenerate it by visiting admin/leaderboard_generate.php - Warning: this may take a lot of time because of the API restriction of 10 requests per second.
</br>
</br>

##Homepage
The Homepage contains a Search bar which allows you to search for a Summoner's Mastery information.
</br>
It has also a brief description of Masterdinger's main features:
* Search
* Compare
* Leaderboard
</br>
A random background is shown every time you load a page.

##Search

In the search page, the Summoner can see how many points does each of his champions own.
Every champion has its own - as we like to call it - 'Champion Card'.
</br>
<center>![Champion Card](/Documentation/championCard.png)</center>
</br>

######Search Options
Between the User information and the Champion Cards, you can see a bar that allows you to:
* Search for a Champion
* Sort Cards by:
*       --> Highest Score
*       --> Lowest Score
*       --> Last Played
*       --> Not Played for longest time (inverted Last Played)
*       --> Highest Highest Grade
*       --> Lowest Highest Grade
*       --> Alphabetically (A-Z)
*       --> Alphabetically (Z-A)
* Only show cards which Champions did not receive a chest yet.
* Compare to another Summoner
</br>
<center>![Search Options](/Documentation/searchingOptions.png)</center>
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
On the right bottom corner, you can see if you have already received a chest with that champion. If the icon is not black and white, it means that you have already received a chest with that champion. You can also hover this icon and it will tell you 'Received' or 'Not Received'.
</br>

### Hover
![Champion Card](/Documentation/championCardHovered.png)
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

###### Last Played
For the curious ones, you may also see when was the last match you played with that champion.

</br>

##Leaderboard

In the Leaderboard you can check who has the Highest Score from the **users who already searched their name**.

There are mainly 3 tables:
* Total Points
* Champion Points
* Per Champion Points

<center>![Leaderboard](/Documentation/leaderboard.png)</center>


####Total Points
Sums the points of every champion from each Summoner and orders the results by highest.
</br>
Default rows displayed is 10. When you click 'See More' it shows up to 100 sorted in pages of 25.
<center>![Total Points](/Documentation/totalPoints.png)</center>

####Champion Points
Shows the champions with highest points from all of the Summoners.
</br>
Default rows displayed is 10. When you click 'See More' it shows up to 100 sorted in pages of 25.
<center>![Champion Points](/Documentation/championPoints.png)</center>

######Global Statistics
When you click the 'See More' button inside the Champion Points table, it will not only show you a bigger table but also a table with Global Statistics.
</br>
In there, you can get a view of:
* Average Highest Grade - Sum of all Highest Grades from all Champions from all Summoners divided by the number of Total Champions played by Summoners. (We gave each possible grade a number, in order to be able to divide it)
* Average Played Champions - Sum of the number of Champions each Summoner has divided by the number of Total Summoners
* Average Level - Sum of all levels from all Champions from all Summoners divided by the number of Total Champions played by Summoners.
* Average Score - Sum of all Champion Points from all Summoners divided by the number of Total Champions played by Summoners.
* Percentage of Highest Grade - Pie chart containing the percentages of each Highest Grades.
* Chest Granted - Number of Champions who have granted a chest dividing by the Number of total Champions.
* Percentage of Levels - Pie chart containing the percentage of each Level.
</br>
<center>![Leaderboard Top Header](/Documentation/globalStats.png)</center>

####Per Champion Points
Creates a table for every Champion and shows the Summoners who have the highest points.
</br>
Default rows displayed is 5. When you click 'See More' it shows up to 100 sorted in pages of 25.
When you hover the table and click 'See More', it will also show different statistics for each champions
<center>![Per Champion Points](/Documentation/perChampionPoints.png)</center>

######Champion Statistics
When you click the 'See More' button inside a Champion Table, it will not only show you a bigger table but also a table with Global Statistics related with that Champion.
</br>
In there, you can get a view of:
* Average Highest Grade - Sum of all Highest Grades for that Champion divided by the number of Total Summoners who have a Highest Grade with that Champion. (We gave each possible grade a number, in order to be able to divide it)
* Played Percentage - Number of Summoners who played the Champion divided by the number of Total Summoners
* Average Level - Sum of all levels divided by the number of Total Summoners who played that Champion.
* Average Score - Sum of all Champion Points divided by the number of Total Summoners who own that Champion.
* Percentage of Highest Grade - Pie chart containing the percentages of each Highest Grades.
* Chest Granted - Number of Summoners whose Champion has granted a chest dividing by Number of total Summoners who played that Champion.
* Percentage of Levels - Pie chart containing the percentage of each Level.
</br>
<center>![Leaderboard Top Header](/Documentation/championStats.png)</center>


######Top Header
When you click "See More" in any of these tables, a bigger table will be shown with a table highlighting the 3 top Summoners in three different circles displayed with a different size/border/position depending on their position.
</br>
When you hover the circle, it will show how many points that Summoner's champion has.
</br>
<center>![Leaderboard Top Header](/Documentation/leaderboardHeader.png)</center>


##Compare
In this page, which can be accessed from the Search page, you can compare a Summoner to another one, which gives you this information per Champion:
* Points - Enabled by Default
* Highest Grade - Enabled by Default
* Level - Disabled by Default
* Chest Received - Disabled by Default
</br>
<center>![Compare](/Documentation/compare.png)</center>
</br>
You can also enable the disabled columns:
</br>
<center>![Compare Options](/Documentation/compareOptions.png)</center>

###Easter Eggs
* Try to visit an inexistent page and see the 404 error page. Keep refreshing that page.
* Try to search for an inexistent Summoner.

###Inside the Code
When you search for a Summoner, it will check if it is already stored in the Database.
</br>
If it is, and if it was last updated less than an hour ago, then it will use that (it doesn't need to do any request to the API).
</br>
If it is not, or if it was last updated more than an hour ago, it will only request the summoner and mastery API.
</br>
If the Champions table in the Database does not have the information about a Champion ID, then it will request to the Champion API and store it.
</br>
</br>
Every time you search for a Summoner, it will generate a list of Champions. When it does that, it will check if that champion for that summoner in that region is saved in the Database.
</br>
If it is, then it will update the row's points. If it is not, then it will create a new row.
</br>
Masterdinger also requests the current LoL version for each Region once an hour and saves it in the DB.
</br>

###### Warning
This project was not yet optimized to the Level 6 and Level 7 Update because by the time the challenge started, it was not yet announced and by the time the challenge ends, the API is not going to be publicly available.
After the challenge we will be working on that new update.

###Challenges faced
As beginners to the world of PHP and MySQL, we found some problems in our way, here are some:

######Search Page Design
Designing the Search page was one of the first and critical challenges and it took some time to find a solution.
</br>
We wanted to find an easy way to keep all information without showing it all at once.
</br>
Then we came with the idea of having a card for each Champion, and that with a little bit of JQuery and CSS we could display the data only when the user wants to see it - by hovering the champion's icon. In that way we could keep the page's look simple and still provide all of the information the user can want.
</br>
</br>
######Search System
We used the magical powers of PHP in order to be able to use the same code on many pages.
</br>
'summoner.php' is a file containing a class that searches, stores and displays data about a Summoner.
</br>
'champion.php' is a file containing a class that searches, stores and displays data about a Champion.
</br>
With this code, which was initially designed for the Search System, we were able to build the Compare and Leaderboard on top of it, making it easier (but not easy at all) because the base is universal.
</br>
It is also easier for fixing bugs, because if you fix it there, you fix it in every other page that uses that class.
</br>
######Leaderboard System
To make the Leaderboard system, we initially came with the idea of basing it on the Summoners MySql table.
</br>
It was fast enough to sum all of a player's points and make tables for every champion for 50 summoners.
</br>
But then we decided to do a stress test with 2000 summoners and we realised it took 10 minutes to proccess that data...
</br>
So we had to design a new system, and then we made a new database table designed only for the leaderboard.
</br>
Yes, it is still dependent on the Summoner and Champions table, but only for minor tasks, like retrieving a name of a Champion when you have its ID.
</br>
To give you an idea of how much it improved, to generate a leaderboard for 50 summoners in the old way, it took aproximately 5 seconds and in the new way, it took 0,015 seconds.
</br>
The first way was mostly based on PHP processing the raw MySQL data and the new way is based on tiny PHP processing and huge MySQL processing the data. MySQL is much better than PHP in processing millions of rows and ordering them (not a surprise).
</br>
</br>
######English Language
It's our first Documentation page, and English is not our native language.
</br>
It's not easy to express ourselves in the right way when you have so little experience of the context you're talking about.
</br>
We used pictures to complement the text because, like they say, "a picture is worth a thousand words".
</br>
If you have any doubts please check the code, most of it is commented and the other part is not hard to understand.
</br>
</br>
##What's Next?
Riot API Challenge 2016 may have been the beginning of this project but it is certainly not the end.
</br>
Like our friend Heimerdinger says: "Every problem solved is another revealed.", there are always things to improve. There is no such way one can reach perfection, especially in programming.
</br>
As mentioned before, we will implement the Level 6 and Level 7 features when it is released (and after the challenge ends).
</br>
We will also need to make the website more mobile-friendly and optimize the whole MySQL/PHP System (again).
</br>
Maybe we can also implement other APIs apart from the Champion Mastery/Summoner/Champion?
</br>
Let's see where time will take Masterdinger!
