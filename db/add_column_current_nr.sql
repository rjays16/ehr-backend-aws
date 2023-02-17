/*[31-Oct 12:05:12 PM][12180 ms]*/ ALTER TABLE `ehrv2_prod`.`smed_dept_encounter` ADD COLUMN `current_ward_nr` VARCHAR(15) NULL AFTER `deptenc_code`; 
/*[8-Nov 3:58:12 PM][5032 ms]*/ ALTER TABLE `ehrv2`.`smed_dept_encounter` CHANGE `current_ward_nr` `current_ward_nr` INT(11) UNSIGNED NULL;
/*[31-Oct 12:16:23 PM][5841 ms]*/ ALTER TABLE `ehrv2_prod`.`smed_dept_encounter` ADD CONSTRAINT FOREIGN KEY (`room_no`) REFERENCES `ehrv2_prod`.`smed_nurse_room_catalog`(`nr`) ON UPDATE CASCADE, DROP FOREIGN KEY `smed_dept_encounter_ibfk_1`; 
/*[31-Oct 12:16:09 PM][2190 ms]*/ ALTER TABLE `ehrv2_prod`.`smed_nurse_room_catalog` CHANGE `nr` `nr` INT(11) UNSIGNED NOT NULL; 
/*[31-Oct 12:22:28 PM][5405 ms]*/ ALTER TABLE `ehrv2_prod`.`smed_dept_encounter` CHANGE `current_ward_nr` `current_ward_nr` INT(11) UNSIGNED NULL, ADD FOREIGN KEY (`current_ward_nr`) REFERENCES `ehrv2_prod`.`smed_nurse_ward_catalog`(`nr`) ON UPDATE CASCADE; 
/*[8-Nov 4:02:49 PM][101 ms]*/ ALTER TABLE `ehrv2`.`smed_nurse_ward_catalog` CHANGE `nr` `nr` SMALLINT(5) NOT NULL, ADD PRIMARY KEY (`nr`);
/*[8-Nov 4:06:29 PM][72 ms]*/ ALTER TABLE `ehrv2`.`smed_nurse_ward_catalog` CHANGE `nr` `nr` INT(11) NOT NULL; 
/*[8-Nov 4:09:05 PM][126 ms]*/ ALTER TABLE `ehrv2`.`smed_nurse_ward_catalog` CHANGE `nr` `nr` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;

/*nurse note */

/*[26-Nov 11:50:01 AM][246 ms]*/ ALTER TABLE `ehrv2`.`smed_nurse_notes` ADD COLUMN `flag_document_id` TINYINT NULL AFTER `index`;

/*[26-Nov 11:50:16 AM][6 ms]*/ ALTER TABLE `ehrv2`.`smed_nurse_notes` CHANGE `flag_document_id` `flag_document_id` TINYINT(1) DEFAULT 0 NULL;