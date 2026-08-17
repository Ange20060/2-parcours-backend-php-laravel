INSERT INTO users (nom, email) VALUES ('Marie', 'marie@x.fr');
SELECT * FROM users;
SELECT * FROM users WHERE id = 1;
UPDATE users SET email = 'nouvelle@x.fr' WHERE id = 1;
DELETE FROM users WHERE id = 1;
