CREATE TABLE IF NOT EXISTS `fastorder_orders` (
    `ID` int(11) NOT NULL AUTO_INCREMENT,
    `DATE_CREATE` datetime NOT NULL,
    `NAME` varchar(255) NOT NULL,
    `PHONE` varchar(255) NOT NULL,
    `EMAIL` varchar(255) DEFAULT NULL,
    `COMMENT` text,
    `PRODUCT_ID` varchar(255) NOT NULL,
    `STATUS` char(1) NOT NULL DEFAULT 'N',
    PRIMARY KEY (`ID`)
);