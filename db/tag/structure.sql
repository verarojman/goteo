CREATE TABLE  `goteo`.`tag` (
`id` SERIAL NOT NULL ,
`name` TINYTEXT NOT NULL ,
`blog` BIGINT( 20 ) UNSIGNED NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT =  'Tags de blogs (de nodo)';

-- los alters
ALTER TABLE `tag` CHANGE `tag` `name` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;