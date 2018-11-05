CREATE TABLE `LookupType` (
  `LookupTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `DisplayValue` varchar(100) DEFAULT NULL,
  `ScopId` int(11) DEFAULT '0',
  `CreatedOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `CreatedBy` int(11) DEFAULT NULL,
  `ModifiedOn` datetime DEFAULT '0000-00-00 00:00:00',
  `ModifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`LookupTypeId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;