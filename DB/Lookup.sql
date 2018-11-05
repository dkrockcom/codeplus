SELECT * FROM dkrock.Lookup;CREATE TABLE `Lookup` (
  `LookupId` int(11) NOT NULL AUTO_INCREMENT,
  `LookupTypeId` int(11) DEFAULT '0',
  `DisplayValue` varchar(100) DEFAULT '',
  `ScopId` int(11) DEFAULT '0',
  `CreatedOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `CreatedBy` int(11) DEFAULT NULL,
  `ModifiedOn` datetime DEFAULT '0000-00-00 00:00:00',
  `ModifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`LookupId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;