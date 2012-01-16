DROP TABLE IF EXISTS `tblConfirmation`;
CREATE TABLE `tblConfirmation` (
  `userID` int(10) NOT NULL,
  `confirmLink` varchar(255) NOT NULL,
  PRIMARY KEY  (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tblSessions`;
CREATE TABLE `tblSessions` (
  `sessionID` char(32) NOT NULL,
  `sessionDate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sessionData` text NOT NULL,
  `sessionExpiry` int(10) NOT NULL,
  PRIMARY KEY  (`sessionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tblUsers`;
CREATE TABLE `tblUsers` (
  `userID` int(10) unsigned NOT NULL auto_increment,
  `userName` varchar(255) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userFirstname` varchar(255) default NULL,
  `userLastname` varchar(255) default NULL,
  `userEmail` varchar(255) NOT NULL,
  `userDateRegistered` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `userSecretAnswer` varchar(255) NOT NULL,
  PRIMARY KEY  (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tblUsers_API`;
CREATE TABLE `tblUsers_API` (
  `userID` int(10) NOT NULL,
  `apiKey` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
