users(id PK, nom, email UNIQUE)
articles(id PK, titre, contenu, user_id FK → users.id)
commentaires(id PK, contenu, article_id FK → articles.id)

Relations : `
users 1—N articles,
articles 1—N commentaires
