

CREATE TABLE `tblbrgyofficial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sPosition` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `completeName` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pcontact` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `paddress` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `termStart` date NOT NULL,
  `termEnd` date NOT NULL,
  `Status` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `barangay` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `image` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tblbrgyofficial VALUES("8","Captain","Rexiber S. Villaceran","09269948693","Tabagak  Madridejos Cebu","2016-05-10","2026-05-14","Ongoing Term","Tabagak","man.png");
INSERT INTO tblbrgyofficial VALUES("9","Captain","Basilio S. Ducay","09215316216","Bunakan","2020-09-09","2025-07-24","Ongoing Term","Bunakan","man.png");
INSERT INTO tblbrgyofficial VALUES("14","Kagawad","Eric S. Despi","09215316216","Tabagak Madridejos Cebu","2020-05-07","2026-09-17","Ongoing Term","Tabagak","man.png");
INSERT INTO tblbrgyofficial VALUES("16","Kagawad","Emilita M. Forsuelo","09215316216","Tabagak Madridejos Cebu ","2015-09-16","2026-05-06","Ongoing Term","Tabagak","women.png");
INSERT INTO tblbrgyofficial VALUES("17","Kagawad","Analina A. Santillan","09215316216","Tabagak Madridejos Cebu","2019-05-09","2026-10-15","Ongoing Term","Tabagak","women.png");
INSERT INTO tblbrgyofficial VALUES("18","Kagawad","Rosalino S. Forrosuelo","09215316216","Tabagak Madridejos Cebu","2018-05-12","2027-06-29","Ongoing Term","Tabagak","women.png");
INSERT INTO tblbrgyofficial VALUES("19","Kagawad","Virgie M. Villaceran","09215316216","Tabagak Madridejos Cebu","2019-05-08","2026-09-17","Ongoing Term","Tabagak","women.png");
INSERT INTO tblbrgyofficial VALUES("20","Kagawad","Norma S. Jimenez","09215316216","Tabagak Madridejos Cebu","2016-05-11","2027-05-05","Ongoing Term","Tabagak","women.png");
INSERT INTO tblbrgyofficial VALUES("21","Kagawad","Bartolome D. Mansueto","09215316216","Tabagak Madridejos Cebu","2016-05-14","2027-05-14","Ongoing Term","Tabagak","man.png");
INSERT INTO tblbrgyofficial VALUES("25","Kagawad","Loudes M. Aniban","09215316216","Bunakan","2018-05-10","2026-03-11","Ongoing Term","Bunakan","women.png");
INSERT INTO tblbrgyofficial VALUES("26","Kagawad","Eric M. Mansueto","09269948693","Bunakan","2017-02-15","2026-03-18","Ongoing Term","Bunakan","man.png");
INSERT INTO tblbrgyofficial VALUES("27","Kagawad","Eduardo T. Magallanes","09213618622","Bunakan","2016-05-19","2026-02-05","Ongoing Term","Bunakan","man.png");
INSERT INTO tblbrgyofficial VALUES("28","Kagawad","Orlando D. Lim","09123131312","Bunakan","2012-05-18","2026-05-06","Ongoing Term","Bunakan","man.png");
INSERT INTO tblbrgyofficial VALUES("29","Kagawad","Criselda D. Mansueto","09269948693","Bunakan","2020-01-16","2026-06-11","Ongoing Term","Bunakan","women.png");
INSERT INTO tblbrgyofficial VALUES("30","Kagawad","Romeo G. Buhayan","09123131312","Bunakan","2019-01-10","2026-10-14","Ongoing Term","Bunakan","man.png");
INSERT INTO tblbrgyofficial VALUES("31","Kagawad","Alfredo V. Ducay","09215316216","Bunakan","2021-02-19","2026-09-09","Ongoing Term","Bunakan","man.png");
INSERT INTO tblbrgyofficial VALUES("34","Captain","Pedro B. Maru","09269948693","Maalat ","2020-12-30","2026-05-07","Ongoing Term","Maalat","man.png");
INSERT INTO tblbrgyofficial VALUES("35","Kagawad","Elmer Ducay","09213618622","Maalat ","2019-05-09","2026-05-07","Ongoing Term","Maalat","man.png");
INSERT INTO tblbrgyofficial VALUES("36","Kagawad","Ruel A. Batuigas","09123131312","Maalat ","2020-05-06","2026-05-07","Ongoing Term","Maalat","man.png");
INSERT INTO tblbrgyofficial VALUES("37","SK","Alviniel A. Giganto","09215316216","Tabagak Madridejos Cebu","2019-05-08","2025-10-10","Ongoing Term","Tabagak","man.png");
INSERT INTO tblbrgyofficial VALUES("38","Secretary","Ma. Corazon Tamayo","09213618622","Tabagak Madridejos Cebu","2022-05-12","2026-01-10","Ongoing Term","Tabagak","women.png");
INSERT INTO tblbrgyofficial VALUES("39","Treasurer","Melanie F. Zata","09123131312","Tabagak, Madridejos Cebu","2021-09-08","2025-05-01","Ongoing Term","Tabagak","women.png");
INSERT INTO tblbrgyofficial VALUES("40","SK","JoylaineMarie A. Dela Cruz","09269948693","Bunakan","2022-09-16","2026-05-14","Ongoing Term","Bunakan","women.png");
INSERT INTO tblbrgyofficial VALUES("41","Secretary","Lovelyn C. Garcia","09269948693","Bunakan","2022-05-06","2026-05-02","Ongoing Term","Bunakan","women.png");
INSERT INTO tblbrgyofficial VALUES("42","Treasurer","Raffy V. Magallanes","09215316216","Bunakan","2019-05-08","2026-02-12","Ongoing Term","Bunakan","man.png");
INSERT INTO tblbrgyofficial VALUES("43","Kagawad","Johnny T. Duran","09269948693","Maalat  ","2021-05-14","2026-05-08","Ongoing Term","Maalat","man.png");
INSERT INTO tblbrgyofficial VALUES("44","Kagawad","Erlinda D. Cataquez","09215316216","Maalat","2022-05-12","2026-05-15","Ongoing Term","Maalat","women.png");
INSERT INTO tblbrgyofficial VALUES("45","Kagawad","Arnold U. Abello","09269948693","Maalat ","2020-12-30","2026-10-17","Ongoing Term","Maalat","man.png");
INSERT INTO tblbrgyofficial VALUES("46","Kagawad","Jose Jimeo P. Abello","09215316216","Maalat ","2021-05-05","2025-10-16","Ongoing Term","Maalat","man.png");
INSERT INTO tblbrgyofficial VALUES("47","Kagawad","Renita V. Maru","09269948693","Maalat","2020-12-30","2026-01-03","Ongoing Term","Maalat","women.png");
INSERT INTO tblbrgyofficial VALUES("48","SK Chairman/Chairperson","Joshua Estellerro","09215316216","Maalat ","2022-05-15","2026-02-14","Ongoing Term","Maalat","man.png");
INSERT INTO tblbrgyofficial VALUES("49","Secretary","Ann HS","09269948693","Maalat ","2020-01-08","2026-05-15","Ongoing Term","Maalat","1729432874664_women.png");
INSERT INTO tblbrgyofficial VALUES("50","Treasurer","Jaime Villaceran","09215316216","Maalat ","2020-09-17","2026-05-22","Ongoing Term","Maalat","1729433124261_women.png");
INSERT INTO tblbrgyofficial VALUES("51","Captain","Jerry Caranzo","09213618622","Pili","2018-01-27","2026-05-06","Ongoing Term","Pili","1729693160718_man.png");
INSERT INTO tblbrgyofficial VALUES("52","Kagawad","Sofia Gido","09215316216","Pili","2021-02-11","2026-05-14","Ongoing Term","Pili","1729693223337_women.png");
INSERT INTO tblbrgyofficial VALUES("53","Kagawad","Jimmy Cahutay","09215316216","Pili","2020-05-13","2026-05-14","Ongoing Term","Pili","1729693342751_man.png");
INSERT INTO tblbrgyofficial VALUES("54","Kagawad","Bernardo Oflas","09269948693","Pili","2021-05-06","2026-05-08","Ongoing Term","Pili","1729693393592_man.png");
INSERT INTO tblbrgyofficial VALUES("55","Kagawad","Gemma Gilbuela","09269948693","Pili","2020-05-02","2026-05-15","Ongoing Term","Pili","1729693494754_women.png");
INSERT INTO tblbrgyofficial VALUES("56","Kagawad","Erwin Corridor","09215316216","Pili","2020-05-16","2026-05-15","Ongoing Term","Pili","1729694357664_man.png");
INSERT INTO tblbrgyofficial VALUES("57","Kagawad","Cristina Caranzo","09215316216","Pili","2019-05-09","2026-09-16","Ongoing Term","Pili","1729694990088_women.png");
INSERT INTO tblbrgyofficial VALUES("58","Kagawad","Maria Lezel Hyer","09269948693","Pili","2021-09-09","2026-05-14","Ongoing Term","Pili","1729695068934_women.png");
INSERT INTO tblbrgyofficial VALUES("59","SK Chairman/Chairperson","Ritchie Sinday","09215316216","Pili","2020-05-14","2026-05-22","Ongoing Term","Pili","1729695629845_man.png");
INSERT INTO tblbrgyofficial VALUES("60","Secretary","Randy B. Despi","09269948693","Pili","2020-05-05","2026-01-14","Ongoing Term","Pili","1729695684666_man.png");
INSERT INTO tblbrgyofficial VALUES("61","Treasurer","Henry Illustrisimo","09269948693","Pili","2021-05-20","2026-05-21","Ongoing Term","Pili","1729695755840_man.png");



CREATE TABLE `tblbunakan` (
  `id` int(11) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `mname` varchar(20) NOT NULL,
  `bdate` varchar(20) NOT NULL,
  `bplace` text NOT NULL,
  `age` int(11) NOT NULL,
  `barangay` varchar(20) NOT NULL,
  `totalhousehold` int(5) NOT NULL,
  `civilstatus` varchar(20) NOT NULL,
  `householdnum` int(11) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `houseOwnershipStatus` varchar(50) NOT NULL,
  `landOwnershipStatus` varchar(20) NOT NULL,
  `lightningFacilities` varchar(20) NOT NULL,
  `formerAddress` text NOT NULL,
  `image` text NOT NULL,
  `purok` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tblbunakan VALUES("1","Alegre","Kurt Brayan","M","2002-05-03","Bunakan","22","Bunakan","3","Single","3","Catholic","Filipino","Male","Own Home","Owned","Electric","Bunakan","1722693603873_448684245_1686707815457085_1699584897398129282_n.jpg","Bilabid");
INSERT INTO tblbunakan VALUES("1","Alegre","Kurt Brayan","M","2002-05-03","Bunakan","22","Bunakan","3","Single","3","Catholic","Filipino","Male","Own Home","Owned","Electric","Bunakan","1722693603873_448684245_1686707815457085_1699584897398129282_n.jpg","Bilabid");
INSERT INTO tblbunakan VALUES("1","Alegre","Kurt Brayan","M","2002-05-03","Bunakan","22","Bunakan","3","Single","3","Catholic","Filipino","Male","Own Home","Owned","Electric","Bunakan","1722693603873_448684245_1686707815457085_1699584897398129282_n.jpg","Bilabid");
INSERT INTO tblbunakan VALUES("1","Alegre","Kurt Brayan","M","2002-05-03","Bunakan","22","Bunakan","3","Single","3","Catholic","Filipino","Male","Own Home","Owned","Electric","Bunakan","1722693603873_448684245_1686707815457085_1699584897398129282_n.jpg","Bilabid");
INSERT INTO tblbunakan VALUES("1","Alegre","Kurt Brayan","M","2002-05-03","Bunakan","22","Bunakan","3","Single","3","Catholic","Filipino","Male","Own Home","Owned","Electric","Bunakan","1722693603873_448684245_1686707815457085_1699584897398129282_n.jpg","Bilabid");
INSERT INTO tblbunakan VALUES("1","Alegre","Kurt Brayan","M","2002-05-03","Bunakan","22","Bunakan","3","Single","3","Catholic","Filipino","Male","Own Home","Owned","Electric","Bunakan","1722693603873_448684245_1686707815457085_1699584897398129282_n.jpg","Bilabid");



CREATE TABLE `tblcertificate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `bdate` varchar(50) NOT NULL,
  `purok` varchar(50) NOT NULL,
  `civilstatus` varchar(20) NOT NULL,
  `barangay` text NOT NULL,
  `dateRecorded` date NOT NULL,
  `report_type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tblcertificate VALUES("1","Gilbert S. Sevillejo","EMPLOYMENT","14","2010-05-22","Lamon-lamon","Single","Tabagak","2024-10-13","Barangay Certificate");
INSERT INTO tblcertificate VALUES("2","Realyn A. Barsaga","FOR EMPLOYMENT","14","2010-01-08","Bilabid","Single","Bunakan","2024-10-17","Barangay Certificate");
INSERT INTO tblcertificate VALUES("3","Rey John B. Cabornay","EMPLOYMENT","20","2004-07-18","Talisay","Single","Maalat","2024-10-20","Barangay Certificate");



CREATE TABLE `tblclearance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clearanceNo` varchar(255) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `purpose` text NOT NULL,
  `orNo` int(11) NOT NULL,
  `samount` int(11) NOT NULL,
  `dateRecorded` date NOT NULL,
  `recordedBy` varchar(50) NOT NULL,
  `barangay` text NOT NULL,
  `age` int(11) NOT NULL,
  `bdate` varchar(50) NOT NULL,
  `purok` varchar(20) NOT NULL,
  `bplace` varchar(100) NOT NULL,
  `civilstatus` varchar(50) NOT NULL,
  `report_type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tblclearance VALUES("65","0001","Realyn A. Barsaga","FOR EMPLOYMENT","12","12","2024-10-17","bunakan","Bunakan","15","2009-05-06","Bilabid","Bunakan","Single","Clearance");
