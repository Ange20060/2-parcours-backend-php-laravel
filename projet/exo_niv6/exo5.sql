##Articles avec le nom de l'auteur'
SELECT articles.titre, users.nom AS auteur
FROM articles
JOIN users ON users.id = articles.user_id;

##Nombre d'articles par auteur'
SELECT users.nom, COUNT(articles.id) AS nb_articles
FROM users
LEFT JOIN articles ON articles.user_id = users.id
GROUP BY users.id;
