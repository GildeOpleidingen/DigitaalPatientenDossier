CREATE TABLE `migrations` (`id` INT NOT NULL AUTO_INCREMENT, `name` VARCHAR(200) NOT NULL , `timestamp` DATE NOT NULL , PRIMARY KEY (`id`));
INSERT INTO `migrations` (`id`, `name`, `timestamp`)
 VALUES ('1', 'dpd_empty', '2025-12-16'),
  ('2', 'dpd_testdata01', '2025-12-16'),
   ('3', 'dpd_testdata02', '2025-12-16'),
    ('4', 'dpd_testdata03', '2025-12-16'),
     ('5', 'dpd_testdata04_alterColumn', '2025-12-16'),
      ('6', 'dpd_testdata05_alterColumn', '2025-12-16'),
       ('7', 'dpd_testdata06', '2025-12-16'),
        ('8', 'dpd_testdata07', '2025-12-16'),
         ('9', 'dpd_testdata08_alterColumn', '2025-12-16'),
          ('10', 'dpd_testdata10_alterColumn', '2025-12-16'),
           ('11', 'dpd_testdata11_alterColumn', '2025-12-16'),
            ('12', 'dpd_testdata12_alterColumn', '2025-12-16'),
             ('13', 'dpd_testdata13_alterColumn', '2025-12-16'),
              ('14', 'dpd_testdata14_alterColumn', '2025-12-16'),
               ('15', 'dpd_testdata14_alterColumn', '2025-12-16'),
                ('16', 'dpd_testdata15', '2025-12-16'),
                 ('17', 'dpd_testdata16_alterColumn', '2025-12-16');

INSERT INTO `migrations` (`id`, `name`, `timestamp`) VALUES (NULL, 'dpd_testdata17_alterColumns', '2025-12-17'), (NULL, 'dpd_testdata18', '2025-12-17');
