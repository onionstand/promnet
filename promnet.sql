-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2017 at 01:02 PM
-- Server version: 5.7.9
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `promnet`
--

-- --------------------------------------------------------

--
-- Table structure for table `avans_rac`
--

DROP TABLE IF EXISTS `avans_rac`;
CREATE TABLE IF NOT EXISTS `avans_rac` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `id_firme` int(10) NOT NULL,
  `opis` varchar(256) NOT NULL,
  `osnovica` decimal(12,0) NOT NULL,
  `porez` decimal(12,0) NOT NULL,
  `zbir` decimal(12,0) NOT NULL,
  `datum` date NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bankaupis`
--

DROP TABLE IF EXISTS `bankaupis`;
CREATE TABLE IF NOT EXISTS `bankaupis` (
  `id_upl` int(11) NOT NULL AUTO_INCREMENT,
  `br_izvoda` int(3) NOT NULL,
  `datum_izv` date NOT NULL,
  `sifra_par` int(11) NOT NULL,
  `broj_dok` varchar(64) DEFAULT NULL,
  `ulaz_novca` decimal(12,2) DEFAULT NULL,
  `izlaz_novca` decimal(12,2) DEFAULT NULL,
  `ziro_rac` varchar(64) DEFAULT NULL,
  `banka` int(3) NOT NULL,
  `svrha` varchar(32) NOT NULL,
  `fit_id` varchar(128) NOT NULL,
  PRIMARY KEY (`id_upl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `banke`
--