INSERT INTO tblclearance VALUES("66","0001","Rey John B. Cabornay","Financial Asesstance","12","12","2024-10-17","maalat","Maalat","22","2002-01-03","Neem Tree","Maalat","Single","Clearance");
INSERT INTO tblclearance VALUES("75","0001","Gilbert S. Sevillejo","FOR EMPLOYMENT","1","1","2024-11-07","tabagak","Tabagak","0","2024-11-07","Tangigue","Tabagak","Single","Clearance");



CREATE TABLE `tblhousehold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `householdno` int(11) NOT NULL,
  `totalhouseholdmembers` int(2) NOT NULL,
  `headoffamily` varchar(100) NOT NULL,
  `purok` varchar(20) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `membersname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tblhousehold VALUES("123","1","2","66","","","Smith, Junjun S");



CREATE TABLE `tblindigency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `age` int(11) NOT NULL,
  `bdate` varchar(20) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `purok` varchar(50) NOT NULL,
  `civilstatus` varchar(20) NOT NULL,
  `barangay` text NOT NULL,
  `dateRecorded` date NOT NULL,
  `report_type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tblindigency VALUES("7","Gilbert S. Sevillejo","Female","20","2004-05-05","Financial Assistance","Lamon-lamon","Single","Tabagak","2024-10-13","Certificate Of Indigency");
