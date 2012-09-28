CREATE TABLE `devclub_story` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `duration` int(11) DEFAULT '45',
  `status` enum('icebox','backlog','current','completed') COLLATE utf8_unicode_ci DEFAULT 'icebox',
  `authors` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `creator_email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `devclub_vote` (
  `storyID` int(11) NOT NULL,
  `user` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) DEFAULT NULL,
  KEY `FK_devclub_vote` (`storyID`),
  CONSTRAINT `FK_devclub_vote` FOREIGN KEY (`storyID`) REFERENCES `devclub_story` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `devclub_yearly_vote` (
  `storyID` int(11) DEFAULT NULL,
  `user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;