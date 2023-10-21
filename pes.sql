

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




--
-- Database: `pes`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admins_id` int NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admins_id`, `username`, `password`) VALUES
(4, 'username', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8'),
(5, 'demo', '2a97516c354b68848cdbd8f54a226a0a55b21ed138e207ad6c5cbb9c00aa5aea');

-- --------------------------------------------------------

--
-- Table structure for table `league_table`
--

CREATE TABLE `league_table` (
  `user_id` int NOT NULL,
  `tournament_id` int DEFAULT NULL,
  `matches` int DEFAULT NULL,
  `win` int DEFAULT NULL,
  `draw` int DEFAULT NULL,
  `loss` int DEFAULT NULL,
  `score` int DEFAULT NULL,
  `points` int DEFAULT NULL,
  `club_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `match_id` int NOT NULL,
  `tournament_id` int NOT NULL,
  `team1_id` int DEFAULT NULL,
  `team2_id` int DEFAULT NULL,
  `match_date` date DEFAULT NULL,
  `team1_result` int DEFAULT NULL,
  `team2_result` int DEFAULT NULL,
  `match_status` int DEFAULT '0'
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `club` int NOT NULL,
  `tournament_id` int NOT NULL,
  `password` varchar(255) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

-- --------------------------------------------------------

--
-- Table structure for table `result_from_user`
--

CREATE TABLE `result_from_user` (
  `id` int NOT NULL,
  `tournament_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `match_id` int DEFAULT NULL,
  `result_image` varchar(255) DEFAULT NULL,
  `team1_score` int DEFAULT NULL,
  `team2_score` int DEFAULT NULL,
  `message` varchar(50) DEFAULT NULL,
  `match_active` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int NOT NULL,
  `tournament_id` int NOT NULL,
  `user_id` int NOT NULL,
  `club_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

CREATE TABLE `tournament` (
  `tournament_id` int NOT NULL,
  `tournament_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `admins_id` int DEFAULT NULL,
  `model` varchar(20) NOT NULL,
  `registration_open` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

-------------------------------------------
-- Indexes for dumped tables
-------------------------------------------


--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admins_id`);

--
-- Indexes for table `league_table`
--
ALTER TABLE `league_table`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `tournament_id` (`tournament_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`match_id`),
  ADD KEY `fk_tournament_id3` (`tournament_id`),
  ADD KEY `fk_team1_id` (`team1_id`),
  ADD KEY `fk_team2_id` (`team2_id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `result_from_user`
--
ALTER TABLE `result_from_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `match_id` (`match_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `fk_tournament_id` (`tournament_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `tournament`
--
ALTER TABLE `tournament`
  ADD PRIMARY KEY (`tournament_id`),
  ADD KEY `fk_admin_id` (`admins_id`);

---------------------------------------------
-- AUTO_INCREMENT for dumped tables
---------------------------------------------

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admins_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `match_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3169;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `result_from_user`
--
ALTER TABLE `result_from_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tournament`
--
ALTER TABLE `tournament`
  MODIFY `tournament_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `league_table`
--
ALTER TABLE `league_table`
  ADD CONSTRAINT `league_table_ibfk_2` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`),
  ADD CONSTRAINT `league_table_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `registration` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `fk_team1_id` FOREIGN KEY (`team1_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_team2_id` FOREIGN KEY (`team2_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tournament_id3` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`) ON DELETE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `fk_tournament_id` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `registration` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admins_id`) REFERENCES `admins` (`admins_id`);
COMMIT;