INSERT INTO tblindigency VALUES("8","Realyn A. Barsaga","Female","20","2004-06-11","Financial Assistance","Helinggero","Single","Bunakan","2024-10-17","Certificate Of Indigency");
INSERT INTO tblindigency VALUES("9","Rey John B. Cabornay","Male","20","2004-07-18","Scholorship","Talisay","Single","Maalat","2024-10-20","Certificate Of Indigency");



CREATE TABLE `tbllogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `logdate` date NOT NULL,
  `action` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1065 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tbllogs VALUES("1052","Brgy.Tabagak","2024-11-07","Update Resident info of NeilAlbert E Gidayawan");
INSERT INTO tbllogs VALUES("1053","Brgy.Tabagak","2024-11-07","Added Clearance named of HUY gwapo");
INSERT INTO tbllogs VALUES("1054","Brgy.Tabagak","2024-11-07","Added Clearance named of HUY gwapo");
INSERT INTO tbllogs VALUES("1055","Brgy.Tabagak","2024-11-07","Added Clearance named of Gilbert S. Sevillejo");
INSERT INTO tbllogs VALUES("1056","Brgy.Tabagak","2024-11-07","Added Clearance named of HUY gwapo");
INSERT INTO tbllogs VALUES("1057","Brgy.Tabagak","2024-11-07","Added Clearance named of Gilbert S. Sevillejo");
INSERT INTO tbllogs VALUES("1058","Brgy.Tabagak","2024-11-07","Added Clearance named of HUY gwapo");
INSERT INTO tbllogs VALUES("1059","Brgy.Tabagak","2024-11-07","Added Clearance named of Gilbert S. Sevillejo");
INSERT INTO tbllogs VALUES("1060","Brgy.Tabagak","2024-11-07","Added Clearance named of Gilbert S. Sevillejo");
INSERT INTO tbllogs VALUES("1061","Brgy.Tabagak","2024-11-07","Added Clearance named of HUY gwapo");
INSERT INTO tbllogs VALUES("1062","Brgy.Tabagak","2024-11-07","Added certificate of residency named of HUY gwapo");
INSERT INTO tbllogs VALUES("1063","Brgy.Tabagak","2024-11-07","Added Household Number 1");
INSERT INTO tbllogs VALUES("1064","Brgy.Tabagak","2024-11-07","Added Household Number 1");



CREATE TABLE `tblmadofficial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sPosition` varchar(50) NOT NULL,
  `completeName` text NOT NULL,
  `pcontact` varchar(20) NOT NULL,
  `paddress` text NOT NULL,
  `termStart` date NOT NULL,
  `termEnd` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tblmadofficial VALUES("31","Mayor","Romeo A. Villaceran","09269948693","Pili Madridejos Cebu","2023-05-11","2025-05-06","Ongoing Term","man.png");
INSERT INTO tblmadofficial VALUES("33","Vice Mayor","Vincent Y. Villacrusis","09123173992","Poblacion Madridejos Cebu","2015-01-01","2026-06-11","Ongoing Term","man.png");
INSERT INTO tblmadofficial VALUES("34","Hon","Perla B. Molina","09123173138","Wapa hibawe","2020-05-03","2026-05-07","Ongoing Term","women.png");
INSERT INTO tblmadofficial VALUES("35","Hon","Julius Villaceran","09127131381","Tabagak Madridejos Cebu","2020-02-07","2026-06-11","Ongoing Term","man.png");
INSERT INTO tblmadofficial VALUES("36","Hon","Jhon Robert S. Dela Fuente","09213187381","Mancilang Madridejos Cebu","2020-05-07","2026-05-13","Ongoing Term","man.png");
INSERT INTO tblmadofficial VALUES("37","Hon","Delfin A. Santillan","09217321317","Wapa hibawe","2020-06-03","2026-05-06","Ongoing Term","man.png");
INSERT INTO tblmadofficial VALUES("38","Hon","Owen G. Daruca","09513464424","Maalat Madridejos Cebu","2020-05-14","2026-05-14","Ongoing Term","man.png");
INSERT INTO tblmadofficial VALUES("39","Hon","Perla A. Bacayo","09123123182","Maalat Madridejos Cebu","2019-07-09","2026-02-10","Ongoing Term","women.png");
INSERT INTO tblmadofficial VALUES("40","Hon","Vanice Cyrus M. Rebadomia","09217371237","Waako kaybaw","2018-05-09","2026-07-11","Ongoing Term","women.png");



CREATE TABLE `tblmember` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `famID` int(11) NOT NULL,
  `name` varchar(59) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `tblpermit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `businessName` text NOT NULL,
  `businessAddress` text NOT NULL,
  `typeOfBusiness` varchar(50) NOT NULL,
  `orNo` int(11) NOT NULL,
  `samount` int(11) NOT NULL,
  `dateRecorded` date NOT NULL,
  `recordedBy` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tblpermit VALUES("81","Merriam Ducay","basta uy","BASTA","Service","12","12","2024-10-31","admin");



