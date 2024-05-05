-- Table structures

-- Appointments
CREATE TABLE `appointments` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `startAt` datetime NOT NULL,
  `endAt` datetime NOT NULL,
  `location` int DEFAULT NULL,
  `category` int DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Categories
CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `color` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Contacts
CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `phone_number` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` longtext COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Locations
CREATE TABLE `locations` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `street_address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Participations
CREATE TABLE `participations` (
  `appointment` int NOT NULL,
  `contact` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Indices

-- Appointments
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment.location` (`location`),
  ADD KEY `appointment.category` (`category`);

-- Categories
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `color` (`color`);

-- Contacts
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD UNIQUE KEY `email_address` (`email_address`);

-- Locations
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

-- Participations
ALTER TABLE `participations`
  ADD PRIMARY KEY (`appointment`,`contact`),
  ADD KEY `participation.contact` (`contact`);


-- Auto Increments

-- Appointments
ALTER TABLE `appointments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Categories
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Contacts
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Locations
ALTER TABLE `locations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


-- Constraints

-- Appointments
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointment.category` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Participations
ALTER TABLE `participations`
  ADD CONSTRAINT `participation.appointment` FOREIGN KEY (`appointment`) REFERENCES `appointments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `participation.contact` FOREIGN KEY (`contact`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;