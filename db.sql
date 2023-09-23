-- database = pes 
-- three tables users , registeration and upload
-- when deploy here need to code SQL codes


-- Table structure for table `users`


CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) 
-- ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'demo', 'demo@demo', 'demo');

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

--
-- Table structure for table `registeration`
--

CREATE TABLE `registeration` (
  `user_id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `club` int NOT NULL
)
-- ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