CREATE TABLE `tblrecidency` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `bdate` varchar(50) NOT NULL,
  `purok` varchar(50) NOT NULL,
  `civilstatus` varchar(20) NOT NULL,
  `barangay` text NOT NULL,
  `dateRecorded` date NOT NULL,
  `report_type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tblrecidency VALUES("14","Gilbert S. Sevillejo","Scholarship","14","2010-05-05","Lamon-Lamon","Single","Tabagak","2024-10-13","Certificate Of Residency");
INSERT INTO tblrecidency VALUES("15","Realyn A. Barsaga","FOR EMPLOYMENT","3","2021-01-11","Helinggero","Single","Bunakan","2024-10-17","Certificate Of Residency");
INSERT INTO tblrecidency VALUES("16","Rey John B. Cabornay","Whatever Legal","25","1999-01-01","Neem Tree","Single","Maalat","2024-10-20","Certificate Of Residency");
INSERT INTO tblrecidency VALUES("17","Jhoana Marie Santillan","Pangguna","19","2005-05-14","Mahigugmaon","Single","Pili","2024-10-23","Certificate Of Residency");



CREATE TABLE `tblstaff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `compass` varchar(255) NOT NULL,
  `logo` text NOT NULL,
  `code` varchar(10) NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tblstaff VALUES("86","Tabagak","tabagak","gilbertsevillejo@gmail.com","$2y$10$Pa2K/Rr7kr/0XGOvGXYzV.CfD4hPNMZOtrp0W0EM5R7RHHQ0jGC6W","$2y$10$sAUysxXN.mmGueC/aEBqu.G1gDFE6iIkc9pmuv/voAWZ7t7mL3BxO","1008223232.png","zN5UwZlfQd","2024-10-10 10:57:19");
