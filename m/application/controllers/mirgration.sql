-- 13_07_2018 added match json column to store match api response
ALTER TABLE `seriesmst` ADD `match_json_updated_on` DATETIME NULL COMMENT 'last updated datetime of match json' AFTER `HelperID`, ADD `match_json` TEXT NULL COMMENT 'api response of series in json format' AFTER `match_json_updated_on`;