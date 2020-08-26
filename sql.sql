
CREATE TABLE `tbl_sample` (
  `id` int(11) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `tbl_sample` (`id`, `first_name`, `last_name`) VALUES
(1, 'Nuri', 'Akman'),
(2, 'Kemal', 'Kırcı'),
(4, 'Zeynep', 'Kılıç');
ALTER TABLE `tbl_sample` ADD PRIMARY KEY (`id`);

ALTER TABLE `tbl_sample` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;