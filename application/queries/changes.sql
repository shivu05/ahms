/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  hp
 * Created: 24 Jan, 2019
 */

update treatmentdata set department=UPPER(replace(department,' ','_'));

CREATE TABLE `i_user_roles` (
  `user_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_role_id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `medicine_frequency`;
CREATE TABLE `medicine_frequency` (
  `med_frq_id` int(10) UNSIGNED NOT NULL,
  `med_freq` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medicine_frequency`
--

INSERT INTO `medicine_frequency` (`med_frq_id`, `med_freq`) VALUES
(1, 'BID'),
(2, 'TID'),
(3, 'QID');

-- --------------------------------------------------------

--
-- Table structure for table `product_master`
--

DROP TABLE IF EXISTS `product_master`;
CREATE TABLE `product_master` (
  `product_id` int(11) NOT NULL,
  `product_unique_id` varchar(50) NOT NULL DEFAULT '',
  `product_master_id` int(11) NOT NULL,
  `product_batch` varchar(45) NOT NULL DEFAULT '',
  `supplier_id` int(11) NOT NULL,
  `packing_name` varchar(10) NOT NULL DEFAULT '',
  `product_mfg` varchar(100) NOT NULL DEFAULT '',
  `product_type` varchar(45) NOT NULL DEFAULT '',
  `manifacture_date` date NOT NULL DEFAULT '0000-00-00',
  `exp_date` date NOT NULL DEFAULT '0000-00-00',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `purchase_rate` float(8,2) NOT NULL DEFAULT '0.00',
  `mrp` float(8,2) NOT NULL DEFAULT '0.00',
  `sale_rate` float(8,2) NOT NULL,
  `vat` float(8,2) NOT NULL,
  `no_of_items_in_pack` int(11) NOT NULL DEFAULT '0',
  `pack_type` varchar(45) NOT NULL DEFAULT '',
  `item_unit_cost` float(8,2) NOT NULL DEFAULT '0.00',
  `no_of_sub_items` int(11) NOT NULL DEFAULT '0',
  `sub_item_pack_type` varchar(45) NOT NULL DEFAULT '',
  `sub_item_unit_cost` float(8,2) NOT NULL,
  `no_of_sub_items_in_pack` int(11) NOT NULL DEFAULT '0',
  `discount` float(8,2) NOT NULL DEFAULT '0.00',
  `reorder_point` int(11) NOT NULL DEFAULT '0',
  `weight` varchar(45) NOT NULL DEFAULT '0.00',
  `rack` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='medicine information';

--
-- Dumping data for table `product_master`
--

INSERT INTO `product_master` (`product_id`, `product_unique_id`, `product_master_id`, `product_batch`, `supplier_id`, `packing_name`, `product_mfg`, `product_type`, `manifacture_date`, `exp_date`, `quantity`, `purchase_rate`, `mrp`, `sale_rate`, `vat`, `no_of_items_in_pack`, `pack_type`, `item_unit_cost`, `no_of_sub_items`, `sub_item_pack_type`, `sub_item_unit_cost`, `no_of_sub_items_in_pack`, `discount`, `reorder_point`, `weight`, `rack`) VALUES
(1, 'ba63b486-1674-4539-8e41-0d45f2f95b67', 2, '102211', 1, '100 ML', 'SDM PAHARMACY', 'TAILA', '2018-07-24', '2019-10-31', 10, 380.00, 455.00, 455.00, 0.00, 12, 'STRIP', 37.92, 10, 'NUMBER', 0.00, 120, 0.00, 5, '100.00', 10),
(2, '064bc9c0-262a-43ad-853f-6b2b04197fd3', 36, '14532018', 221, '100 mg', 'BHIDHYANATH', 'GUGGULU', '2018-12-01', '2020-12-31', 10, 480.00, 500.00, 500.00, 0.00, 12, 'STRIP', 41.67, 10, 'NUMBER', 0.00, 120, 0.00, 0, '100.00', 12);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_entry`
--

DROP TABLE IF EXISTS `purchase_entry`;
CREATE TABLE `purchase_entry` (
  `id` int(11) NOT NULL,
  `supplier_id` varchar(45) DEFAULT NULL,
  `billno` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `refno` varchar(45) DEFAULT NULL,
  `price` varchar(45) DEFAULT NULL,
  `opbal` varchar(45) DEFAULT NULL,
  `product` varchar(45) DEFAULT NULL,
  `batch` varchar(45) DEFAULT NULL,
  `pty` varchar(45) DEFAULT NULL,
  `fty` varchar(45) DEFAULT NULL,
  `prate` varchar(45) DEFAULT NULL,
  `mrp` varchar(45) DEFAULT NULL,
  `discount` varchar(45) DEFAULT NULL,
  `vat` varchar(45) DEFAULT NULL,
  `btype` varchar(45) DEFAULT NULL,
  `total` varchar(45) DEFAULT NULL,
  `product_name` varchar(45) DEFAULT NULL,
  `user_id` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_temp`
--

DROP TABLE IF EXISTS `purchase_return_temp`;
CREATE TABLE `purchase_return_temp` (
  `id` int(11) NOT NULL,
  `supplier_id` varchar(45) DEFAULT NULL,
  `billno` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `refno` varchar(45) DEFAULT NULL,
  `price` varchar(45) DEFAULT NULL,
  `opbal` varchar(45) DEFAULT NULL,
  `product` varchar(45) DEFAULT NULL,
  `batch` varchar(45) DEFAULT NULL,
  `pty` varchar(45) DEFAULT NULL,
  `fty` varchar(45) DEFAULT NULL,
  `prate` varchar(45) DEFAULT NULL,
  `mrp` varchar(45) DEFAULT NULL,
  `discount` varchar(45) DEFAULT NULL,
  `vat` varchar(45) DEFAULT NULL,
  `btype` varchar(45) DEFAULT NULL,
  `total` varchar(45) DEFAULT NULL,
  `product_name` varchar(45) DEFAULT NULL,
  `user_id` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_types_master`
--

DROP TABLE IF EXISTS `purchase_types_master`;
CREATE TABLE `purchase_types_master` (
  `pt_id` int(10) UNSIGNED NOT NULL,
  `pt_type` varchar(45) NOT NULL DEFAULT '',
  `pt_desc` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_types_master`
--

INSERT INTO `purchase_types_master` (`pt_id`, `pt_type`, `pt_desc`) VALUES
(1, 'SUPPLIER', 'Supplier'),
(2, 'PRODUCT', 'Product'),
(3, 'MFG', 'MFG'),
(4, 'GROUP', 'Group'),
(5, 'CATEGORY', 'Category');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_variables`
--

DROP TABLE IF EXISTS `purchase_variables`;
CREATE TABLE `purchase_variables` (
  `id` int(11) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `extrainfo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_variables`
--

INSERT INTO `purchase_variables` (`id`, `type`, `name`, `extrainfo`) VALUES
(1, 'supplier', 'SHRI VINAYAKA AYURVEDIC AGENCIES UDUPI', NULL),
(2, 'product', 'MAHANARAYANA TAILA', NULL),
(3, 'mfg', 'SDM PAHARMACY', '947'),
(4, 'group', 'TAILA', NULL),
(5, 'category', 'TAILA', NULL),
(6, 'supplier', 'M/S ROYAL PHARMA BIDAR', NULL),
(7, 'product', 'KSHEERA BALA TAILA', NULL),
(10, 'product', 'DHANWANTARAM TAILA', NULL),
(13, 'product', 'MURIVENNA TAILA', NULL),
(15, 'product', 'SAHACHARADI TAILA', NULL),
(17, 'product', 'KOTTAMCHUKKADI TAILA', NULL),
(18, 'product', 'BRUHAT SAINDHAVADHYA TAILA', NULL),
(19, 'product', 'GANDHARVAHASTHYADHYA TAILA', NULL),
(20, 'product', 'MOORCHITA TILA TAILA', NULL),
(29, 'product', 'TRIPHALA GUGGULU', NULL),
(30, 'product', 'KAISHORA GUGGULU', NULL),
(31, 'product', 'TRAYODASHANGA GUGGULU', NULL),
(32, 'product', 'YOGARAJA GUGGULU', NULL),
(33, 'product', 'SIMHANADA GUGGULU', NULL),
(34, 'product', 'MAHAYOGARAJA GUGGULU', NULL),
(35, 'product', 'RASNADI GUGGULU', NULL),
(36, 'product', 'LAXADI GUGGULU', NULL),
(37, 'product', 'PANCHATIKTA GRUTA GUGGULU', NULL),
(38, 'product', 'NAVAKA GUGGULU', NULL),
(39, 'group', 'GUGGULU', NULL),
(40, 'category', 'GUGGULU', NULL),
(42, 'product', 'KANCHANARA GUGGULU', NULL),
(50, 'product', 'CHITRAKADI VATI', NULL),
(51, 'product', 'AGNITUNDI VATI', NULL),
(52, 'product', 'AMAPACHANA VATI', NULL),
(53, 'product', 'VATAGAJANKUSHA RASA', NULL),
(54, 'product', 'MAHAVATAVIDHWAMSA RASA', NULL),
(55, 'product', 'VATAVIDHWAMSA RASA', NULL),
(56, 'product', 'BRUHATVATA CHINTAMANI RASA ', NULL),
(57, 'product', 'EKANGAVEERA RASA', NULL),
(58, 'product', 'SANDHI ABHAYA', NULL),
(59, 'product', 'ABHARAKA BHASMA', NULL),
(60, 'product', 'KHADIRADI VAT', NULL),
(61, 'product', 'AROGYAVARDHINI VATI', NULL),
(62, 'product', 'GANDHAKA RASYANA', NULL),
(63, 'product', 'CHANDRAPRABHA VATI', NULL),
(64, 'product', 'SHILAJATU VATI', NULL),
(65, 'product', 'SHIVA GUTIKA', NULL),
(66, 'product', 'VISHAMUSTI VATI', NULL),
(67, 'product', 'VASANTAKUSUMAKARA RASA', NULL),
(68, 'product', 'TARAKESHWARA RASA', NULL),
(69, 'product', 'LUMBATONE ', NULL),
(70, 'product', 'CERVILONE', NULL),
(71, 'product', 'SOOTASHEKARA RASA', NULL),
(72, 'product', 'LAGHUSOOTASHEKARA RASA', NULL),
(73, 'product', 'PALSINURON', NULL),
(74, 'product', 'KSHEERABALA 101 DS', NULL),
(75, 'product', 'DHANWANTARAM DS', NULL),
(76, 'product', 'KSHEERA BALA 101 TAILA', NULL),
(77, 'product', 'TRIGUNAKYA RASA', NULL),
(78, 'product', 'MEDHOHARA VIDANGADI LOHA', NULL),
(79, 'product', 'KAMDUGDHA RASA', NULL),
(80, 'product', 'SWASAKUTARA RASA', NULL),
(81, 'product', 'SWASANANDANA GUTIKA', NULL),
(82, 'product', 'SWASAKASACHINTAMANI RASA', NULL),
(83, 'product', 'KAPHAKUTARA RASA ', NULL),
(84, 'product', 'KRIMIKUTARA RASA', NULL),
(85, 'product', 'SUDARSHANA VATI', NULL),
(86, 'product', 'GODANTI BHASMA', NULL),
(87, 'product', 'SHANKA VATI', NULL),
(88, 'product', 'PUNANRNAVA MANDOORA VATAKA', NULL),
(89, 'product', 'MANDOORA VATAKA', NULL),
(90, 'product', 'NAVAYASA LOHA', NULL),
(91, 'product', 'DHATRI LOHA', NULL),
(92, 'product', 'SAPTAMRUTA LOHA VATI', NULL),
(93, 'product', 'SHIRASHULADRI VAJRA RASA ', NULL),
(94, 'product', 'CEPHAGRAINE ', NULL),
(95, 'product', 'NAGAGUTI', NULL),
(96, 'product', 'CHANDRAKALA RASA', NULL),
(97, 'product', 'BOLABADDHA RASA', NULL),
(98, 'product', 'PRAVALA PISTI', NULL),
(99, 'product', 'PANCHAMRUTA PARPATI', NULL),
(100, 'product', 'RASA PARPATI', NULL),
(101, 'product', 'SAMSHAMANA VATI', NULL),
(102, 'product', 'BRAHMI VATI ', NULL),
(103, 'product', 'MANASAMITRA VATI', NULL),
(104, 'product', 'LAXMI VILASA RASA', NULL),
(105, 'product', 'MAHALAXMI VILASA RASA', NULL),
(106, 'product', 'SUNIDRA ', NULL),
(107, 'product', 'RECTONE', NULL),
(108, 'product', 'ANARSHA', NULL),
(109, 'product', 'ARSHACARE', NULL),
(110, 'product', 'TANTUPASHANA VATI', NULL),
(111, 'product', 'UNMADA GAJAKESHARI RASA', NULL),
(112, 'product', 'GRAHANI KAPATA RASA', NULL),
(113, 'product', 'MYROLAX', NULL),
(114, 'product', 'ANULOMANA DS', NULL),
(115, 'product', 'STOAMATAB', NULL),
(116, 'product', 'PROLIFE', NULL),
(117, 'product', 'NEERI', NULL),
(118, 'product', 'STONEX', NULL),
(119, 'product', 'MULTITONE', NULL),
(120, 'product', 'DHATUVRUDDHI', NULL),
(121, 'product', 'PRAMEHIN', NULL),
(122, 'product', 'LIV 52 DS', NULL),
(123, 'product', 'KANKAYANA VATI', NULL),
(124, 'product', 'PILEX', NULL),
(125, 'product', 'ABHANA', NULL),
(126, 'product', 'SARPHAGANDHAGHANA VATI', NULL),
(127, 'product', 'KUSTAGNA VATI', NULL),
(128, 'product', 'TIKTAMRUTA', NULL),
(129, 'product', 'ARSHOGHNA VATI', NULL),
(130, 'mfg', 'BHIDHYANATH', '125'),
(131, 'mfg', 'SWADESHI', '156'),
(132, 'mfg', 'DHOOTAPAPESHWAR', '5689'),
(133, 'mfg', 'ARYAVAIDHYA PHARMACY', '56792'),
(134, 'mfg', 'ALWAS PHARMACY', '2598'),
(135, 'mfg', 'PENTACARE', '56421'),
(136, 'mfg', 'CHARAKA', '8913'),
(137, 'group', 'VATI', NULL),
(138, 'mfg', 'ZANDU', '64123'),
(139, 'group', 'ASAVA', NULL),
(140, 'mfg', 'CHAITANYA PHARMACY', '32468'),
(141, 'mfg', 'NAGARJUNA', '3649'),
(142, 'mfg', 'ARYAVAIDHYA SHALA', '7619'),
(143, 'group', 'ARISTA', NULL),
(144, 'group', 'DROPS', NULL),
(145, 'group', 'LEHYA', NULL),
(146, 'group', 'CHOORNA', NULL),
(147, 'group', 'BHASMA', NULL),
(148, 'group', 'GRUTA', NULL),
(149, 'group', 'OINTMENT', NULL),
(150, 'group', 'KASHAYA', NULL),
(151, 'category', 'VATI', NULL),
(152, 'category', 'ASAVA', NULL),
(153, 'category', 'ARISTA', NULL),
(154, 'category', 'ASAVA', NULL),
(155, 'category', 'DROPS', NULL),
(156, 'category', 'LEHYA', NULL),
(157, 'category', 'CHOORNA', NULL),
(158, 'category', 'BHASMA', NULL),
(159, 'category', 'GRUTA', NULL),
(160, 'category', 'OINTMENT', NULL),
(161, 'category', 'KASHAYA', NULL),
(162, 'group', 'TABLET', NULL),
(163, 'group', 'SYRUP', NULL),
(164, 'group', 'INJECTION', NULL),
(165, 'group', 'CAPSULES', NULL),
(166, 'group', 'SUPOOSITORS', NULL),
(167, 'group', 'SPRAY', NULL),
(168, 'group', 'LINAMENT', NULL),
(169, 'category', 'TABLET', NULL),
(170, 'category', 'SYRUP', NULL),
(171, 'category', 'INJECTION', NULL),
(172, 'category', 'CAPSULES', NULL),
(173, 'category', 'SUPPOSITORS', NULL),
(174, 'category', 'SPRAY', NULL),
(175, 'category', 'LINAMENT', NULL),
(179, 'supplier', 'xyz', NULL),
(198, 'mfg', 'SG PHYATOPHARMA', '3679'),
(215, 'mfg', 'HIMALAYA PHARMA', '4593'),
(221, 'supplier', 'DHANWANTARI PHARMA', NULL),
(233, 'mfg', 'BAL PHARMA', '5986'),
(235, 'mfg', 'BAYIR CHEMICALS', '3489'),
(237, 'mfg', 'SAGAR PHARMACEUTICALS', '2896'),
(238, 'product', 'ANULOMANA DS', 'extra'),
(239, 'product', 'STOMATAB', ''),
(240, 'product', 'PROLIFE', ''),
(241, 'product', 'NEERI', ''),
(242, 'product', 'STONEX', ''),
(243, 'product', 'MULTITONE', ''),
(244, 'product', 'DHATUVRUDDI', ''),
(245, 'product', 'PRAMEHIN', ''),
(246, 'product', 'LIV 52 DS', ''),
(247, 'product', 'KANKAYANA VATI', ''),
(248, 'product', 'PILEX', ''),
(249, 'product', 'SARPHAGANDHA GHANA VATI', ''),
(250, 'product', 'ABHANA', ''),
(251, 'product', 'KUSTAGHNA VATI', ''),
(252, 'product', 'TIKTAMRUTA', ''),
(253, 'product', 'ARSHOGHNA ', ''),
(254, 'product', 'SAHACHARADI KASHAYA', ''),
(255, 'product', 'TRIPHALA KASHAYA', ''),
(256, 'product', 'DASHAMOOLA KASHAYA', ''),
(257, 'product', 'RASNAERANADADI KASHAYA', ''),
(258, 'product', 'MAHARASNADI KASHAYA', ''),
(259, 'product', 'MANJISTADI KASHAYA', ''),
(260, 'product', 'MAHAMANJISTADI KASHAYA', ''),
(261, 'product', 'ASTAVARGA KASHAYA', ''),
(262, 'product', 'RASNADI KASHAYA', ''),
(263, 'product', 'GUGGULU TIKTAKA KASHAYA', ''),
(264, 'product', 'TIKTA KASHAYA', ''),
(265, 'product', 'PANCHATIKTA KASHAYA', ''),
(266, 'product', 'MAHATIKTAKA KASHAYA', ''),
(267, 'product', 'GANDHARVA HASTHYADI KASHAYA', ''),
(268, 'product', 'BRUNGASMALAKA KASHAYA', ''),
(269, 'product', 'AMRUTOTTARA KASHAYA', ''),
(270, 'product', 'BRUHATYADI KASHAYA', ''),
(271, 'product', 'CHIRABILWADI KASHAYA', ''),
(272, 'product', 'DHANADHANYADI KASHAYA', ''),
(273, 'product', 'DHANWANTARAM KASHAYA', ''),
(274, 'product', 'GOKSHURADI KWATHA', ''),
(275, 'product', 'JIVANTHYADI KASHAYA', ''),
(276, 'product', 'MULAKADI KASHAY', ''),
(277, 'product', 'NIMBHADI KASHAYA', ''),
(278, 'product', 'PATHYADI KHADA', ''),
(279, 'product', 'PATOLADI KWATHA', ''),
(280, 'product', 'PUNARNAVADI KWATHA', ''),
(281, 'product', 'VARUNADI KASHAYA', ''),
(282, 'product', 'BRAHMI GRUTA', ''),
(283, 'product', 'PANCHATIKTA GRUTA', ''),
(284, 'product', 'MAHATIKTAKA GRUTA', ''),
(285, 'product', 'GUGGULUTIKTAKA GRUTA', ''),
(286, 'product', 'INDUKANTA GRUTA', ''),
(287, 'product', 'SUKUMARA GRUTA', ''),
(288, 'product', 'VIDARYADI GRUTA', ''),
(289, 'product', 'PANCHAGAVYAGRUTA', ''),
(290, 'product', 'TRIPHALA GRUTA', ''),
(291, 'product', 'PHALA GRUTA', ''),
(292, 'product', 'PIPPALYADI GRUTA', ''),
(293, 'product', 'KANTAKARI GRUTA', ''),
(294, 'product', 'KALYANAKA GRUTA', ''),
(295, 'product', 'MAHAKALYANAKA GRITA', ''),
(296, 'product', 'JIVANTYADI GRUTA', ''),
(297, 'product', 'DASHAMOOLA GRUTA', ''),
(298, 'product', 'AMRUTAPRASHA GRUTA', ''),
(299, 'product', 'ABHAYRISTA', ''),
(300, 'product', 'DASHAMOOLARISTA', ''),
(301, 'product', 'ASHOKARISTA', ''),
(302, 'product', 'SARASWATARISTA', ''),
(303, 'product', 'KANAKASAVA', ''),
(304, 'product', 'PUSKARAMOOLASAVA', ''),
(305, 'product', 'ASHWAGANDHARISTA', ''),
(306, 'product', 'BALARISTA', ''),
(307, 'product', 'BRUNGARJASAVA', ''),
(308, 'product', 'DRAKSHARISTA', ''),
(309, 'product', 'JEERAKADHYARISTA', ''),
(310, 'product', 'KAROORASAVA', ''),
(311, 'product', 'KHADIRARISTA', ''),
(312, 'product', 'KUMARYASAVA', ''),
(313, 'product', 'LOHASAVA', ''),
(314, 'product', 'KUTAJARISTA', ''),
(315, 'product', 'KUTAJAMUSTAKARISTA', ''),
(316, 'product', 'PATRANGASAVA', ''),
(317, 'product', 'PIPPALYASAVA', ''),
(318, 'product', 'ROHITAKARISTA', ''),
(319, 'product', 'SARIVADHYASAVA', ''),
(320, 'product', 'CHANDANASAVA', ''),
(321, 'product', 'USHEERASAVA', ''),
(322, 'product', 'VASAKASAVA', ''),
(323, 'product', 'VIDANGARISTA', ''),
(324, 'product', 'SITOPALADI CHOORNA', ''),
(325, 'product', 'TALISADI CHOORNA', ''),
(326, 'product', 'AMALAKICHOORNA', ''),
(327, 'product', 'AVIPATTIKARA CHOORNA', ''),
(328, 'product', 'HINGWASTAKA CHOORNA', ''),
(329, 'product', 'GANGHADHARA CHOORNA', ''),
(330, 'product', 'DADIMASTAKA CHOORNA', ''),
(331, 'product', 'ELADI CHOORNA', ''),
(332, 'product', 'HARIDRAKHANDA', ''),
(333, 'product', 'JATIPHALA CHOORNA', ''),
(334, 'product', 'KALYANAKA AVALEHA CHOORNA', ''),
(335, 'product', 'NARAYANA CHOORNA', ''),
(336, 'product', 'NIMBADI CHOORNA', ''),
(337, 'product', 'PUSHYANAGA CHOORNA', ''),
(338, 'product', 'SHATAVARYADI GRANULES', ''),
(339, 'product', 'SUDARSHANA CHOORNA', ''),
(340, 'product', 'TRIPHALA CHOORNA', ''),
(341, 'product', 'YAVANISHADAVA CHOORNA', ''),
(342, 'product', 'DERMACID ', ''),
(343, 'product', 'DERMEX', ''),
(344, 'product', 'KANAKA LEPA', ''),
(345, 'product', 'MYOSTAL OINT', ''),
(346, 'product', 'SHALLAKI PLUS', ''),
(347, 'product', 'SHALLAKI LINAMENT', ''),
(348, 'product', 'DASHANGA LEPA', ''),
(349, 'product', 'SINDURADI LEPA', ''),
(350, 'product', 'AVALGUJADI LEPA', ''),
(351, 'product', 'ROPANI OINT', ''),
(352, 'product', 'KUNKUMADI LEPA', ''),
(353, 'product', 'SHOTHAGNA LEPA', ''),
(354, 'product', 'MUKHADOOSHIHARA LEPA', ''),
(355, 'product', 'FESTIVE E', ''),
(356, 'product', 'FESTIVE D', ''),
(357, 'product', 'CEFLOX E', ''),
(358, 'product', ' CEFLOX D', ''),
(359, 'product', 'WOFLOX D', ''),
(360, 'product', 'UNIDINE POWDER', ''),
(361, 'product', 'WOMETRO', ''),
(362, 'product', 'Q MAX OZ', ''),
(363, 'product', 'L DOPER', ''),
(364, 'product', 'ZENLOC ', ''),
(365, 'product', 'B CO PAC ', ''),
(366, 'product', 'NODARD PLUS', ''),
(367, 'product', 'WOFLOX OZ', ''),
(368, 'product', 'LOVOLKEM', ''),
(369, 'product', 'MULTIPREX', ''),
(370, 'product', 'NASELIN SPRAY', ''),
(371, 'product', 'NIRCIP', ''),
(372, 'product', 'COTTON WOOL IP', ''),
(373, 'product', 'PILES CURE', ''),
(374, 'product', 'MAFENAR SPAS', ''),
(375, 'product', 'DERIPHYLLIN', ''),
(376, 'product', 'ALPHA B12', ''),
(377, 'product', 'ALPHA CPM', ''),
(378, 'product', 'RANIVIN', ''),
(379, 'product', 'ALPHAMET', ''),
(380, 'product', 'MEDRAGAN', ''),
(381, 'product', 'DICLOVERON', ''),
(382, 'product', 'ALPHADEXA', ''),
(383, 'product', 'ALPHAPLEX', ''),
(384, 'product', 'ALPHAGEN', ''),
(385, 'product', 'ONDITRON', ''),
(386, 'product', 'VITCOFOL', ''),
(387, 'product', 'RANIPEP', ''),
(388, 'product', 'AVIL', ''),
(389, 'product', 'REACTIN PLUS', ''),
(390, 'product', 'HEXAMOX', ''),
(391, 'product', 'CITZER COLD', ''),
(392, 'product', 'ACEFLAM P', ''),
(393, 'product', 'ACEFLAM SR', ''),
(394, 'product', 'SCALP VEIN 23 G', ''),
(395, 'product', 'SCALP VEIN 22 G', ''),
(396, 'product', 'AVIL AMP', ''),
(397, 'product', 'SAFTI URINE BAGS', ''),
(398, 'product', 'FOLLIES CATHETOR 16 G', ''),
(399, 'product', 'ROLLED BANDAGE 150CMX3', ''),
(400, 'product', 'GLOVES 7.5', ''),
(401, 'product', 'DISPOVAN SYRINGE 5ML', ''),
(402, 'product', 'DISPOVAN SYRINGE 2ML', ''),
(403, 'product', 'HYDROZEN PEROXIDE', ''),
(404, 'product', 'SURGICAL SPIRIT', ''),
(405, 'product', 'CIPLADINE SOLUTION', ''),
(406, 'product', 'IV SET', ''),
(407, 'product', 'NASOCEF 1GM', ''),
(408, 'product', 'NASOCEF S 1.5GM', ''),
(409, 'product', 'MEDGRIP 10 CM', ''),
(410, 'product', 'AMIJECT ', ''),
(411, 'product', 'ATROPIN', ''),
(412, 'product', 'MAEDITAPE', ''),
(413, 'product', 'POVIKEM OINT', ''),
(414, 'product', 'RAPID GEL', ''),
(415, 'product', 'WOCANE 2%', ''),
(416, 'product', 'OMPRAZ NOVA ', ''),
(417, 'product', 'HANSAPLAST', ''),
(418, 'product', 'DNS ', ''),
(419, 'product', 'RL', ''),
(420, 'product', 'NS', ''),
(421, 'product', 'DEXTROSE', ''),
(422, 'product', 'KETOKEM SHAMPOO', ''),
(423, 'product', 'SPINAL NEEDLE NO 22', ''),
(424, 'product', 'OMEZ', ''),
(425, 'product', 'CALMPOSE', ''),
(426, 'product', 'PERINORM', ''),
(427, 'product', 'XYLOCAINE', ''),
(428, 'product', 'MARICHYADI TAILA', NULL),
(429, 'product', 'PANCHAMRUTA PARPATI', NULL),
(430, 'product', 'BILVADI GULIKA ', NULL),
(431, 'product', 'MEDHOHARA KASHAYA', NULL),
(432, 'product', 'VATSAKADI KASHAYA', NULL),
(433, 'product', 'GOMUTRA HAREETAKI', NULL),
(434, 'product', 'CHITRAKA HAREETAKI', NULL),
(435, 'product', 'PANCHAMRUTA PARPATI', NULL),
(436, 'product', 'PANCHAMRUTA RASA', NULL),
(437, 'product', 'TRIKATU CHOORNA', NULL),
(438, 'product', 'BRAHMA RASAYANA', NULL),
(439, 'product', 'KELSILOHA', NULL),
(440, 'product', 'LODRASAVA', NULL),
(441, 'product', 'MAHACEF ', NULL),
(442, 'category', 'MANKIND PHARMA', NULL),
(443, 'mfg', 'MANKIND', '12345'),
(444, 'product', 'ACILOC', NULL),
(445, 'product', 'JYATHYADI TAILA', NULL),
(446, 'product', 'ARAGWADA KASHAYA', NULL),
(447, 'product', 'NITYANANDA RASA', NULL),
(448, 'product', 'NITYANANDA RASA', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medicine_frequency`
--
ALTER TABLE `medicine_frequency`
  ADD PRIMARY KEY (`med_frq_id`);

--
-- Indexes for table `product_master`
--
ALTER TABLE `product_master`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `purchase_entry`
--
ALTER TABLE `purchase_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return_temp`
--
ALTER TABLE `purchase_return_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_types_master`
--
ALTER TABLE `purchase_types_master`
  ADD PRIMARY KEY (`pt_id`);

--
-- Indexes for table `purchase_variables`
--
ALTER TABLE `purchase_variables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `medicine_frequency`
--
ALTER TABLE `medicine_frequency`
  MODIFY `med_frq_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_master`
--
ALTER TABLE `product_master`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_entry`
--
ALTER TABLE `purchase_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_temp`
--
ALTER TABLE `purchase_return_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_types_master`
--
ALTER TABLE `purchase_types_master`
  MODIFY `pt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_variables`
--
ALTER TABLE `purchase_variables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=449;
COMMIT;