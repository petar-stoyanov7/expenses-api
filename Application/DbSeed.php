<?php
use Core\DbModelAbstract;

class DbSeed extends DbModelAbstract
{
    public function setupDb()
    {
        $sql = <<<___SQL
            CREATE TABLE IF NOT EXISTS `Users` (
              `ID` int(11) NOT NULL AUTO_INCREMENT,
              `Username` varchar(50) DEFAULT NULL,
              `Password` varchar(120) DEFAULT NULL,
              `Group` varchar(50) DEFAULT NULL,
              `Email` varchar(100) DEFAULT NULL,
              `Fname` varchar(120) DEFAULT NULL,
              `Lname` varchar(120) DEFAULT NULL,
              `City` varchar(50) DEFAULT NULL,
              `Sex` varchar(10) DEFAULT NULL,
              `Notes` mediumtext,
              PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
___SQL;
        $this->execute($sql);

        $sql = <<<___SQL
            CREATE TABLE `Expense_Types` (
              `ID` int(11) NOT NULL AUTO_INCREMENT,
              `Name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
              PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;
___SQL;
        $this->execute($sql);

        $sql = <<<___SQL
            INSERT INTO `Expense_Types` 
            VALUES 
            (1,'Fuel'),
            (2,'Insurance'),
            (3,'Maintenance'),
            (4,'Tax'),
            (5,'Parts'),
            (6,'Fine'),
            (999,'Other');
___SQL;
        $this->execute($sql);



        $sql = <<<___SQL
            CREATE TABLE IF NOT EXISTS `Cars` (
              `ID` int(11) NOT NULL AUTO_INCREMENT,
              `UID` int(11) DEFAULT NULL COMMENT 'User ID',
              `Brand` varchar(50) DEFAULT NULL,
              `Model` varchar(50) DEFAULT NULL,
              `Year` varchar(5) DEFAULT NULL,
              `Color` varchar(50) DEFAULT NULL,
              `Mileage` int(11) DEFAULT NULL,
              `Fuel_ID` int(11) DEFAULT NULL,
              `Fuel_ID2` int(11) DEFAULT NULL,
              `Notes` mediumtext,
              PRIMARY KEY (`ID`),
              KEY `Fuel_ID` (`Fuel_ID`),
              KEY `Fuel_ID2` (`Fuel_ID2`),
              CONSTRAINT `Cars_ibfk_1` FOREIGN KEY (`Fuel_ID`) REFERENCES `Fuel_Types` (`ID`),
              CONSTRAINT `Cars_ibfk_2` FOREIGN KEY (`Fuel_ID2`) REFERENCES `Fuel_Types` (`ID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
___SQL;
        $this->execute($sql);

        $sql = <<<___SQL
            CREATE TABLE IF NOT EXISTS `Parts` (
              `ID` int(11) NOT NULL AUTO_INCREMENT,
              `UID` int(11) NOT NULL COMMENT 'User ID',
              `CID` int(11) NOT NULL COMMENT 'Car ID',
              `EID` int(11) NOT NULL COMMENT 'Expense ID',
              `Mileage` int(11) NOT NULL,
              `Date` date NOT NULL,
              `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Name of the part',
              PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
___SQL;
        $this->execute($sql);

        $sql = <<<___SQL
            CREATE TABLE IF NOT EXISTS `Fuel_Types` (
              `ID` int(11) NOT NULL AUTO_INCREMENT,
              `Name` varchar(50) DEFAULT NULL,
              PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
___SQL;
        $this->execute($sql);

        $sql = <<<___SQL
            INSERT INTO `Fuel_Types`
             VALUES 
             (1,'Gasoline'),
             (2,'Diesel'),
             (3,'LPG'),
             (4,'Methane'),
             (5,'Electricity'),
             (6,'Other');             
___SQL;
        $this->execute($sql);

        $sql = <<<___SQL
            CREATE TABLE `Insurance_Types` (
              `ID` int(11) NOT NULL AUTO_INCREMENT,
              `Name` varchar(50) DEFAULT NULL,
              PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
___SQL;
        $this->execute($sql);

        $sql = <<<___SQL
            INSERT INTO `Insurance_Types` 
            VALUES 
            (1,'GO'),
            (2,'Kasko'),
            (3,'Other');
___SQL;
        $this->execute($sql);

    }
}