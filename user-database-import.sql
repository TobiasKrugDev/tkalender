-- Table structure
DROP TABLE `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `db_hash` varchar(10000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Indices
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);


-- Auto Increment
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Create Demo User
DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `password_hash`, `db_hash`) VALUES
(1, 'Demo User', '$2y$10$YE8oeXOFA8fTMrJmKYeCEuGLX66jSkb.g2ZNWhxSPEGphJSSijyoK', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJob3N0X25hbWUiOiJkYjUwMTQyMDExMjIuaG9zdGluZy1kYXRhLmlvIiwiZGF0YWJhc2UiOiJkYnMxMTgxOTcyNCIsInVzZXJfbmFtZSI6ImRidTUwNjE4NjkiLCJwYXNzd29yZCI6IjNzNSF2ODEwVHo_Lmg1TG84dFN1ejQjQXAhIn0.Cg5GL1thc9zOcP2ovkfisFJdTfTS0PR5F2uDJc108sM'),
(2, 'Zweiter User', '$2y$10$.AR4f1fALf6wfUJbro0ZGe2HewKQ/RiUiFfgKHBl4GTfByQ/6737m', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJob3N0X25hbWUiOiJkYjUwMTU3NjkxNDMuaG9zdGluZy1kYXRhLmlvIiwiZGF0YWJhc2UiOiJkYnMxMjg2NTY2NiIsInVzZXJfbmFtZSI6ImRidTM5MzE0MzYiLCJwYXNzd29yZCI6ImEuVHowNCFlOCJ9.BZgPnSBi7zHmR3xdWRiAaSUmuVYBDvfH8e1bHzAAxFg');