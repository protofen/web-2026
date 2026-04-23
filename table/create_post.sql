CREATE TABLE IF NOT EXISTS `blog`.`post` (
  `idpost` INT NOT NULL,
  `likes` INT NULL,
  `comments` INT NULL,
  `date` DATE NOT NULL,
  PRIMARY KEY (`idpost`))
ENGINE = InnoDB