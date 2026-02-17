-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 18, 2026 at 12:06 AM
-- Server version: 5.6.51
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boost_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `boost_activities`
--

CREATE TABLE `boost_activities` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(20) NOT NULL,
  `item_id` int(11) NOT NULL,
  `short_message` varchar(100) NOT NULL,
  `label` varchar(50) NOT NULL,
  `link` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_activities`
--

INSERT INTO `boost_activities` (`id`, `date_created`, `category`, `item_id`, `short_message`, `label`, `link`) VALUES
(1, '2015-10-07 11:20:08', 'invoices', 1182, '1200 created', 'Invoice #INV-1000063', 'invoices/1182'),
(2, '2015-10-07 11:27:53', 'invoices', 1183, '450 created', 'Invoice #INV-1000064', 'invoices/1183'),
(3, '2015-10-07 11:30:05', 'invoices', 1185, '450 created', 'Invoice #INV-1000066', 'invoices/1185'),
(4, '2015-10-07 11:32:52', 'invoices', 1188, 'R450 created', 'Invoice #INV-1000069', 'invoices/1188'),
(5, '2015-10-07 12:04:51', 'invoices', 1188, 'R50 Payment added', 'Invoice #INV-1000069', 'invoices/1188'),
(6, '2015-10-07 12:17:46', 'invoices', 1188, 'R200 Paid in full', 'Invoice #INV-1000069', 'invoices/1188'),
(7, '2015-10-07 12:18:29', 'invoices', 1187, 'R50 Paid in full', 'Invoice #INV-1000068', 'invoices/1187'),
(8, '2015-10-07 12:19:35', 'invoices', 1186, 'R-50 ', 'Invoice #INV-1000067', 'invoices/1186'),
(9, '2015-10-07 12:21:09', 'invoices', 1186, 'R200 Paid in full', 'Invoice #INV-1000067', 'invoices/1186'),
(10, '2015-10-07 12:24:40', 'invoices', 1185, 'R200 Payment added', 'Invoice #INV-1000066', 'invoices/1185');

-- --------------------------------------------------------

--
-- Table structure for table `boost_company_sizes`
--

CREATE TABLE `boost_company_sizes` (
  `id` int(11) NOT NULL,
  `size` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_company_sizes`
--

INSERT INTO `boost_company_sizes` (`id`, `size`) VALUES
(1, '0-10'),
(2, '11-20'),
(3, '21-30');

-- --------------------------------------------------------

--
-- Table structure for table `boost_contacts`
--

CREATE TABLE `boost_contacts` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `contact_type_id` int(11) NOT NULL,
  `organisation` varchar(50) NOT NULL,
  `vat_number` varchar(15) DEFAULT NULL,
  `industry_id` varchar(35) DEFAULT NULL,
  `company_size_id` varchar(15) DEFAULT NULL,
  `first_name` varchar(40) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `land_line` varchar(25) DEFAULT NULL,
  `mobile` varchar(25) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_contacts`
--

INSERT INTO `boost_contacts` (`id`, `date_created`, `contact_type_id`, `organisation`, `vat_number`, `industry_id`, `company_size_id`, `first_name`, `last_name`, `email`, `land_line`, `mobile`, `address`, `date_modified`) VALUES
(1, '2015-08-12 10:33:34', 2, 'Builders (Pty)Ltd', '', '1', '1', 'Leopold', 'McNificent', 'leo@mac.co.za', '011 655 7895', '082 694 4428', '109 Plein street\r\nJohannesburg\r\n1854', '2015-10-19 11:45:40'),
(2, '2015-08-14 09:49:01', 1, 'Cookies Galore', NULL, '1', '2', 'Janet', 'Fienkelstein', 'janet@fienkelstein.co.za', '012 556 4789', NULL, '25 Orange Pear Ave\r\nCenturion\r\nCity Of Tshwane', '2015-08-18 10:47:00'),
(3, '2015-08-14 09:51:21', 1, 'Imbvu Holdings', NULL, '1', '1', 'Nomusa', 'Nene', 'nomusa@nene.co.za', '011 365 4882', NULL, '122 DD Drive\r\nJump City\r\nJohannesburg', '2015-08-14 09:51:21'),
(4, '2015-08-14 09:53:34', 2, 'Extraodinary Stationers', NULL, '1', '3', 'James', 'Martin', 'james@martin.com', '011 985 3695', NULL, '235 Fairway rd\r\nRiver Rock\r\nJohannesburg', '2015-08-14 09:53:34'),
(5, '2015-09-01 08:42:49', 1, 'Elite Insurance', NULL, '1', '3', 'Nicholsa', 'McGregor', 'nic@ez.co.za', '011 321 9876', NULL, '247 Roosevelt Avenue, Randburg', '2015-09-01 08:42:49'),
(6, '2015-09-14 08:59:44', 1, 'test addition', '', '1', '1', 'Brad', '', 'brad@sointeractive.co.za', '', '', '', '2015-09-14 08:59:44'),
(7, '2015-09-14 09:01:21', 1, 'test 2', '', '1', '1', 'Brad', '', 'brad@sointeractive.co.za', '', '', '', '2015-09-14 09:01:21'),
(8, '2015-09-14 12:04:18', 1, 'test 3', '', '1', '1', 'brad', '', 'brad@sointeractive.co.za', '', '', '', '2015-09-14 12:04:18'),
(9, '2015-09-14 12:05:07', 1, 'test 4', '', '1', '1', 'Brad', '', 'brad@sointeractive.co.za', '', '', '', '2015-09-14 12:05:07'),
(10, '2015-09-14 12:06:00', 1, 'test 5', '', '1', '1', 'Brad', '', 'brad@sointeractive.co.za', '', '', '', '2015-09-14 12:06:00'),
(11, '2015-09-25 10:35:31', 1, 'Pride (Pty) Ltd', '', '1', '1', 'Pride', '', 'pride@sointeractive.co.za', '', '', '', '2015-09-25 10:35:31'),
(12, '2015-10-06 14:30:48', 1, 'X Men', '', '1', '1', 'Charles', 'Xavier', 'charles@xmen.org', '', '', '', '2015-10-06 14:30:48'),
(13, '2015-10-16 13:31:12', 1, 'Darrens super slaves', '', '1', '1', '', '', 'darren@sointeractive.co.za', '', '', '', '2015-10-16 13:31:12'),
(14, '2015-10-19 11:21:00', 1, 'Le Twins', '', '1', '1', '', '', 'twins@gmail.com', '', '', '', '2015-10-19 11:21:00'),
(15, '2015-10-19 11:21:56', 1, 'Flowers Inc.', '', '1', '1', '', '', 'florist@flowers.com', '', '', '', '2015-10-19 11:23:45'),
(16, '2015-10-19 12:14:37', 1, 'Brads Dev Shop', '123456', '3', '3', 'Brad', 'Greenwood', 'brad@sointeractive.co.za', '+27 011 609 1986', '+27 82 694 4428', 'Unit 57 Gleneagles, 13 Uys Avenue\r\nEdenglen\r\nEdenvale\r\n1613', '2015-10-19 12:15:31'),
(17, '2015-11-18 07:39:34', 1, 'test client', '', '1', '1', '', '', 'brad@sointeractive.co.za', '', '', '', '2015-11-18 07:39:34'),
(18, '2015-11-18 07:41:06', 1, 'brad test', '', '1', '1', '', '', 'brad@sointeractive.co.za', '', '', '', '2015-11-18 07:41:06'),
(19, '2015-11-18 08:02:31', 1, 'Brads pc house', '', '1', '1', '', '', 'brad@sointeractive.co.za', '', '', '', '2015-11-18 08:02:31'),
(20, '2015-12-11 14:13:54', 1, 'test n1', '', '1', '1', '', '', 'brad@sointeractive.co.za', '', '', '', '2015-12-11 14:13:54'),
(21, '2015-12-11 14:22:03', 1, 'test n2', '', '1', '1', '', '', 'bradley.greenwood@gmail.com', '', '', '', '2015-12-11 14:22:03'),
(22, '2015-12-11 14:22:54', 1, 'test n3', '', '1', '1', '', '', 'bradley.greenwood@gmail.com', '', '', '', '2015-12-11 14:22:54'),
(23, '2015-12-11 14:26:23', 1, 'test n4', '', '1', '1', '', '', 'brad@sointeractive.co.za', '', '', '', '2015-12-11 14:26:23'),
(24, '2015-12-11 14:39:18', 1, 'test n5', '', '1', '1', '', '', 'brad@sointeractive.co.za', '', '', '', '2015-12-11 14:39:18'),
(25, '2015-12-11 14:42:15', 1, 'test n6', '', '1', '1', '', '', 'brad@sointeractive.co.za', '', '', '', '2015-12-11 14:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `boost_contact_types`
--

CREATE TABLE `boost_contact_types` (
  `id` int(11) NOT NULL,
  `type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_contact_types`
--

INSERT INTO `boost_contact_types` (`id`, `type`) VALUES
(1, 'client'),
(2, 'supplier');

-- --------------------------------------------------------

--
-- Table structure for table `boost_countries`
--

CREATE TABLE `boost_countries` (
  `id` int(11) NOT NULL,
  `country` varchar(100) NOT NULL,
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_countries`
--

INSERT INTO `boost_countries` (`id`, `country`, `active`) VALUES
(1, 'Afghanistan', 1),
(2, 'Åland Islands', 1),
(3, 'Albania', 1),
(4, 'Algeria', 1),
(5, 'American Samoa', 1),
(6, 'Andorra', 1),
(7, 'Angola', 1),
(8, 'Anguilla', 1),
(9, 'Antarctica', 1),
(10, 'Antigua and Barbuda', 1),
(11, 'Argentina', 1),
(12, 'Armenia', 1),
(13, 'Aruba', 1),
(14, 'Australia', 1),
(15, 'Austria', 1),
(16, 'Azerbaijan', 1),
(17, 'Bahamas', 1),
(18, 'Bahrain', 1),
(19, 'Bangladesh', 1),
(20, 'Barbados', 1),
(21, 'Belarus', 1),
(22, 'Belgium', 1),
(23, 'Belize', 1),
(24, 'Benin', 1),
(25, 'Bermuda', 1),
(26, 'Bhutan', 1),
(27, 'Bolivia', 1),
(28, 'Bosnia and Herzegovina', 1),
(29, 'Botswana', 1),
(30, 'Bouvet Island', 1),
(31, 'Brazil', 1),
(32, 'British Indian Ocean Territory', 1),
(33, 'Brunei Darussalam', 1),
(34, 'Bulgaria', 1),
(35, 'Burkina Faso', 1),
(36, 'Burundi', 1),
(37, 'Cambodia', 1),
(38, 'Cameroon', 1),
(39, 'Canada', 1),
(40, 'Cape Verde', 1),
(41, 'Cayman Islands', 1),
(42, 'Central African Republic', 1),
(43, 'Chad', 1),
(44, 'Chile', 1),
(45, 'China', 1),
(46, 'Christmas Island', 1),
(47, 'Cocos (Keeling) Islands', 1),
(48, 'Colombia', 1),
(49, 'Comoros', 1),
(50, 'Congo', 1),
(51, 'Congo, The Democratic Republic of The', 1),
(52, 'Cook Islands', 1),
(53, 'Costa Rica', 1),
(54, 'Cote D\'ivoire', 1),
(55, 'Croatia', 1),
(56, 'Cuba', 1),
(57, 'Cyprus', 1),
(58, 'Czech Republic', 1),
(59, 'Denmark', 1),
(60, 'Djibouti', 1),
(61, 'Dominica', 1),
(62, 'Dominican Republic', 1),
(63, 'Ecuador', 1),
(64, 'Egypt', 1),
(65, 'El Salvador', 1),
(66, 'Equatorial Guinea', 1),
(67, 'Eritrea', 1),
(68, 'Estonia', 1),
(69, 'Ethiopia', 1),
(70, 'Falkland Islands (Malvinas)', 1),
(71, 'Faroe Islands', 1),
(72, 'Fiji', 1),
(73, 'Finland', 1),
(74, 'France', 1),
(75, 'French Guiana', 1),
(76, 'French Polynesia', 1),
(77, 'French Southern Territories', 1),
(78, 'Gabon', 1),
(79, 'Gambia', 1),
(80, 'Georgia', 1),
(81, 'Germany', 1),
(82, 'Ghana', 1),
(83, 'Gibraltar', 1),
(84, 'Greece', 1),
(85, 'Greenland', 1),
(86, 'Grenada', 1),
(87, 'Guadeloupe', 1),
(88, 'Guam', 1),
(89, 'Guatemala', 1),
(90, 'Guernsey', 1),
(91, 'Guinea', 1),
(92, 'Guinea-bissau', 1),
(93, 'Guyana', 1),
(94, 'Haiti', 1),
(95, 'Heard Island and Mcdonald Islands', 1),
(96, 'Holy See (Vatican City State)', 1),
(97, 'Honduras', 1),
(98, 'Hong Kong', 1),
(99, 'Hungary', 1),
(100, 'Iceland', 1),
(101, 'India', 1),
(102, 'Indonesia', 1),
(103, 'Iran, Islamic Republic of', 1),
(104, 'Iraq', 1),
(105, 'Ireland', 1),
(106, 'Isle of Man', 1),
(107, 'Israel', 1),
(108, 'Italy', 1),
(109, 'Jamaica', 1),
(110, 'Japan', 1),
(111, 'Jersey', 1),
(112, 'Jordan', 1),
(113, 'Kazakhstan', 1),
(114, 'Kenya', 1),
(115, 'Kiribati', 1),
(116, 'Korea, Democratic People\'s Republic of', 1),
(117, 'Korea, Republic of', 1),
(118, 'Kuwait', 1),
(119, 'Kyrgyzstan', 1),
(120, 'Lao People\'s Democratic Republic', 1),
(121, 'Latvia', 1),
(122, 'Lebanon', 1),
(123, 'Lesotho', 1),
(124, 'Liberia', 1),
(125, 'Libyan Arab Jamahiriya', 1),
(126, 'Liechtenstein', 1),
(127, 'Lithuania', 1),
(128, 'Luxembourg', 1),
(129, 'Macao', 1),
(130, 'Macedonia, The Former Yugoslav Republic of', 1),
(131, 'Madagascar', 1),
(132, 'Malawi', 1),
(133, 'Malaysia', 1),
(134, 'Maldives', 1),
(135, 'Mali', 1),
(136, 'Malta', 1),
(137, 'Marshall Islands', 1),
(138, 'Martinique', 1),
(139, 'Mauritania', 1),
(140, 'Mauritius', 1),
(141, 'Mayotte', 1),
(142, 'Mexico', 1),
(143, 'Micronesia, Federated States of', 1),
(144, 'Moldova, Republic of', 1),
(145, 'Monaco', 1),
(146, 'Mongolia', 1),
(147, 'Montenegro', 1),
(148, 'Montserrat', 1),
(149, 'Morocco', 1),
(150, 'Mozambique', 1),
(151, 'Myanmar', 1),
(152, 'Namibia', 1),
(153, 'Nauru', 1),
(154, 'Nepal', 1),
(155, 'Netherlands', 1),
(156, 'Netherlands Antilles', 1),
(157, 'New Caledonia', 1),
(158, 'New Zealand', 1),
(159, 'Nicaragua', 1),
(160, 'Niger', 1),
(161, 'Nigeria', 1),
(162, 'Niue', 1),
(163, 'Norfolk Island', 1),
(164, 'Northern Mariana Islands', 1),
(165, 'Norway', 1),
(166, 'Oman', 1),
(167, 'Pakistan', 1),
(168, 'Palau', 1),
(169, 'Palestinian Territory, Occupied', 1),
(170, 'Panama', 1),
(171, 'Papua New Guinea', 1),
(172, 'Paraguay', 1),
(173, 'Peru', 1),
(174, 'Philippines', 1),
(175, 'Pitcairn', 1),
(176, 'Poland', 1),
(177, 'Portugal', 1),
(178, 'Puerto Rico', 1),
(179, 'Qatar', 1),
(180, 'Reunion', 1),
(181, 'Romania', 1),
(182, 'Russian Federation', 1),
(183, 'Rwanda', 1),
(184, 'Saint Helena', 1),
(185, 'Saint Kitts and Nevis', 1),
(186, 'Saint Lucia', 1),
(187, 'Saint Pierre and Miquelon', 1),
(188, 'Saint Vincent and The Grenadines', 1),
(189, 'Samoa', 1),
(190, 'San Marino', 1),
(191, 'Sao Tome and Principe', 1),
(192, 'Saudi Arabia', 1),
(193, 'Senegal', 1),
(194, 'Serbia', 1),
(195, 'Seychelles', 1),
(196, 'Sierra Leone', 1),
(197, 'Singapore', 1),
(198, 'Slovakia', 1),
(199, 'Slovenia', 1),
(200, 'Solomon Islands', 1),
(201, 'Somalia', 1),
(202, 'South Africa', 1),
(203, 'South Georgia and The South Sandwich Islands', 1),
(204, 'Spain', 1),
(205, 'Sri Lanka', 1),
(206, 'Sudan', 1),
(207, 'Suriname', 1),
(208, 'Svalbard and Jan Mayen', 1),
(209, 'Swaziland', 1),
(210, 'Sweden', 1),
(211, 'Switzerland', 1),
(212, 'Syrian Arab Republic', 1),
(213, 'Taiwan, Province of China', 1),
(214, 'Tajikistan', 1),
(215, 'Tanzania, United Republic of', 1),
(216, 'Thailand', 1),
(217, 'Timor-leste', 1),
(218, 'Togo', 1),
(219, 'Tokelau', 1),
(220, 'Tonga', 1),
(221, 'Trinidad and Tobago', 1),
(222, 'Tunisia', 1),
(223, 'Turkey', 1),
(224, 'Turkmenistan', 1),
(225, 'Turks and Caicos Islands', 1),
(226, 'Tuvalu', 1),
(227, 'Uganda', 1),
(228, 'Ukraine', 1),
(229, 'United Arab Emirates', 1),
(230, 'United Kingdom', 1),
(231, 'United States', 1),
(232, 'United States Minor Outlying Islands', 1),
(233, 'Uruguay', 1),
(234, 'Uzbekistan', 1),
(235, 'Vanuatu', 1),
(236, 'Venezuela', 1),
(237, 'Viet Nam', 1),
(238, 'Virgin Islands, British', 1),
(239, 'Virgin Islands, U.S.', 1),
(240, 'Wallis and Futuna', 1),
(241, 'Western Sahara', 1),
(242, 'Yemen', 1),
(243, 'Zambia', 1),
(244, 'Zimbabwe', 1);

-- --------------------------------------------------------

--
-- Table structure for table `boost_credit_log`
--

CREATE TABLE `boost_credit_log` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `contact_id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT '0',
  `invoice_id` int(11) NOT NULL,
  `credit_note_id` int(11) DEFAULT '0',
  `credit` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_credit_log`
--

INSERT INTO `boost_credit_log` (`id`, `date_created`, `contact_id`, `payment_id`, `invoice_id`, `credit_note_id`, `credit`) VALUES
(1, '2015-09-02 15:08:13', 1, 23, 23, 0, 28258.45),
(2, '2015-09-02 15:09:29', 1, 45, 1097, 0, -100),
(3, '2015-09-03 11:42:25', 1, 47, 1110, 0, -12000),
(4, '2015-09-03 11:47:54', 1, 48, 1111, 0, 1050),
(5, '2015-09-03 11:50:09', 1, 49, 1112, 0, -450),
(7, '2015-09-03 13:24:59', 3, 51, 1114, 0, 1800),
(8, '2015-09-03 13:26:32', 3, 52, 1116, 0, -1200),
(9, '2015-09-03 15:48:57', 1, 54, 1109, 0, 1901),
(10, '2015-09-04 10:00:34', 1, 58, 1089, 0, -1200),
(11, '2015-09-04 10:00:57', 1, 59, 1086, 0, -450),
(12, '2015-09-04 10:02:45', 1, 61, 1084, 0, 2200),
(13, '2015-09-04 11:02:13', 1, 62, 1118, 0, -1200),
(14, '2015-09-04 12:53:46', 1, 64, 1115, 0, -1200),
(15, '2015-09-04 12:54:18', 1, 66, 1113, 0, -1200),
(16, '2015-09-07 10:36:03', 1, 67, 1082, 0, 538.3),
(17, '2015-09-07 10:36:04', 1, 68, 1083, 0, -1200),
(18, '2015-09-07 10:55:09', 1, 83, 1080, 0, -3960),
(19, '2015-09-07 10:55:09', 1, 84, 1081, 0, -3960),
(20, '2015-09-08 15:08:59', 1, 97, 1135, 0, -450),
(23, '2015-09-08 15:38:24', 5, 102, 1136, 0, 1000),
(24, '2015-09-08 15:42:22', 5, 103, 1137, 0, -100),
(25, '2015-09-10 11:57:06', 5, 111, 1136, 0, 1),
(26, '2015-09-10 11:57:06', 5, 112, 1137, 0, 1),
(27, '2015-09-10 12:01:44', 5, 115, 1136, 0, 1),
(28, '2015-09-10 12:01:45', 5, 116, 1137, 0, 1),
(29, '2015-09-10 12:35:46', 5, 117, 1136, 0, 1),
(30, '2015-09-10 12:35:48', 5, 118, 1137, 0, 1),
(31, '2015-09-10 12:35:57', 1, 119, 1135, 0, 100),
(32, '2015-09-10 14:45:14', 1, 133, 1143, 0, -1965.528),
(33, '2015-09-10 14:46:03', 1, 134, 1144, 0, -1509.66),
(34, '2015-09-10 14:49:51', 1, 136, 1131, 0, -3000),
(36, '2015-09-11 12:48:16', 1, 150, 1144, 0, 1000),
(38, '2015-09-11 13:08:30', 1, 152, 1154, 0, -350),
(39, '2015-09-11 13:10:17', 1, 154, 1155, 0, -350),
(42, '2015-09-11 14:24:14', 1, 160, 1156, 0, -502.562),
(43, '2015-09-11 14:27:51', 1, 161, 1156, 0, 1000),
(44, '2015-09-11 14:30:40', 1, 162, 1157, 0, 1800),
(45, '2015-09-11 14:58:24', 1, 165, 1156, 0, 0.00000000000011368683772162),
(46, '2015-09-11 15:07:04', 1, 166, 1156, 0, 1),
(47, '2015-09-11 15:10:05', 1, 167, 1156, 0, -1),
(48, '2015-09-15 13:16:49', 1, 168, 1123, 0, 100),
(49, '2015-09-15 13:17:21', 1, 169, 1122, 0, 100),
(50, '2015-09-15 13:19:28', 1, 170, 1158, 0, -1000),
(51, '2015-09-16 07:55:44', 1, 177, 1156, 0, -100),
(52, '2015-09-16 07:56:41', 1, 178, 1159, 0, -1000),
(53, '2015-09-16 08:00:20', 1, 180, 1161, 0, -900),
(54, '2015-09-16 08:00:51', 1, 181, 1161, 0, 1000),
(55, '2015-09-16 08:02:14', 1, 182, 1162, 0, -1000),
(56, '2015-10-07 12:18:29', 1, 199, 1187, 0, 50),
(57, '2015-10-07 12:19:35', 1, 200, 1186, 0, -50),
(58, '2015-10-07 12:21:09', 1, 201, 1186, 0, 200),
(59, '2015-10-07 12:24:40', 1, 202, 1185, 0, -200),
(60, '2015-10-16 14:04:12', 13, 205, 1193, 0, 3967),
(61, '2015-10-16 14:07:04', 13, 206, 1194, 0, -2000),
(62, '2015-10-16 14:20:11', 13, 207, 1195, 0, -1967),
(63, '2015-10-16 14:28:04', 1, 208, 1185, 0, 1101.48),
(64, '2015-10-16 14:53:05', 1, 211, 1190, 0, 2881),
(65, '2015-10-16 14:56:27', 3, 212, 1182, 0, -400),
(66, '2015-10-16 14:56:27', 3, 213, 1183, 0, -200),
(67, '2015-11-05 14:13:37', 1, 0, 1205, 0, 3000),
(68, '2015-12-11 13:35:08', 16, 219, 1225, 0, 9200),
(69, '2015-12-11 13:58:20', 16, 220, 1231, 0, -9000),
(71, '2015-12-11 15:01:49', 16, 222, 1221, 0, -300),
(72, '2015-12-11 15:03:05', 16, 223, 1227, 0, 210),
(73, '2015-12-11 15:03:28', 16, 224, 1221, 0, -210),
(74, '2015-12-11 15:05:29', 16, 226, 1226, 0, 200),
(75, '2015-12-11 15:21:06', 16, 0, 1229, 36, 114);

-- --------------------------------------------------------

--
-- Table structure for table `boost_credit_notes`
--

