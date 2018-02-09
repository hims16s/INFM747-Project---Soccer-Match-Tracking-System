-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: sawant_soccer_db
-- ------------------------------------------------------
-- Server version	5.6.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `fixtures`
--

DROP TABLE IF EXISTS `fixtures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fixtures` (
  `FIXTURE_ID` int(5) NOT NULL AUTO_INCREMENT,
  `HOME_TEAM_ID` int(5) NOT NULL,
  `AWAY_TEAM_ID` int(5) NOT NULL,
  `FIXTURE_DATE` date NOT NULL,
  `MATCH_VENUE_ID` int(11) NOT NULL,
  PRIMARY KEY (`FIXTURE_ID`),
  KEY `fk_TEAMS_TEAM2_ID` (`HOME_TEAM_ID`),
  KEY `fk_TEAMS_TEAM3_ID` (`AWAY_TEAM_ID`),
  KEY `fk_fixtures_venues1_idx` (`MATCH_VENUE_ID`),
  CONSTRAINT `fk_TEAMS_TEAM2_ID` FOREIGN KEY (`HOME_TEAM_ID`) REFERENCES `teams` (`TEAM_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_TEAMS_TEAM3_ID` FOREIGN KEY (`AWAY_TEAM_ID`) REFERENCES `teams` (`TEAM_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_fixtures_venues1` FOREIGN KEY (`MATCH_VENUE_ID`) REFERENCES `venues` (`VENUE_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fixtures`
--

LOCK TABLES `fixtures` WRITE;
/*!40000 ALTER TABLE `fixtures` DISABLE KEYS */;
INSERT INTO `fixtures` VALUES (1,1,2,'2017-08-12',1),(2,2,3,'2017-08-19',2),(3,4,5,'2017-08-19',4),(4,5,6,'2017-08-26',5),(5,1,6,'2017-08-26',1),(6,4,2,'2017-09-02',4),(7,5,2,'2017-09-02',5),(8,4,3,'2017-09-09',4),(9,6,2,'2017-09-09',6),(10,7,8,'2017-09-16',7),(11,18,19,'2017-09-16',18),(12,20,21,'2017-09-23',20),(13,24,25,'2017-09-23',24),(14,27,28,'2017-09-30',27),(15,29,30,'2017-09-30',29),(16,10,9,'2017-10-05',10),(17,13,14,'2017-10-05',13),(18,12,13,'2017-10-12',12),(19,15,18,'2017-10-12',15),(20,18,19,'2017-10-19',18),(21,6,10,'2017-10-19',6),(22,5,3,'2017-10-19',5),(23,20,21,'2017-10-26',20),(24,21,20,'2017-11-01',21),(25,8,7,'2017-12-24',8),(26,10,11,'2017-12-24',10),(27,3,4,'2017-12-30',3),(28,1,2,'2017-12-30',1),(29,22,23,'2017-12-31',22),(30,26,25,'2017-12-31',26);
/*!40000 ALTER TABLE `fixtures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leagues`
--

DROP TABLE IF EXISTS `leagues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leagues` (
  `LEAGUE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `LEAGUE_NAME` varchar(95) NOT NULL,
  `LEAGUE_COUNTRY` varchar(45) NOT NULL,
  PRIMARY KEY (`LEAGUE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leagues`
--

LOCK TABLES `leagues` WRITE;
/*!40000 ALTER TABLE `leagues` DISABLE KEYS */;
INSERT INTO `leagues` VALUES (1,'English Premier League','England'),(2,'La Liga','Spain'),(3,'Serie A','Italy'),(4,'Ligue 1','France'),(5,'Bundesliga','Germany'),(6,'Championship','England'),(7,'Superliga','Argentina'),(8,'A-League','Australia'),(9,'MLS','USA'),(10,'I-League','India'),(11,'UEFA Champions League','Europe'),(12,'League 2','England'),(13,'Serie B','Brazil'),(14,'Ligue 1','Tunisia'),(15,'Premiership','Northern Ireland');
/*!40000 ALTER TABLE `leagues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `match_cards`
--

DROP TABLE IF EXISTS `match_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `match_cards` (
  `MATCH_CARD_ID` int(11) NOT NULL AUTO_INCREMENT,
  `FIXTURE_ID` int(5) NOT NULL,
  `PLAYER_ID` int(5) NOT NULL,
  `CARD_COLOR` tinyint(1) NOT NULL COMMENT 'TRUE (1) means red card\nFALSE (0) means yellow card',
  `CARD_MINUTE` tinyint(4) NOT NULL,
  PRIMARY KEY (`MATCH_CARD_ID`),
  KEY `fk_match_cards_fixtures1_idx` (`FIXTURE_ID`),
  KEY `fk_match_cards_players1_idx` (`PLAYER_ID`),
  CONSTRAINT `fk_match_cards_fixtures1` FOREIGN KEY (`FIXTURE_ID`) REFERENCES `fixtures` (`FIXTURE_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_match_cards_players1` FOREIGN KEY (`PLAYER_ID`) REFERENCES `players` (`PLAYER_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `match_cards`
--

LOCK TABLES `match_cards` WRITE;
/*!40000 ALTER TABLE `match_cards` DISABLE KEYS */;
INSERT INTO `match_cards` VALUES (1,1,20,0,15),(2,2,9,0,45),(3,3,5,0,22),(4,4,13,0,33),(5,5,7,0,47),(6,6,6,0,18),(7,6,9,0,89),(8,7,2,0,11),(9,8,4,0,21),(10,9,9,1,4),(11,10,29,0,55),(12,10,30,0,32),(13,6,26,0,5),(14,6,27,0,32),(15,6,28,0,89);
/*!40000 ALTER TABLE `match_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `match_goals`
--

DROP TABLE IF EXISTS `match_goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `match_goals` (
  `MATCH_EVENT_ID` int(5) NOT NULL AUTO_INCREMENT,
  `FIXTURE_ID` int(5) NOT NULL,
  `GOAL_FOR` tinyint(1) NOT NULL COMMENT 'TRUE (1) means goal for home team\nFALSE (0) means goal for away team',
  `GOAL_MINUTE` tinyint(4) NOT NULL,
  `GOAL_BY_PLAYER_ID` int(5) NOT NULL,
  `ASSIST_BY_PLAYER_ID` int(5) DEFAULT NULL,
  PRIMARY KEY (`MATCH_EVENT_ID`),
  KEY `fk_FIXTURES_FIXTURE_ID` (`FIXTURE_ID`),
  KEY `fk_match_goals_players1_idx` (`GOAL_BY_PLAYER_ID`),
  KEY `fk_match_goals_players2_idx` (`ASSIST_BY_PLAYER_ID`),
  CONSTRAINT `fk_FIXTURES_FIXTURE_ID` FOREIGN KEY (`FIXTURE_ID`) REFERENCES `fixtures` (`FIXTURE_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_match_goals_players1` FOREIGN KEY (`GOAL_BY_PLAYER_ID`) REFERENCES `players` (`PLAYER_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_match_goals_players2` FOREIGN KEY (`ASSIST_BY_PLAYER_ID`) REFERENCES `players` (`PLAYER_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `match_goals`
--

LOCK TABLES `match_goals` WRITE;
/*!40000 ALTER TABLE `match_goals` DISABLE KEYS */;
INSERT INTO `match_goals` VALUES (1,1,1,17,3,20),(2,1,0,77,6,5),(3,2,1,44,9,2),(4,2,1,87,2,9),(5,3,1,33,4,5),(6,3,1,43,4,6),(7,4,1,52,1,13),(8,4,1,58,1,13),(9,5,1,30,3,20),(10,5,0,42,7,7),(11,5,1,72,20,3),(12,6,1,12,6,5),(13,6,0,42,10,9),(14,6,0,77,2,10),(15,7,0,64,2,9),(16,8,1,45,4,5),(17,9,0,72,2,9),(18,10,1,72,14,14),(19,10,1,22,14,29),(20,10,1,35,29,30),(21,7,0,12,2,10),(22,7,0,26,10,9),(23,7,0,38,2,9),(24,8,1,10,26,27),(25,8,1,12,27,26),(26,8,1,24,28,26),(27,8,0,66,11,8),(28,8,0,88,8,11),(29,6,0,89,9,10),(30,6,0,11,10,9);
/*!40000 ALTER TABLE `match_goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `PLAYER_ID` int(5) NOT NULL AUTO_INCREMENT,
  `TEAM_ID` int(5) DEFAULT NULL,
  `FIRST_NAME` varchar(50) DEFAULT NULL,
  `LAST_NAME` varchar(50) DEFAULT NULL,
  `HEIGHT` smallint(6) DEFAULT NULL COMMENT 'In centimeters',
  `WEIGHT` smallint(6) DEFAULT NULL COMMENT 'In kilograms',
  `POSITION_ID` int(11) NOT NULL,
  PRIMARY KEY (`PLAYER_ID`),
  KEY `fk_TEAMS_TEAM1_ID` (`TEAM_ID`),
  KEY `fk_players_positions1_idx` (`POSITION_ID`),
  CONSTRAINT `fk_TEAMS_TEAM1_ID` FOREIGN KEY (`TEAM_ID`) REFERENCES `teams` (`TEAM_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_players_positions1` FOREIGN KEY (`POSITION_ID`) REFERENCES `positions` (`POSITION_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` VALUES (1,5,'Alexis','Sanchez',173,160,4),(2,2,'Eden','Hazard',173,160,4),(3,1,'Paul','Pogba',180,160,2),(4,4,'Kevin','De Bruyne',173,160,2),(5,4,'David','Silva',163,160,2),(6,4,'Sergio','Aguero',166,150,1),(7,6,'Harry','Kane',173,160,1),(8,3,'Phil','Coutinho',173,160,2),(9,2,'Alvaro','Morata',165,162,1),(10,2,'David','Luiz',168,166,16),(11,3,'Adam','Lallana',169,163,5),(12,3,'Daniel','Sturridge',168,158,1),(13,5,'Theo','Walcott',163,160,5),(14,7,'Lionel','Messi',165,158,1),(15,8,'Cristiano','Ronaldo',170,165,5),(16,8,'Luka','Modric',163,163,6),(17,3,'Toni','Kroos',168,167,6),(18,8,'Gareth','Bale',175,167,4),(19,1,'David','De Gea',171,160,17),(20,1,'Antonia','Valencia',166,164,12),(21,13,'Wilfred','Zaha',164,167,4),(22,12,'Andy','Caroll',171,168,2),(23,17,'Arjen','Robben',163,164,5),(24,17,'Frank','Ribery',166,162,4),(25,17,'Kingsley','Coman',157,160,4),(26,4,'Leroy','Sane',169,166,4),(27,4,'Raheem','Sterling',166,160,5),(28,4,'Gabriel','Jesus',166,168,2),(29,7,'Luis','Suarez',166,165,2),(30,1,'Andres','Iniesta',162,166,6);
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `positions` (
  `POSITION_ID` int(11) NOT NULL AUTO_INCREMENT,
  `POSITION_NAME` varchar(45) NOT NULL,
  PRIMARY KEY (`POSITION_ID`),
  UNIQUE KEY `POSITION_NAME` (`POSITION_NAME`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `positions`
--

LOCK TABLES `positions` WRITE;
/*!40000 ALTER TABLE `positions` DISABLE KEYS */;
INSERT INTO `positions` VALUES (2,'Centre Attacking Midfielder'),(16,'Centre Back'),(9,'Centre Defensive Midfielder'),(1,'Centre Forward'),(6,'Centre Midfielder'),(17,'Goal Keeper'),(13,'Left Back'),(15,'Left Centre Back'),(7,'Left Defensive Midfielder'),(10,'Left Midfielder'),(4,'Left Winger'),(12,'Right Back'),(14,'Right Centre Back'),(8,'Right Defensive Midfielder'),(11,'Right Midfielder'),(5,'Right Winger'),(3,'Striker');
/*!40000 ALTER TABLE `positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `TEAM_ID` int(5) NOT NULL AUTO_INCREMENT,
  `TEAM_NAME` varchar(50) DEFAULT NULL,
  `LEAGUE_ID` int(11) NOT NULL,
  `HOME_GROUND_VENUE_ID` int(11) NOT NULL,
  PRIMARY KEY (`TEAM_ID`),
  UNIQUE KEY `TEAM_NAME` (`TEAM_NAME`),
  KEY `fk_teams_leagues1_idx` (`LEAGUE_ID`),
  KEY `fk_teams_venues1_idx` (`HOME_GROUND_VENUE_ID`),
  CONSTRAINT `fk_teams_leagues1` FOREIGN KEY (`LEAGUE_ID`) REFERENCES `leagues` (`LEAGUE_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_teams_venues1` FOREIGN KEY (`HOME_GROUND_VENUE_ID`) REFERENCES `venues` (`VENUE_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (1,'Manchester United',1,1),(2,'Chelsea',1,2),(3,'Liverpool',1,3),(4,'Manchester City',1,4),(5,'Arsenal',1,5),(6,'Tottenham Hotspur',1,6),(7,'Barcelona',2,7),(8,'Real Madrid',2,8),(9,'Leicester City',1,9),(10,'Newcastle United',1,10),(11,'Everton',1,11),(12,'West Ham United',1,12),(13,'Crystal Palace',1,13),(14,'Sunderland',1,14),(15,'Middlesbrough',1,15),(16,'Espanyol',2,16),(17,'Bayern Munich',5,17),(18,'Watford F.C.',1,18),(19,'West Bromwich Albion',1,19),(20,'Fulham',1,20),(21,'Burnley',1,21),(22,'Hull City',1,22),(23,'Stoke City',1,23),(24,'Swansea City',1,24),(25,'Noriwich City',1,25),(26,'Huddersfield Town',1,26),(27,'Osasuna',2,27),(28,'Malaga',2,28),(29,'AC Milan',3,29),(30,'Inter Milan',3,30);
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venues`
--

DROP TABLE IF EXISTS `venues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venues` (
  `VENUE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `VENUE_NAME` varchar(45) NOT NULL,
  `VENUE_COUNTRY` varchar(45) NOT NULL,
  PRIMARY KEY (`VENUE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venues`
--

LOCK TABLES `venues` WRITE;
/*!40000 ALTER TABLE `venues` DISABLE KEYS */;
INSERT INTO `venues` VALUES (1,'Old Trafford','England'),(2,'Stamford Bridge','England'),(3,'Anfield','England'),(4,'Ethiad Stadium','England'),(5,'Emirates Stadium','England'),(6,'White Hart Lane','England'),(7,'Camp Nou','Spain'),(8,'Santiago Bernabeu','Spain'),(9,'King Power Stadium','England'),(10,'St James Park','England'),(11,'Goodison Park','England'),(12,'London Stadium','England'),(13,'Selhurst Park','England'),(14,'Stadium of Light','England'),(15,'Riverside Stadium','England'),(16,'RCDE Stadium','Spain'),(17,'Allianz Arena','Germany'),(18,'Vicarage Road','England'),(19,'The Hawthorns','England'),(20,'Craven Cottage','England'),(21,'Turf Moor','Spain'),(22,'KCOM Stadium','Spain'),(23,'bet365 Stadium','England'),(24,'Liberty Stadium','England'),(25,'Carrow Road','England'),(26,'Kirklees Stadium','Spain'),(27,'El Sadar','Spain'),(28,'La Rosaleda','Spain'),(29,'San Siro1','Italy'),(30,'San Siro2','Italy');
/*!40000 ALTER TABLE `venues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'sawant_soccer_db'
--

--
-- Dumping routines for database 'sawant_soccer_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-22 21:58:33
