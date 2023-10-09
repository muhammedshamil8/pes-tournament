-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql_db
-- Generation Time: Oct 07, 2023 at 07:57 AM
-- Server version: 8.0.33
-- PHP Version: 8.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_34999877_pes`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admins_id` int NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admins_id`, `username`, `password`) VALUES
(1, 'demo', 'demo');

-- --------------------------------------------------------

--
-- Table structure for table `league_table`
--

CREATE TABLE `league_table` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  `matches_played` int DEFAULT NULL,
  `wins` int DEFAULT NULL,
  `draws` int DEFAULT NULL,
  `losses` int DEFAULT NULL,
  `score` int DEFAULT NULL,
  `points` int DEFAULT NULL,
  `tournament_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `league_table`
--

INSERT INTO `league_table` (`id`, `user_id`, `club_id`, `matches_played`, `wins`, `draws`, `losses`, `score`, `points`, `tournament_id`) VALUES
(15, 20, 16, 0, 0, 0, 0, 0, 0, 7),
(16, 21, 3, 0, 0, 0, 0, 0, 0, 7),
(17, 22, 11, 0, 0, 0, 0, 0, 0, 7);

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `match_id` int NOT NULL,
  `team1_id` int NOT NULL,
  `team2_id` int NOT NULL,
  `match_date` date NOT NULL,
  `tournament_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registeration`
--

CREATE TABLE `registeration` (
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `club` int NOT NULL,
  `password` varchar(255) NOT NULL,
  `tournament_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registeration`
--

INSERT INTO `registeration` (`user_id`, `name`, `full_name`, `age`, `phone_number`, `club`, `password`, `tournament_id`) VALUES
(20, 'z', 'z', 7, '+9900998877', 16, 'z', 7),
(21, 'a', 'a', 5, '9996668888', 3, 'a', 7),
(22, 'demo', 'Trailor', 7, '+9900998877', 11, 'demo', 7);

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int NOT NULL,
  `tournament_id` int DEFAULT NULL,
  `team_club_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `tournament_id`, `team_club_id`) VALUES
(1, 7, 16),
(2, 7, 3),
(3, 7, 11);

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

CREATE TABLE `tournament` (
  `tournament_id` int NOT NULL,
  `tournament_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `admins_id` int DEFAULT NULL,
  `model` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournament`
--

INSERT INTO `tournament` (`tournament_id`, `tournament_name`, `start_date`, `admins_id`, `model`) VALUES
(4, 'g', '2023-10-27', 1, ''),
(5, 'a', '2023-10-05', 1, ''),
(6, 'new', '2023-10-26', 1, ''),
(7, 'pes', '2023-10-07', 1, 'league');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admins_id`);

--
-- Indexes for table `league_table`
--
ALTER TABLE `league_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `club_id` (`club_id`),
  ADD KEY `fk_tournament` (`tournament_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`match_id`),
  ADD KEY `fk_tournaments` (`tournament_id`);

--
-- Indexes for table `registeration`
--
ALTER TABLE `registeration`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `idx_club` (`club`),
  ADD KEY `fk_tournament_id` (`tournament_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `team_club_id` (`team_club_id`);

--
-- Indexes for table `tournament`
--
ALTER TABLE `tournament`
  ADD PRIMARY KEY (`tournament_id`),
  ADD KEY `fk_admin_id` (`admins_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admins_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `league_table`
--
ALTER TABLE `league_table`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `match_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registeration`
--
ALTER TABLE `registeration`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tournament`
--
ALTER TABLE `tournament`
  MODIFY `tournament_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `league_table`
--
ALTER TABLE `league_table`
  ADD CONSTRAINT `fk_tournament` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`),
  ADD CONSTRAINT `league_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `registeration` (`user_id`),
  ADD CONSTRAINT `league_table_ibfk_2` FOREIGN KEY (`club_id`) REFERENCES `registeration` (`club`);

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `fk_tournaments` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`);

--
-- Constraints for table `registeration`
--
ALTER TABLE `registeration`
  ADD CONSTRAINT `fk_tournament_id` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`);

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `registeration` (`tournament_id`),
  ADD CONSTRAINT `teams_ibfk_2` FOREIGN KEY (`team_club_id`) REFERENCES `registeration` (`club`);

--
-- Constraints for table `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admins_id`) REFERENCES `admins` (`admins_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


--
-- Triggers `registeration`
--

-- Grant TRIGGER privilege to the user for the registeration table
GRANT TRIGGER ON `if0_34999877_pes`.`registeration` TO 'if0_34999877'@'192.168.0.6';

-- Triggers `registeration`
DELIMITER $$
CREATE TRIGGER `after_register_insert` AFTER INSERT ON `registeration` FOR EACH ROW BEGIN
    INSERT INTO league_table (user_id, club_id, matches_played, wins, draws, losses, score, points, tournament_id)
    VALUES (NEW.user_id, NEW.club, 0, 0, 0, 0, 0, 0, NEW.tournament_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_register_insert2` AFTER INSERT ON `registeration` FOR EACH ROW BEGIN
    INSERT INTO teams (tournament_id, team_club_id)
    VALUES (NEW.tournament_id, NEW.club);
END
$$
DELIMITER ;

-- --------------------------------------------------------