INSERT INTO tblstaff VALUES("89","Bunakan","bunakan","brgybunakan03@gmail.com","$2y$10$dzHYEkpU3We4imL6wr0UKe9ujrVN4259848eaczLrfVT2srN7KYUK","$2y$10$dzHYEkpU3We4imL6wr0UKe9ujrVN4259848eaczLrfVT2srN7KYUK","1016225509.png","","2024-10-16 22:55:09");
INSERT INTO tblstaff VALUES("92","Maalat","maalat","brgymaalat24@gmail.com","$2y$10$c48NqGG5Y1TCDLy9WyWWnuK00ll/eyVi21YGQUghmpBc6W/aCN9Ly","$2y$10$c48NqGG5Y1TCDLy9WyWWnuK00ll/eyVi21YGQUghmpBc6W/aCN9Ly","1019121231.png","","2024-10-19 12:12:31");
INSERT INTO tblstaff VALUES("93","Pili","pili","brgypili24@gmail.com","$2y$10$EaonnBO.uxcz8KsQyGVbp.WvkqkI5gehUyEWJrKtEK4dCWWfjNAju","$2y$10$EaonnBO.uxcz8KsQyGVbp.WvkqkI5gehUyEWJrKtEK4dCWWfjNAju","1023212206.png","","2024-10-23 21:22:06");



