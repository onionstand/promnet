-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2017 at 11:53 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `enef-2017`
--

-- --------------------------------------------------------

--
-- Table structure for table `avans_rac`
--

CREATE TABLE `avans_rac` (
  `id` int(12) NOT NULL,
  `id_firme` int(10) NOT NULL,
  `opis` varchar(256) NOT NULL,
  `osnovica` decimal(12,0) NOT NULL,
  `porez` decimal(12,0) NOT NULL,
  `zbir` decimal(12,0) NOT NULL,
  `datum` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bankaupis`
--

CREATE TABLE `bankaupis` (
  `id_upl` int(11) NOT NULL,
  `br_izvoda` int(3) NOT NULL,
  `datum_izv` date NOT NULL,
  `sifra_par` int(11) NOT NULL,
  `broj_dok` varchar(64) DEFAULT NULL,
  `ulaz_novca` decimal(12,2) DEFAULT NULL,
  `izlaz_novca` decimal(12,2) DEFAULT NULL,
  `ziro_rac` varchar(64) DEFAULT NULL,
  `banka` int(3) NOT NULL,
  `svrha` varchar(32) NOT NULL,
  `fit_id` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `banke`
--

CREATE TABLE `banke` (
  `id_banke` int(11) NOT NULL,
  `ime_banke` varchar(64) NOT NULL,
  `poc_stanje` decimal(12,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blagajna`
--

CREATE TABLE `blagajna` (
  `br_blag` int(11) NOT NULL,
  `br_konta` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `opis_troska` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `blagulaz` decimal(12,2) DEFAULT NULL,
  `blagizn` decimal(12,2) DEFAULT NULL,
  `pdv_izn` decimal(12,2) DEFAULT NULL,
  `datum` date NOT NULL,
  `brupl` int(11) NOT NULL,
  `napomena` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dob_kup`
--

CREATE TABLE `dob_kup` (
  `sif_kup` int(11) NOT NULL,
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
  `ziro_rac2` varchar(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dosta`
--

CREATE TABLE `dosta` (
  `broj_dost` int(11) NOT NULL,
  `datum_d` date DEFAULT NULL,
  `sifra_fir` int(11) DEFAULT NULL,
  `rok` varchar(3) DEFAULT NULL,
  `izzad` decimal(12,2) DEFAULT NULL,
  `ispor` decimal(12,2) DEFAULT NULL,
  `odo_rab` decimal(12,2) DEFAULT NULL,
  `bruc` decimal(12,2) DEFAULT NULL,
  `napomena` text,
  `uplaceni_avans` decimal(12,2) DEFAULT NULL,
  `racun_rucni` int(12) DEFAULT NULL,
  `datum_prom` date DEFAULT NULL,
  `racun_poslat` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `glknjiga`
--

CREATE TABLE `glknjiga` (
  `id_glknjiga` int(12) NOT NULL,
  `sifradok` int(12) NOT NULL,
  `brdok` int(12) NOT NULL,
  `brkonta` int(12) NOT NULL,
  `datdok` date NOT NULL,
  `duguje` decimal(12,2) DEFAULT NULL,
  `potraz` decimal(12,2) DEFAULT NULL,
  `opis` varchar(128) NOT NULL,
  `prokont` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `izlaz`
--

CREATE TABLE `izlaz` (
  `id` int(11) NOT NULL,
  `br_dos` int(11) NOT NULL,
  `srob_dos` int(11) NOT NULL,
  `koli_dos` decimal(12,2) DEFAULT NULL,
  `cena_d` decimal(12,2) DEFAULT NULL,
  `rab_dos` decimal(12,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kalk`
--

CREATE TABLE `kalk` (
  `broj_kalk` int(11) NOT NULL,
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
  `rok_pl` varchar(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `konto`
--

CREATE TABLE `konto` (
  `id_kont` int(11) NOT NULL,
  `broj_kont` varchar(64) NOT NULL,
  `naziv_kont` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `k_pism_fin`
--

CREATE TABLE `k_pism_fin` (
  `id` int(12) NOT NULL,
  `id_firme` int(12) NOT NULL,
  `opis` text NOT NULL,
  `osnovica` decimal(12,0) NOT NULL,
  `pdv` int(12) NOT NULL,
  `zbir` int(12) NOT NULL,
  `duguje_potr` tinyint(2) NOT NULL,
  `propratni_dok` varchar(128) DEFAULT NULL,
  `datum` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `k_pism_r`
--

CREATE TABLE `k_pism_r` (
  `broj_k` int(11) NOT NULL,
  `kod_p` tinyint(4) NOT NULL,
  `dos_kal` varchar(64) NOT NULL,
  `dat_k` date NOT NULL,
  `tekst_k` text NOT NULL,
  `iznos_k` decimal(12,2) DEFAULT NULL,
  `iznos_f` decimal(12,2) DEFAULT NULL,
  `vel_rab_k` decimal(12,2) DEFAULT NULL,
  `vel_por_k` decimal(12,2) DEFAULT NULL,
  `partner` varchar(100) DEFAULT NULL,
  `sif_firme` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `k_pism_tr`
--

CREATE TABLE `k_pism_tr` (
  `id_k` int(11) NOT NULL,
  `broj_p` int(11) NOT NULL,
  `sif_rob_p` int(11) NOT NULL,
  `kolic_p` decimal(12,2) DEFAULT NULL,
  `rabat_p` int(11) DEFAULT NULL,
  `id_u_i` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nivel`
--

CREATE TABLE `nivel` (
  `broj_niv` int(11) NOT NULL,
  `datum_niv` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `niv_robe`
--

CREATE TABLE `niv_robe` (
  `id` int(11) NOT NULL,
  `br_niv` int(11) NOT NULL,
  `srob` int(11) NOT NULL,
  `srob_niv` int(11) NOT NULL,
  `koli_niv` decimal(12,2) DEFAULT NULL,
  `iznos_niv` decimal(12,0) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `plate`
--

CREATE TABLE `plate` (
  `id_plate` int(11) NOT NULL,
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
  `id_usluge_porez` int(11) NOT NULL,
  `id_usluge_pio_rad` int(11) NOT NULL,
  `id_usluge_zdrav_rad` int(11) NOT NULL,
  `id_usluge_nezap_rad` int(11) NOT NULL,
  `id_usluge_pio_pred` int(11) NOT NULL,
  `id_usluge_zdrav_pred` int(11) NOT NULL,
  `id_usluge_nezap_pred` int(11) NOT NULL,
  `id_usluge_porez_m` int(11) NOT NULL,
  `id_usluge_pio_rad_m` int(11) NOT NULL,
  `id_usluge_zdrav_rad_m` int(11) NOT NULL,
  `id_usluge_nezap_rad_m` int(11) NOT NULL,
  `id_usluge_pio_pred_m` int(11) NOT NULL,
  `id_usluge_zdrav_pred_m` int(11) NOT NULL,
  `id_usluge_nezap_pred_m` int(11) NOT NULL,
  `id_usluge_zbirni_doprinosi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pods_kalk`
--

CREATE TABLE `pods_kalk` (
  `brojpod` int(11) NOT NULL,
  `partner` varchar(64) NOT NULL,
  `poziv_na_b` varchar(64) DEFAULT NULL,
  `iznos` decimal(12,2) DEFAULT NULL,
  `stiklirano` int(11) DEFAULT NULL,
  `b_kalkulacije` int(11) NOT NULL,
  `datum_za_plac` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ponuda`
--

CREATE TABLE `ponuda` (
  `id_ponude` int(11) NOT NULL,
  `datum` date DEFAULT NULL,
  `sifra_fir` int(11) DEFAULT NULL,
  `rok` varchar(3) DEFAULT NULL,
  `izzad` decimal(12,0) DEFAULT NULL,
  `ispor` decimal(12,0) DEFAULT NULL,
  `odo_rab` decimal(12,0) DEFAULT NULL,
  `napomena` text,
  `ponuda_br_rucni` int(12) DEFAULT NULL,
  `partner_tekst` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ponuda_stavke`
--

CREATE TABLE `ponuda_stavke` (
  `id_rob` int(11) NOT NULL,
  `br_ponude` int(11) NOT NULL,
  `naziv_robe` varchar(128) DEFAULT NULL,
  `sifra_robe` int(11) DEFAULT NULL,
  `kolicina` decimal(12,0) DEFAULT NULL,
  `jed_mere` tinytext,
  `cena_profak` decimal(12,0) DEFAULT NULL,
  `rabat` decimal(12,0) DEFAULT NULL,
  `porez` int(4) DEFAULT NULL,
  `ruc_profak` decimal(12,0) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `poreske_stope`
--

CREATE TABLE `poreske_stope` (
  `id_poreske_stope` int(11) NOT NULL,
  `opis_stope` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `porez_procenat` int(11) NOT NULL,
  `tarifa_stope` int(11) NOT NULL,
  `porez_datum` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prenos_stan`
--

CREATE TABLE `prenos_stan` (
  `id` int(11) NOT NULL,
  `naziv_robe` varchar(64) DEFAULT NULL,
  `cena_robe` decimal(12,2) DEFAULT NULL,
  `porez` decimal(10,0) DEFAULT NULL,
  `jed_mere` tinytext,
  `ruc` decimal(10,0) DEFAULT NULL,
  `kolicina` decimal(12,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profak`
--

CREATE TABLE `profak` (
  `broj_prof` int(11) NOT NULL,
  `datum_prof` date DEFAULT NULL,
  `sifra_fir` int(11) DEFAULT NULL,
  `rok` varchar(3) DEFAULT NULL,
  `izzad` decimal(12,2) DEFAULT NULL,
  `ispor` decimal(12,2) DEFAULT NULL,
  `odo_rab` decimal(12,2) DEFAULT NULL,
  `bruc` decimal(12,2) DEFAULT NULL,
  `napomena` text,
  `brofak_rucni` int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profakrob`
--

CREATE TABLE `profakrob` (
  `id_rob` int(11) NOT NULL,
  `br_profak` int(11) NOT NULL,
  `naziv_robe` varchar(128) DEFAULT NULL,
  `sifra_robe` int(11) DEFAULT NULL,
  `koli_profak` decimal(12,2) DEFAULT NULL,
  `jed_mere` tinytext,
  `cena_profak` decimal(12,5) DEFAULT NULL,
  `rab_dos` decimal(12,5) DEFAULT NULL,
  `porez` int(4) DEFAULT NULL,
  `ruc_profak` decimal(12,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roba`
--

CREATE TABLE `roba` (
  `sifra` int(11) NOT NULL,
  `naziv_robe` varchar(256) DEFAULT NULL,
  `cena_robe` decimal(12,2) DEFAULT NULL,
  `porez` decimal(10,0) DEFAULT NULL,
  `stanje` decimal(12,2) DEFAULT NULL,
  `jed_mere` varchar(6) DEFAULT NULL,
  `ruc` decimal(10,2) DEFAULT NULL,
  `kolicina` decimal(12,2) DEFAULT NULL,
  `poc_stanje` decimal(12,2) DEFAULT NULL,
  `usluga_opis` varchar(256) DEFAULT NULL,
  `sifra_knjig` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ulaz`
--

CREATE TABLE `ulaz` (
  `id` int(11) NOT NULL,
  `br_kal` int(11) NOT NULL,
  `srob_kal` int(11) DEFAULT NULL,
  `kol_kalk` decimal(12,2) DEFAULT NULL,
  `cena_k` decimal(12,2) DEFAULT NULL,
  `rab_kalk` decimal(12,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `usluge`
--

CREATE TABLE `usluge` (
  `br_usluge` int(11) NOT NULL,
  `sifusluge` varchar(64) DEFAULT NULL,
  `br_dok_us` varchar(64) DEFAULT NULL,
  `opis` varchar(64) DEFAULT NULL,
  `datum` date NOT NULL,
  `kontous` varchar(64) NOT NULL,
  `iznosus` decimal(12,2) NOT NULL,
  `pdv` decimal(12,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avans_rac`
--
ALTER TABLE `avans_rac`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `bankaupis`
--
ALTER TABLE `bankaupis`
  ADD PRIMARY KEY (`id_upl`);

--
-- Indexes for table `banke`
--
ALTER TABLE `banke`
  ADD PRIMARY KEY (`id_banke`);

--
-- Indexes for table `blagajna`
--
ALTER TABLE `blagajna`
  ADD PRIMARY KEY (`br_blag`);

--
-- Indexes for table `dob_kup`
--
ALTER TABLE `dob_kup`
  ADD PRIMARY KEY (`sif_kup`);

--
-- Indexes for table `dosta`
--
ALTER TABLE `dosta`
  ADD PRIMARY KEY (`broj_dost`);

--
-- Indexes for table `glknjiga`
--
ALTER TABLE `glknjiga`
  ADD PRIMARY KEY (`id_glknjiga`);

--
-- Indexes for table `izlaz`
--
ALTER TABLE `izlaz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kalk`
--
ALTER TABLE `kalk`
  ADD PRIMARY KEY (`broj_kalk`);

--
-- Indexes for table `konto`
--
ALTER TABLE `konto`
  ADD PRIMARY KEY (`id_kont`);

--
-- Indexes for table `k_pism_fin`
--
ALTER TABLE `k_pism_fin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `k_pism_r`
--
ALTER TABLE `k_pism_r`
  ADD PRIMARY KEY (`broj_k`);

--
-- Indexes for table `k_pism_tr`
--
ALTER TABLE `k_pism_tr`
  ADD PRIMARY KEY (`id_k`);

--
-- Indexes for table `nivel`
--
ALTER TABLE `nivel`
  ADD PRIMARY KEY (`broj_niv`);

--
-- Indexes for table `niv_robe`
--
ALTER TABLE `niv_robe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plate`
--
ALTER TABLE `plate`
  ADD PRIMARY KEY (`id_plate`);

--
-- Indexes for table `pods_kalk`
--
ALTER TABLE `pods_kalk`
  ADD PRIMARY KEY (`brojpod`);

--
-- Indexes for table `ponuda`
--
ALTER TABLE `ponuda`
  ADD PRIMARY KEY (`id_ponude`);

--
-- Indexes for table `ponuda_stavke`
--
ALTER TABLE `ponuda_stavke`
  ADD PRIMARY KEY (`id_rob`);

--
-- Indexes for table `poreske_stope`
--
ALTER TABLE `poreske_stope`
  ADD PRIMARY KEY (`id_poreske_stope`);

--
-- Indexes for table `prenos_stan`
--
ALTER TABLE `prenos_stan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profak`
--
ALTER TABLE `profak`
  ADD PRIMARY KEY (`broj_prof`);

--
-- Indexes for table `profakrob`
--
ALTER TABLE `profakrob`
  ADD PRIMARY KEY (`id_rob`);

--
-- Indexes for table `roba`
--
ALTER TABLE `roba`
  ADD PRIMARY KEY (`sifra`);

--
-- Indexes for table `ulaz`
--
ALTER TABLE `ulaz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usluge`
--
ALTER TABLE `usluge`
  ADD PRIMARY KEY (`br_usluge`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avans_rac`
--
ALTER TABLE `avans_rac`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `bankaupis`
--
ALTER TABLE `bankaupis`
  MODIFY `id_upl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=543;
--
-- AUTO_INCREMENT for table `banke`
--
ALTER TABLE `banke`
  MODIFY `id_banke` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `blagajna`
--
ALTER TABLE `blagajna`
  MODIFY `br_blag` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dob_kup`
--
ALTER TABLE `dob_kup`
  MODIFY `sif_kup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;
--
-- AUTO_INCREMENT for table `dosta`
--
ALTER TABLE `dosta`
  MODIFY `broj_dost` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;
--
-- AUTO_INCREMENT for table `glknjiga`
--
ALTER TABLE `glknjiga`
  MODIFY `id_glknjiga` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `izlaz`
--
ALTER TABLE `izlaz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=413;
--
-- AUTO_INCREMENT for table `kalk`
--
ALTER TABLE `kalk`
  MODIFY `broj_kalk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;
--
-- AUTO_INCREMENT for table `konto`
--
ALTER TABLE `konto`
  MODIFY `id_kont` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `k_pism_fin`
--
ALTER TABLE `k_pism_fin`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `k_pism_r`
--
ALTER TABLE `k_pism_r`
  MODIFY `broj_k` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `k_pism_tr`
--
ALTER TABLE `k_pism_tr`
  MODIFY `id_k` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nivel`
--
ALTER TABLE `nivel`
  MODIFY `broj_niv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `niv_robe`
--
ALTER TABLE `niv_robe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `plate`
--
ALTER TABLE `plate`
  MODIFY `id_plate` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pods_kalk`
--
ALTER TABLE `pods_kalk`
  MODIFY `brojpod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;
--
-- AUTO_INCREMENT for table `ponuda`
--
ALTER TABLE `ponuda`
  MODIFY `id_ponude` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `ponuda_stavke`
--
ALTER TABLE `ponuda_stavke`
  MODIFY `id_rob` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;
--
-- AUTO_INCREMENT for table `poreske_stope`
--
ALTER TABLE `poreske_stope`
  MODIFY `id_poreske_stope` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `prenos_stan`
--
ALTER TABLE `prenos_stan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `profak`
--
ALTER TABLE `profak`
  MODIFY `broj_prof` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
--
-- AUTO_INCREMENT for table `profakrob`
--
ALTER TABLE `profakrob`
  MODIFY `id_rob` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;
--
-- AUTO_INCREMENT for table `roba`
--
ALTER TABLE `roba`
  MODIFY `sifra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=447;
--
-- AUTO_INCREMENT for table `ulaz`
--
ALTER TABLE `ulaz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;
--
-- AUTO_INCREMENT for table `usluge`
--
ALTER TABLE `usluge`
  MODIFY `br_usluge` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
