USE id2086086_songs;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS songs;
SET FOREIGN_KEY_CHECKS = 1;
CREATE TABLE IF NOT EXISTS songs (
	songId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50),
    artist VARCHAR(50),
    setlist VARCHAR(50),
    songDate DATETIME
);

/*INSERT INTO songs (title, artist, setlist, songDate)
VALUES
('A', 'A', 'A', '9999-12-31'),
('B', 'S', 'B', '9999-12-31'),
('C', 'A', 'A', '9999-12-31'),
('D', 'Z', 'B', '9999-12-31'),
('E', 'X', 'A', '9999-12-31'),
('F', 'C', 'B', '9999-12-31'),
('G', 'A', 'A', '9999-12-31'),
('H', 'H', 'B', '9999-12-31'),
('I', 'A', 'A', '9999-12-31'),
('J', 'H', 'B', '9999-12-31'),
('K', 'B', 'A', '9999-12-31'),
('L', 'S', 'B', '9999-12-31'),
('M', 'A', 'A', '9999-12-31'),
('N', 'F', 'C', '9999-12-31'),
('O', 'F', 'A', '9999-12-31'),
('P', 'S', 'B', '9999-12-31');*/