CREATE TABLE `boost_credit_notes` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `credit_note_number` varchar(15) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `currency_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `due_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `discount_percentage` double DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `status` varchar(15) DEFAULT 'draft',
  `sub_total` varchar(15) DEFAULT NULL,
  `discount_total` varchar(15) DEFAULT NULL,
  `vat_amount` varchar(15) DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `terms` text,
  `closing_note` text,
  `reminder` int(3) DEFAULT '0',
  `content_status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_credit_notes`
--

INSERT INTO `boost_credit_notes` (`id`, `date_created`, `date_modified`, `credit_note_number`, `contact_id`, `invoice_id`, `currency_id`, `date`, `due_date`, `discount_percentage`, `reference`, `status`, `sub_total`, `discount_total`, `vat_amount`, `total_amount`, `terms`, `closing_note`, `reminder`, `content_status`) VALUES
(1, '2015-09-02 10:23:33', '2015-10-16 13:01:25', 'CE-46460', 1, 1050, 1, '2015-05-31 22:00:00', '2015-07-04 22:00:00', 1, 'lalala', 'sent', '3000', '0', '0', 3000, '', '', 0, 'active'),
(2, '2015-09-02 10:26:34', '2015-10-15 13:18:52', 'CE-46461', 1, 1055, 1, '2015-05-31 22:00:00', '2015-07-04 22:00:00', 10, 'test', 'sent', '2000', '228', '280', 2052, 'Terms 2', 'notes 2', 0, 'active'),
(3, '2015-10-15 12:40:20', '2015-10-15 13:18:52', 'CN-046462', 1, 1111, 1, '2015-10-14 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000066', 'sent', '50', '0', '7', 57, '', '', 0, 'active'),
(4, '2015-10-15 12:46:10', '2015-10-15 13:18:52', 'CN-046463', 1, 1122, 1, '2015-10-14 22:00:00', '0000-00-00 00:00:00', 0, '', 'draft', '1200', '0', '0', 1200, '', '', 0, 'active'),
(5, '2015-10-15 15:06:23', '2015-10-15 13:18:52', 'CN-046464', 1, 1185, 1, '2015-10-14 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000066', 'sent', '50', '0', '7', 57, '', '', 0, 'active'),
(6, '2015-10-15 15:09:50', '2015-10-15 15:13:59', 'CN-046465', 1, 1185, 1, '2015-10-14 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000066', 'sent', '1282', '0', '11.48', 1293.48, '', '', 0, 'active'),
(7, '2015-10-15 15:15:30', '2015-10-16 13:01:04', 'CN-046466', 1, 1188, 1, '2015-10-14 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000069', 'sent', '1650', '0', '63', 1713, '', '', 0, 'active'),
(8, '2015-10-15 15:33:44', '2015-10-16 14:47:56', 'CN-046467', 1, 1190, 1, '2015-10-14 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000071', 'sent', '13200', '0', '1680', 14880, '', '', 0, 'active'),
(9, '2015-10-16 14:30:37', '2015-10-19 14:40:35', 'CN-046468', 13, 1196, 1, '2015-10-26 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000076', 'sent', '400', '0', '56', 456, '', '', 0, 'active'),
(10, '2015-11-05 09:05:02', '2015-11-05 09:05:02', 'CN-046469', 1, 1, 1, '2015-11-04 22:00:00', '0000-00-00 00:00:00', 0, '', 'draft', '1200', '0', '0', 1200, 'credit note terms', 'credit note closing note', 0, 'active'),
(11, '2015-11-05 09:08:33', '2015-11-05 09:08:33', 'CN-046470', 3, 1, 1, '2015-11-04 22:00:00', '0000-00-00 00:00:00', 0, '', 'draft', '1200', '0', '0', 1200, 'credit note terms', 'credit note closing note', 0, 'active'),
(12, '2015-11-05 09:11:12', '2015-11-05 14:27:25', 'CN-046471', 2, 1207, 1, '2015-11-04 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000086', 'draft', '6070.26', '0', '0', 6070.26, 'credit note terms', 'credit note closing note', 0, 'active'),
(13, '2015-11-05 13:59:23', '2015-11-05 14:02:49', 'CN-046472', 1, 1208, 1, '2015-11-04 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000087', 'draft', '1800', '0', '0', 1800, 'credit note terms', 'credit note closing note', 0, 'active'),
(14, '2015-11-05 14:06:27', '2015-11-05 14:06:27', 'CN-046473', 1, 1205, 1, '2015-11-04 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000084', 'draft', '2200', '0', '0', 2200, 'credit note terms', 'credit note closing note', 0, 'active'),
(19, '2015-11-06 07:53:29', '2015-11-18 07:44:27', 'CN-046478', 16, 0, 1, '2015-11-05 22:00:00', '0000-00-00 00:00:00', 0, '', 'sent', '1200', '0', '0', 1200, 'credit note terms', 'credit note closing note', 0, 'active'),
(31, '2015-11-11 09:24:52', '2015-11-11 15:03:30', 'CN-046479', 14, 1211, 1, '2015-11-10 22:00:00', '2015-07-04 22:00:00', 0, 'INV-1000088', 'paid', '1200', '0', '0', 1200, 'credit note terms', 'credit note closing note', 0, 'active'),
(32, '2015-11-18 07:46:04', '2015-11-18 07:51:04', 'CN-046480', 1, 1214, 1, '2015-11-17 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000090', 'partial', '300', '0', '0', 300, 'credit note terms', 'credit note closing note', 0, 'active'),
(33, '2015-12-04 12:42:49', '2015-12-04 12:52:25', 'CN-046481', 16, 1, 1, '2015-12-03 22:00:00', '0000-00-00 00:00:00', 0, '', 'sent', '450', '0', '0', 450, 'credit note terms', 'credit note closing note', 0, 'active'),
(34, '2015-12-04 13:22:26', '2015-12-04 13:22:34', 'CN-046482', 16, 1, 1, '2015-12-03 22:00:00', '0000-00-00 00:00:00', 0, '', 'sent', '0', '0', '0', 0, 'credit note terms', 'credit note closing note', 0, 'active'),
(35, '2015-12-04 13:23:00', '2015-12-04 13:23:15', 'CN-046483', 16, 1, 1, '2015-12-03 22:00:00', '0000-00-00 00:00:00', 0, '', 'sent', '0', '0', '0', 0, 'credit note terms', 'credit note closing note', 0, 'active'),
(36, '2015-12-11 12:52:26', '2015-12-11 15:21:06', 'CN-046484', 16, 1229, 1, '2015-12-10 22:00:00', '0000-00-00 00:00:00', 0, 'INV-1000104', 'sent', '900', '0', '126', 1026, 'credit note terms', 'credit note closing note', 0, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `boost_credit_note_items`
--

CREATE TABLE `boost_credit_note_items` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `credit_note_id` int(11) NOT NULL,
  `item_name` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `rate` int(11) DEFAULT NULL,
  `total_amount` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_credit_note_items`
--

INSERT INTO `boost_credit_note_items` (`id`, `date_created`, `credit_note_id`, `item_name`, `description`, `quantity`, `tax`, `rate`, `total_amount`) VALUES
(1, '2015-09-02 10:23:33', 1, 'Coffee table', 'the first item\'s description', 1, 0, 1000, '1000'),
(2, '2015-09-02 10:23:33', 1, 'the second item', 'the second item\'s description', 1, 0, 1000, '1000'),
(3, '2015-09-02 10:23:33', 1, 'the third item', 'the third item\'s description', 1, 0, 1000, '1000'),
(25, '2015-10-15 12:40:20', 3, 'Chair', 'With wheels', 1, 1, 50, '50'),
(29, '2015-10-15 12:44:37', 2, 'Coffee table', 'the first item\'s description', 1, 1, 1000, '1000'),
(30, '2015-10-15 12:44:37', 2, 'the second item', 'the second item\'s description', 1, 1, 1000, '1000'),
(31, '2015-10-15 12:46:10', 4, 'Table', 'Accomodates 4 seats', 1, NULL, 1200, '1200'),
(32, '2015-10-15 15:06:23', 5, 'Chair', 'With wheels', 1, 1, 50, '50'),
(35, '2015-10-15 15:33:44', 8, 'Table', 'Accomodates 4 seats', 1, NULL, 1200, '1200'),
(36, '2015-10-15 15:33:44', 8, 'Bed', 'Queen size', 1, 1, 12000, '12000'),
(40, '2015-10-19 14:40:35', 9, 'Table', 'Accomodates 4 seats', 1, 1, 400, '400'),
(41, '2015-11-05 09:05:03', 10, 'Table', 'Accomodates 4 seats', 1, NULL, 1200, '1200'),
(42, '2015-11-05 09:08:33', 11, 'Table', 'Accomodates 4 seats', 1, NULL, 1200, '1200'),
(46, '2015-11-05 14:02:49', 13, 'Coffee Table', 'Wooden', 1, NULL, 1800, '1800'),
(47, '2015-11-05 14:06:27', 14, 'Table', 'Accomodates 4 seats', 1, NULL, 2200, '2200'),
(51, '2015-11-05 14:27:25', 12, 'Table', 'Test Accomodates 4 seats', 1, NULL, 6070, '6070.26'),
(53, '2015-11-05 14:46:44', 15, 'Two', 'The second ', 1, NULL, 2000, '2000'),
(54, '2015-11-05 14:46:44', 15, 'One', 'First Credit note application', 1, NULL, 2000, '2000'),
(55, '2015-11-05 14:48:44', 16, 'One More', 'Another Credit not for the twins', 1, NULL, 8000, '8000'),
(57, '2015-11-06 07:41:08', 17, 'Both', 'Dance Duo', 2, NULL, 500, '1000'),
(60, '2015-11-06 07:57:10', 19, 'Table', 'Accomodates 4 seats', 1, NULL, 1200, '1200'),
(63, '2015-11-06 08:29:08', 18, 'Shoes', 'interesting', 1, NULL, 1000, '1000'),
(66, '2015-11-06 10:44:41', 20, 'Table', 'Accomodates 4 seats', 1, NULL, 10, '10'),
(68, '2015-11-06 10:51:16', 22, 'Table', 'Accomodates 4 seats', 1, NULL, 200, '200'),
(75, '2015-11-06 11:12:54', 21, 'Table', 'Accomodates 4 seats!', 1, 0, 2000, '2000'),
(76, '2015-11-06 11:19:11', 23, 'Table', 'Accomodates 4 seats!', 1, 0, 2000, '2000'),
(77, '2015-11-06 11:22:11', 24, 'Table', 'Accomodates 4 seats!', 1, 0, 2000, '2000'),
(78, '2015-11-06 11:34:56', 25, 'Table', 'Accomodates 4 seats!', 1, 0, 2000, '2000'),
(79, '2015-11-06 11:38:14', 26, 'Table', 'Accomodates 4 seats!', 1, 0, 2000, '2000'),
(80, '2015-11-06 11:39:52', 27, 'Table', 'Accomodates 4 seats!', 1, 0, 2000, '2000'),
(81, '2015-11-06 11:41:20', 28, 'Table', 'Accomodates 4 seats!', 1, 0, 2000, '2000'),
(82, '2015-11-06 11:45:10', 29, 'Table', 'Accomodates 4 seats!', 1, 0, 2000, '2000'),
(86, '2015-11-06 13:19:25', 30, 'Table', 'Accomodates 4 seats!', 1, NULL, 2000, '2000'),
(148, '2015-11-11 15:03:31', 31, 'Table', 'Accomodates 4 seats', 1, NULL, 1200, '1200'),
(152, '2015-11-18 07:51:04', 32, 'Coffee Table', 'Wooden', 1, NULL, 300, '300'),
(153, '2015-12-04 12:42:50', 33, 'Chair', 'With wheels', 1, NULL, 450, '450'),
(154, '2015-12-04 13:22:26', 34, 'Chair', 'With wheels', 0, NULL, 450, '0'),
(155, '2015-12-04 13:23:00', 35, 'Chair', 'With wheels', 0, NULL, 450, '0'),
(162, '2015-12-11 15:21:07', 36, 'Coffee Table', 'Wooden', 1, 1, 900, '900');

-- --------------------------------------------------------

--
-- Table structure for table `boost_currencies`
--

CREATE TABLE `boost_currencies` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `currency_name` varchar(50) NOT NULL,
  `currency_symbol` varchar(5) DEFAULT NULL,
  `short_code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_currencies`
--

INSERT INTO `boost_currencies` (`id`, `date_created`, `currency_name`, `currency_symbol`, `short_code`) VALUES
(1, '2015-06-30 13:04:33', 'South African Rand', 'R', 'ZAR'),
(2, '2015-07-23 11:06:55', 'US Dollar', '$', 'USD'),
(3, '2015-07-23 11:08:11', 'Euro', '€', 'EUR'),
(4, '2015-07-23 11:10:34', 'Japanese Yen', '¥', 'JPY'),
(5, '2015-07-23 11:11:36', 'Pound Sterling', '£', 'GBP'),
(6, '2015-07-23 11:12:17', 'Australian Dollar', '$', 'AUD'),
(7, '2015-07-23 11:13:29', 'Swiss Franc', 'Fr', 'CHF'),
(8, '2015-07-23 11:13:41', 'Canadian Dollar', '$', 'CAD'),
(9, '2015-07-23 11:14:19', 'Mexican Peso', '$', 'MXN'),
(10, '2015-07-23 11:14:50', 'Chinese Yuan', '¥', 'CNY'),
(11, '2015-07-23 11:15:10', 'New Zealand Dollar', '$', 'NZD');

-- --------------------------------------------------------

--
-- Table structure for table `boost_email_settings`
--

CREATE TABLE `boost_email_settings` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email_signature` varchar(150) DEFAULT NULL,
  `invoice_message` text,
  `estimate_message` text,
  `credit_note_message` text,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_email_settings`
--

INSERT INTO `boost_email_settings` (`id`, `date_created`, `email_signature`, `invoice_message`, `estimate_message`, `credit_note_message`, `date_modified`) VALUES
(1, '2015-08-25 10:38:28', 'Regards,\r\nSo Interactive\r\n', 'Amount Due: {{amount}}\r\nCompany Name: {{company_name}}\r\nClient Company Name: {{client_company_name}}\r\nClient First Name: {{client_first_name}}\r\nClient Last Name: {{client_last_name}}\r\nInvoice Number: {{invoice_number}}\r\nReference: {{reference}}\r\nDue Date: {{due_date}}', 'Amount Estimated: {{amount}}\r\nCompany Name: {{company_name}}\r\nClient Company Name: {{client_company_name}}\r\nReference: {{reference}}\r\n', 'Amount Credited:{{amount}}\r\nCompany Name: {{company_name}}\r\nClient Company Name: {{client_company_name}}\r\nClient First Name: {{client_first_name}}\r\nClient Last Name: {{client_last_name}}\r\nCredit Note Number: {{credit_note_number}}\r\nReference: {{reference}}\r\nDue Date: {{due_date}}', '2015-12-11 13:29:17');

-- --------------------------------------------------------

--
-- Table structure for table `boost_email_tokens`
--

CREATE TABLE `boost_email_tokens` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `token` varchar(50) NOT NULL,
  `short_name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_email_tokens`
--

INSERT INTO `boost_email_tokens` (`id`, `date_created`, `date_modified`, `token`, `short_name`) VALUES
(1, '2015-08-25 09:52:39', '2015-08-25 09:52:39', '{{amount}}', 'amount'),
(2, '2015-08-25 09:52:58', '2015-08-25 09:52:58', '{{company_name}}', 'company_name'),
(3, '2015-08-25 09:53:06', '2015-08-25 09:53:06', '{{client_company_name}}', 'client_company_name'),
(4, '2015-08-25 09:53:26', '2015-08-25 09:53:26', '{{client_first_name}}', 'client_first_name'),
(5, '2015-08-25 09:53:49', '2015-08-25 09:53:49', '{{client_last_name}}', 'client_last_name'),
(6, '2015-08-25 09:54:00', '2015-08-25 09:54:00', '{{invoice_number}}', 'invoice_number'),
(7, '2015-08-25 09:54:31', '2015-08-25 09:54:31', '{{reference}}', 'reference'),
(8, '2015-08-25 09:54:43', '2015-08-25 09:54:43', '{{due_date}}', 'due_date'),
(9, '2015-08-25 10:31:52', '2015-08-25 10:31:52', '{{estimate_number}}', 'estimate_number'),
(10, '2015-08-25 10:32:01', '2015-08-25 10:32:01', '{{credit_note_number}}', 'credit_note_number'),
(11, '2015-08-25 10:32:38', '2015-08-25 10:32:38', '{{contact_first_name}}', 'contact_first_name'),
(12, '2015-08-25 10:33:03', '2015-08-25 10:33:03', '{{contact_last_name}}', 'contact_last_name'),
(13, '2015-08-25 11:44:46', '2015-08-25 11:44:46', '{{contact_company_name}}', 'contact_company_name'),
(14, '2015-08-25 11:45:08', '2015-08-25 11:45:08', '{{contact_organisation}}', 'contact_organisation');

-- --------------------------------------------------------

--
-- Table structure for table `boost_estimates`
--

CREATE TABLE `boost_estimates` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `estimate_number` varchar(15) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `due_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `discount_percentage` double DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `status` varchar(15) DEFAULT 'draft',
  `sub_total` varchar(15) DEFAULT NULL,
  `discount_total` varchar(15) DEFAULT NULL,
  `vat_amount` varchar(15) DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `terms` text,
  `closing_note` text,
  `reminder` int(3) DEFAULT '0',
  `content_status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_estimates`
--

INSERT INTO `boost_estimates` (`id`, `date_created`, `date_modified`, `estimate_number`, `contact_id`, `currency_id`, `date`, `due_date`, `discount_percentage`, `reference`, `status`, `sub_total`, `discount_total`, `vat_amount`, `total_amount`, `terms`, `closing_note`, `reminder`, `content_status`) VALUES
(1, '2015-09-09 12:55:01', '2016-02-15 14:05:48', '1063', 1, 1, '2015-05-31 22:00:00', '2015-07-04 22:00:00', 0, 'lalala', 'declined', '3000', '0', '0', 3000, NULL, NULL, 0, 'archived'),
(2, '2015-09-22 09:38:34', '2015-10-16 12:14:13', '1064', 1, 1, '2015-05-31 22:00:00', '2015-07-04 22:00:00', 0, 'lalala', 'draft', '3000', '0', '0', 3000, NULL, NULL, 0, 'archived'),
(3, '2015-10-16 09:43:02', '2015-10-16 12:39:55', 'CE-001065', 1, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 0, '', 'accepted', '1200', '0', '0', 1200, '', '', 0, 'active'),
(4, '2015-10-16 12:34:03', '2015-10-16 12:41:26', 'CE-001066', 1, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 0, '', 'declined', '2400', '0', '336', 2736, '', '', 0, 'active'),
(5, '2015-10-16 12:46:49', '2015-10-16 12:48:01', 'CE-001067', 1, 1, '2015-10-11 22:00:00', '2015-10-14 22:00:00', 0, 'test', 'sent', '3600', '0', '504', 4104, '', '', 0, 'active'),
(6, '2015-10-16 12:48:36', '2015-10-16 12:49:14', 'CE-001068', 1, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 0, 'test', 'declined', '3600', '0', '504', 4104, '', '', 0, 'active'),
(7, '2015-10-16 12:50:59', '2015-10-16 13:25:33', 'CE-001069', 1, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 0, 'test', 'sent', '3600', '0', '504', 4104, '', '', 0, 'active'),
(8, '2015-10-16 12:51:23', '2015-10-16 15:35:27', 'CE-001070', 1, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 0, 'test', 'accepted', '3600', '0', '504', 4104, '', '', 0, 'active'),
(9, '2015-10-16 15:22:03', '2015-10-16 15:32:19', 'CE-001071', 13, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 10, 'Purchase of Pride as slave', 'accepted', '1000000', '100000', '0', 900000, '', '', 0, 'active'),
(10, '2015-10-16 15:27:10', '2015-10-16 15:27:16', 'CE-001072', 13, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 10, 'Purchase of Pride as slave', 'sent', '1000000', '100000', '0', 900000, '', '', 0, 'active'),
(11, '2015-10-19 13:30:53', '2015-12-03 11:36:27', 'CE-001073', 16, 1, '2015-10-18 22:00:00', '2015-10-25 22:00:00', 0, 'test estimate', 'sent', '0', '0', '0', 0, '', '', 0, 'active'),
(12, '2015-12-04 10:11:52', '2015-12-04 10:12:09', 'CE-001074', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, 'test', 'sent', '800', '0', '112', 912, 'estimate terms', 'estimate closing note', 0, 'active'),
(13, '2015-12-04 12:57:44', '2015-12-04 12:58:05', 'CE-001075', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'sent', '800', '0', '0', 800, 'estimate terms', 'estimate closing note', 0, 'active'),
(14, '2015-12-04 12:58:32', '2016-02-15 14:54:01', 'CE-001076', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'accepted', '450', '0', '0', 450, 'estimate terms', 'estimate closing note', 0, 'active'),
(15, '2015-12-04 13:02:03', '2015-12-11 13:13:29', 'CE-001077', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'accepted', '800', '0', '0', 800, 'estimate terms', 'estimate closing note', 0, 'active'),
(16, '2015-12-04 13:24:45', '2015-12-04 13:56:16', 'CE-001078', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'accepted', '0', '0', '0', 0, 'estimate terms', 'estimate closing note', 0, 'active'),
(17, '2015-12-04 13:25:13', '2015-12-04 13:56:05', 'CE-001079', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'declined', '0', '0', '0', 0, 'estimate terms', 'estimate closing note', 0, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `boost_estimate_items`
--

CREATE TABLE `boost_estimate_items` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estimate_id` int(11) NOT NULL,
  `item_name` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `rate` int(11) DEFAULT NULL,
  `total_amount` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_estimate_items`
--

INSERT INTO `boost_estimate_items` (`id`, `date_created`, `estimate_id`, `item_name`, `description`, `quantity`, `tax`, `rate`, `total_amount`) VALUES
(1, '2015-09-09 12:55:01', 1, 'Coffee table', 'sdsadadas', 1, 0, 1000, '1000'),
(2, '2015-09-09 12:55:01', 1, 'the second item', 'the second item\'s description', 1, 0, 1000, '1000'),
(3, '2015-09-09 12:55:01', 1, 'the third item', 'the third item\'s description', 1, 0, 1000, '1000'),
(4, '2015-09-22 09:38:34', 2, 'Coffee table', 'sdsadadas', 1, 0, 1000, '1000'),
(5, '2015-09-22 09:38:34', 2, 'the second item', 'the second item\'s description', 1, 0, 1000, '1000'),
(6, '2015-09-22 09:38:34', 2, 'the third item', 'the third item\'s description', 1, 0, 1000, '1000'),
(7, '2015-10-16 09:43:03', 3, 'Table', 'Accomodates 4 seats', 1, NULL, 1200, '1200'),
(8, '2015-10-16 12:34:03', 4, 'Table', 'Accomodates 4 seats', 2, 1, 1200, '2400'),
(10, '2015-10-16 12:48:01', 5, 'Table', 'Accomodates 4 seats', 3, 1, 1200, '3600'),
(11, '2015-10-16 12:48:36', 6, 'Table', 'Accomodates 4 seats', 3, 1, 1200, '3600'),
(14, '2015-10-16 13:05:33', 8, 'Table', 'Accomodates 4 seats', 3, 1, 1200, '3600'),
(15, '2015-10-16 13:25:33', 7, 'Table', 'Accomodates 4 seats', 3, 1, 1200, '3600'),
(16, '2015-10-16 15:22:03', 9, 'Pride', 'Works well with code', 1, NULL, 1000000, '1000000'),
(17, '2015-10-16 15:27:10', 10, 'Pride', 'Works well with code', 1, 0, 1000000, '1000000'),
(20, '2015-12-03 11:36:27', 11, 't14', 't14', 1, 6, 0, '0'),
(21, '2015-12-04 10:11:52', 12, 'Coffee Table', 'Wooden', 1, 1, 800, '800'),
(22, '2015-12-04 12:57:44', 13, 'Coffee Table', 'Wooden', 1, NULL, 800, '800'),
(23, '2015-12-04 12:58:33', 14, 'Chair', 'With wheels', 1, NULL, 450, '450'),
(24, '2015-12-04 13:02:03', 15, 'Coffee Table', 'Wooden', 1, NULL, 800, '800'),
(25, '2015-12-04 13:24:45', 16, 'Chair', 'With wheels', 0, NULL, 450, '0'),
(26, '2015-12-04 13:25:13', 17, 'Coffee Table', 'Wooden', 0, NULL, 800, '0');

-- --------------------------------------------------------

--
-- Table structure for table `boost_industries`
--

CREATE TABLE `boost_industries` (
  `id` int(11) NOT NULL,
  `industry_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_industries`
--

INSERT INTO `boost_industries` (`id`, `industry_name`) VALUES
(1, 'Communications'),
(2, 'Housing'),
(3, 'Culinary'),
(4, 'Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `boost_invoices`
--

CREATE TABLE `boost_invoices` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `invoice_number` varchar(15) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `due_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `discount_percentage` double DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `status` varchar(15) DEFAULT 'draft',
  `sub_total` varchar(15) DEFAULT NULL,
  `discount_total` varchar(15) DEFAULT NULL,
  `vat_amount` varchar(15) DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `terms` text,
  `closing_note` text,
  `reminder` int(3) DEFAULT '0',
  `content_status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_invoices`
--

INSERT INTO `boost_invoices` (`id`, `date_created`, `date_modified`, `invoice_number`, `contact_id`, `currency_id`, `date`, `due_date`, `discount_percentage`, `reference`, `status`, `sub_total`, `discount_total`, `vat_amount`, `total_amount`, `terms`, `closing_note`, `reminder`, `content_status`) VALUES
(1001, '2015-06-30 12:54:15', '2015-09-16 12:08:51', 'INV-001001', 2, 1, '2015-09-16 12:08:51', '2015-07-04 22:00:00', 0, 'CE-001001', 'paid', NULL, '0', NULL, 1000, 'Terms', 'closing note', 10, 'active'),
(1002, '2015-06-30 12:54:50', '2015-09-16 12:08:56', 'INV-1002', 1, 1, '2015-09-16 12:08:56', '2015-10-12 22:00:00', 0, 'Reference', 'paid', '702', '0', '92.3376', 794.3376, '', '', 0, 'active'),
(1003, '2015-06-30 12:54:50', '2015-09-16 12:08:58', 'INV-001003', 1, 1, '2015-09-16 12:08:58', '2015-07-04 22:00:00', 10, 'CE-001003', 'paid', NULL, '1675.02', NULL, NULL, 'terms', 'notes', 0, 'active'),
(1004, '2015-06-30 12:54:50', '2015-09-16 12:09:00', 'INV-0001004', 2, 1, '2015-09-16 12:09:00', '2015-07-04 22:00:00', 0, 'CE-0001004', 'paid', '8090', '0', '980.264', 9070.264, 'this will be terms and conditions or banking details', 'this will be closing notes', 0, 'active'),
(1052, '2015-07-28 15:48:15', '2015-09-16 12:09:02', 'INV-000049', 1, 1, '2015-09-16 12:09:02', '2015-07-27 22:00:00', 10, 'testref', 'paid', '100', '11.05', '10.5', 99.45, 'terms', 'notes', 0, 'active'),
(1053, '2015-07-29 09:28:51', '2015-09-16 12:09:04', 'INV-000053', 1, 1, '2015-09-16 12:09:04', '2015-07-28 22:00:00', 0, '', 'paid', '0', '0', '0', 0, 'terms', 'notes', 0, 'active'),
(1054, '2015-07-29 09:28:56', '2015-09-16 12:09:07', 'INV-000053', 3, 1, '2015-09-16 12:09:07', '2015-07-28 22:00:00', 0, '', 'paid', '0', '0', '0', 0, '', '', 0, 'active'),
(1055, '2015-07-30 07:38:30', '2015-09-16 12:09:09', '46460', 1, 1, '2015-09-16 12:09:09', '2015-07-04 22:00:00', 0, 'lalala', 'paid', '3000', '0', '0', 3000, NULL, NULL, 0, 'active'),
(1056, '2015-07-30 07:39:46', '2015-09-16 12:09:11', '46461', 1, 1, '2015-09-16 12:09:11', '2015-07-04 22:00:00', 10, 'lalala', 'paid', '4000', '440', '400', 3960, NULL, NULL, 0, 'active'),
(1057, '2015-07-30 07:40:34', '2015-09-16 12:09:13', '46462', 1, 1, '2015-09-16 12:09:13', '2015-07-04 22:00:00', 10, 'lalala', 'paid', '4000', '440', '400', 3960, NULL, NULL, 0, 'active'),
(1058, '2015-07-30 07:41:06', '2015-09-16 12:09:15', '46463', 1, 1, '2015-09-16 12:09:15', '2015-07-04 22:00:00', 10, 'lalala', 'paid', '4000', '440', '400', 3960, NULL, NULL, 7, 'active'),
(1059, '2015-07-30 07:42:53', '2015-09-16 12:09:18', '46464', 1, 1, '2015-09-16 12:09:18', '2015-07-04 22:00:00', 10, 'lalala', 'paid', '4000', '440', '400', 3960, NULL, NULL, 0, 'active'),
(1060, '2015-07-30 07:44:13', '0000-00-00 00:00:00', '46465', 1, 1, '2015-09-16 11:57:31', '2015-07-04 22:00:00', 10, 'lalala', 'sent', '4000', '440', '400', 3960, NULL, NULL, 0, 'active'),
(1061, '2015-07-30 07:45:40', '0000-00-00 00:00:00', 'INV-000053', 1, 1, '2015-09-16 11:57:31', '2015-07-04 22:00:00', 10, 'lalala', 'sent', NULL, '440', NULL, NULL, NULL, NULL, 0, 'active'),
(1062, '2015-07-30 09:35:14', '0000-00-00 00:00:00', '123456', 1, 1, '2015-09-16 11:57:31', '2015-07-04 22:00:00', 10, 'lalala', 'sent', NULL, '440', NULL, NULL, 'terms', 'notes', 15, 'active'),
(1063, '2015-08-12 12:40:20', '0000-00-00 00:00:00', 'INV-00684', 1, 1, '2015-09-16 11:57:31', '2015-07-04 22:00:00', 10, 'lalala', 'sent', NULL, '440', NULL, NULL, '', '', 0, 'active'),
(1064, '2015-08-12 12:43:40', '0000-00-00 00:00:00', '123450', 1, 1, '2015-09-16 11:57:31', '2015-07-04 22:00:00', 10, 'lalala', 'sent', NULL, '440', NULL, NULL, NULL, NULL, 7, 'active'),
(1065, '2015-08-14 11:43:15', '0000-00-00 00:00:00', 'INV-012346', 4, 1, '2015-09-16 11:57:31', '2015-08-13 22:00:00', 10, 'ref', 'sent', NULL, '132.6', NULL, NULL, 'terms', 'notes', 7, 'active'),
(1066, '2015-08-14 11:55:12', '0000-00-00 00:00:00', 'INV-012347', 4, 1, '2015-09-16 11:57:31', '2015-08-13 22:00:00', 10, 'dress to i', 'sent', NULL, '132.6', NULL, NULL, 'terms', 'notes', 0, 'active'),
(1070, '2015-08-21 10:08:42', '0000-00-00 00:00:00', '12345', 1, 1, '2015-09-16 11:57:31', '2015-07-04 22:00:00', 10, 'lalala', 'sent', NULL, '440', '10', 10000, 'terms', 'closing note', 7, 'active'),
(1071, '2015-08-24 11:52:29', '0000-00-00 00:00:00', 'INV-000024', 1, 1, '2015-09-16 11:57:31', '2015-08-23 22:00:00', 0, 'CE-000024', 'sent', '450', '0', '0', 450, 'terms', 'notes', 0, 'active'),
(1072, '2015-08-24 11:53:33', '0000-00-00 00:00:00', 'INV-000025', 1, 1, '2015-09-16 11:57:31', '2015-08-23 22:00:00', 0, '', 'sent', '450', '0', '0', 450, 'terms', 'notes', 7, 'active'),
(1073, '2015-08-24 11:53:58', '0000-00-00 00:00:00', 'INV-000026', 1, 1, '2015-09-16 11:57:31', '2015-08-23 22:00:00', 0, '', 'sent', '450', '0', '63', 513, 'terms', 'notes', 0, 'active'),
(1074, '2015-08-24 12:21:51', '0000-00-00 00:00:00', 'INV-000027', 1, 1, '2015-09-16 11:57:31', '2015-08-23 22:00:00', 10, '', 'sent', '450', '51.3', '63', 461.7, 'terms', 'notes', 7, 'active'),
(1075, '2015-08-24 12:22:02', '0000-00-00 00:00:00', 'INV-000028', 1, 1, '2015-09-16 11:57:31', '2015-08-23 22:00:00', 10, '', 'sent', '450', '51.3', '63', 461.7, 'terms', 'notes', 30, 'active'),
(1076, '2015-08-25 13:00:33', '0000-00-00 00:00:00', 'INV-000029', 1, 1, '2015-09-16 11:57:31', '2015-08-24 22:00:00', 10, '', 'sent', '450', '51.3', '63', 461.7, 'terms', 'notes', 15, 'active'),
(1079, '2015-08-26 15:14:06', '0000-00-00 00:00:00', 'INV-000032', 1, 1, '2015-09-16 11:57:31', '2015-08-25 22:00:00', 10, '', 'sent', '450', '51.3', '63', 461.7, 'terms', 'notes', 15, 'active'),
(1080, '2015-08-26 15:21:25', '0000-00-00 00:00:00', '4587', 1, 1, '2015-09-16 11:57:31', '2015-07-04 22:00:00', 10, 'lalala', 'sent', '4000', '440', '400', 3960, NULL, NULL, 0, 'archived'),
(1081, '2015-08-26 15:22:48', '0000-00-00 00:00:00', '4588', 1, 1, '2015-09-16 11:57:31', '2015-07-04 22:00:00', 10, 'lalala', 'sent', '4000', '440', '400', 3960, NULL, NULL, 0, 'archived'),
(1082, '2015-08-26 15:23:58', '0000-00-00 00:00:00', 'INV-000035', 1, 1, '2015-09-16 11:57:31', '2015-08-25 22:00:00', 10, '', 'sent', '450', '51.3', '63', 461.7, 'terms', 'notes', 15, 'active'),
(1083, '2015-08-27 12:46:28', '0000-00-00 00:00:00', 'INV-000036', 1, 1, '2015-09-16 11:57:31', '2015-08-26 22:00:00', 0, 'ref', 'sent', '1200', '0', '0', 1200, 'test', 'test', 0, 'archived'),
(1084, '2015-08-27 12:46:58', '0000-00-00 00:00:00', 'INV-000037', 1, 1, '2015-09-16 11:57:31', '2015-08-26 22:00:00', 0, '', 'sent', '450', '0', '0', 450, '', '', 0, 'archived'),
(1085, '2015-08-31 15:14:16', '0000-00-00 00:00:00', 'INV-000038', 1, 1, '2015-09-16 11:57:31', '2015-08-30 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'archived'),
(1086, '2015-08-31 16:22:00', '0000-00-00 00:00:00', 'INV-000039', 1, 1, '2015-09-16 11:57:31', '2015-08-30 22:00:00', 0, 'test', 'sent', '450', '0', '0', 450, '', '', 0, 'archived'),
(1089, '2015-09-01 07:49:58', '0000-00-00 00:00:00', 'INV-000042', 1, 1, '2015-09-16 11:57:31', '2015-08-31 22:00:00', 0, 'test', 'sent', '1200', '0', '0', 1200, '', '', 0, 'archived'),
(1094, '2015-09-01 07:55:28', '0000-00-00 00:00:00', 'INV-000047', 1, 1, '2015-09-16 11:57:31', '2015-08-31 22:00:00', 0, '', 'sent', '0', '0', '0', 0, '', '', 0, 'archived'),
(1096, '2015-09-01 08:00:51', '0000-00-00 00:00:00', 'INV-000050', 1, 1, '2015-09-16 11:57:31', '2015-09-08 22:00:00', 0, '', 'sent', '1250', '0', '0', 1250, '', '', 0, 'active'),
(1097, '2015-09-01 08:53:21', '0000-00-00 00:00:00', 'INV-000051', 1, 1, '2015-09-16 11:57:31', '2015-09-02 22:00:00', 0, '', 'sent', '100', '0', '0', 100, '', '', 0, 'archived'),
(1099, '2015-09-01 09:53:22', '0000-00-00 00:00:00', 'INV-000054', 1, 1, '2015-09-16 11:57:31', '2015-09-03 22:00:00', 0, 'test', 'sent', '450', '0', '0', 450, '', '', 0, 'archived'),
(1106, '2015-09-01 14:53:32', '0000-00-00 00:00:00', 'INV-123458', 1, 1, '2015-09-16 11:57:31', '2015-09-01 22:00:00', 0, 'ref', 'sent', '2100', '0', '168', 2268, 'terms', 'notes', 15, 'active'),
(1107, '2015-09-01 15:03:01', '0000-00-00 00:00:00', 'INV-123460', 1, 1, '2015-09-16 11:57:31', '2015-09-02 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1108, '2015-09-01 15:08:26', '0000-00-00 00:00:00', 'INV-123462', 1, 1, '2015-09-16 11:57:31', '2015-09-29 22:00:00', 0, 'ref', 'sent', '3300', '0', '336', 3636, 'terms', 'notes', 30, 'active'),
(1109, '2015-09-01 15:24:46', '0000-00-00 00:00:00', 'INV-123464', 1, 1, '2015-09-16 11:57:31', '2015-09-02 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1110, '2015-09-03 11:33:55', '0000-00-00 00:00:00', 'INV-123466', 1, 1, '2015-09-16 11:57:31', '2015-09-02 22:00:00', 0, 'test', 'sent', '12000', '0', '0', 12000, '', '', 0, 'active'),
(1111, '2015-09-03 11:38:21', '0000-00-00 00:00:00', 'INV-999998', 1, 1, '2015-09-16 11:57:31', '2015-09-02 22:00:00', 0, '', 'sent', '450', '0', '0', 450, '', '', 0, 'archived'),
(1112, '2015-09-03 11:39:29', '0000-00-00 00:00:00', 'INV-000001', 1, 1, '2015-09-16 11:57:31', '2015-09-02 22:00:00', 0, '', 'sent', '450', '0', '0', 450, '', '', 0, 'archived'),
(1113, '2015-09-03 11:53:01', '0000-00-00 00:00:00', 'INV-1000000', 1, 1, '2015-09-16 11:57:31', '2015-09-02 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1114, '2015-09-03 12:14:18', '0000-00-00 00:00:00', 'INV-1000002', 3, 1, '2015-09-16 11:57:31', '2015-09-02 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1115, '2015-09-03 12:23:08', '0000-00-00 00:00:00', 'INV-1000004', 1, 1, '2015-09-16 11:57:31', '2015-09-02 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1116, '2015-09-03 13:25:30', '0000-00-00 00:00:00', 'INV-1000006', 3, 1, '2015-09-16 11:57:31', '2015-09-02 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1117, '2015-09-04 10:54:58', '0000-00-00 00:00:00', 'INV-1000008', 1, 1, '2015-09-16 11:57:31', '2015-09-03 22:00:00', 0, '', 'sent', '0', '0', '0', 0, '', '', 0, 'active'),
(1118, '2015-09-04 10:59:23', '0000-00-00 00:00:00', 'INV-1000010', 1, 1, '2015-09-16 11:57:31', '2015-09-03 22:00:00', 0, '', 'sent', '1650', '0', '0', 1650, '', '', 0, 'active'),
(1119, '2015-09-04 11:00:16', '0000-00-00 00:00:00', 'INV-1000012', 3, 1, '2015-09-16 11:57:31', '2015-09-03 22:00:00', 0, '', 'sent', '1050', '0', '0', 1050, '', '', 0, 'active'),
(1120, '2015-09-04 12:09:12', '0000-00-00 00:00:00', 'INV-1000014', 1, 1, '2015-09-16 11:57:31', '2015-09-03 22:00:00', 0, '', 'sent', '0', '0', '0', 0, '', '', 0, 'active'),
(1121, '2015-09-04 12:40:19', '0000-00-00 00:00:00', 'INV-1000016', 1, 1, '2015-09-16 11:57:31', '2015-09-03 22:00:00', 0, '', 'sent', '1250', '0', '0', 1250, '', '', 0, 'active'),
(1122, '2015-09-07 13:33:08', '0000-00-00 00:00:00', 'INV-1000018', 1, 1, '2015-09-16 11:57:31', '2015-09-06 22:00:00', 0, '', 'sent', '0', '0', '0', 0, '', '', 0, 'active'),
(1123, '2015-09-07 13:36:41', '0000-00-00 00:00:00', 'INV-1000020', 1, 1, '2015-09-16 11:57:31', '2015-09-06 22:00:00', 0, '', 'sent', '0', '0', '0', 0, '', '', 0, 'active'),
(1124, '2015-09-07 13:41:05', '2015-09-16 12:10:13', '1055', 1, 1, '2015-09-16 12:10:13', '2015-07-04 22:00:00', 0, 'lalala', 'paid', '3000', '0', '0', 3000, NULL, NULL, 0, 'active'),
(1125, '2015-09-07 13:42:05', '2015-09-16 12:10:10', '1056', 1, 1, '2015-09-16 12:10:10', '2015-07-04 22:00:00', 0, 'lalala', 'paid', '3000', '0', '0', 3000, NULL, NULL, 0, 'active'),
(1126, '2015-09-07 13:42:49', '2015-09-16 12:10:08', '1057', 1, 1, '2015-09-16 12:10:08', '2015-07-04 22:00:00', 0, 'lalala', 'paid', '3000', '0', '0', 3000, NULL, NULL, 0, 'active'),
(1127, '2015-09-07 13:44:03', '2015-09-16 12:10:07', '1058', 1, 1, '2015-09-16 12:10:07', '2015-07-04 22:00:00', 0, 'lalala', 'paid', '3000', '0', '0', 3000, NULL, NULL, 0, 'active'),
(1128, '2015-09-07 13:44:39', '2015-09-16 12:10:05', '1059', 1, 1, '2015-09-16 12:10:05', '2015-07-04 22:00:00', 0, 'lalala', 'paid', '3000', '0', '0', 3000, NULL, NULL, 0, 'active'),
(1129, '2015-09-07 13:46:04', '2015-09-16 12:10:03', '1060', 1, 1, '2015-09-16 12:10:03', '2015-07-04 22:00:00', 0, 'lalala', 'paid', '3000', '0', '0', 3000, NULL, NULL, 0, 'active'),
(1130, '2015-09-07 13:47:03', '2015-09-16 12:10:02', '1061', 1, 1, '2015-09-16 12:10:02', '2015-07-04 22:00:00', 0, 'lalala', 'paid', '3000', '0', '0', 3000, NULL, NULL, 0, 'active'),
(1131, '2015-09-07 13:47:19', '2015-09-16 12:10:00', '1062', 1, 1, '2015-09-16 12:10:00', '2015-07-04 22:00:00', 0, 'lalala', 'paid', '3000', '0', '0', 3000, NULL, NULL, 0, 'active'),
(1134, '2015-09-07 13:59:45', '2015-09-16 12:09:59', '1063', 1, 1, '2015-09-16 12:09:59', '2015-07-04 22:00:00', 0, 'lalala', 'paid', '3000', '0', '0', 3000, NULL, NULL, 0, 'active'),
(1135, '2015-09-08 14:47:36', '2015-09-16 12:09:57', 'INV-1000022', 1, 1, '2015-09-16 12:09:57', '2015-09-07 22:00:00', 0, '', 'paid', '450', '0', '0', 450, 'These are the tems', 'The closing notes', 0, 'active'),
(1136, '2015-09-08 15:13:15', '2015-09-16 12:09:56', 'INV-1000024', 5, 1, '2015-09-16 12:09:56', '2015-09-07 22:00:00', 0, '', 'paid', '10000', '0', '0', 10000, '', '', 0, 'active'),
(1137, '2015-09-08 15:40:01', '2015-09-16 12:09:54', 'INV-1000026', 5, 1, '2015-09-16 12:09:54', '2015-09-07 22:00:00', 0, '', 'paid', '100', '0', '0', 100, '', '', 0, 'active'),
(1139, '2015-09-10 13:07:23', '2015-09-16 12:09:53', 'INV-1000027', 1, 1, '2015-09-16 12:09:53', '2015-09-20 22:00:00', 30, 'First Invoice Created By Darren Mansour', 'paid', '701420', '210426', '0', 490994, 'test terms', 'Clsoing Note', 15, 'active'),
(1140, '2015-09-10 13:14:57', '2015-09-16 12:09:51', 'INV-1000029', 1, 1, '2015-09-16 12:09:51', '2015-09-09 22:00:00', 30, 'Second Invoice Created By Darren Mansour', 'paid', '701420', '210426', '0', 490994, 'test terms', 'Clsoing Note', 15, 'active'),
(1141, '2015-09-10 13:22:48', '2015-09-16 12:09:49', 'INV-1000031', 4, 1, '2015-09-16 12:09:49', '2015-09-09 22:00:00', 0, '', 'paid', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1142, '2015-09-10 13:27:38', '2015-09-16 12:09:47', 'INV-1000032', 1, 1, '2015-09-16 12:09:47', '2015-09-09 22:00:00', 0, '', 'paid', '600', '0', '0', 600, '', '', 0, 'active'),
(1143, '2015-09-10 14:23:14', '2015-09-16 12:09:46', 'INV-1000034', 1, 1, '2015-09-16 12:09:46', '2015-09-09 22:00:00', 10, 'testing inv functionality', 'paid', '1930', '218.392', '253.92', 1965.528, '', '', 7, 'archived'),
(1144, '2015-09-10 14:35:59', '2015-09-16 12:09:44', 'INV-1000033', 1, 1, '2015-09-16 12:09:44', '2015-09-09 22:00:00', 10, 'testing inv functionality', 'paid', '1480', '167.74', '197.4', 1509.66, '', '', 7, 'active'),
(1154, '2015-09-11 10:15:31', '2015-09-16 12:09:43', 'INV-1000036', 1, 1, '2015-09-16 12:09:43', '2015-09-10 22:00:00', 0, '', 'paid', '450', '0', '0', 450, '', '', 0, 'active'),
(1155, '2015-09-11 13:09:22', '2015-09-16 12:09:41', 'INV-1000037', 1, 1, '2015-09-16 12:09:41', '2015-09-17 22:00:00', 0, '', 'paid', '450', '0', '0', 450, '', '', 0, 'active'),
(1156, '2015-09-11 13:11:42', '2015-09-16 12:09:39', 'INV-1000038', 1, 1, '2015-09-16 12:09:39', '2015-09-17 22:00:00', 0, '', 'paid', '1200', '0', '168', 1368, '', '', 0, 'active'),
(1157, '2015-09-11 14:29:56', '2015-09-16 12:09:38', 'INV-1000039', 1, 1, '2015-09-16 12:09:38', '2015-09-17 22:00:00', 0, '', 'paid', '1200', '0', '168', 1368, '', '', 0, 'active'),
(1158, '2015-09-15 13:18:19', '2015-09-16 12:09:36', 'INV-1000040', 1, 1, '2015-09-16 12:09:36', '2015-09-21 22:00:00', 0, 'test', 'paid', '1200', '0', '168', 1368, '', '', 0, 'active'),
(1159, '2015-09-15 13:20:08', '2015-09-16 12:09:34', 'INV-1000041', 1, 1, '2015-09-16 12:09:34', '2015-09-21 22:00:00', 0, '', 'paid', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1160, '2015-09-15 13:44:03', '2015-09-16 12:09:33', 'INV-1000042', 1, 1, '2015-09-16 12:09:33', '2015-09-21 22:00:00', 0, '', 'paid', '1200', '0', '168', 1368, '', '', 0, 'active'),
(1161, '2015-09-15 14:05:57', '2015-09-16 12:09:31', 'INV-1000043', 1, 1, '2015-09-16 12:09:31', '2015-09-21 22:00:00', 0, '', 'paid', '1350', '0', '189', 1539, '', '', 0, 'active'),
(1162, '2015-09-16 08:01:37', '2015-09-16 12:09:30', 'INV-1000044', 1, 1, '2015-09-16 12:09:30', '2015-09-22 22:00:00', 0, '', 'paid', '1600', '0', '0', 1600, '', '', 0, 'active'),
(1163, '2015-09-16 10:56:38', '2015-09-16 12:09:28', 'INV-1000045', 1, 1, '2015-09-16 12:09:28', '2015-09-29 22:00:00', 0, '', 'paid', '1200', '0', '168', 1368, '', '', 0, 'active'),
(1164, '2015-09-16 11:01:42', '2015-09-16 12:09:26', 'INV-1000046', 1, 1, '2015-09-16 12:09:26', '2015-09-22 22:00:00', 0, '', 'paid', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1165, '2015-09-16 11:31:04', '2015-09-16 13:53:48', 'INV-1000047', 1, 1, '2015-09-16 13:53:49', '2015-09-22 22:00:00', 0, '', 'partial', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1166, '2015-09-16 12:21:03', '2015-09-16 13:53:17', 'INV-1000048', 1, 1, '2015-09-16 13:53:17', '2015-09-22 22:00:00', 0, '', 'partial', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1167, '2015-09-16 12:21:57', '2015-09-16 12:25:10', 'INV-1000049', 1, 1, '2015-09-16 12:25:10', '2015-09-22 22:00:00', 0, '', 'paid', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1168, '2015-09-16 13:54:37', '2015-09-16 13:54:54', 'INV-1000050', 1, 1, '2015-09-16 13:54:54', '2015-09-22 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1169, '2015-09-16 14:00:06', '2015-09-16 14:00:17', 'INV-1000051', 1, 1, '2015-09-16 14:00:17', '2015-09-22 22:00:00', 0, '', 'sent', '800', '0', '0', 800, '', '', 0, 'active'),
(1170, '2015-09-16 14:00:43', '2015-09-16 14:00:52', 'INV-1000052', 1, 1, '2015-09-16 14:00:52', '2015-09-22 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1171, '2015-09-16 14:01:48', '2015-09-16 14:01:59', 'INV-1000053', 1, 1, '2015-09-16 14:01:59', '2015-09-22 22:00:00', 0, '', 'sent', '800', '0', '0', 800, '', '', 0, 'active'),
(1172, '2015-09-16 14:02:25', '2015-09-16 14:18:11', 'INV-1000054', 1, 1, '2015-09-16 14:18:11', '2015-09-22 22:00:00', 0, '', 'sent', '0', '0', '0', 0, '', '', 0, 'active'),
(1173, '2015-09-16 14:38:43', '2015-09-16 14:54:38', 'INV-1000055', 1, 1, '2015-09-16 14:54:38', '2015-09-22 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1174, '2015-09-16 14:49:58', '2015-09-22 15:30:39', 'INV-1000056', 1, 1, '2015-09-15 22:00:00', '2015-09-22 22:00:00', 10, '', 'sent', '1200', '136.8', '168', 1231.2, '', '', 0, 'active'),
(1176, '2015-09-16 14:59:01', '2015-09-16 14:59:16', 'INV-1000057', 1, 1, '2015-09-16 14:59:16', '2015-09-22 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1177, '2015-09-25 08:44:09', '2015-09-25 08:59:53', 'INV-1000058', 1, 1, '2015-09-24 22:00:00', '2015-10-01 22:00:00', 0, '', 'partial', '1200', '0', '168', 1368, '', '', 0, 'active'),
(1178, '2015-09-25 08:58:59', '0000-00-00 00:00:00', 'INV-1000059', 1, 1, '2015-09-24 22:00:00', '2015-10-01 22:00:00', 0, '', 'draft', '0', '0', '0', 0, '', '', 0, 'active'),
(1179, '2015-09-25 10:22:30', '2015-10-19 09:31:57', 'INV-1000060', 1, 1, '2015-09-24 22:00:00', '2015-10-01 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1180, '2015-09-25 10:36:06', '2015-10-19 09:23:55', 'INV-1000061', 1, 1, '2015-09-24 22:00:00', '2015-10-01 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1181, '2015-10-06 14:32:50', '2015-10-06 14:33:32', 'INV-1000062', 12, 1, '2015-10-05 22:00:00', '2015-10-12 22:00:00', 0, '', 'partial', '100000', '0', '0', 100000, '', '', 0, 'active'),
(1182, '2015-10-07 11:20:08', '2015-10-16 14:56:27', 'INV-1000063', 3, 1, '2015-10-06 22:00:00', '2015-10-13 22:00:00', 0, '', 'paid', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1183, '2015-10-07 11:27:53', '2015-10-16 14:57:34', 'INV-1000064', 3, 1, '2015-10-06 22:00:00', '2015-10-13 22:00:00', 0, '', 'paid', '450', '0', '0', 450, '', '', 0, 'active'),
(1184, '2015-10-07 11:29:14', '2015-10-16 14:51:35', 'INV-1000065', 1, 1, '2015-10-06 22:00:00', '2015-10-13 22:00:00', 0, '', 'sent', '450', '0', '63', 513, '', '', 0, 'active'),
(1185, '2015-10-07 11:30:05', '2015-10-16 14:28:04', 'INV-1000066', 1, 1, '2015-10-06 22:00:00', '2015-10-13 22:00:00', 0, '', 'paid', '450', '0', '0', 450, '', '', 0, 'active'),
(1186, '2015-10-07 11:30:48', '2015-10-07 12:21:08', 'INV-1000067', 1, 1, '2015-10-06 22:00:00', '2015-10-13 22:00:00', 0, '', 'paid', '450', '0', '0', 450, '', '', 0, 'active'),
(1187, '2015-10-07 11:31:25', '2015-10-07 12:18:28', 'INV-1000068', 1, 1, '2015-10-06 22:00:00', '2015-10-13 22:00:00', 0, '', 'paid', '450', '0', '0', 450, '', '', 0, 'active'),
(1188, '2015-10-07 11:32:52', '2015-10-15 15:29:23', 'INV-1000069', 1, 1, '2015-10-06 22:00:00', '2015-10-13 22:00:00', 0, '', 'paid', '2450', '0', '231', 2681, '', '', 0, 'active'),
(1189, '2015-10-15 15:30:33', '2015-10-16 14:53:04', 'INV-1000070', 1, 1, '2015-10-14 22:00:00', '2015-10-21 22:00:00', 0, '', 'paid', '12800', '0', '0', 12800, '', '', 0, 'active'),
(1190, '2015-10-15 15:32:11', '2015-10-16 14:53:05', 'INV-1000071', 1, 1, '2015-10-14 22:00:00', '2015-10-21 22:00:00', 0, '', 'paid', '12000', '0', '0', 12000, '', '', 0, 'active'),
(1192, '2015-10-16 13:40:08', '2015-10-16 14:02:16', 'INV-1000072', 13, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 0, 'darrens 1st invoice', 'paid', '18450', '0', '2583', 21033, '', '', 7, 'active'),
(1193, '2015-10-16 14:03:26', '2015-10-16 14:04:11', 'INV-1000073', 13, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 0, 'darrens 1st invoice', 'paid', '18450', '0', '2583', 21033, 'these are terms', 'this is a closing note', 7, 'active'),
(1194, '2015-10-16 14:05:34', '2015-10-16 14:07:04', 'INV-1000074', 13, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 0, '', 'paid', '2000', '0', '0', 2000, '', '', 0, 'active'),
(1195, '2015-10-16 14:07:37', '2015-10-16 14:20:11', 'INV-1000075', 13, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 0, '', 'paid', '2000', '0', '0', 2000, '', '', 0, 'active'),
(1196, '2015-10-16 14:20:39', '2015-10-16 14:33:47', 'INV-1000076', 13, 1, '2015-10-15 22:00:00', '2015-10-22 22:00:00', 0, '', 'paid', '2000', '0', '280', 2280, '', '', 0, 'active'),
(1197, '2015-10-19 09:52:51', '2015-10-19 09:52:51', 'INV-1000077', 1, 1, '2015-10-18 22:00:00', '2015-10-25 22:00:00', 0, '', 'draft', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1198, '2015-10-19 09:56:43', '2015-10-19 09:56:43', 'INV-1000078', 1, 1, '2015-10-18 22:00:00', '2015-10-25 22:00:00', 0, '', 'draft', '13200', '0', '0', 13200, '', '', 0, 'active'),
(1199, '2015-10-19 10:05:48', '2015-10-19 10:05:48', 'INV-1000079', 1, 1, '2015-10-18 22:00:00', '2015-10-25 22:00:00', 0, '', 'draft', '13200', '0', '0', 13200, '', '', 0, 'active'),
(1200, '2015-10-19 10:07:34', '2015-10-19 10:07:34', 'INV-1000080', 1, 1, '2015-10-18 22:00:00', '2015-10-25 22:00:00', 0, '', 'draft', '13200', '0', '0', 13200, '', '', 0, 'active'),
(1201, '2015-10-19 10:14:13', '2015-11-18 07:37:07', 'INV-1000081', 1, 1, '2015-10-18 22:00:00', '2015-10-25 22:00:00', 0, '', 'sent', '13200', '0', '1848', 15048, '', '', 0, 'active'),
(1202, '2015-10-19 10:23:26', '2015-11-18 07:36:49', 'INV-1000082', 1, 1, '2015-10-18 22:00:00', '2015-10-25 22:00:00', 0, '', 'sent', '0', '0', '0', 0, '', '', 0, 'active'),
(1203, '2015-10-19 10:35:59', '2015-11-18 07:36:20', 'INV-1000083', 1, 1, '2015-10-18 22:00:00', '2015-10-25 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1205, '2015-10-19 10:41:44', '2015-11-05 14:13:37', 'INV-1000084', 1, 1, '2015-10-18 22:00:00', '2015-10-25 22:00:00', 0, '', 'paid', '1200', '0', '0', 1200, '', '', 0, 'active'),
(1206, '2015-10-19 13:11:34', '2015-11-03 15:22:56', 'INV-1000085', 16, 1, '2015-10-18 22:00:00', '2015-10-25 22:00:00', 0, 'test', 'partial', '1600', '0', '224', 1824, '', '', 0, 'active'),
(1207, '2015-11-03 13:48:30', '2015-11-18 07:35:56', 'INV-1000086', 2, 1, '2015-11-02 22:00:00', '2015-11-09 22:00:00', 0, '', 'sent', '1200', '0', '0', 1200, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1208, '2015-11-03 15:32:44', '2015-11-05 13:59:23', 'INV-1000087', 1, 1, '2015-11-02 22:00:00', '2015-11-09 22:00:00', 0, '', 'paid', '800', '0', '0', 800, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1211, '2015-11-06 10:39:44', '2015-11-11 15:03:30', 'INV-1000088', 14, 1, '2015-11-05 22:00:00', '2015-11-12 22:00:00', 0, '', 'paid', '1200', '0', '0', 1200, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1213, '2015-11-18 07:23:21', '2015-11-18 07:25:27', 'INV-1000089', 1, 1, '2015-11-17 22:00:00', '2015-11-24 22:00:00', 0, 'test', 'sent', '1200', '0', '168', 1368, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1214, '2015-11-18 07:26:01', '2015-12-03 12:21:58', 'INV-1000090', 1, 1, '2015-12-01 22:00:00', '2015-12-02 22:00:00', 0, 'test ref', 'sent', '800', '0', '0', 800, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1215, '2015-12-03 12:24:30', '2015-12-03 12:24:47', 'INV-1000091', 16, 1, '2015-12-02 22:00:00', '2015-12-09 22:00:00', 0, 'test ref', 'sent', '800', '0', '112', 912, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1216, '2015-12-03 12:25:59', '2015-12-04 11:38:26', 'INV-1000092', 16, 1, '2015-12-02 22:00:00', '2015-12-09 22:00:00', 0, '', 'sent', '1250', '0', '175', 1425, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1217, '2015-12-03 12:29:51', '2015-12-03 13:29:43', 'INV-1000093', 16, 1, '2015-12-02 22:00:00', '2015-12-09 22:00:00', 0, '', 'sent', '0', '0', '0', 0, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1218, '2015-12-04 11:41:49', '2015-12-04 11:45:59', 'INV-1000094', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'sent', '800', '0', '0', 800, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1219, '2015-12-04 11:51:35', '2015-12-04 11:51:53', 'INV-1000095', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'sent', '450', '0', '0', 450, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1220, '2015-12-04 11:59:02', '2015-12-04 11:59:42', 'INV-1000096', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'sent', '450', '0', '0', 450, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1221, '2015-12-04 12:07:16', '2015-12-11 15:05:47', 'INV-1000097', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'partial', '800', '0', '0', 800, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1222, '2015-12-04 12:08:31', '2015-12-04 12:08:40', 'INV-1000098', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'sent', '0', '0', '0', 0, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1224, '2015-12-04 12:10:39', '2015-12-04 12:10:46', 'INV-1000099', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'sent', '0', '0', '0', 0, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1225, '2015-12-04 13:07:23', '2015-12-11 15:07:55', 'INV-1000100', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'paid', '900', '0', '0', 900, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1226, '2015-12-04 13:13:41', '2015-12-11 15:05:29', 'INV-1000101', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'paid', '0', '0', '0', 0, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1227, '2015-12-04 13:20:29', '2015-12-11 15:03:05', 'INV-1000102', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'paid', '800', '0', '0', 800, 'invoice terms', 'Invoice closing note', 0, 'active'),
(1228, '2015-12-04 13:21:14', '2015-12-11 12:50:44', 'INV-1000103', 16, 1, '2015-12-03 22:00:00', '2015-12-10 22:00:00', 0, '', 'paid', '800', '0', '0', 800, 'invoice terms', 'Invoice closing note', 7, 'active'),
(1229, '2015-12-11 12:51:57', '2015-12-11 15:21:06', 'INV-1000104', 16, 1, '2015-12-10 22:00:00', '2015-12-17 22:00:00', 0, '', 'paid', '800', '0', '112', 912, 'invoice terms', 'Invoice closing note', 15, 'active'),
(1230, '2015-12-11 12:58:25', '2015-12-11 15:14:25', 'INV-1000105', 16, 1, '2015-12-10 22:00:00', '2015-12-17 22:00:00', 0, '', 'draft', '450', '0', '0', 450, 'invoice terms', 'Invoice closing note', 7, 'active'),
(1231, '2015-12-11 12:58:51', '2015-12-11 13:58:20', 'INV-1000106', 16, 1, '2015-12-10 22:00:00', '2015-12-17 22:00:00', 0, '', 'paid', '9000', '0', '0', 9000, 'invoice terms', 'Invoice closing note', 0, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `boost_invoice_items`
--

CREATE TABLE `boost_invoice_items` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `invoice_id` int(11) NOT NULL,
  `item_name` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `tax` int(11) NOT NULL DEFAULT '0',
  `rate` varchar(15) NOT NULL,
  `total_amount` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_invoice_items`
--

INSERT INTO `boost_invoice_items` (`id`, `date_created`, `invoice_id`, `item_name`, `description`, `quantity`, `tax`, `rate`, `total_amount`) VALUES
(9, '2015-06-30 12:54:50', 1005, 'Coffee table', 'the first item\'s description', 5, 4, '14', NULL),
(10, '2015-06-30 12:54:50', 1005, 'the second item', 'the second item\'s description', 2, 5, '5467', NULL),
(11, '2015-06-30 12:54:51', 1006, 'Coffee table', 'the first item\'s description', 1, 6, '665', NULL),
(12, '2015-06-30 12:54:51', 1006, 'the second item', 'the second item\'s description', 2, 7, '14', NULL),
(13, '2015-06-30 12:54:51', 1007, 'Coffee table', 'the first item\'s description', 2, 6, '2345', NULL),
(14, '2015-06-30 12:54:51', 1007, 'the second item', 'the second item\'s description', 2, 4, '14', NULL),
(15, '2015-06-30 12:54:51', 1008, 'Coffee table', 'the first item\'s description', 3, 4, '3434', NULL),
(16, '2015-06-30 12:54:51', 1008, 'the second item', 'the second item\'s description', 2, 3, '14', NULL),
(17, '2015-06-30 12:54:51', 1009, 'Coffee table', 'the first item\'s description', 2, 3, '324', NULL),
(18, '2015-06-30 12:54:51', 1009, 'the second item', 'the second item\'s description', 2, 4, '4523', NULL),
(19, '2015-06-30 12:54:51', 1010, 'Coffee table', 'the first item\'s description', 3, 4, '45', NULL),
(20, '2015-06-30 12:54:51', 1010, 'the second item', 'the second item\'s description', 3, 6, '14', NULL),
(21, '2015-06-30 12:54:52', 1011, 'Coffee table', 'the first item\'s description', 1, 2, '344', NULL),
(22, '2015-06-30 12:54:52', 1011, 'the second item', 'the second item\'s description', 2, 1, '3223', NULL),
(23, '2015-06-30 12:54:52', 1012, 'Coffee table', 'the first item\'s description', 2, 5, '14', NULL),
(24, '2015-06-30 12:54:52', 1012, 'the second item', 'the second item\'s description', 3, 4, '4556', NULL),
(25, '2015-07-15 13:41:35', 1013, 'Coffee table', 'the first item\'s description', 1, 4, '14', NULL),
(26, '2015-07-15 13:41:35', 1013, 'the second item', 'the second item\'s description', 3, 3, '454', NULL),
(27, '2015-07-15 13:43:18', 1014, 'Coffee table', 'the first item\'s description', 2, 1, '123', NULL),
(28, '2015-07-15 13:43:18', 1014, 'the second item', 'the second item\'s description', 2, 1, '14', NULL),
(29, '2015-07-15 14:05:08', 1015, 'Coffee table', 'the first item\'s description', 1, 5, '14', NULL),
(30, '2015-07-15 14:05:08', 1015, 'the second item', 'the second item\'s description', 2, 4, '433', NULL),
(31, '2015-07-15 14:05:12', 1016, 'Coffee table', 'the first item\'s description', 2, 3, '14', NULL),
(32, '2015-07-15 14:05:12', 1016, 'the second item', 'the second item\'s description', 11, 6, '445', NULL),
(33, '2015-07-15 14:05:55', 1017, 'Coffee table', 'the first item\'s description', 2, 1, '14', NULL),
(34, '2015-07-15 14:05:55', 1017, 'the second item', 'the second item\'s description', 2, 5, '14', NULL),
(35, '2015-07-16 09:26:25', 1018, 'Coffee table', 'the first item\'s description', 1, 4, '14', NULL),
(36, '2015-07-16 09:26:25', 1018, 'the second item', 'the second item\'s description', 2, 4, '14', NULL),
(37, '2015-07-16 14:44:44', 1019, 'Coffee table', 'the first item\'s description', 2, 3, '4566', NULL),
(38, '2015-07-16 14:44:44', 1019, 'the second item', 'the second item\'s description', 5, 2, '14', NULL),
(39, '2015-07-16 14:45:52', 1020, 'Coffee table', 'the first item\'s description', 2, 1, '14', NULL),
(40, '2015-07-16 14:45:52', 1020, 'the second item', 'the second item\'s description', 2, 4, '3423', NULL),
(41, '2015-07-17 07:29:09', 1021, 'Coffee table', 'the first item\'s description', 5, 3, '14', NULL),
(42, '2015-07-17 07:29:09', 1021, 'the second item', 'the second item\'s description', 1, 2, '14', NULL),
(43, '2015-07-17 07:29:22', 1022, 'Coffee table', 'the first item\'s description', 3, 4, '453', NULL),
(44, '2015-07-17 07:29:22', 1022, 'the second item', 'the second item\'s description', 2, 4, '3454', NULL),
(53, '2015-07-21 10:07:13', 1045, 'Coffee table', 'the first item\'s description', 1, 4, '4534', NULL),
(54, '2015-07-21 10:07:13', 1045, 'the second item', 'the second item\'s description', 2, 6, '14', NULL),
(55, '2015-07-21 10:10:26', 1046, 'Coffee table', 'the first item\'s description', 3, 1, '3455', NULL),
(56, '2015-07-21 10:10:26', 1046, 'the second item', 'the second item\'s description', 1, 3, '3454', NULL),
(67, '2015-07-21 11:25:29', 1023, 'Coffee table', 'the first item\'s description', 3, 2, '545', NULL),
(68, '2015-07-21 11:25:29', 1023, 'the second item', 'the second item\'s description', 2, 1, '455', NULL),
(77, '2015-07-24 14:28:29', 1040, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(78, '2015-07-24 14:28:29', 1040, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(79, '2015-07-24 14:28:56', 10398, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(80, '2015-07-24 14:28:56', 10398, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(89, '2015-07-24 14:39:13', 1039, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(90, '2015-07-24 14:39:13', 1039, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(91, '2015-07-28 15:32:44', 1048, 'test item', 'test item desc', 10, 0, '10', '100'),
(92, '2015-07-28 15:37:20', 1049, 'test item', 'test item desc', 10, 0, '10', '100'),
(93, '2015-07-28 15:37:41', 1050, 'test item', 'test item desc', 10, 1, '10', '100'),
(94, '2015-07-28 15:44:48', 1051, 'test item', 'test item desc', 10, 1, '10', '100'),
(122, '2015-07-30 07:39:46', 1056, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(123, '2015-07-30 07:39:46', 1056, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(124, '2015-07-30 07:40:34', 1057, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(125, '2015-07-30 07:40:34', 1057, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(126, '2015-07-30 07:41:06', 1058, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(127, '2015-07-30 07:41:06', 1058, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(128, '2015-07-30 07:42:54', 1059, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(129, '2015-07-30 07:42:54', 1059, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(130, '2015-07-30 07:44:13', 1060, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(131, '2015-07-30 07:44:13', 1060, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(132, '2015-07-30 07:45:40', 1061, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(133, '2015-07-30 07:45:40', 1061, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(147, '2015-07-30 09:35:14', 1062, 'Bed', 'Queen size', 2, 0, '12000', '24000'),
(148, '2015-07-30 09:35:14', 1062, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(149, '2015-08-12 12:43:40', 1064, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(150, '2015-08-12 12:43:40', 1064, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(160, '2015-08-14 11:11:11', 1053, 'Table', 'Accomodates 4 seats', 0, 1, '1200', '0'),
(164, '2015-08-14 11:48:21', 1065, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(167, '2015-08-14 12:15:01', 1054, '', '', 0, 1, '0', '0'),
(170, '2015-08-18 08:10:27', 1052, 'test item', 'test item desc', 10, 1, '10', '100'),
(174, '2015-08-19 12:43:08', 1066, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(175, '2015-08-21 10:08:42', 1070, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(176, '2015-08-21 10:08:42', 1070, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(177, '2015-08-24 11:52:29', 1071, 'Chair', 'With wheels', 1, 0, '450', '450'),
(178, '2015-08-24 11:52:29', 1071, 'Table', 'Accomodates 4 seats', 0, 1, '1200', '0'),
(179, '2015-08-24 11:53:33', 1072, 'Chair', 'With wheels', 1, 0, '450', '450'),
(180, '2015-08-24 11:53:33', 1072, 'Table', 'Accomodates 4 seats', 0, 1, '1200', '0'),
(181, '2015-08-24 11:53:58', 1073, 'Chair', 'With wheels', 1, 2, '450', '450'),
(182, '2015-08-24 11:53:58', 1073, 'Table', 'Accomodates 4 seats', 0, 1, '1200', '0'),
(183, '2015-08-24 12:21:51', 1074, 'Chair', 'With wheels', 1, 2, '450', '450'),
(184, '2015-08-24 12:21:51', 1074, 'Table', 'Accomodates 4 seats', 0, 1, '1200', '0'),
(185, '2015-08-24 12:22:02', 1075, 'Chair', 'With wheels', 1, 2, '450', '450'),
(186, '2015-08-24 12:22:02', 1075, 'Table', 'Accomodates 4 seats', 0, 1, '1200', '0'),
(187, '2015-08-25 13:00:33', 1076, 'Chair', 'With wheels', 1, 2, '450', '450'),
(188, '2015-08-25 13:00:33', 1076, 'Table', 'Accomodates 4 seats', 0, 1, '1200', '0'),
(189, '2015-08-26 14:27:25', 1077, 'Chair', 'With wheels', 1, 0, '450', '450'),
(190, '2015-08-26 14:27:25', 1077, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(191, '2015-08-26 14:42:35', 1078, 'Chair', 'With wheels', 1, 0, '450', '450'),
(192, '2015-08-26 14:42:35', 1078, 'Table', 'Accomodates 4 seats', 0, 0, '1200', '0'),
(193, '2015-08-26 15:14:06', 1079, 'Chair', 'With wheels', 1, 2, '450', '450'),
(194, '2015-08-26 15:14:06', 1079, 'Table', 'Accomodates 4 seats', 0, 1, '1200', '0'),
(195, '2015-08-26 15:21:25', 1080, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(196, '2015-08-26 15:21:25', 1080, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(197, '2015-08-26 15:22:48', 1081, 'Coffee table', 'the first item\'s description', 2, 5, '1000', '2000'),
(198, '2015-08-26 15:22:48', 1081, 'the second item', 'the second item\'s description', 2, 5, '1000', '2000'),
(199, '2015-08-26 15:23:59', 1082, 'Chair', 'With wheels', 1, 2, '450', '450'),
(200, '2015-08-26 15:23:59', 1082, 'Table', 'Accomodates 4 seats', 0, 1, '1200', '0'),
(201, '2015-08-27 12:46:29', 1083, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(202, '2015-08-27 12:46:58', 1084, 'Chair', 'With wheels', 1, 0, '450', '450'),
(203, '2015-08-31 15:14:16', 1085, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(204, '2015-08-31 16:22:01', 1086, 'Chair', 'With wheels', 1, 0, '450', '450'),
(205, '2015-09-01 07:45:21', 1087, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(206, '2015-09-01 07:47:44', 1088, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(208, '2015-09-01 07:51:22', 1090, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(209, '2015-09-01 07:51:52', 1091, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(210, '2015-09-01 07:53:55', 1092, '', '', 1, 0, '0', '0'),
(211, '2015-09-01 07:54:37', 1093, '', '', 1, 0, '0', '0'),
(212, '2015-09-01 07:55:28', 1094, '', '', 1, 0, '0', '0'),
(213, '2015-09-01 08:00:05', 1095, '', '', 1, 0, '0', '0'),
(214, '2015-09-01 08:00:51', 1096, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(215, '2015-09-01 08:53:21', 1097, 'Samsung TV', '52\" HD TV', 1, 0, '100', '100'),
(216, '2015-09-01 09:52:18', 1098, '', '', 1, 0, '0', '0'),
(218, '2015-09-01 12:47:20', 1100, '', '', 1, 0, '0', '0'),
(219, '2015-09-01 12:50:51', 1101, '', '', 1, 0, '0', '0'),
(220, '2015-09-01 14:25:58', 1102, '', '', 1, 0, '0', '0'),
(221, '2015-09-01 14:28:09', 1103, '', '', 1, 0, '0', '0'),
(222, '2015-09-01 14:28:31', 1104, '', '', 1, 0, '0', '0'),
(223, '2015-09-01 14:53:32', 1106, 'Table', 'Accomodates 4 seats', 1, 2, '1200', '1200'),
(224, '2015-09-01 14:53:32', 1106, 'Chair', 'With wheels', 2, 0, '450', '900'),
(246, '2015-09-01 15:10:17', 1108, 'Table', 'Accomodates 4 seats', 2, 2, '1200', '2400'),
(247, '2015-09-01 15:10:17', 1108, 'Chair', 'With wheels', 2, 0, '450', '900'),
(262, '2015-09-01 15:23:54', 1107, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(270, '2015-09-02 09:57:38', 1055, 'Coffee table', 'the first item\'s description', 1, 0, '1000', '1000'),
(271, '2015-09-02 09:57:38', 1055, 'the second item', 'the second item\'s description', 1, 0, '1000', '1000'),
(272, '2015-09-02 09:57:38', 1055, 'the third item', 'the third item\'s description', 1, 0, '1000', '1000'),
(273, '2015-09-03 10:13:42', 1109, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(275, '2015-09-03 10:50:23', 1099, 'Chair', 'With wheels', 1, 0, '450', '450'),
(276, '2015-09-03 11:33:55', 1110, 'Table', 'Accomodates 4 seats', 10, 0, '1200', '12000'),
(277, '2015-09-03 11:38:21', 1111, 'Chair', 'With wheels', 1, 0, '450', '450'),
(278, '2015-09-03 11:39:29', 1112, 'Chair', 'With wheels', 1, 0, '450', '450'),
(280, '2015-09-03 11:55:38', 1113, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(281, '2015-09-03 12:14:18', 1114, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(282, '2015-09-03 12:23:08', 1115, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(283, '2015-09-03 13:25:30', 1116, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(285, '2015-09-04 11:00:16', 1119, 'Chair', 'With wheels', 1, 0, '450', '450'),
(286, '2015-09-04 11:00:16', 1119, 'Ladder', '15 Meters', 1, 0, '600', '600'),
(287, '2015-09-04 12:50:30', 1118, 'Chair', 'With wheels', 1, 0, '450', '450'),
(288, '2015-09-04 12:50:30', 1118, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(291, '2015-09-04 13:27:04', 1121, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(292, '2015-09-04 13:27:04', 1121, 'Chair', 'With wheels', 1, 0, '450', '450'),
(293, '2015-09-07 13:33:08', 1122, '', '', 1, 0, '0', '0'),
(294, '2015-09-07 13:36:41', 1123, '', '', 1, 0, '0', '0'),
(295, '2015-09-07 13:46:04', 1129, 'Coffee table', 'the first item\'s description', 1, 0, '1000', '1000'),
(296, '2015-09-07 13:46:04', 1129, 'the second item', 'the second item\'s description', 1, 0, '1000', '1000'),
(297, '2015-09-07 13:46:04', 1129, 'the third item', 'the third item\'s description', 1, 0, '1000', '1000'),
(298, '2015-09-07 13:47:03', 1130, 'Coffee table', 'the first item\'s description', 1, 0, '1000', '1000'),
(299, '2015-09-07 13:47:03', 1130, 'the second item', 'the second item\'s description', 1, 0, '1000', '1000'),
(300, '2015-09-07 13:47:03', 1130, 'the third item', 'the third item\'s description', 1, 0, '1000', '1000'),
(301, '2015-09-07 13:59:45', 1134, 'Coffee table', 'sdsadadas', 1, 0, '1000', '1000'),
(302, '2015-09-07 13:59:45', 1134, 'the second item', 'the second item\'s description', 1, 0, '1000', '1000'),
(303, '2015-09-07 13:59:45', 1134, 'the third item', 'the third item\'s description', 1, 0, '1000', '1000'),
(305, '2015-09-08 14:49:10', 1135, 'Chair', 'With wheels', 1, 0, '450', '450'),
(306, '2015-09-08 15:13:15', 1136, 'Samsung TV', '52', 1, 0, '10000', '10000'),
(307, '2015-09-08 15:40:01', 1137, 'Chair', 'With wheels', 1, 0, '100', '100'),
(313, '2015-09-10 13:07:42', 1139, '', 'Graphic Design', 20, 0, '150', '3000'),
(314, '2015-09-10 13:07:42', 1139, '', 'Web Design', 1, 0, '1200', '1200'),
(315, '2015-09-10 13:07:42', 1139, '', 'Development', 200, 0, '3000', '600000'),
(316, '2015-09-10 13:07:42', 1139, 'design', 'Test ', 300, 0, '30', '9000'),
(317, '2015-09-10 13:07:42', 1139, '', 'Account Management', 401, 0, '220', '88220'),
(318, '2015-09-10 13:14:57', 1140, '', 'Graphic Design', 20, 0, '150', '3000'),
(319, '2015-09-10 13:14:57', 1140, '', 'Web Design', 1, 0, '1200', '1200'),
(320, '2015-09-10 13:14:57', 1140, '', 'Development', 200, 0, '3000', '600000'),
(321, '2015-09-10 13:14:57', 1140, 'design', 'Test ', 300, 0, '30', '9000'),
(322, '2015-09-10 13:14:57', 1140, '', 'Account Management', 401, 0, '220', '88220'),
(323, '2015-09-10 13:22:48', 1141, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(324, '2015-09-10 13:27:38', 1142, 'Ladder', '15 Meters', 1, 0, '600', '600'),
(328, '2015-09-10 14:32:20', 1143, 'Table', 'Accomodates 4 seats', 1, 2, '1200', '1200'),
(329, '2015-09-10 14:32:20', 1143, 'Hard Hat', 'Man size', 1, 1, '280', '280'),
(330, '2015-09-10 14:32:20', 1143, 'Chair', 'With wheels', 1, 3, '450', '450'),
(331, '2015-09-10 14:35:59', 1144, 'Table', 'Accomodates 4 seats', 1, 2, '1200', '1200'),
(332, '2015-09-10 14:35:59', 1144, 'Hard Hat', 'Man size', 1, 1, '280', '280'),
(333, '2015-09-11 10:15:31', 1154, 'Chair', 'With wheels', 1, 0, '450', '450'),
(334, '2015-09-11 13:09:22', 1155, 'Chair', 'With wheels', 1, 0, '450', '450'),
(342, '2015-09-15 11:12:30', 1156, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(344, '2015-09-15 13:18:43', 1158, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(345, '2015-09-15 13:20:09', 1159, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(346, '2015-09-15 13:44:03', 1160, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(348, '2015-09-16 07:59:37', 1161, 'Chair', 'With wheels', 3, 1, '450', '1350'),
(349, '2015-09-16 08:01:37', 1162, 'Table', 'Accomodates 4 seats', 1, 0, '1600', '1600'),
(350, '2015-09-16 10:46:08', 1157, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(352, '2015-09-16 11:01:42', 1164, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(354, '2015-09-16 11:16:50', 1163, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(355, '2015-09-16 11:31:04', 1165, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(356, '2015-09-16 12:21:03', 1166, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(357, '2015-09-16 12:21:57', 1167, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(358, '2015-09-16 13:54:38', 1168, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(359, '2015-09-16 14:00:06', 1169, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(360, '2015-09-16 14:00:43', 1170, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(361, '2015-09-16 14:01:48', 1171, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(362, '2015-09-16 14:02:25', 1172, 'Table', 'Accomodates 4 seats', 0, 1, '1200', '0'),
(363, '2015-09-16 14:38:44', 1173, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(365, '2015-09-16 14:59:01', 1176, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(366, '2015-09-22 15:30:40', 1174, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(368, '2015-09-25 08:45:07', 1177, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(369, '2015-09-25 08:58:59', 1178, 'Table', 'Accomodates 4 seats', 0, 0, '1200', '0'),
(370, '2015-09-25 10:22:30', 1179, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(372, '2015-10-06 14:31:17', 1180, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(373, '2015-10-06 14:32:51', 1181, 'Cerebro', 'Mind machine', 1, 0, '100000', '100000'),
(374, '2015-10-07 11:20:08', 1182, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(375, '2015-10-07 11:27:53', 1183, 'Chair', 'With wheels', 1, 0, '450', '450'),
(377, '2015-10-07 11:30:05', 1185, 'Chair', 'With wheels', 1, 0, '450', '450'),
(378, '2015-10-07 11:30:48', 1186, 'Chair', 'With wheels', 1, 0, '450', '450'),
(379, '2015-10-07 11:31:25', 1187, 'Chair', 'With wheels', 1, 0, '450', '450'),
(388, '2015-10-15 15:29:23', 1188, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(389, '2015-10-15 15:29:23', 1188, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(390, '2015-10-15 15:29:23', 1188, 'Chair', 'With wheels', 1, 1, '450', '450'),
(391, '2015-10-15 15:30:33', 1189, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(392, '2015-10-15 15:30:33', 1189, 'Bed', 'Queen size', 1, 0, '12000', '12000'),
(393, '2015-10-15 15:32:11', 1190, 'Bed', 'Queen size', 1, 0, '12000', '12000'),
(400, '2015-10-16 13:57:43', 1192, 'Bar one chocolate', 'very sweet', 5, 1, '10', '50'),
(401, '2015-10-16 13:57:43', 1192, 'Table', 'Accomodates 4 seats', 2, 1, '1200', '2400'),
(402, '2015-10-16 13:57:43', 1192, 'Coffee Table', 'Wooden', 20, 1, '800', '16000'),
(403, '2015-10-16 14:03:26', 1193, 'Bar one chocolate', 'very sweet', 5, 1, '10', '50'),
(404, '2015-10-16 14:03:26', 1193, 'Table', 'Accomodates 4 seats', 2, 1, '1200', '2400'),
(405, '2015-10-16 14:03:26', 1193, 'Coffee Table', 'Wooden', 20, 1, '800', '16000'),
(407, '2015-10-16 14:06:38', 1194, 'Table', 'Accomodates 4 seats', 1, 0, '2000', '2000'),
(408, '2015-10-16 14:07:37', 1195, 'Table', 'Accomodates 4 seats', 1, 0, '2000', '2000'),
(410, '2015-10-16 14:21:34', 1196, 'Table', 'Accomodates 4 seats', 1, 1, '2000', '2000'),
(411, '2015-10-16 14:28:56', 1184, 'Chair', 'With wheels', 1, 1, '450', '450'),
(434, '2015-10-19 10:22:58', 1201, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(435, '2015-10-19 10:22:58', 1201, 'Bed', 'Queen size', 1, 1, '12000', '12000'),
(436, '2015-10-19 10:22:59', 1201, '', 'Imaginary Product Desription', 0, 1, '0', '0'),
(437, '2015-10-19 10:23:26', 1202, 'Table', 'Accomodates 4 seats', 0, 0, '1200', '0'),
(438, '2015-10-19 10:23:26', 1202, NULL, NULL, 0, 0, '', NULL),
(452, '2015-11-03 15:22:56', 1206, 'Coffee Table', 'Wooden', 2, 1, '800', '1600'),
(454, '2015-11-05 13:19:52', 1208, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(455, '2015-11-05 14:05:38', 1205, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(456, '2015-11-05 14:20:30', 1207, 'Table', 'Test Accomodates 4 seats', 1, 0, '1200', '1200'),
(458, '2015-11-05 14:31:04', 1209, 'Both', 'Dance Duo', 2, 0, '5000', '10000'),
(461, '2015-11-06 08:28:28', 1210, 'Shoes', 'interesting', 1, 0, '1000', '1000'),
(462, '2015-11-06 10:39:44', 1211, 'Table', 'Accomodates 4 seats', 1, 0, '1200', '1200'),
(465, '2015-11-18 07:25:12', 1213, 'Table', 'Accomodates 4 seats', 1, 1, '1200', '1200'),
(485, '2015-12-03 12:21:58', 1214, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(486, '2015-12-03 12:24:30', 1215, 'Coffee Table', 'Wooden', 1, 1, '800', '800'),
(487, '2015-12-03 12:25:59', 1216, 'Coffee Table', 'Wooden', 1, 1, '800', '800'),
(488, '2015-12-03 12:25:59', 1216, 'Chair', 'With wheels', 1, 1, '450', '450'),
(501, '2015-12-03 12:56:34', 1217, 'Chair', 'With wheels', 0, 0, '450', '0'),
(502, '2015-12-03 12:56:34', 1217, 'Coffee Table', 'Wooden', 0, 0, '800', '0'),
(503, '2015-12-04 11:41:49', 1218, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(504, '2015-12-04 11:51:35', 1219, 'Chair', 'With wheels', 1, 0, '450', '450'),
(505, '2015-12-04 11:59:02', 1220, 'Chair', 'With wheels', 1, 0, '450', '450'),
(506, '2015-12-04 12:07:17', 1221, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(507, '2015-12-04 12:08:31', 1222, 'Coffee Table', 'Wooden', 0, 0, '800', '0'),
(508, '2015-12-04 12:10:40', 1224, 'Chair', 'With wheels', 0, 0, '450', '0'),
(511, '2015-12-04 13:20:29', 1227, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(512, '2015-12-04 13:21:14', 1228, 'Coffee Table', 'Wooden', 1, 0, '800', '800'),
(514, '2015-12-11 12:58:26', 1230, 'Chair', 'With wheels', 1, 0, '450', '450'),
(516, '2015-12-11 13:56:32', 1231, 'Chair', 'With wheels', 1, 0, '9000', '9000'),
(517, '2015-12-11 14:59:39', 1226, 'Coffee Table', 'Wooden', 0, 0, '800', '0'),
(518, '2015-12-11 15:07:55', 1225, 'Coffee Table', 'Wooden', 1, 0, '900', '900'),
(520, '2015-12-11 15:13:59', 1229, 'Coffee Table', 'Wooden', 1, 1, '800', '800');

-- --------------------------------------------------------

--
-- Table structure for table `boost_invoice_payments`
--

CREATE TABLE `boost_invoice_payments` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `invoice_id` int(11) NOT NULL,
  `payment_amount` varchar(20) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `credit_applied` double DEFAULT '0',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payment_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notification` varchar(10) DEFAULT NULL,
  `use_credit` varchar(5) DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_invoice_payments`
--

INSERT INTO `boost_invoice_payments` (`id`, `date_created`, `invoice_id`, `payment_amount`, `payment_method_id`, `reference`, `credit_applied`, `date_modified`, `payment_date`, `notification`, `use_credit`) VALUES
(1, '2015-08-14 13:21:20', 1001, '1000', 1, 'Part payment ', 0, '2015-08-21 09:24:12', '0000-00-00 00:00:00', NULL, 'no'),
(2, '2015-08-18 10:05:16', 1004, '1000', 1, 'Part payment 2', 0, '2015-08-18 10:05:16', '0000-00-00 00:00:00', NULL, 'no'),
(3, '2015-08-18 10:06:09', 1004, '1000', 1, 'Part payment 2', 0, '2015-08-18 10:06:09', '0000-00-00 00:00:00', NULL, 'no'),
(4, '2015-08-18 10:33:05', 1004, '1000', 1, 'Part payment 2', 0, '2015-08-18 10:33:05', '0000-00-00 00:00:00', NULL, 'no'),
(5, '2015-08-31 13:32:47', 1084, '450.00', 1, 'INV-000037', 0, '2015-08-31 13:32:47', '0000-00-00 00:00:00', NULL, 'no'),
(9, '2015-08-31 14:24:28', 1084, '200', 1, 'INV-000037', 0, '2015-08-31 14:24:28', '0000-00-00 00:00:00', NULL, 'no'),
(10, '2015-08-31 14:53:48', 1082, '1000', 1, 'INV-000035', 0, '2015-08-31 14:53:48', '0000-00-00 00:00:00', NULL, 'no'),
(11, '2015-08-31 15:06:00', 1066, '50000', 1, 'INV-012347', 0, '2015-08-31 15:06:00', '0000-00-00 00:00:00', NULL, 'no'),
(12, '2015-08-31 15:11:40', 1063, '48000', 1, 'INV-00684', 0, '2015-08-31 15:11:40', '0000-00-00 00:00:00', NULL, 'no'),
(13, '2015-08-31 15:21:07', 1084, '2000', 1, 'INV-000037', 0, '2015-08-31 15:21:07', '0000-00-00 00:00:00', NULL, 'no'),
(18, '2015-08-31 16:11:04', 1085, '1200', 1, 'Part payment ', 200, '2015-08-31 16:11:04', '0000-00-00 00:00:00', NULL, 'yes'),
(22, '2015-09-01 08:32:35', 1096, '800', 1, 'INV-000050', 0, '2015-09-01 08:32:35', '0000-00-00 00:00:00', NULL, 'no'),
(23, '2015-09-01 09:56:35', 1099, '450', 1, 'INV-000054', 250, '2015-09-01 09:56:35', '0000-00-00 00:00:00', NULL, 'yes'),
(24, '2015-09-01 15:00:22', 1106, '2268.00', 1, 'INV-123458', 0, '2015-09-01 15:00:22', '0000-00-00 00:00:00', NULL, 'no'),
(25, '2015-09-01 15:03:27', 1107, '100', 1, 'INV-123460', 0, '2015-09-01 15:03:27', '0000-00-00 00:00:00', NULL, 'no'),
(26, '2015-09-01 15:25:10', 1109, '1,200.00', 1, 'INV-123464', 0, '2015-09-01 15:25:10', '0000-00-00 00:00:00', NULL, 'no'),
(27, '2015-09-01 15:26:36', 1108, '100', 1, 'INV-123462', 0, '2015-09-01 15:26:36', '0000-00-00 00:00:00', NULL, 'no'),
(28, '2015-09-01 15:26:54', 1109, '100', 1, 'INV-123464', 0, '2015-09-01 15:26:54', '0000-00-00 00:00:00', NULL, 'no'),
(29, '2015-09-01 15:35:02', 1109, '3000', 1, 'INV-123464', 0, '2015-09-01 15:35:02', '0000-00-00 00:00:00', NULL, 'no'),
(30, '2015-09-01 15:35:36', 1108, '100', 1, 'INV-123462', 0, '2015-09-01 15:35:36', '0000-00-00 00:00:00', NULL, 'no'),
(35, '2015-09-02 14:02:16', 1108, '4436', 1, 'INV-123462', 0, '2015-09-02 14:02:16', '0000-00-00 00:00:00', NULL, 'no'),
(36, '2015-09-02 14:35:44', 1096, '550', 1, 'INV-000050', 0, '2015-09-02 14:35:44', '0000-00-00 00:00:00', NULL, 'no'),
(37, '2015-09-02 14:39:17', 1107, '30000', 1, 'INV-123460', 0, '2015-09-02 14:39:17', '0000-00-00 00:00:00', NULL, 'no'),
(45, '2015-09-02 15:09:29', 1097, '0.00', 1, 'INV-000051', 0, '2015-09-02 15:09:29', '0000-00-00 00:00:00', NULL, 'yes'),
(46, '2015-09-03 11:28:53', 1099, '0.00', 1, 'INV-000054', 0, '2015-09-03 11:28:53', '0000-00-00 00:00:00', NULL, 'yes'),
(47, '2015-09-03 11:42:24', 1110, '0.00', 1, 'INV-123466', 0, '2015-09-03 11:42:24', '0000-00-00 00:00:00', NULL, 'yes'),
(48, '2015-09-03 11:47:54', 1111, '1500', 1, 'INV-999998', 0, '2015-09-03 11:47:54', '0000-00-00 00:00:00', NULL, 'no'),
(49, '2015-09-03 11:50:09', 1112, '100', 1, 'INV-000001', 0, '2015-09-03 11:50:09', '0000-00-00 00:00:00', NULL, 'yes'),
(50, '2015-09-03 12:11:05', 1113, '0.00', 1, 'INV-1000000', 0, '2015-09-03 12:11:05', '0000-00-00 00:00:00', NULL, 'yes'),
(51, '2015-09-03 13:24:59', 1114, '3000', 1, 'INV-1000002', 0, '2015-09-03 13:24:59', '0000-00-00 00:00:00', NULL, 'no'),
(52, '2015-09-03 13:26:32', 1116, '0.00', 1, 'INV-1000006', 0, '2015-09-03 13:26:32', '0000-00-00 00:00:00', NULL, 'yes'),
(53, '2015-09-03 13:40:22', 1116, '0.00', 1, 'INV-1000006', 0, '2015-09-03 13:40:22', '0000-00-00 00:00:00', NULL, 'yes'),
(54, '2015-09-03 15:48:57', 1109, '0.00', 1, 'INV-123464', 0, '2015-09-03 15:48:57', '0000-00-00 00:00:00', NULL, 'yes'),
(55, '2015-09-03 15:49:30', 1106, '0.00', 1, 'INV-123458', 0, '2015-09-03 15:49:30', '0000-00-00 00:00:00', NULL, 'yes'),
(56, '2015-09-03 15:58:34', 1116, '0.00', 1, 'INV-1000006', 0, '2015-09-03 15:58:34', '0000-00-00 00:00:00', NULL, 'yes'),
(57, '2015-09-04 09:56:34', 1094, '0.00', 1, 'INV-000047', 0, '2015-09-04 09:56:34', '0000-00-00 00:00:00', NULL, 'yes'),
(58, '2015-09-04 10:00:34', 1089, '0.00', 1, 'INV-000042', 0, '2015-09-04 10:00:34', '0000-00-00 00:00:00', NULL, 'yes'),
(59, '2015-09-04 10:00:57', 1086, '0.00', 1, 'INV-000039', 0, '2015-09-04 10:00:57', '0000-00-00 00:00:00', NULL, 'yes'),
(60, '2015-09-04 10:01:32', 1085, '0.00', 1, 'INV-000038', 0, '2015-09-04 10:01:32', '0000-00-00 00:00:00', NULL, 'yes'),
(61, '2015-09-04 10:02:45', 1084, '0.00', 1, 'INV-000037', 0, '2015-09-04 10:02:45', '0000-00-00 00:00:00', NULL, 'yes'),
(62, '2015-09-04 11:02:13', 1118, '0.00', 1, 'INV-1000010', 0, '2015-09-04 11:02:13', '0000-00-00 00:00:00', NULL, 'yes'),
(63, '2015-09-04 11:02:13', 1119, '1050', 1, 'INV-1000012', 0, '2015-09-04 11:02:13', '0000-00-00 00:00:00', NULL, 'no'),
(64, '2015-09-04 12:53:46', 1115, '0.00', 1, 'INV-1000004', 0, '2015-09-04 12:53:46', '0000-00-00 00:00:00', NULL, 'yes'),
(65, '2015-09-04 12:53:46', 1117, '0.00', 1, 'INV-1000008', 0, '2015-09-04 12:53:46', '0000-00-00 00:00:00', NULL, 'yes'),
(66, '2015-09-04 12:54:18', 1113, '0.00', 1, 'INV-1000000', 0, '2015-09-04 12:54:18', '0000-00-00 00:00:00', NULL, 'yes'),
(67, '2015-09-07 10:36:03', 1082, '0.00', 1, 'INV-000035', 0, '2015-09-07 10:36:03', '0000-00-00 00:00:00', NULL, 'yes'),
(68, '2015-09-07 10:36:03', 1083, '0.00', 1, 'INV-000036', 0, '2015-09-07 10:36:03', '0000-00-00 00:00:00', NULL, 'yes'),
(69, '2015-09-07 10:37:44', 1082, '0.00', 1, 'INV-000035', 0, '2015-09-07 10:37:44', '0000-00-00 00:00:00', NULL, 'yes'),
(70, '2015-09-07 10:37:44', 1083, '0.00', 1, 'INV-000036', 0, '2015-09-07 10:37:44', '0000-00-00 00:00:00', NULL, 'yes'),
(71, '2015-09-07 10:39:10', 1082, '0.00', 1, 'INV-000035', 0, '2015-09-07 10:39:10', '0000-00-00 00:00:00', NULL, 'yes'),
(72, '2015-09-07 10:39:10', 1083, '0.00', 1, 'INV-000036', 0, '2015-09-07 10:39:10', '0000-00-00 00:00:00', NULL, 'yes'),
(73, '2015-09-07 10:45:04', 1082, '0.00', 1, 'INV-000035', 0, '2015-09-07 10:45:04', '0000-00-00 00:00:00', NULL, 'yes'),
(74, '2015-09-07 10:45:04', 1083, '0.00', 1, 'INV-000036', 0, '2015-09-07 10:45:04', '0000-00-00 00:00:00', NULL, 'yes'),
(75, '2015-09-07 10:46:55', 1082, '0.00', 1, 'INV-000035', 0, '2015-09-07 10:46:55', '0000-00-00 00:00:00', NULL, 'yes'),
(76, '2015-09-07 10:46:55', 1083, '0.00', 1, 'INV-000036', 0, '2015-09-07 10:46:55', '0000-00-00 00:00:00', NULL, 'yes'),
(77, '2015-09-07 10:50:11', 1082, '0.00', 1, 'INV-000035', 0, '2015-09-07 10:50:11', '0000-00-00 00:00:00', NULL, 'yes'),
(78, '2015-09-07 10:50:12', 1083, '0.00', 1, 'INV-000036', 0, '2015-09-07 10:50:12', '0000-00-00 00:00:00', NULL, 'yes'),
(79, '2015-09-07 10:51:09', 1082, '0.00', 1, 'INV-000035', 0, '2015-09-07 10:51:09', '0000-00-00 00:00:00', NULL, 'yes'),
(80, '2015-09-07 10:51:09', 1083, '0.00', 1, 'INV-000036', 0, '2015-09-07 10:51:09', '0000-00-00 00:00:00', NULL, 'yes'),
(81, '2015-09-07 10:54:08', 1082, '0.00', 1, 'INV-000035', 0, '2015-09-07 10:54:08', '0000-00-00 00:00:00', NULL, 'yes'),
(82, '2015-09-07 10:54:08', 1083, '0.00', 1, 'INV-000036', 0, '2015-09-07 10:54:08', '0000-00-00 00:00:00', NULL, 'yes'),
(83, '2015-09-07 10:55:08', 1080, '0.00', 1, '4587', 0, '2015-09-07 10:55:08', '0000-00-00 00:00:00', NULL, 'yes'),
(84, '2015-09-07 10:55:09', 1081, '0.00', 1, '4588', 0, '2015-09-07 10:55:09', '0000-00-00 00:00:00', NULL, 'yes'),
(85, '2015-09-07 11:37:20', 1086, '0.00', 1, 'INV-000039', 0, '2015-09-07 11:37:20', '0000-00-00 00:00:00', NULL, 'yes'),
(86, '2015-09-07 11:37:21', 1089, '0.00', 1, 'INV-000042', 0, '2015-09-07 11:37:21', '0000-00-00 00:00:00', NULL, 'yes'),
(87, '2015-09-07 11:44:22', 1086, '0.00', 1, 'INV-000039', 0, '2015-09-07 11:44:22', '0000-00-00 00:00:00', NULL, 'yes'),
(88, '2015-09-07 11:44:22', 1089, '0.00', 1, 'INV-000042', 0, '2015-09-07 11:44:22', '0000-00-00 00:00:00', NULL, 'yes'),
(89, '2015-09-07 11:46:37', 1086, '0.00', 1, 'INV-000039', 0, '2015-09-07 11:46:37', '0000-00-00 00:00:00', NULL, 'yes'),
(90, '2015-09-07 11:46:37', 1089, '0.00', 1, 'INV-000042', 0, '2015-09-07 11:46:37', '0000-00-00 00:00:00', NULL, 'yes'),
(91, '2015-09-07 11:57:18', 1086, '0.00', 1, 'INV-000039', 0, '2015-09-07 11:57:18', '0000-00-00 00:00:00', NULL, 'yes'),
(92, '2015-09-07 11:57:19', 1089, '0.00', 1, 'INV-000042', 0, '2015-09-07 11:57:19', '0000-00-00 00:00:00', NULL, 'yes'),
(93, '2015-09-07 12:03:37', 1086, '0.00', 1, 'INV-000039', 0, '2015-09-07 12:03:37', '0000-00-00 00:00:00', NULL, 'yes'),
(94, '2015-09-07 12:03:38', 1089, '0.00', 1, 'INV-000042', 0, '2015-09-07 12:03:38', '0000-00-00 00:00:00', NULL, 'yes'),
(95, '2015-09-07 12:56:22', 1086, '0.00', 1, 'INV-000039', 0, '2015-09-07 12:56:22', '0000-00-00 00:00:00', NULL, 'yes'),
(96, '2015-09-07 12:56:23', 1089, '0.00', 1, 'INV-000042', 0, '2015-09-07 12:56:23', '0000-00-00 00:00:00', NULL, 'yes'),
(97, '2015-09-08 15:08:59', 1135, '100', 1, 'INV-1000022', 0, '2015-09-08 15:08:59', '0000-00-00 00:00:00', NULL, 'no'),
(100, '2015-09-08 15:26:15', 1136, '1000', 1, 'INV-1000024', 0, '2015-09-08 15:26:15', '0000-00-00 00:00:00', NULL, 'no'),
(101, '2015-09-08 15:37:33', 1136, '3000', 1, 'INV-1000024', 0, '2015-09-08 15:37:33', '0000-00-00 00:00:00', NULL, 'no'),
(102, '2015-09-08 15:38:24', 1136, '7000', 1, 'INV-1000024', 0, '2015-09-08 15:38:24', '0000-00-00 00:00:00', NULL, 'no'),
(103, '2015-09-08 15:42:22', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-08 15:42:22', '0000-00-00 00:00:00', NULL, 'yes'),
(104, '2015-09-10 11:16:43', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 11:16:43', '0000-00-00 00:00:00', NULL, 'yes'),
(105, '2015-09-10 11:20:15', 1136, '0.00', 1, 'INV-1000024', 0, '2015-09-10 11:20:15', '0000-00-00 00:00:00', NULL, 'no'),
(106, '2015-09-10 11:20:49', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 11:20:49', '0000-00-00 00:00:00', NULL, 'no'),
(107, '2015-09-10 11:21:04', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 11:21:04', '0000-00-00 00:00:00', NULL, 'yes'),
(108, '2015-09-10 11:21:54', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 11:21:54', '0000-00-00 00:00:00', NULL, 'yes'),
(109, '2015-09-10 11:56:44', 1136, '0.00', 1, 'INV-1000024', 0, '2015-09-10 11:56:44', '0000-00-00 00:00:00', NULL, 'no'),
(110, '2015-09-10 11:56:44', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 11:56:44', '0000-00-00 00:00:00', NULL, 'no'),
(111, '2015-09-10 11:57:06', 1136, '1.00', 1, 'INV-1000024', 0, '2015-09-10 11:57:06', '0000-00-00 00:00:00', NULL, 'no'),
(112, '2015-09-10 11:57:06', 1137, '1.00', 1, 'INV-1000026', 0, '2015-09-10 11:57:06', '0000-00-00 00:00:00', NULL, 'no'),
(113, '2015-09-10 12:00:38', 1136, '0.00', 1, 'INV-1000024', 0, '2015-09-10 12:00:38', '0000-00-00 00:00:00', NULL, 'no'),
(114, '2015-09-10 12:00:38', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 12:00:38', '0000-00-00 00:00:00', NULL, 'no'),
(115, '2015-09-10 12:01:44', 1136, '1.00', 1, 'INV-1000024', 0, '2015-09-10 12:01:44', '0000-00-00 00:00:00', NULL, 'no'),
(116, '2015-09-10 12:01:44', 1137, '1.00', 1, 'INV-1000026', 0, '2015-09-10 12:01:44', '0000-00-00 00:00:00', NULL, 'no'),
(117, '2015-09-10 12:35:45', 1136, '1.00', 1, 'INV-1000024', 0, '2015-09-10 12:35:45', '0000-00-00 00:00:00', NULL, 'no'),
(118, '2015-09-10 12:35:47', 1137, '1.00', 1, 'INV-1000026', 0, '2015-09-10 12:35:47', '0000-00-00 00:00:00', NULL, 'no'),
(119, '2015-09-10 12:35:57', 1135, '0.00', 1, 'INV-1000022', 0, '2015-09-10 12:35:57', '0000-00-00 00:00:00', NULL, 'yes'),
(120, '2015-09-10 12:35:57', 1136, '0.00', 1, 'INV-1000024', 0, '2015-09-10 12:35:57', '0000-00-00 00:00:00', NULL, 'yes'),
(121, '2015-09-10 12:35:58', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 12:35:58', '0000-00-00 00:00:00', NULL, 'yes'),
(122, '2015-09-10 12:38:02', 1135, '0.00', 1, 'INV-1000022', 0, '2015-09-10 12:38:02', '0000-00-00 00:00:00', NULL, 'yes'),
(123, '2015-09-10 12:38:02', 1136, '0.00', 1, 'INV-1000024', 0, '2015-09-10 12:38:02', '0000-00-00 00:00:00', NULL, 'yes'),
(124, '2015-09-10 12:38:03', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 12:38:03', '0000-00-00 00:00:00', NULL, 'yes'),
(125, '2015-09-10 12:48:11', 1136, '0.00', 1, 'INV-1000024', 0, '2015-09-10 12:48:11', '0000-00-00 00:00:00', NULL, 'yes'),
(126, '2015-09-10 12:48:12', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 12:48:12', '0000-00-00 00:00:00', NULL, 'yes'),
(127, '2015-09-10 12:53:00', 1136, '0.00', 1, 'INV-1000024', 0, '2015-09-10 12:53:00', '0000-00-00 00:00:00', NULL, 'yes'),
(128, '2015-09-10 12:53:00', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 12:53:00', '0000-00-00 00:00:00', NULL, 'yes'),
(129, '2015-09-10 12:57:24', 1136, '0.00', 1, 'INV-1000024', 0, '2015-09-10 12:57:24', '0000-00-00 00:00:00', NULL, 'yes'),
(130, '2015-09-10 12:57:25', 1137, '0.00', 1, 'INV-1000026', 0, '2015-09-10 12:57:25', '0000-00-00 00:00:00', NULL, 'yes'),
(131, '2015-09-10 14:41:24', 1139, '300000', 1, 'INV-1000027', 0, '2015-09-10 14:41:24', '0000-00-00 00:00:00', NULL, 'yes'),
(132, '2015-09-10 14:43:36', 1139, '190994', 1, 'INV-1000027', 0, '2015-09-10 14:43:36', '0000-00-00 00:00:00', NULL, 'no'),
(133, '2015-09-10 14:45:14', 1143, '0.00', 1, 'INV-1000034', 0, '2015-09-10 14:45:14', '0000-00-00 00:00:00', NULL, 'yes'),
(134, '2015-09-10 14:46:03', 1144, '1000', 1, 'INV-1000033', 0, '2015-09-10 14:46:03', '0000-00-00 00:00:00', NULL, 'yes'),
(135, '2015-09-10 14:49:20', 1134, '3000', 1, '1063', 0, '2015-09-10 14:49:20', '0000-00-00 00:00:00', NULL, 'no'),
(136, '2015-09-10 14:49:51', 1131, '3000', 1, '1062', 0, '2015-09-10 14:49:51', '0000-00-00 00:00:00', NULL, 'yes'),
(137, '2015-09-10 14:51:04', 1130, '3000', 1, '1061', 0, '2015-09-10 14:51:04', '0000-00-00 00:00:00', NULL, 'yes'),
(138, '2015-09-10 14:51:24', 1129, '2000', 1, '1060', 0, '2015-09-10 14:51:24', '0000-00-00 00:00:00', NULL, 'no'),
(139, '2015-09-10 15:12:02', 1142, '100', 1, 'INV-1000032', 0, '2015-09-10 15:12:02', '0000-00-00 00:00:00', NULL, 'yes'),
(140, '2015-09-10 15:13:06', 1141, '100', 1, 'INV-1000031', 0, '2015-09-10 15:13:06', '0000-00-00 00:00:00', NULL, 'no'),
(141, '2015-09-10 15:19:19', 1141, '0.00', 1, 'INV-1000031', 0, '2015-09-10 15:19:19', '0000-00-00 00:00:00', NULL, 'yes'),
(142, '2015-09-10 15:19:19', 1142, '500', 1, 'INV-1000032', 0, '2015-09-10 15:19:19', '0000-00-00 00:00:00', NULL, 'no'),
(143, '2015-09-10 15:20:21', 1140, '490994', 1, 'INV-1000029', 0, '2015-09-10 15:20:21', '0000-00-00 00:00:00', NULL, 'no'),
(144, '2015-09-10 15:20:21', 1141, '0.00', 1, 'INV-1000031', 0, '2015-09-10 15:20:21', '0000-00-00 00:00:00', NULL, 'yes'),
(145, '2015-09-10 15:21:01', 1141, '0.00', 1, 'INV-1000031', 0, '2015-09-10 15:21:01', '0000-00-00 00:00:00', NULL, 'yes'),
(146, '2015-09-10 15:21:23', 1141, '0.00', 1, 'INV-1000031', 0, '2015-09-10 15:21:23', '0000-00-00 00:00:00', NULL, 'yes'),
(147, '2015-09-10 15:21:41', 1141, '0.00', 1, 'INV-1000031', 0, '2015-09-10 15:21:41', '0000-00-00 00:00:00', NULL, 'yes'),
(148, '2015-09-10 15:23:27', 1141, '1100', 1, 'INV-1000031', 0, '2015-09-10 15:23:27', '0000-00-00 00:00:00', NULL, 'no'),
(150, '2015-09-11 12:48:16', 1144, '0.00', 1, 'INV-1000033', 0, '2015-09-11 12:48:16', '0000-00-00 00:00:00', NULL, 'no'),
(152, '2015-09-11 13:08:30', 1154, '100', 1, 'INV-1000036', 0, '2015-09-11 13:08:30', '0000-00-00 00:00:00', NULL, 'yes'),
(153, '2015-09-11 13:09:47', 1155, '100', 1, 'INV-1000037', 0, '2015-09-11 13:09:47', '0000-00-00 00:00:00', NULL, 'no'),
(154, '2015-09-11 13:10:17', 1155, '0.00', 1, 'INV-1000037', 0, '2015-09-11 13:10:17', '0000-00-00 00:00:00', NULL, 'yes'),
(155, '2015-09-11 13:22:18', 1156, '200', 1, 'INV-1000038', 0, '2015-09-11 13:22:18', '0000-00-00 00:00:00', NULL, 'no'),
(157, '2015-09-11 14:18:55', 1156, '0.00', 1, 'INV-1000033', 0, '2015-09-11 14:18:55', '0000-00-00 00:00:00', NULL, 'yes'),
(158, '2015-09-11 14:20:31', 1156, '0.00', 1, 'INV-1000033', 0, '2015-09-11 14:20:31', '0000-00-00 00:00:00', NULL, 'yes'),
(160, '2015-09-11 14:24:14', 1156, '0.00', 1, 'INV-1000038', 0, '2015-09-11 14:24:14', '0000-00-00 00:00:00', NULL, 'yes'),
(161, '2015-09-11 14:27:51', 1156, '1497.438', 1, 'INV-1000038', 0, '2015-09-11 14:27:51', '0000-00-00 00:00:00', NULL, 'no'),
(162, '2015-09-11 14:30:40', 1157, '3000', 1, 'INV-1000039', 0, '2015-09-11 14:30:40', '0000-00-00 00:00:00', NULL, 'yes'),
(163, '2015-09-11 14:53:15', 1157, '0.00', 1, 'INV-1000039', 0, '2015-09-11 14:53:15', '0000-00-00 00:00:00', NULL, 'yes'),
(164, '2015-09-11 14:53:37', 1157, '0.00', 1, 'INV-1000039', 0, '2015-09-11 14:53:37', '0000-00-00 00:00:00', NULL, 'yes'),
(165, '2015-09-11 14:58:24', 1156, '0.00', 1, 'INV-1000033', 0, '2015-09-11 14:58:24', '0000-00-00 00:00:00', NULL, 'yes'),
(167, '2015-09-11 15:10:05', 1156, 'ab', 1, 'INV-1000033', 0, '2015-09-11 15:10:05', '0000-00-00 00:00:00', NULL, 'yes'),
(168, '2015-09-15 13:16:48', 1123, '100.00', 1, 'INV-1000020', 0, '2015-09-15 13:16:48', '0000-00-00 00:00:00', NULL, 'yes'),
(169, '2015-09-15 13:17:20', 1122, '100', 1, 'INV-1000018', 0, '2015-09-15 13:17:20', '0000-00-00 00:00:00', NULL, 'yes'),
(170, '2015-09-15 13:19:28', 1158, '368', 1, 'INV-1000040', 0, '2015-09-15 13:19:28', '0000-00-00 00:00:00', NULL, 'yes'),
(171, '2015-09-15 13:20:36', 1159, '100.00', 1, 'INV-1000041', 0, '2015-09-15 13:20:36', '0000-00-00 00:00:00', NULL, 'no'),
(172, '2015-09-15 13:53:05', 1160, '100', 1, 'INV-1000042', 0, '2015-09-15 13:53:05', '0000-00-00 00:00:00', NULL, 'no'),
(173, '2015-09-15 15:46:49', 1160, '268.00', 1, 'INV-1000042', 0, '2015-09-15 15:46:49', '0000-00-00 00:00:00', NULL, 'no'),
(174, '2015-09-15 15:57:04', 1160, '100', 1, 'INV-1000042', 0, '2015-09-15 15:57:04', '0000-00-00 00:00:00', NULL, 'no'),
(175, '2015-09-15 16:08:05', 1160, '100', 1, 'INV-1000042', 0, '2015-09-15 16:08:05', '0000-00-00 00:00:00', NULL, 'no'),
(176, '2015-09-15 16:08:22', 1160, '800', 1, 'INV-1000042', 0, '2015-09-15 16:08:22', '0000-00-00 00:00:00', NULL, 'yes'),
(177, '2015-09-16 07:55:44', 1156, '68', 1, 'INV-1000038', 0, '2015-09-16 07:55:44', '0000-00-00 00:00:00', NULL, 'yes'),
(178, '2015-09-16 07:56:41', 1159, '100', 1, 'INV-1000041', 0, '2015-09-16 07:56:41', '0000-00-00 00:00:00', NULL, 'yes'),
(179, '2015-09-16 07:57:52', 1157, '126', 1, 'INV-1000039', 0, '2015-09-16 07:57:52', '0000-00-00 00:00:00', NULL, 'yes'),
(180, '2015-09-16 08:00:20', 1161, '100', 1, 'INV-1000043', 0, '2015-09-16 08:00:20', '0000-00-00 00:00:00', NULL, 'yes'),
(181, '2015-09-16 08:00:51', 1161, '1539', 1, 'INV-1000043', 0, '2015-09-16 08:00:51', '0000-00-00 00:00:00', NULL, 'no'),
(182, '2015-09-16 08:02:14', 1162, '100', 1, 'INV-1000044', 0, '2015-09-16 08:02:14', '0000-00-00 00:00:00', NULL, 'yes'),
(183, '2015-09-16 08:04:45', 1162, '500', 1, 'INV-1000044', 0, '2015-09-16 08:04:45', '0000-00-00 00:00:00', NULL, 'no'),
(184, '2015-09-16 10:46:42', 1157, '42', 1, 'INV-1000039', 0, '2015-09-16 10:46:42', '0000-00-00 00:00:00', NULL, 'no'),
(185, '2015-09-16 10:57:26', 1163, '200', 1, 'INV-1000045', 0, '2015-09-16 10:57:26', '0000-00-00 00:00:00', NULL, 'no'),
(186, '2015-09-16 11:01:23', 1163, '500', 1, 'INV-1000045', 0, '2015-09-16 11:01:23', '0000-00-00 00:00:00', NULL, 'no'),
(187, '2015-09-16 11:23:45', 1164, '1200', 1, 'INV-1000046', 0, '2015-09-16 11:23:45', '0000-00-00 00:00:00', NULL, 'no'),
(188, '2015-09-16 11:31:23', 1165, '100', 1, 'INV-1000047', 0, '2015-09-16 11:31:23', '0000-00-00 00:00:00', NULL, 'no'),
(189, '2015-09-16 12:22:22', 1167, '100', 1, 'INV-1000049', 0, '2015-09-16 12:22:22', '0000-00-00 00:00:00', NULL, 'no'),
(190, '2015-09-16 12:25:10', 1167, '1100', 1, 'INV-1000049', 0, '2015-09-16 12:25:10', '0000-00-00 00:00:00', NULL, 'no'),
(191, '2015-09-16 13:53:17', 1166, '100', 1, 'INV-1000048', 0, '2015-09-16 13:53:17', '0000-00-00 00:00:00', NULL, 'no'),
(192, '2015-09-16 13:53:49', 1165, '100', 1, 'INV-1000047', 0, '2015-09-16 13:53:49', '0000-00-00 00:00:00', NULL, 'no'),
(193, '2015-09-25 08:59:53', 1177, '368', 1, 'INV-1000058', 0, '2015-09-25 08:59:53', '0000-00-00 00:00:00', NULL, 'no'),
(194, '2015-10-06 14:33:32', 1181, '10000', 1, 'INV-1000062', 0, '2015-10-06 14:33:32', '0000-00-00 00:00:00', NULL, 'no'),
(195, '2015-10-07 12:02:31', 1188, '100', 1, 'INV-1000069', 0, '2015-10-07 12:02:31', '0000-00-00 00:00:00', NULL, 'no'),
(196, '2015-10-07 12:04:06', 1188, '100', 1, 'INV-1000069', 0, '2015-10-07 12:04:06', '0000-00-00 00:00:00', NULL, 'no'),
(197, '2015-10-07 12:04:51', 1188, '50', 1, 'INV-1000069', 0, '2015-10-07 12:04:51', '0000-00-00 00:00:00', NULL, 'no'),
(198, '2015-10-07 12:17:46', 1188, '200', 1, 'INV-1000069', 0, '2015-10-07 12:17:46', '0000-00-00 00:00:00', NULL, 'no'),
(199, '2015-10-07 12:18:28', 1187, '500', 1, 'INV-1000068', 0, '2015-10-07 12:18:28', '0000-00-00 00:00:00', NULL, 'no'),
(200, '2015-10-07 12:19:35', 1186, '0.00', 1, 'INV-1000067', 0, '2015-10-07 12:19:35', '0000-00-00 00:00:00', NULL, 'yes'),
(201, '2015-10-07 12:21:09', 1186, '600', 1, 'INV-1000067', 0, '2015-10-07 12:21:09', '0000-00-00 00:00:00', NULL, 'no'),
(202, '2015-10-07 12:24:40', 1185, '0.00', 1, 'INV-1000066', 0, '2015-10-07 12:24:40', '0000-00-00 00:00:00', NULL, 'yes'),
(203, '2015-10-16 14:01:39', 1192, '11000', 1, 'INV-1000072', 0, '2015-10-16 14:01:39', '0000-00-00 00:00:00', NULL, 'no'),
(204, '2015-10-16 14:02:16', 1192, '10033', 1, 'INV-1000072', 0, '2015-10-16 14:02:16', '0000-00-00 00:00:00', NULL, 'no'),
(205, '2015-10-16 14:04:12', 1193, '25000', 1, 'INV-1000073', 0, '2015-10-16 14:04:12', '0000-00-00 00:00:00', NULL, 'no'),
(206, '2015-10-16 14:07:04', 1194, '0.00', 1, 'INV-1000074', 0, '2015-10-16 14:07:04', '0000-00-00 00:00:00', NULL, 'yes'),
(207, '2015-10-16 14:20:11', 1195, '33', 1, 'INV-1000075', 0, '2015-10-16 14:20:11', '0000-00-00 00:00:00', NULL, 'yes'),
(208, '2015-10-16 14:28:04', 1185, '1', 1, 'INV-1000066', 0, '2015-10-16 14:28:04', '0000-00-00 00:00:00', NULL, 'no'),
(209, '2015-10-16 14:33:47', 1196, '1710', 1, 'INV-1000076', 0, '2015-10-16 14:33:47', '0000-00-00 00:00:00', NULL, 'no'),
(210, '2015-10-16 14:53:04', 1189, '12800', 1, 'INV-1000070', 0, '2015-10-16 14:53:04', '0000-00-00 00:00:00', NULL, 'no'),
(211, '2015-10-16 14:53:05', 1190, '1', 1, 'INV-1000071', 0, '2015-10-16 14:53:05', '0000-00-00 00:00:00', NULL, 'no'),
(212, '2015-10-16 14:56:27', 1182, '800', 1, 'INV-1000063', 0, '2015-10-16 14:56:27', '0000-00-00 00:00:00', NULL, 'yes'),
(213, '2015-10-16 14:56:27', 1183, '0.00', 1, 'INV-1000064', 0, '2015-10-16 14:56:27', '0000-00-00 00:00:00', NULL, 'yes'),
(214, '2015-10-16 14:57:34', 1183, '250', 1, 'INV-1000064', 0, '2015-10-16 14:57:34', '0000-00-00 00:00:00', NULL, 'no'),
(215, '2015-10-19 13:15:03', 1206, '1000', 1, 'INV-1000085', 0, '2015-10-19 13:15:03', '0000-00-00 00:00:00', NULL, 'no'),
(216, '2015-11-18 07:45:25', 1214, '400', 1, 'INV-1000090', 0, '2015-11-18 07:45:25', '0000-00-00 00:00:00', NULL, 'no'),
(217, '2015-12-11 12:50:44', 1228, '800', 1, 'INV-1000103', 0, '2015-12-11 12:50:44', '0000-00-00 00:00:00', NULL, 'no'),
(218, '2015-12-11 12:54:12', 1227, '10.00', 1, 'INV-1000102', 0, '2015-12-11 12:54:12', '0000-00-00 00:00:00', NULL, 'no'),
(219, '2015-12-11 13:35:08', 1225, '10000.00', 1, 'INV-1000100', 0, '2015-12-11 13:35:08', '0000-00-00 00:00:00', NULL, 'no'),
(220, '2015-12-11 13:58:20', 1231, '0.00', 1, 'INV-1000106', 0, '2015-12-11 13:58:20', '0000-00-00 00:00:00', NULL, 'yes'),
(221, '2015-12-11 15:00:30', 1221, '100.00', 1, 'INV-1000097', 0, '2015-12-11 15:00:30', '0000-00-00 00:00:00', NULL, 'no'),
(222, '2015-12-11 15:01:48', 1221, '0.00', 1, 'INV-1000097', 0, '2015-12-11 15:01:48', '0000-00-00 00:00:00', NULL, 'yes'),
(223, '2015-12-11 15:03:05', 1227, '1000.00', 1, 'INV-1000102', 0, '2015-12-11 15:03:05', '0000-00-00 00:00:00', NULL, 'no'),
(224, '2015-12-11 15:03:28', 1221, '0.00', 1, 'INV-1000097', 0, '2015-12-11 15:03:28', '0000-00-00 00:00:00', NULL, 'yes'),
(225, '2015-12-11 15:05:02', 1221, '100.00', 1, 'INV-1000097', 0, '2015-12-11 15:05:02', '0000-00-00 00:00:00', NULL, 'no'),
(226, '2015-12-11 15:05:29', 1226, '200.00', 1, 'INV-1000101', 0, '2015-12-11 15:05:29', '0000-00-00 00:00:00', NULL, 'no'),
(227, '2015-12-11 15:05:47', 1221, '200.00', 1, 'INV-1000097', 0, '2015-12-11 15:05:47', '0000-00-00 00:00:00', NULL, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `boost_invoice_payment_methods`
--

CREATE TABLE `boost_invoice_payment_methods` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_invoice_payment_methods`
--

INSERT INTO `boost_invoice_payment_methods` (`id`, `date_created`, `payment_method`) VALUES
(1, '2015-08-14 09:43:36', 'Bank Transfer'),
(2, '2015-08-14 09:43:40', 'Cash'),
(3, '2015-08-14 09:43:49', 'Cheque');

-- --------------------------------------------------------

--
-- Table structure for table `boost_items`
--

CREATE TABLE `boost_items` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `item_name` varchar(20) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `rate` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_items`
--

INSERT INTO `boost_items` (`id`, `date_created`, `item_name`, `description`, `tax`, `quantity`, `rate`) VALUES
(1, '2015-07-01 14:41:18', 'Bed', 'Queen size', NULL, NULL, '12000'),
(2, '2015-07-01 14:41:22', 'Table', 'Accomodates 4 seats', NULL, NULL, '1200'),
(3, '2015-07-01 14:41:24', 'Coffee Table', 'Wooden', NULL, NULL, '800'),
(4, '2015-07-01 14:43:21', 'Chair', 'With wheels', NULL, NULL, '450'),
(5, '2015-07-01 14:44:07', 'Ladder', '15 Meters', NULL, NULL, '600'),
(6, '2015-07-01 14:45:27', 'Hard Hat', 'Man size', NULL, NULL, '280'),
(7, '2015-07-01 14:49:19', 'LCD Monitor', '19\"', NULL, NULL, '1500'),
(8, '2015-09-01 08:46:20', 'Samsung TV', '52\" HD TV', NULL, NULL, '10000'),
(9, '2015-12-03 09:48:42', 'test name', 'test desc', NULL, NULL, '100.00'),
(10, '2015-12-03 10:53:00', 'test2', 'test2', NULL, NULL, '100'),
(11, '2015-12-03 10:53:31', 't2', 't2', NULL, NULL, '0.00'),
(12, '2015-12-03 10:55:23', 't2', 't2', NULL, NULL, '0.00'),
(13, '2015-12-03 10:58:42', 't3', 't3', NULL, NULL, '0.00'),
(14, '2015-12-03 11:03:15', 't4', 't4', NULL, NULL, '0.00'),
(15, '2015-12-03 11:04:30', 't5', 't5', NULL, NULL, '0.00'),
(16, '2015-12-03 11:10:26', 't6', 't6', NULL, NULL, '0.00'),
(17, '2015-12-03 11:11:32', 't7', 't7', NULL, NULL, '0.00'),
(18, '2015-12-03 11:14:40', 't8', 't8', NULL, NULL, '0.00'),
(19, '2015-12-03 11:19:20', 't9', 't9', NULL, NULL, '0.00'),
(20, '2015-12-03 11:24:23', 't10', 't10', NULL, NULL, '0.00'),
(21, '2015-12-03 11:27:47', 't11', 't11', NULL, NULL, '0.00'),
(22, '2015-12-03 11:30:40', 'test Item', 'Item description', NULL, NULL, '100'),
(23, '2015-12-03 11:32:37', 't12', 't12', NULL, NULL, '200'),
(24, '2015-12-03 11:34:18', 't14', 't14', NULL, NULL, '0.00'),
(25, '2015-12-03 11:50:01', 't14', '', NULL, NULL, '0.00'),
(26, '2015-12-03 11:50:38', 't16', '', NULL, NULL, '0.00'),
(27, '2015-12-03 11:52:11', 't16', '', NULL, NULL, '0.00'),
(28, '2015-12-03 11:52:54', 't17', '', NULL, NULL, '0.00'),
(29, '2015-12-03 11:53:48', 't18', '', NULL, NULL, '0.00'),
(30, '2015-12-03 11:54:32', 't18', '', NULL, NULL, '0.00'),
(31, '2015-12-03 11:55:18', 't19', '', NULL, NULL, '0.00'),
(32, '2015-12-03 12:00:24', 't19', '', NULL, NULL, '0.00'),
(33, '2015-12-03 12:01:00', 't20', '', NULL, NULL, '0.00'),
(34, '2015-12-03 14:00:46', 'test 3', 'test 3 desc', NULL, NULL, '300'),
(35, '2015-12-03 14:03:53', 'dessign', 'pretty drawings', NULL, NULL, '100'),
(36, '2015-12-11 14:51:07', 'test item ddd', 'description', NULL, NULL, '100.00');

-- --------------------------------------------------------

--
-- Table structure for table `boost_logos`
--

CREATE TABLE `boost_logos` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `image_string` mediumtext,
  `logo_name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_logos`
--

INSERT INTO `boost_logos` (`id`, `date_created`, `date_modified`, `image_string`, `logo_name`) VALUES
(1, '2015-08-13 09:27:03', '2015-10-30 13:02:25', 'http://192.168.0.151/boost/api/assets/images/so_interactive.png', 'Boost');

-- --------------------------------------------------------

--
-- Table structure for table `boost_organisations`
--

CREATE TABLE `boost_organisations` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `company_name` varchar(50) NOT NULL,
  `vat_number` varchar(35) DEFAULT NULL,
  `industry_id` int(11) DEFAULT NULL,
  `address_line_1` varchar(150) DEFAULT NULL,
  `address_line_2` varchar(150) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `region_state` varbinary(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `time_zone_id` int(11) DEFAULT NULL,
  `account_url` varchar(100) DEFAULT NULL,
  `day_light_savings` varchar(5) DEFAULT 'no',
  `account_id` int(11) DEFAULT NULL,
  `account_db` varchar(150) DEFAULT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `subdomain` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_organisations`
--

INSERT INTO `boost_organisations` (`id`, `date_created`, `date_modified`, `company_name`, `vat_number`, `industry_id`, `address_line_1`, `address_line_2`, `country_id`, `region_state`, `city`, `zip`, `email`, `mobile`, `telephone`, `fax`, `currency_id`, `time_zone_id`, `account_url`, `day_light_savings`, `account_id`, `account_db`, `account_name`, `postal_code`, `subdomain`) VALUES
(1, '2016-03-11 09:21:29', '2016-03-11 09:27:37', 'boost', '123456', 38, 'Unit 57 Gleneagles', '13 Uys Avenue', 202, 0x47617574656e67, 'Edenglen', '1619', 'brad@sointeractive.co.za', '+27 82 694 4428', '+27 11 609 1986', '', 1, NULL, 'boost', 'no', NULL, 'boost_acc1', 'boost', NULL, NULL),
(36, '2016-04-01 13:33:41', '2016-04-01 13:33:42', 'SnapBill (Pty) Ltd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'jaco@snapbill.com', NULL, NULL, NULL, NULL, NULL, 'snapbillptyltd', 'no', NULL, 'boost_acc36', 'snapbillptyltd', NULL, NULL),
(39, '2016-06-28 12:23:51', '2016-06-28 12:26:36', 'ABC trading', '4540002580', 27, '38 Vardy street', 'la lucia', 202, 0x4b77617a756c75206e6174616c, 'Durban', '4051', 'pierre@origingroup.co.za', '', '', '', 1, NULL, 'abctrading', 'no', NULL, 'boost_acc39', 'abctrading', NULL, NULL),
(49, '2016-07-08 10:52:23', '2016-07-08 10:52:52', 'Brad Test', '', NULL, '', '', NULL, '', '', '', 'bradley.greenwood@gmail.com', '', '', '', 1, NULL, 'bradtest', 'no', NULL, 'boost_acc49', 'bradtest', NULL, NULL),
(50, '2016-09-08 02:03:35', '2016-09-08 02:04:14', 'Dan', '', NULL, '', '', NULL, '', '', '', 'hello@danrowden.com', '', '', '', 2, NULL, 'dan', 'no', NULL, 'boost_acc50', 'dan', NULL, NULL),
(52, '2017-01-25 08:09:59', '2017-03-23 07:33:22', 'So Interactive', '', NULL, 'Address test', '', NULL, '', '', '', 'cornelias@sointeractive.co.za', '', '', '', 0, NULL, 'sointeractive1', 'no', NULL, 'boost_acc52', 'sointeractive1', NULL, NULL),
(54, '2017-06-01 19:16:29', '2017-06-01 19:16:29', 'So Interactive Web Designs CC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'careers@sointeractive.co.za', NULL, NULL, NULL, NULL, NULL, 'sointeractivewebdesignscc', 'no', NULL, 'acc54', 'sointeractivewebdesignscc', NULL, NULL),
(73, '2017-06-13 22:30:08', '2017-06-13 22:35:12', 'So Interactive Web Designs CC', '356749033', 3, 'Bentley Office Park', '67 Wessels Road', 202, 0x4761756e74656e67, 'Rivonia', '2128', 'darren@sointeractive.co.za', '0823303460', '011 807 6828', '', 1, NULL, 'sointeractivewebdesignscc1', 'no', NULL, 'boost_acc73', 'sointeractivewebdesignscc1', NULL, NULL),
(74, '2017-06-14 10:31:21', '2017-06-14 10:31:21', 'cornelias', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dev@sointeractive.co.za', NULL, NULL, NULL, NULL, NULL, 'cornelias', 'no', NULL, 'boost_acc74', 'cornelias', NULL, NULL),
(75, '2018-04-05 09:04:07', '2018-04-05 09:04:07', 'PYDS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lebo@plainyoghurtds.co.za', NULL, NULL, NULL, NULL, NULL, 'pyds', 'no', NULL, 'boost_acc75', 'pyds', NULL, NULL),
(76, '2018-08-17 12:02:57', '2018-08-17 12:02:57', 'soStudio', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'hello@sointeractive.co.za', NULL, NULL, NULL, NULL, NULL, 'sostudio', 'no', NULL, 'boost_acc76', 'sostudio', NULL, NULL),
(77, '2018-08-17 12:09:01', '2018-08-17 12:09:01', 'soStudio', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'darrenmansour@icloud.com', NULL, NULL, NULL, NULL, NULL, 'sostudio1', 'no', NULL, 'boost_acc77', 'sostudio1', NULL, NULL),
(78, '2019-07-12 21:50:33', '2019-07-12 21:50:33', 'Verticle', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sointeractivedigitalagency@gmail.com', NULL, NULL, NULL, NULL, NULL, 'verticle', 'no', NULL, 'boost_acc78', 'verticle', NULL, NULL),
(79, '2024-07-19 14:08:49', '2024-07-19 14:08:49', 'adZVNMrKpYkvfEb', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ryan30garciafla@outlook.com', NULL, NULL, NULL, NULL, NULL, 'adzvnmrkpykvfeb', 'no', NULL, 'boost_acc79', 'adzvnmrkpykvfeb', NULL, NULL),
(80, '2024-08-10 21:04:12', '2024-08-10 21:04:12', 'YriSdspLK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dagreat.john3134@yahoo.com', NULL, NULL, NULL, NULL, NULL, 'yrisdsplk', 'no', NULL, 'boost_acc80', 'yrisdsplk', NULL, NULL),
(81, '2024-08-17 04:23:56', '2024-08-17 04:23:56', 'aUbwqNkHQspvZDOR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ollistersaunders@gmail.com', NULL, NULL, NULL, NULL, NULL, 'aubwqnkhqspvzdor', 'no', NULL, 'boost_acc81', 'aubwqnkhqspvzdor', NULL, NULL),
(82, '2024-08-22 10:40:37', '2024-08-22 10:40:37', 'mKUhVJyrLgFnEPzi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'charles_hastingsk4bh@outlook.com', NULL, NULL, NULL, NULL, NULL, 'mkuhvjyrlgfnepzi', 'no', NULL, 'boost_acc82', 'mkuhvjyrlgfnepzi', NULL, NULL),
(83, '2024-09-06 06:16:06', '2024-09-06 06:16:06', 'MawNpRsDiIXfObhV', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'richard_wickg40y@outlook.com', NULL, NULL, NULL, NULL, NULL, 'mawnprsdiixfobhv', 'no', NULL, 'boost_acc83', 'mawnprsdiixfobhv', NULL, NULL),
(84, '2024-09-17 13:22:45', '2024-09-17 13:22:45', 'kYfOHcovUv', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dzhahirul_zamri@yahoo.com', NULL, NULL, NULL, NULL, NULL, 'kyfohcovuv', 'no', NULL, 'boost_acc84', 'kyfohcovuv', NULL, NULL),
(85, '2024-09-22 18:38:07', '2024-09-22 18:38:07', 'hcyHqjuvfh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'chinzdd02@yahoo.com', NULL, NULL, NULL, NULL, NULL, 'hcyhqjuvfh', 'no', NULL, 'boost_acc85', 'hcyhqjuvfh', NULL, NULL),
(86, '2024-10-06 08:53:00', '2024-10-06 08:53:00', 'GftqvoNQ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'bleinavery1999@gmail.com', NULL, NULL, NULL, NULL, NULL, 'gftqvonq', 'no', NULL, 'boost_acc86', 'gftqvonq', NULL, NULL),
(87, '2024-10-12 11:06:21', '2024-10-12 11:06:21', 'siIvbzvMZBL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'maciamahalagg5346@gmail.com', NULL, NULL, NULL, NULL, NULL, 'siivbzvmzbl', 'no', NULL, 'boost_acc87', 'siivbzvmzbl', NULL, NULL),
(88, '2024-10-18 07:07:43', '2024-10-18 07:07:43', 'ShpjeuIMQniiL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vboltonzk@gmail.com', NULL, NULL, NULL, NULL, NULL, 'shpjeuimqniil', 'no', NULL, 'boost_acc88', 'shpjeuimqniil', NULL, NULL),
(89, '2024-10-21 16:03:45', '2024-10-21 16:03:46', 'jOwqqWDbollJL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dtaylorri6106@gmail.com', NULL, NULL, NULL, NULL, NULL, 'jowqqwdbolljl', 'no', NULL, 'boost_acc89', 'jowqqwdbolljl', NULL, NULL),
(90, '2024-10-26 00:27:36', '2024-10-26 00:27:36', 'jdbhIFGgykbL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'avilaogastip37@gmail.com', NULL, NULL, NULL, NULL, NULL, 'jdbhifggykbl', 'no', NULL, 'boost_acc90', 'jdbhifggykbl', NULL, NULL),
(166, '2024-11-12 06:01:46', '2024-11-12 06:01:46', 'Testit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'accounts@sointeractive.co.za', NULL, NULL, NULL, NULL, NULL, 'testit', 'no', NULL, 'boost_acc166', 'testit', NULL, NULL),
(190, '2024-11-14 04:09:00', '2024-11-14 04:09:00', 'PGBABU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'babu313136@gmail.com', NULL, NULL, NULL, NULL, NULL, 'pgbabu', 'no', NULL, 'boost_acc190', 'pgbabu', NULL, NULL),
(191, '2024-11-14 04:39:37', '2024-11-27 12:06:04', 'best', '', 3, 'Test ', 'Test', 2, '', '', '', 'news@boostaccounting.com', '', '', '', 1, NULL, 'best', 'no', NULL, 'boost_acc191', 'best', NULL, NULL),
(192, '2024-11-26 16:30:54', '2024-11-26 16:35:55', 'PG', '8hdsfhdsf', 2, '', '', NULL, '', '', '', 'babu313137@gmail.com', '', '', '', 0, NULL, 'pg1', 'no', NULL, 'boost_acc192', 'pg1', NULL, NULL),
(193, '2025-07-02 15:07:22', '2025-07-02 15:07:22', 'inOYmpnOq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'craneabott77@gmail.com', NULL, NULL, NULL, NULL, NULL, 'inoympnoq', 'no', NULL, 'boost_acc193', 'inoympnoq', NULL, NULL),
(194, '2025-07-04 17:50:23', '2025-07-04 17:50:23', 'kAEtzTnWKcNKAsL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'nsexton@martinadvertising.com', NULL, NULL, NULL, NULL, NULL, 'kaetztnwkcnkasl', 'no', NULL, 'boost_acc194', 'kaetztnwkcnkasl', NULL, NULL),
(195, '2025-07-08 21:34:44', '2025-07-08 21:34:44', 'AJhjRqhSBIoHqXn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ericgrothe61@gmail.com', NULL, NULL, NULL, NULL, NULL, 'ajhjrqhsbiohqxn', 'no', NULL, 'boost_acc195', 'ajhjrqhsbiohqxn', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `boost_role_permissions`
--

CREATE TABLE `boost_role_permissions` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_role_permissions`
--

INSERT INTO `boost_role_permissions` (`id`, `date_created`, `role_id`, `permission_id`) VALUES
(21, '2015-12-11 13:28:28', 1, 1),
(22, '2015-12-11 13:28:28', 1, 2),
(23, '2015-12-11 13:28:28', 1, 3),
(24, '2015-12-11 13:28:28', 1, 4),
(25, '2015-12-11 13:28:28', 1, 5),
(26, '2015-12-11 13:28:28', 1, 6),
(27, '2015-12-11 13:28:28', 1, 7),
(28, '2015-12-11 13:28:28', 1, 8),
(29, '2015-12-11 13:28:28', 1, 9),
(30, '2015-12-11 13:28:28', 2, 1),
(31, '2015-12-11 13:28:28', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `boost_sessions`
--

CREATE TABLE `boost_sessions` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `boost_taxes`
--

CREATE TABLE `boost_taxes` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tax_name` varchar(30) NOT NULL,
  `percentage` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_taxes`
--

INSERT INTO `boost_taxes` (`id`, `date_created`, `tax_name`, `percentage`) VALUES
(1, '2015-09-15 10:43:46', 'VAT (South Africa)', 14),
(7, '2015-10-30 08:06:57', 'US Tax', 20),
(8, '2015-12-03 12:08:07', 'UK Tax', 20),
(10, '2015-12-11 14:51:35', 'tax test', 10);

-- --------------------------------------------------------

--
-- Table structure for table `boost_templates`
--

CREATE TABLE `boost_templates` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `invoice_name` varchar(30) DEFAULT NULL,
  `invoice_terms` varchar(150) DEFAULT NULL,
  `invoice_closing_note` varchar(150) DEFAULT NULL,
  `invoice_number_prefix` varchar(10) DEFAULT NULL,
  `estimate_name` varchar(30) DEFAULT NULL,
  `estimate_terms` varchar(150) DEFAULT NULL,
  `estimate_closing_note` varchar(150) DEFAULT NULL,
  `estimate_number_prefix` varchar(10) DEFAULT NULL,
  `credit_note_name` varchar(30) DEFAULT NULL,
  `credit_note_terms` varchar(150) DEFAULT NULL,
  `credit_note_closing_note` varchar(150) DEFAULT NULL,
  `credit_note_number_prefix` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_templates`
--

INSERT INTO `boost_templates` (`id`, `date_created`, `date_modified`, `invoice_name`, `invoice_terms`, `invoice_closing_note`, `invoice_number_prefix`, `estimate_name`, `estimate_terms`, `estimate_closing_note`, `estimate_number_prefix`, `credit_note_name`, `credit_note_terms`, `credit_note_closing_note`, `credit_note_number_prefix`) VALUES
(1, '2015-10-20 13:44:03', '2015-12-11 13:16:27', 'INVOICE', 'invoice terms', 'Invoice closing note', NULL, 'ESTIMATE', 'estimate terms', 'estimate closing note', NULL, 'CREDIT NOTE', 'credit note terms', 'credit note closing note', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `boost_themes`
--

CREATE TABLE `boost_themes` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `theme_name` varchar(100) DEFAULT NULL,
  `theme_image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_themes`
--

INSERT INTO `boost_themes` (`id`, `date_created`, `date_modified`, `theme_name`, `theme_image`) VALUES
(1, '2015-11-03 14:08:28', '2015-11-03 14:08:28', 'Deafult', 'http://api.boostaccounting.com/assets/themes/default_template_prieview.png'),
(2, '2015-12-04 08:40:02', '2015-12-04 08:40:02', 'Theme Two', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `boost_theme_settings`
--

CREATE TABLE `boost_theme_settings` (
  `id` int(11) NOT NULL,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `theme_id` int(11) DEFAULT NULL,
  `image_string` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_theme_settings`
--

INSERT INTO `boost_theme_settings` (`id`, `date_modified`, `theme_id`, `image_string`) VALUES
(1, '2015-12-03 14:39:16', 1, 'http://api.boostaccounting.com/assets/images/so_interactive.png?720');

-- --------------------------------------------------------

--
-- Table structure for table `boost_timezones`
--

CREATE TABLE `boost_timezones` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timezone` varchar(100) NOT NULL,
  `time` float NOT NULL DEFAULT '0',
  `daylight_saving` int(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_timezones`
--

INSERT INTO `boost_timezones` (`id`, `date_created`, `timezone`, `time`, `daylight_saving`) VALUES
(1, '2015-09-14 09:10:20', '(GMT-12:00) International Date Line West', -12, 0),
(2, '2015-09-14 09:10:20', '(GMT-11:00) Midway Island, Samoa', -11, 0),
(3, '2015-09-14 09:10:20', '(GMT-10:00) Hawaii', -10, 0),
(4, '2015-09-14 09:10:20', '(GMT-09:00) Alaska', -9, 1),
(5, '2015-09-14 09:10:20', '(GMT-08:00) Pacific Time (US & Canada)', -8, 1),
(6, '2015-09-14 09:10:20', '(GMT-08:00) Tijuana, Baja California', -8, 0),
(7, '2015-09-14 09:10:20', '(GMT-07:00) Arizona', -7, 0),
(8, '2015-09-14 09:10:20', '(GMT-07:00) Chihuahua, La Paz, Mazatlan', -7, 1),
(9, '2015-09-14 09:10:20', '(GMT-07:00) Mountain Time (US & Canada)', -7, 1),
(10, '2015-09-14 09:10:20', '(GMT-06:00) Central America', -6, 0),
(11, '2015-09-14 09:10:20', '(GMT-06:00) Central Time (US & Canada)', -6, 1),
(12, '2015-09-14 09:10:20', '(GMT-06:00) Guadalajara, Mexico City, Monterrey', -6, 1),
(13, '2015-09-14 09:10:20', '(GMT-06:00) Saskatchewan', -6, 0),
(14, '2015-09-14 09:10:20', '(GMT-05:00) Bogota, Lima, Quito, Rio Branco', -5, 0),
(15, '2015-09-14 09:10:20', '(GMT-05:00) Eastern Time (US & Canada)', -5, 1),
(16, '2015-09-14 09:10:20', '(GMT-05:00) Indiana (East)', -5, 1),
(17, '2015-09-14 09:10:20', '(GMT-04:00) Atlantic Time (Canada)', -4, 1),
(18, '2015-09-14 09:10:20', '(GMT-04:00) Caracas, La Paz', -4, 0),
(19, '2015-09-14 09:10:20', '(GMT-04:00) Manaus', -4, 0),
(20, '2015-09-14 09:10:20', '(GMT-04:00) Santiago', -4, 1),
(21, '2015-09-14 09:10:20', '(GMT-03:30) Newfoundland', -3.5, 1),
(22, '2015-09-14 09:10:20', '(GMT-03:00) Brasilia', -3, 1),
(23, '2015-09-14 09:10:20', '(GMT-03:00) Buenos Aires, Georgetown', -3, 0),
(24, '2015-09-14 09:10:20', '(GMT-03:00) Greenland', -3, 1),
(25, '2015-09-14 09:10:20', '(GMT-03:00) Montevideo', -3, 1),
(26, '2015-09-14 09:10:20', '(GMT-02:00) Mid-Atlantic', -2, 1),
(27, '2015-09-14 09:10:20', '(GMT-01:00) Cape Verde Is.', -1, 0),
(28, '2015-09-14 09:10:20', '(GMT-01:00) Azores', -1, 1),
(29, '2015-09-14 09:10:20', '(GMT+00:00) Casablanca, Monrovia, Reykjavik', 0, 0),
(30, '2015-09-14 09:10:20', '(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London', 0, 1),
(31, '2015-09-14 09:10:20', '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna', 0, 1),
(32, '2015-09-14 09:10:20', '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague', 1, 1),
(33, '2015-09-14 09:10:20', '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris', 1, 1),
(34, '2015-09-14 09:10:20', '(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb', 1, 1),
(35, '2015-09-14 09:10:20', '(GMT+01:00) West Central Africa', 1, 1),
(36, '2015-09-14 09:10:20', '(GMT+02:00) Amman', 2, 1),
(37, '2015-09-14 09:10:20', '(GMT+02:00) Athens, Bucharest, Istanbul', 2, 1),
(38, '2015-09-14 09:10:20', '(GMT+02:00) Beirut', 2, 1),
(39, '2015-09-14 09:10:20', '(GMT+02:00) Cairo', 2, 1),
(40, '2015-09-14 09:10:20', '(GMT+02:00) Harare, Pretoria', 2, 0),
(41, '2015-09-14 09:10:20', '(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius', 2, 1),
(42, '2015-09-14 09:10:20', '(GMT+02:00) Jerusalem', 2, 1),
(43, '2015-09-14 09:10:20', '(GMT+02:00) Minsk', 2, 1),
(44, '2015-09-14 09:10:20', '(GMT+02:00) Windhoek', 2, 1),
(45, '2015-09-14 09:10:20', '(GMT+03:00) Kuwait, Riyadh, Baghdad', 3, 0),
(46, '2015-09-14 09:10:20', '(GMT+03:00) Moscow, St. Petersburg, Volgograd', 3, 1),
(47, '2015-09-14 09:10:20', '(GMT+03:00) Nairobi', 3, 0),
(48, '2015-09-14 09:10:20', '(GMT+03:00) Tbilisi', 3, 0),
(49, '2015-09-14 09:10:20', '(GMT+03:30) Tehran', 3.5, 1),
(50, '2015-09-14 09:10:20', '(GMT+04:00) Abu Dhabi, Muscat', 4, 0),
(51, '2015-09-14 09:10:20', '(GMT+04:00) Baku', 4, 1),
(52, '2015-09-14 09:10:20', '(GMT+04:00) Yerevan', 4, 1),
(53, '2015-09-14 09:10:20', '(GMT+04:30) Kabul', 4.5, 0),
(54, '2015-09-14 09:10:20', '(GMT+05:00) Yekaterinburg', 5, 1),
(55, '2015-09-14 09:10:20', '(GMT+05:00) Islamabad, Karachi, Tashkent', 5, 0),
(56, '2015-09-14 09:10:20', '(GMT+05:30) Sri Jayawardenapura', 5.5, 0),
(57, '2015-09-14 09:10:20', '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi', 5.5, 0),
(58, '2015-09-14 09:10:20', '(GMT+05:45) Kathmandu', 5.75, 0),
(59, '2015-09-14 09:10:20', '(GMT+06:00) Almaty, Novosibirsk', 6, 1),
(60, '2015-09-14 09:10:20', '(GMT+06:00) Astana, Dhaka', 6, 0),
(61, '2015-09-14 09:10:20', '(GMT+06:30) Yangon (Rangoon)', 6.5, 0),
(62, '2015-09-14 09:10:20', '(GMT+07:00) Bangkok, Hanoi, Jakarta', 7, 0),
(63, '2015-09-14 09:10:20', '(GMT+07:00) Krasnoyarsk', 7, 1),
(64, '2015-09-14 09:10:20', '(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi', 8, 0),
(65, '2015-09-14 09:10:20', '(GMT+08:00) Kuala Lumpur, Singapore', 8, 0),
(66, '2015-09-14 09:10:20', '(GMT+08:00) Irkutsk, Ulaan Bataar', 8, 0),
(67, '2015-09-14 09:10:20', '(GMT+08:00) Perth', 8, 0),
(68, '2015-09-14 09:10:20', '(GMT+08:00) Taipei', 8, 0),
(69, '2015-09-14 09:10:20', '(GMT+09:00) Osaka, Sapporo, Tokyo', 9, 0),
(70, '2015-09-14 09:10:20', '(GMT+09:00) Seoul', 9, 0),
(71, '2015-09-14 09:10:20', '(GMT+09:00) Yakutsk', 9, 1),
(72, '2015-09-14 09:10:20', '(GMT+09:30) Adelaide', 9.5, 0),
(73, '2015-09-14 09:10:20', '(GMT+09:30) Darwin', 9.5, 0),
(74, '2015-09-14 09:10:20', '(GMT+10:00) Brisbane', 10, 0),
(75, '2015-09-14 09:10:20', '(GMT+10:00) Canberra, Melbourne, Sydney', 10, 1),
(76, '2015-09-14 09:10:20', '(GMT+10:00) Hobart', 10, 1),
(77, '2015-09-14 09:10:20', '(GMT+10:00) Guam, Port Moresby', 10, 0),
(78, '2015-09-14 09:10:20', '(GMT+10:00) Vladivostok', 10, 1),
(79, '2015-09-14 09:10:20', '(GMT+11:00) Magadan, Solomon Is., New Caledonia', 11, 1),
(80, '2015-09-14 09:10:20', '(GMT+12:00) Auckland, Wellington', 12, 1),
(81, '2015-09-14 09:10:20', '(GMT+12:00) Fiji, Kamchatka, Marshall Is.', 12, 0),
(82, '2015-09-14 09:10:20', '(GMT+13:00) Nuku\'alofa', 13, 0);

-- --------------------------------------------------------

--
-- Table structure for table `boost_users`
--

CREATE TABLE `boost_users` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `contact_number` varchar(30) DEFAULT NULL,
  `user_role_id` int(3) NOT NULL,
  `password` varchar(33) DEFAULT NULL,
  `failed_attempts` int(2) DEFAULT '0',
  `last_attempt_datetime` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `token` char(35) DEFAULT NULL,
  `token_expire` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_users`
--

INSERT INTO `boost_users` (`id`, `date_created`, `first_name`, `last_name`, `email`, `contact_number`, `user_role_id`, `password`, `failed_attempts`, `last_attempt_datetime`, `date_modified`, `token`, `token_expire`, `last_activity`) VALUES
(16, '2015-04-29 11:53:48', 'Brad', 'Greenwood', 'brad@sointeractive.co.za', '082 694 4428', 1, '098f6bcd4621d373cade4e832627b4f6', 0, '2016-02-10 15:45:51', '2016-02-10 15:45:51', '26abaddc2f5fe68f17bedd57888fff6d', '2015-12-14 23:21:14', '2015-12-11 15:21:20'),
(25, '2015-10-21 13:13:23', 'Pride', 'Mokhele', 'pride@sointeractive.co.za', '011 807 4621', 2, '098f6bcd4621d373cade4e832627b4f6', 0, '2016-02-12 08:19:01', '2016-02-12 08:19:01', '130f43406d5b06420c028bd4f79c67d7', '2015-12-14 15:54:41', '2015-11-13 16:32:00'),
(26, '2015-10-30 08:23:23', 'Darren', 'Mansour', 'darren@sointeractive.co.za', '082 330 3460', 1, '098f6bcd4621d373cade4e832627b4f6', 1, '2016-01-13 12:23:09', '2016-01-13 12:23:09', NULL, NULL, '2015-10-30 10:25:34'),
(27, '2015-11-04 12:52:51', 'James', 'McAvoy', 'james@m.co.za', '011 565 4444', 2, '098f6bcd4621d373cade4e832627b4f6', 0, '0000-00-00 00:00:00', '2015-11-04 12:52:51', NULL, NULL, '2015-11-04 14:52:51');

-- --------------------------------------------------------

--
-- Table structure for table `boost_user_permissions`
--

CREATE TABLE `boost_user_permissions` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `permission` varchar(50) NOT NULL,
  `short_name` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'default',
  `description` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_user_permissions`
--

INSERT INTO `boost_user_permissions` (`id`, `date_created`, `permission`, `short_name`, `type`, `description`) VALUES
(1, '2015-08-13 13:00:39', 'Dashboard', 'dashboard', 'default', NULL),
(2, '2015-08-13 13:00:44', 'Invoicing', 'invoices', 'default', NULL),
(3, '2015-08-13 13:01:05', 'Supplier Invoice', 'supplier_invoices', 'default', NULL),
(4, '2015-08-13 13:01:16', 'Expenses', 'expenses', 'default', NULL),
(5, '2015-08-13 13:01:23', 'Travel Tracker', 'travel_tracker', 'default', NULL),
(6, '2015-08-13 13:01:32', 'Estimates', 'estimates', 'default', NULL),
(7, '2015-08-13 13:01:36', 'Reports', 'reports', 'default', NULL),
(8, '2015-08-13 13:01:42', 'Contacts', 'contacts', 'default', NULL),
(9, '2015-08-13 13:01:53', 'Account Settings', 'account_settings', 'default', NULL),
(10, '2015-08-20 09:35:19', 'Send Invoices', 'send_invoices', 'alternative', 'Staff are permitted to send Invoices to client via email'),
(11, '2015-08-20 09:35:29', 'Send Estimates', 'send_estimates', 'alternative', 'Staff are permitted to send Estimates to client via email'),
(12, '2015-08-20 09:35:56', 'Send Credit Notes', 'send_credit_notes', 'alternative', 'Staff are permitted to send Credit Notes to client via email'),
(13, '2015-08-20 09:36:35', 'Edit/Create Contacts', 'create_contacts', 'alternative', 'Staff are permitted to edit/create contacts. If staff are not able to view contacts at all then uncheck the \"Contacts\" section'),
(14, '2016-02-12 09:40:07', 'Credit Notes', 'credit_notes', 'default', NULL),
(15, '2016-02-12 07:40:07', 'Expenses', 'expenses', 'default', NULL),
(16, '2015-08-20 07:36:35', 'Edit/Create Expenses', 'create_expenses', 'alternative', 'Staff are permitted to edit/create contacts. If staff are not able to view contacts at all then uncheck the \"Contacts\" section');

-- --------------------------------------------------------

--
-- Table structure for table `boost_user_roles`
--

CREATE TABLE `boost_user_roles` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role_name` varchar(50) NOT NULL,
  `short_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_user_roles`
--

INSERT INTO `boost_user_roles` (`id`, `date_created`, `role_name`, `short_name`) VALUES
(1, '2015-08-13 13:10:35', 'Admin', 'admin'),
(2, '2015-08-13 13:10:45', 'Staff', 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `boost_user_tokens`
--

CREATE TABLE `boost_user_tokens` (
  `id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `request_token` varchar(200) DEFAULT NULL,
  `token_expire` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boost_user_tokens`
--

INSERT INTO `boost_user_tokens` (`id`, `date_created`, `user_id`, `session_id`, `token`, `request_token`, `token_expire`, `date_modified`) VALUES
(1, '2015-12-09 11:31:20', 25, '0bpumrkk2l5apifvj4ot7kb6q4', 'bbd23b77b00b5af7fffd7177d1462ebe', NULL, '2015-12-09 14:14:46', '2015-12-09 13:14:46'),
(2, '2015-12-09 12:37:42', 25, '5k8utpqi9d6di3m0bvv8lpsdh1', 'e00a1705e2f311397a9f5c80c565dc26', NULL, '2015-12-10 10:55:26', '2015-12-10 09:55:26'),
(3, '2015-12-10 08:58:38', 25, '6ocnjupntg7d66qoe7a2ktbdm6', '0c9883ddde407b0795a2a62b5fb5f240', NULL, '2015-12-10 09:58:38', '2015-12-10 08:58:38'),
(4, '2015-12-10 09:01:21', 25, 'pg1s82scseaigim4ure7ntjnl7', '4c82d9d6884a8738b6cf04cc463b2776', NULL, '2015-12-10 10:01:21', '2015-12-10 09:01:21'),
(5, '2015-12-10 09:02:07', 25, '3oe5cld19m89r2gefiq40u1g40', '91007d8051d899c7f1bbe7fc43ee9e04', NULL, '2015-12-10 10:02:07', '2015-12-10 09:02:07'),
(6, '2015-12-10 10:20:38', 25, 'ea6iul1kvdinobofpl9o5tho62', 'd4a123148052f4dfffcbd75f3661e9f3', NULL, '2015-12-10 11:20:38', '2015-12-10 10:20:38'),
(7, '2015-12-10 10:21:29', 25, '672023mq6s027abnra0v315086', '77b05d5b2f716a1d2274376af0c6f894', NULL, '2015-12-10 11:21:29', '2015-12-10 10:21:29'),
(8, '2015-12-10 10:25:59', 25, 'dv3qtmptmjr73m5sifr4cpalp1', 'edce484168a8889d340a4afe74c6fa5d', NULL, '2015-12-10 11:26:17', '2015-12-10 10:26:17'),
(9, '2015-12-10 14:59:43', 25, 'p17293040f7i0bifr8vdb545f0', '501b3f65403e2c02269397bc22e3fa6c', NULL, '2015-12-10 15:59:42', '2015-12-10 14:59:43'),
(10, '2015-12-11 13:32:51', 25, 'a0d45ed0l4n0dn7dspkpm8iae1', 'bc15cf4f3b39389091e2df2ee8a60bb7', NULL, '2015-12-11 14:32:51', '2015-12-11 13:32:51'),
(11, '2015-12-14 06:42:58', 16, 'ho8d6da8nb4jp9ttf54bmr36j7', '530e024e8557b4d42ffce8f1da3a48b7', NULL, '2015-12-14 16:06:55', '2015-12-14 15:06:55'),
(12, '2015-12-18 06:59:29', 25, '6bcf7122809c29c0b9f80672b265b40a', 'fdeea42b14a2e7159cec874d8453c389', NULL, '2015-12-18 07:59:29', '2015-12-18 06:59:29'),
(13, '2015-12-18 07:31:46', 25, 'b871a6bff94fc48daafd436be0450a4e', '9c864efa6c3c3420e7a568f347948d6d', NULL, '2015-12-18 08:31:46', '2015-12-18 07:31:46'),
(14, '2016-01-06 07:51:20', 25, 'f32b8d505682fbd6ce5effbc7a8db27e', '878ebb4570465566e1b746b3b642c928', NULL, '2016-01-06 08:51:20', '2016-01-06 07:51:20'),
(15, '2016-01-07 06:42:39', 25, '4ec0e31b2c2c275ba19260b0509e1d4c', '6d8b5ed588aa13f4f50c99da8726a799', NULL, '2016-01-07 07:42:39', '2016-01-07 06:42:39'),
(16, '2016-02-09 14:40:54', 25, 'cfc1f77ea00f1756e02882460013c796', '0fa32b5f6f57166d411f830e666aa3f8', NULL, '2016-02-09 15:40:54', '2016-02-09 14:40:54'),
(17, '2016-02-09 14:41:31', 25, '2eab83dd57e73f96e74e6cca170ee0b7', '6d6529342799b8ed885a46dbd7076407', NULL, '2016-02-09 15:41:31', '2016-02-09 14:41:31'),
(18, '2016-02-09 14:43:18', 16, 'c8b0a1ac1ca83d9937aa9569466dfd3f', '1dd132e9071334287c607067b103d0f6', NULL, '2016-02-09 15:43:18', '2016-02-09 14:43:18'),
(19, '2016-02-09 15:19:17', 25, '137a060b998ed5ed2f3fef6c31896f83', 'd9bd61f0d2d5e4cfd1c9f7672bca289d', NULL, '2016-02-09 16:19:17', '2016-02-09 15:19:17'),
(20, '2016-02-09 15:19:46', 25, '665c73d5893531dc70ce110a5ab05ce2', '8245a5fff4625f34a48a3d4aafaafcb0', NULL, '2016-02-09 16:19:46', '2016-02-09 15:19:46'),
(21, '2016-02-10 08:06:15', 16, '46d282d7818decd5081a523280b5a494', '0cfd97a98a344cb3cf00d44271cb9ed3', NULL, '2016-02-10 09:06:15', '2016-02-10 08:06:15'),
(22, '2016-02-10 10:13:02', 25, 'dab5ebf77c420f46034cdfc3e8068210', 'f7ef8b8275ff3d873dfd636153b772b8', NULL, '2016-02-10 11:13:02', '2016-02-10 10:13:02'),
(23, '2016-02-10 10:13:12', 25, '0b0553bff920bc98f66312130c79f38a', '35f705a199e762836850bd24b1f5a0e7', NULL, '2016-02-10 11:13:12', '2016-02-10 10:13:12'),
(24, '2016-02-10 10:58:30', 16, '6fcbaae12a326e5fc6ddd0de579d223f', '460891c55c0c091a87c9f917bbc0b694', NULL, '2016-02-10 11:58:30', '2016-02-10 10:58:30'),
(25, '2016-02-10 15:45:51', 16, '07cad4313a157c84e04fb921517b5cc5', '610c1e61b289f77a5ce903a24e5d9c96', NULL, '2016-02-10 16:45:51', '2016-02-10 15:45:51'),
(26, '2016-02-11 08:17:36', 25, '23145bbf4f8fb37d7187bc3a81722b84', 'e5e75b6bf71ab5ca81eddd39dd19fbbc', NULL, '2016-02-11 09:17:36', '2016-02-11 08:17:36'),
(27, '2016-02-11 08:18:04', 25, '4fe2fdd41dc972fe67de3dd45bbc53c0', 'b80e6171f663ff51853b3d9cfecaa1c6', NULL, '2016-02-11 09:18:04', '2016-02-11 08:18:04'),
(28, '2016-02-11 08:18:22', 25, 'b98a6f93984d262f40acc939fce95221', '32f57914b2594d10cc5b0a934bdae43c', NULL, '2016-02-11 09:18:22', '2016-02-11 08:18:22'),
(29, '2016-02-11 08:18:40', 25, '8dab9aa3d1238db9a33769df3f2a029f', '77b84211fe71c564b4c2e78fc75124e5', NULL, '2016-02-11 09:18:40', '2016-02-11 08:18:40'),
(30, '2016-02-11 13:35:44', 25, 'ca408a1a9af5a771f5fab9f0d66036fb', 'a103c80911503e654fcc4470e5a4e237', NULL, '2016-02-11 14:35:44', '2016-02-11 13:35:44'),
(31, '2016-02-11 13:35:52', 25, '295df21cf27beb88c713c1af037850c9', '4932a92fe1908d24089654d43ac8bbd7', NULL, '2016-02-11 14:35:52', '2016-02-11 13:35:52'),
(32, '2016-02-11 13:36:29', 25, '5c0e26ccbb1054c65e01b0cb22d7c2a1', 'c4f12dba57fcdff6812487f858f32a49', NULL, '2016-02-11 14:36:29', '2016-02-11 13:36:29'),
(33, '2016-02-12 08:19:01', 25, 'dd5c0787c7dddb96d6c42a2421359312', '262da536a2137daa1cbf17e359869081', NULL, '2016-02-12 09:19:01', '2016-02-12 08:19:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boost_activities`
--
ALTER TABLE `boost_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_company_sizes`
--
ALTER TABLE `boost_company_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_contacts`
--
ALTER TABLE `boost_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_contact_types`
--
ALTER TABLE `boost_contact_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_countries`
--
ALTER TABLE `boost_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_credit_log`
--
ALTER TABLE `boost_credit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_credit_notes`
--
ALTER TABLE `boost_credit_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_credit_note_items`
--
ALTER TABLE `boost_credit_note_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_currencies`
--
ALTER TABLE `boost_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_email_settings`
--
ALTER TABLE `boost_email_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_email_tokens`
--
ALTER TABLE `boost_email_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_estimates`
--
ALTER TABLE `boost_estimates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_estimate_items`
--
ALTER TABLE `boost_estimate_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_industries`
--
ALTER TABLE `boost_industries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_invoices`
--
ALTER TABLE `boost_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_invoice_items`
--
ALTER TABLE `boost_invoice_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_invoice_payments`
--
ALTER TABLE `boost_invoice_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_invoice_payment_methods`
--
ALTER TABLE `boost_invoice_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_items`
--
ALTER TABLE `boost_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_logos`
--
ALTER TABLE `boost_logos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_organisations`
--
ALTER TABLE `boost_organisations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_role_permissions`
--
ALTER TABLE `boost_role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_sessions`
--
ALTER TABLE `boost_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `boost_taxes`
--
ALTER TABLE `boost_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_templates`
--
ALTER TABLE `boost_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_themes`
--
ALTER TABLE `boost_themes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_theme_settings`
--
ALTER TABLE `boost_theme_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_timezones`
--
ALTER TABLE `boost_timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_users`
--
ALTER TABLE `boost_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_user_permissions`
--
ALTER TABLE `boost_user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_user_roles`
--
ALTER TABLE `boost_user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boost_user_tokens`
--
ALTER TABLE `boost_user_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boost_activities`
--
ALTER TABLE `boost_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `boost_company_sizes`
--
ALTER TABLE `boost_company_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `boost_contacts`
--
ALTER TABLE `boost_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `boost_contact_types`
--
ALTER TABLE `boost_contact_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `boost_countries`
--
ALTER TABLE `boost_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT for table `boost_credit_log`
--
ALTER TABLE `boost_credit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `boost_credit_notes`
--
ALTER TABLE `boost_credit_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `boost_credit_note_items`
--
ALTER TABLE `boost_credit_note_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `boost_currencies`
--
ALTER TABLE `boost_currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `boost_email_settings`
--
ALTER TABLE `boost_email_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `boost_email_tokens`
--
ALTER TABLE `boost_email_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `boost_estimates`
--
ALTER TABLE `boost_estimates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `boost_estimate_items`
--
ALTER TABLE `boost_estimate_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `boost_industries`
--
ALTER TABLE `boost_industries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `boost_invoices`
--
ALTER TABLE `boost_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1232;

--
-- AUTO_INCREMENT for table `boost_invoice_items`
--
ALTER TABLE `boost_invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=521;

--
-- AUTO_INCREMENT for table `boost_invoice_payments`
--
ALTER TABLE `boost_invoice_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;

--
-- AUTO_INCREMENT for table `boost_invoice_payment_methods`
--
ALTER TABLE `boost_invoice_payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `boost_items`
--
ALTER TABLE `boost_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `boost_logos`
--
ALTER TABLE `boost_logos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `boost_organisations`
--
ALTER TABLE `boost_organisations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `boost_role_permissions`
--
ALTER TABLE `boost_role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `boost_sessions`
--
ALTER TABLE `boost_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boost_taxes`
--
ALTER TABLE `boost_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `boost_templates`
--
ALTER TABLE `boost_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `boost_themes`
--
ALTER TABLE `boost_themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `boost_theme_settings`
--
ALTER TABLE `boost_theme_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `boost_timezones`
--
ALTER TABLE `boost_timezones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `boost_users`
--
ALTER TABLE `boost_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `boost_user_permissions`
--
ALTER TABLE `boost_user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `boost_user_roles`
--
ALTER TABLE `boost_user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `boost_user_tokens`
--
ALTER TABLE `boost_user_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
