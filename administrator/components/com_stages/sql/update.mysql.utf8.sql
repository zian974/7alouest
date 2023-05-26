/* Only premium users are allowed to update a component */
ALTER TABLE `budo3557_7aw_dev`.`wa7_stages_stagiaires` 
ADD COLUMN `licence_num` VARCHAR(45) NULL AFTER `presence`;