DROP TABLE IF EXISTS `banke`;
CREATE TABLE IF NOT EXISTS `banke` (
  `id_banke` int(11) NOT NULL AUTO_INCREMENT,
  `ime_banke` varchar(64) NOT NULL,
  `poc_stanje` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id_banke`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `blagajna`
--

DROP TABLE IF EXISTS `blagajna`;
CREATE TABLE IF NOT EXISTS `blagajna` (
  `br_blag` int(11) NOT NULL AUTO_INCREMENT,
  `br_konta` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `opis_troska` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `blagulaz` decimal(12,2) DEFAULT NULL,
  `blagizn` decimal(12,2) DEFAULT NULL,
  `pdv_izn` decimal(12,2) DEFAULT NULL,
  `datum` date NOT NULL,
  `brupl` int(11) NOT NULL,
  `napomena` varchar(64) NOT NULL,
  PRIMARY KEY (`br_blag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dob_kup`
--

DROP TABLE IF EXISTS `dob_kup`;
CREATE TABLE IF NOT EXISTS `dob_kup` (
  `sif_kup` int(11) NOT NULL AUTO_INCREMENT,
  `naziv_kup` varchar(128) DEFAULT NULL,
  `postbr` varchar(11) DEFAULT NULL,
  `mesto_kup` varchar(64) DEFAULT NULL,
  `ulica_kup` varchar(64) DEFAULT NULL,
  `rab_ugo` int(4) DEFAULT NULL,
  `ziro_rac` varchar(22) DEFAULT NULL,
  `tel` varchar(22) DEFAULT NULL,
  `pib` varchar(12) DEFAULT NULL,
  `mat_br` varchar(12) DEFAULT NULL,
  `stanje` decimal(12,2) DEFAULT NULL,
  `kupci` decimal(12,2) NOT NULL,
  `dobavljaci` decimal(12,2) NOT NULL,
  `ziro_rac2` varchar(22) NOT NULL,
  PRIMARY KEY (`sif_kup`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `dosta`
--

DROP TABLE IF EXISTS `dosta`;
CREATE TABLE IF NOT EXISTS `dosta` (
  `broj_dost` int(11) NOT NULL AUTO_INCREMENT,
  `datum_d` date DEFAULT NULL,
  `sifra_fir` int(11) DEFAULT NULL,
  `rok` varchar(3) DEFAULT NULL,
  `izzad` decimal(12,2) DEFAULT NULL,
  `ispor` decimal(12,2) DEFAULT NULL,
  `odo_rab` decimal(12,2) DEFAULT NULL,
  `bruc` decimal(12,2) DEFAULT NULL,
  `napomena` text,
  `uplaceni_avans` decimal(12,2) DEFAULT NULL,
  `datum_prom` date DEFAULT NULL,
  `racun_poslat` date DEFAULT NULL,
  PRIMARY KEY (`broj_dost`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--
-- Table structure for table `glknjiga`
--

DROP TABLE IF EXISTS `glknjiga`;
CREATE TABLE IF NOT EXISTS `glknjiga` (
  `id_glknjiga` int(12) NOT NULL AUTO_INCREMENT,
  `sifradok` int(12) NOT NULL,
  `brdok` int(12) NOT NULL,
  `brkonta` int(12) NOT NULL,
  `datdok` date NOT NULL,
  `duguje` decimal(12,2) DEFAULT NULL,
  `potraz` decimal(12,2) DEFAULT NULL,
  `opis` varchar(128) NOT NULL,
  `prokont` int(11) NOT NULL,
  PRIMARY KEY (`id_glknjiga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `izlaz`
--

DROP TABLE IF EXISTS `izlaz`;
CREATE TABLE IF NOT EXISTS `izlaz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `br_dos` int(11) NOT NULL,
  `srob_dos` int(11) NOT NULL,
  `koli_dos` decimal(12,2) DEFAULT NULL,
  `cena_d` decimal(12,2) DEFAULT NULL,
  `rab_dos` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `kalk`
--

DROP TABLE IF EXISTS `kalk`;
CREATE TABLE IF NOT EXISTS `kalk` (
  `broj_kalk` int(11) NOT NULL AUTO_INCREMENT,
  `datum` date NOT NULL,
  `dostav` varchar(64) NOT NULL,
  `faktura` varchar(64) NOT NULL,
  `sif_firme` int(11) NOT NULL,
  `placanje_index` int(3) DEFAULT NULL,
  `nabav_vre` decimal(12,2) DEFAULT NULL,
  `pro_vre` decimal(12,2) DEFAULT NULL,
  `ukal_porez` decimal(12,2) DEFAULT NULL,
  `odora` decimal(12,2) DEFAULT NULL,
  `izv` varchar(64) NOT NULL,
  `rok_pl` varchar(4) NOT NULL,
  PRIMARY KEY (`broj_kalk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `konto`
--

DROP TABLE IF EXISTS `konto`;
CREATE TABLE IF NOT EXISTS `konto` (
  `id_kont` int(11) NOT NULL AUTO_INCREMENT,
  `broj_kont` varchar(64) NOT NULL,
  `naziv_kont` varchar(64) NOT NULL,
  PRIMARY KEY (`id_kont`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `konto`
--

INSERT INTO `konto` (`id_kont`, `broj_kont`, `naziv_kont`) VALUES
(1, '23', 'OPREMA ZA OBAVLJANJE TRGOVINE'),
(2, '130', 'ROBA NA SKLADISTU'),
(3, '139', 'UKALKULISANA RAZLIKA U CENI ROBE'),
(4, '152', 'DATI AVANSI DOBAVLJACIMA'),
(5, '201', 'KUPAC U ZEMLJI'),
(6, '210', 'SPECIFICNA POTRAZIVANJA'),
(7, '221', 'KRATKOROCNI KREDITI'),
(8, '241', 'ZIRO RACUN'),
(9, '242', 'PRENOS NOVCANIH SRED UNUTAR PREDUZECA'),
(10, '243', 'BLAGAJNA'),
(11, '262', 'OBAVEZE POREZA NA PRIHOD'),
(12, '270', 'PLACEN POREZ PDV U NABAVCI VISA TARIFA'),
(13, '271', 'PLACEN POREZ PDV U NABAVCI NIZA TARIFA'),
(14, '301', 'OSNIVACKI KAPITAL'),
(15, '340', 'DOBIT NERASPOREDJENA IZ RANIH GODINA'),
(16, '429', 'OBAVEZE PREMA OSNIVACIMA'),
(17, '430', 'PRIMLJENI AVANSI OD KUPACA'),
(18, '433', 'DOBAVLJACI U ZEMLJI'),
(19, '450', 'OBAVEZA ZA NETO ZARADU'),
(20, '451', 'OBAVEZA POREZA NA ZARADU'),
(21, '452', 'OBAVEZA PIO NA TERET ZAPOSLENOG'),
(22, '453', 'OBAZEZA SOCIJALNO OSIGRANJE NA TERET ZAPOSL'),
(23, '454', 'OBAVEZA ZA ZAPOSLJAVANJE TA TERET ZAPOSLENO'),
(24, '455', 'OBAVEZA PIO NA TERET POSLODAVCA'),
(25, '456', 'OBAVEZA ZDRAVSTVENO OSIGURANJE NA TERET POS'),
(26, '457', 'OBAVEZA ZA ZAPOSLJAVANJE NA TERET POSLODAVC'),
(27, '469', 'OSTALE OBAVEZE'),
(28, '470', 'OBAVEZE POREZ NA DODATU VREDNOST PDV OPSTA'),
(29, '471', 'OBAVEZE POREZ NA DODATU VREDNOST PDV NIZAA'),
(30, '500', 'NABAVNA VREDNOST PRODATE ROBE'),
(31, '511', 'TROSKOVI DORADE PROIZVODA'),
(32, '512', 'TROSKOVI MATERIJALA ZA TEKUCE ODRZAVANJE'),
(33, '514', 'TROSKOVI REZERVNIH DELOVI ZA VOZILA'),
(34, '515', 'TROSKOVI OTPISA ZATITNE OBUCE I ODECE'),
(35, '558', 'TROSKOVI KANCELARISKOG MATER'),
(36, '513', 'TROSKOVI GORIVA I ENERGIJE'),
(37, '520', 'TROSKOVI ZARADA I NAKNADA ZAPOSLENIH BRUTO'),
(38, '521', 'TROSKOVI POREZA I DOPRINOSA TERET RADNIKA'),
(39, '522', 'TROSKOVI DOPRINOSA NA TERET POSLODAVCA'),
(40, '529', 'TROSKOVI JENOKRATNE POMOCI RADNICIMA'),
(41, '531', 'TROSKOVI TRANSPORTNIH USLUGA I PUTARINE'),
(42, '534', 'TROSKOVI TELEFONA PTT I INTERNETA'),
(43, '532', 'TROSKOVI TEKUCEG ODRZAVANJA VOZILA'),
(44, '538', 'TROSKOVI REGISTRACIJE VOZILA'),
(45, '533', 'TROSKOVI ZAKUPNINA-KIRIJA ZA POSL.PROSTOR'),
(46, '535', 'TROSKOVI REKLAME I PROPAGANDE'),
(47, '539', 'TROSKOVI OSTALIH USLUGA'),
(48, '550', 'TROSKOVI ADVOKATSKIH USLUGA'),
(49, '540', 'TROSKOVI AMORTIZACIJ PO PROPISANIM STOPAMA'),
(50, '551', 'TROSKOVI REPREZENTACIJA'),
(51, '553', 'TROSKOVI PROVIZIJA PLATNOG PROMETA'),
(52, '559', 'TROSKOVI BANKARSKIH USLUGA'),
(53, '555', 'TROSKOV  POREZA'),
(54, '556', 'TROSKOVI DOPRINOSA'),
(55, '559', 'TROSKOVI OSTALI NEPOMENUTI NEMATERIJALNI'),
(56, '554', 'TROSKOVI SUDSKE I ADMINISTRATIVNE TAKSE'),
(57, '561', 'TROSKOVI KAMATA'),
(58, '574', 'TROSKOVI MANJKOVA'),
(59, '576', 'TROSKOVI DIREKTNOG OPISA POTRAZIVANJA'),
(60, '577', 'TROSKOVI KALO RASTURA KVARA I LOMA'),
(61, '579', 'VANREDNI RASHODI'),
(62, '590', 'RASPORED TROSKOAVA'),
(63, '600', 'PRIHOD OD PRODAJE'),
(64, '661', 'PRIHODI OD KAMATA'),
(65, '679', 'VANREDNI PRIHODI'),
(66, '710', 'RACUN POSLOVNIH RASHODA I PRIHODA'),
(67, '711', 'TROSKOVI POREZA NA PRIHOD KORPORACIJE'),
(68, '720', 'RAZLIKA PRIHODA I RASHODA'),
(69, '690', 'POREZ NA PRIHOD KORPORACIJE'),
(70, '579', 'IZDACI ZA HUMANITARNE POTEBE');

-- --------------------------------------------------------

--
-- Table structure for table `k_pism_fin`
--

DROP TABLE IF EXISTS `k_pism_fin`;
CREATE TABLE IF NOT EXISTS `k_pism_fin` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `id_firme` int(12) NOT NULL,
  `opis` text NOT NULL,
  `osnovica` decimal(12,0) NOT NULL,
  `pdv` int(12) NOT NULL,
  `zbir` int(12) NOT NULL,
  `duguje_potr` tinyint(2) NOT NULL,
  `propratni_dok` varchar(128) DEFAULT NULL,
  `datum` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `k_pism_r`
--

DROP TABLE IF EXISTS `k_pism_r`;
CREATE TABLE IF NOT EXISTS `k_pism_r` (
  `broj_k` int(11) NOT NULL AUTO_INCREMENT,
  `kod_p` tinyint(4) NOT NULL,
  `dos_kal` varchar(64) NOT NULL,
  `dat_k` date NOT NULL,
  `tekst_k` text NOT NULL,
  `iznos_k` decimal(12,2) DEFAULT NULL,
  `iznos_f` decimal(12,2) DEFAULT NULL,
  `vel_rab_k` decimal(12,2) DEFAULT NULL,
  `vel_por_k` decimal(12,2) DEFAULT NULL,
  `partner` varchar(100) DEFAULT NULL,
  `sif_firme` varchar(64) NOT NULL,
  PRIMARY KEY (`broj_k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `k_pism_tr`
--

DROP TABLE IF EXISTS `k_pism_tr`;
CREATE TABLE IF NOT EXISTS `k_pism_tr` (
  `id_k` int(11) NOT NULL AUTO_INCREMENT,
  `broj_p` int(11) NOT NULL,
  `sif_rob_p` int(11) NOT NULL,
  `kolic_p` decimal(12,2) DEFAULT NULL,
  `rabat_p` int(11) DEFAULT NULL,
  `id_u_i` int(11) NOT NULL,
  PRIMARY KEY (`id_k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nivel`
--

DROP TABLE IF EXISTS `nivel`;
CREATE TABLE IF NOT EXISTS `nivel` (
  `broj_niv` int(11) NOT NULL AUTO_INCREMENT,
  `datum_niv` date NOT NULL,
  PRIMARY KEY (`broj_niv`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `niv_robe`
--

DROP TABLE IF EXISTS `niv_robe`;
CREATE TABLE IF NOT EXISTS `niv_robe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `br_niv` int(11) NOT NULL,
  `srob` int(11) NOT NULL,
  `srob_niv` int(11) NOT NULL,
  `koli_niv` decimal(12,2) DEFAULT NULL,
  `iznos_niv` decimal(12,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pdv_obracun`
--

DROP TABLE IF EXISTS `pdv_obracun`;
CREATE TABLE IF NOT EXISTS `pdv_obracun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prom_osn_opst_stop` decimal(12,2) NOT NULL,
  `prom_osn_pos_stop` decimal(12,2) NOT NULL,
  `prom_pdv_opst_stop` decimal(12,2) NOT NULL,
  `prom_pdv_pos_stop` decimal(12,2) NOT NULL,
  `prom_osn_zbir` decimal(12,2) NOT NULL,
  `prom_pdv_zbir` decimal(12,2) NOT NULL,
  `preth_porez_osn` decimal(12,2) NOT NULL,
  `preth_porez_pdv` decimal(12,2) NOT NULL,
  `poreska_obaveza` decimal(12,2) NOT NULL,
  `datum_od` date NOT NULL,
  `datum_do` date NOT NULL,
  `id_usl_k470potr` int(11) NOT NULL,
  `id_usl_k470dug` int(11) NOT NULL,
  `id_usl_k270potr` int(11) NOT NULL,
  `id_usl_k270dug` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `plate`
--

DROP TABLE IF EXISTS `plate`;
CREATE TABLE IF NOT EXISTS `plate` (
  `id_plate` int(11) NOT NULL AUTO_INCREMENT,
  `datum_plate` date NOT NULL,
  `redni_br` varchar(64) NOT NULL,
  `vrsta_ind_prim_prih` varchar(64) NOT NULL,
  `jmbg` int(32) NOT NULL,
  `ime` varchar(64) NOT NULL,
  `prezime` varchar(64) NOT NULL,
  `sifra_opstine` varchar(128) NOT NULL,
  `sifra_vrste_prih` varchar(64) NOT NULL,
  `broj_dana` int(4) NOT NULL,
  `broj_sati` int(4) NOT NULL,
  `iznos_por_umanje` decimal(12,2) NOT NULL,
  `poreska_stopa` decimal(4,2) NOT NULL,
  `pio_radnika_stopa` decimal(4,2) NOT NULL,
  `zdrav_radnika_stopa` decimal(4,2) NOT NULL,
  `zapos_radnika_stopa` decimal(4,2) NOT NULL,
  `ukupno_ter_radnik` decimal(4,2) NOT NULL,
  `pio_preduz_stopa` decimal(4,2) NOT NULL,
  `zdrav_predu_stopa` decimal(4,2) NOT NULL,
  `zapos_preduz_stopa` decimal(4,2) NOT NULL,
  `ukupno_ter_predu` decimal(4,2) NOT NULL,
  `neto_zarada` decimal(12,2) NOT NULL,
  `bruto_zarada` decimal(12,2) NOT NULL,
  `poresko_umanj` decimal(12,2) NOT NULL,
  `osnovica_za_porez` decimal(12,2) NOT NULL,
  `porez_na_licna_prim` decimal(12,2) NOT NULL,
  `pio_radnik_uplat` decimal(12,2) NOT NULL,
  `zdrav_radnik_upl` decimal(12,2) NOT NULL,
  `zaposl_radnik_upl` decimal(12,2) NOT NULL,
  `pio_preduz_uplat` decimal(12,2) NOT NULL,
  `zdravstv_preduz_upl` decimal(12,2) NOT NULL,
  `zaposlj_preduz_upl` decimal(12,2) NOT NULL,
  `ukupni_doprinosi` decimal(12,2) NOT NULL,
  `id_usluge_doprinosi` varchar(128) NOT NULL,
  PRIMARY KEY (`id_plate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pods_kalk`
--

DROP TABLE IF EXISTS `pods_kalk`;
CREATE TABLE IF NOT EXISTS `pods_kalk` (
  `brojpod` int(11) NOT NULL AUTO_INCREMENT,
  `partner` varchar(64) NOT NULL,
  `poziv_na_b` varchar(64) DEFAULT NULL,
  `iznos` decimal(12,2) DEFAULT NULL,
  `stiklirano` int(11) DEFAULT NULL,
  `b_kalkulacije` int(11) NOT NULL,
  `datum_za_plac` date NOT NULL,
  PRIMARY KEY (`brojpod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `ponuda`
--

DROP TABLE IF EXISTS `ponuda`;
CREATE TABLE IF NOT EXISTS `ponuda` (
  `id_ponude` int(11) NOT NULL AUTO_INCREMENT,
  `datum` date DEFAULT NULL,
  `sifra_fir` int(11) DEFAULT NULL,
  `rok` varchar(3) DEFAULT NULL,
  `izzad` decimal(12,0) DEFAULT NULL,
  `ispor` decimal(12,0) DEFAULT NULL,
  `odo_rab` decimal(12,0) DEFAULT NULL,
  `napomena` text,
  `partner_tekst` text,
  PRIMARY KEY (`id_ponude`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ponuda_stavke`
--

DROP TABLE IF EXISTS `ponuda_stavke`;
CREATE TABLE IF NOT EXISTS `ponuda_stavke` (
  `id_rob` int(11) NOT NULL AUTO_INCREMENT,
  `br_ponude` int(11) NOT NULL,
  `naziv_robe` varchar(128) DEFAULT NULL,
  `sifra_robe` int(11) DEFAULT NULL,
  `kolicina` decimal(12,0) DEFAULT NULL,
  `jed_mere` tinytext,
  `cena_profak` decimal(12,0) DEFAULT NULL,
  `rabat` decimal(12,0) DEFAULT NULL,
  `porez` int(4) DEFAULT NULL,
  `ruc_profak` decimal(12,0) DEFAULT NULL,
  PRIMARY KEY (`id_rob`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `poreske_stope`
--

DROP TABLE IF EXISTS `poreske_stope`;
CREATE TABLE IF NOT EXISTS `poreske_stope` (
  `id_poreske_stope` int(11) NOT NULL AUTO_INCREMENT,
  `opis_stope` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `porez_procenat` int(11) NOT NULL,
  `tarifa_stope` int(11) NOT NULL,
  `porez_datum` date NOT NULL,
  PRIMARY KEY (`id_poreske_stope`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `poreske_stope`
--

INSERT INTO `poreske_stope` (`id_poreske_stope`, `opis_stope`, `porez_procenat`, `tarifa_stope`, `porez_datum`) VALUES
(1, 'NIZA PORESKA STOPA', 10, 10, '2013-11-01'),
(2, 'VISA PORESKA STOPA', 20, 20, '2013-11-01'),
(4, 'OSLOBODJEN POREZA', 0, 30, '2014-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `prenos_stan`
--

DROP TABLE IF EXISTS `prenos_stan`;
CREATE TABLE IF NOT EXISTS `prenos_stan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naziv_robe` varchar(64) DEFAULT NULL,
  `cena_robe` decimal(12,2) DEFAULT NULL,
  `porez` decimal(10,0) DEFAULT NULL,
  `jed_mere` tinytext,
  `ruc` decimal(10,0) DEFAULT NULL,
  `kolicina` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profak`
--

DROP TABLE IF EXISTS `profak`;
CREATE TABLE IF NOT EXISTS `profak` (
  `broj_prof` int(11) NOT NULL AUTO_INCREMENT,
  `datum_prof` date DEFAULT NULL,
  `sifra_fir` int(11) DEFAULT NULL,
  `rok` varchar(3) DEFAULT NULL,
  `izzad` decimal(12,2) DEFAULT NULL,
  `ispor` decimal(12,2) DEFAULT NULL,
  `odo_rab` decimal(12,2) DEFAULT NULL,
  `bruc` decimal(12,2) DEFAULT NULL,
  `napomena` text,
  PRIMARY KEY (`broj_prof`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--
-- Table structure for table `profakrob`
--

DROP TABLE IF EXISTS `profakrob`;
CREATE TABLE IF NOT EXISTS `profakrob` (
  `id_rob` int(11) NOT NULL AUTO_INCREMENT,
  `br_profak` int(11) NOT NULL,
  `naziv_robe` varchar(128) DEFAULT NULL,
  `sifra_robe` int(11) DEFAULT NULL,
  `koli_profak` decimal(12,2) DEFAULT NULL,
  `jed_mere` tinytext,
  `cena_profak` decimal(12,5) DEFAULT NULL,
  `rab_dos` decimal(12,5) DEFAULT NULL,
  `porez` int(4) DEFAULT NULL,
  `ruc_profak` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id_rob`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `roba`
--

DROP TABLE IF EXISTS `roba`;
CREATE TABLE IF NOT EXISTS `roba` (
  `sifra` int(11) NOT NULL AUTO_INCREMENT,
  `naziv_robe` varchar(256) DEFAULT NULL,
  `cena_robe` decimal(12,2) DEFAULT NULL,
  `porez` decimal(10,0) DEFAULT NULL,
  `stanje` decimal(12,2) DEFAULT NULL,
  `jed_mere` varchar(6) DEFAULT NULL,
  `ruc` decimal(10,2) DEFAULT NULL,
  `kolicina` decimal(12,2) DEFAULT NULL,
  `poc_stanje` decimal(12,2) DEFAULT NULL,
  `usluga_opis` varchar(256) DEFAULT NULL,
  `sifra_knjig` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`sifra`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `ulaz`
--

DROP TABLE IF EXISTS `ulaz`;
CREATE TABLE IF NOT EXISTS `ulaz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `br_kal` int(11) NOT NULL,
  `srob_kal` int(11) DEFAULT NULL,
  `kol_kalk` decimal(12,2) DEFAULT NULL,
  `cena_k` decimal(12,2) DEFAULT NULL,
  `rab_kalk` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--
-- Table structure for table `usluge`
--

DROP TABLE IF EXISTS `usluge`;
CREATE TABLE IF NOT EXISTS `usluge` (
  `br_usluge` int(11) NOT NULL AUTO_INCREMENT,
  `sifusluge` varchar(64) DEFAULT NULL,
  `br_dok_us` varchar(64) DEFAULT NULL,
  `opis` varchar(64) DEFAULT NULL,
  `datum` date NOT NULL,
  `kontous` varchar(64) NOT NULL,
  `iznosus` decimal(12,2) NOT NULL,
  `pdv` decimal(12,2) NOT NULL,
  PRIMARY KEY (`br_usluge`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
