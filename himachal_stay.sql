-- table for user
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` char(50) NOT NULL,
  `lastname` char(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(150) NOT NULL,
  `mobile` varchar(20),
  `address` varchar(255),
  `gender` varchar(20),
  `district` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `user_type` int(11) NOT NULL,
  `user_verify` int(11) NOT NULL,
  `dob` varchar(50) NOT NULL
);
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- ends here ~ table for user

-- table for himachal district
CREATE TABLE `himachal_district` (
    `id` INT(11) NOT NULL,
    `district_val` CHAR(100) NOT NULL,
    `district_name` CHAR(100) NOT NULL
);

INSERT INTO `himachal_district` (`id`,`district_val`,`district_name`) VALUES 
    (1,'bilaspur','Bilaspur'),
  (2,'chamba','Chamba'),
  (3,'hamirpur','Hamirpur'),
  (4,'kangra','Kangra'),
  (5,'kinnaur','Kinnaur'),
  (6,'kullu','Kullu'),
  (7,'mandi','Mandi'),
  (8,'shimla','Shimla'),
  (9,'solan','Solan'),
  (10,'una','Una'),
  (11,'lahaul_spiti','Lahaul &amp; Spiti'),
  (12,'sirmaur','Sirmaur (Sirmour)');

ALTER TABLE `himachal_district`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `himachal_district`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- ends here ~ table for himachal district

-- table for hotel details
CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `hotel_name` char(50) NOT NULL,
  `hotel_addr` char(50) NOT NULL,
  `hotel_city` varchar(100) NOT NULL,
  `hotel_district` varchar(150) NOT NULL,
  `hotel_pincode` varchar(20),
  `hotel_state` varchar(255),
  `hotel_status` int(11) NOT NULL,
  `hotel_desc` varchar(1500) NOT NULL,
  `hotel_image` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL
);

ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- ends here ~ table for hotel details

-- table for hotel rooms
CREATE TABLE `hotel_rooms` (
  `id` int(11) NOT NULL,
  `room_number` char(50) NOT NULL,
  `room_type` char(50) NOT NULL,
  `room_price` varchar(100) NOT NULL,
  `room_details` varchar(300) NOT NULL,
  `room_booked_status` varchar(20),
  `hotel_id` varchar(50) NOT NULL,
  `check_in_date` timestamp NULL DEFAULT NULL,
  `check_out_date` timestamp NULL DEFAULT NULL,
  `reserved_user_id` int(11) NULL DEFAULT NULL,
  `payment_id` int(11) NULL DEFAULT NULL

);

ALTER TABLE `hotel_rooms`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `hotel_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `himachal_stay`.`hotel_rooms` 
  ADD COLUMN `room_booking_table_id` INT(11) NULL AFTER `payment_id`;
-- ends here ~ table for hotel rooms

-- table for store hotel room features details
CREATE TABLE `hotel_rooms_features` (
  `id` int(11) NOT NULL,
  `room_id` char(50) NOT NULL,
  `value` varchar(50) NOT NULL
);

ALTER TABLE `hotel_rooms_features`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `hotel_rooms_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- ends here ~ table for store hotel room features details

-- table for room booking details
CREATE TABLE `room_booking_details` (
  `id` int(11) NOT NULL,
  `hotel_room_id` int(11) NOT NULL,
  `payment_id` int(11) NULL DEFAULT NULL,
  `payment_status` int(11) NULL DEFAULT NULL,
  `room_booked_status` int(11) NULL DEFAULT NULL,
  `check_in_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `check_out_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reserved_user_id` int(11)  NOT NULL,
  `totalAdult` int(11) NULL DEFAULT NULL,
  `totalChildren` int(11) NULL DEFAULT NULL,
  `hotel_location` varchar(100) NOT NULL
);

ALTER TABLE `room_booking_details`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `room_booking_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- ends here ~ table for room booking details