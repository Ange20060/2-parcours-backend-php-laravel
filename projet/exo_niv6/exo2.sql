CREATE TABLE users (
    id    INTEGER PRIMARY KEY AUTOINCREMENT,
    nom   TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE
);

CREATE TABLE articles (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    titre    TEXT NOT NULL,
    contenu  TEXT NOT NULL,
    user_id  INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```