CREATE TABLE `tbltabagak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lname` varchar(20) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `mname` varchar(20) NOT NULL,
  `bdate` varchar(20) NOT NULL,
  `bplace` text NOT NULL,
  `age` int(11) NOT NULL,
  `barangay` varchar(120) NOT NULL,
  `civilstatus` varchar(20) NOT NULL,
  `householdnum` int(11) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `houseOwnershipStatus` varchar(50) NOT NULL,
  `landOwnershipStatus` varchar(20) NOT NULL,
  `lightningFacilities` varchar(20) NOT NULL,
  `formerAddress` text NOT NULL,
  `image` text NOT NULL,
  `purok` varchar(20) NOT NULL,
  `role` text NOT NULL,
  `hof` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tbltabagak VALUES("66","Smith","Gilbert","D","2000-05-07","Tabagak","24","Tabagak","Married","1","Catholic","Filipino","Male","Own Home","Owned","Electric","Tabagak","1730384492470_man.png","Lamon-Lamon","Head of Family","");
INSERT INTO tbltabagak VALUES("67","Gidayawan","NeilAlbert","E","1999-05-05","Tabagak","25","Tabagak","Married","2","Catholic","Filipino","Male","Own Home","Owned","Electric","Tabagak","1730386156528_man.png","Tangigue","Head of Family","");
INSERT INTO tbltabagak VALUES("68","Smith","Junjun","S","2015-05-06","Tabagak","9","Tabagak","Single","1","Catholic","Filipino","Male","Own Home","Owned","Electric","Tabagak","1730386922472_man.png","Lamon-Lamon","Members","66");
INSERT INTO tbltabagak VALUES("69","Smith","John","Q","2012-05-18","Tabagak","12","Tabagak","Single","1","Catholic","Filipino","Female","Own Home","Owned","Electric","Tabagak","1730389232739_man.png","Lamon-Lamon","Members","66");



CREATE TABLE `tbluser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `code` varchar(10) NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tbluser VALUES("1","admin","lawishomeresidences12@gmail.com","$2y$10$MJE6XGPxKlWy3OjVr/sqCeZWedcOjtvYdFK/CKOg6bmOeFzKwLMLG","administrator","","2024-10-10 20:28:11");

