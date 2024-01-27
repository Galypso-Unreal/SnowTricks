<h1>Projet Symfony SnowTricks</h1>

<p>Bienvenue dans le projet Symfony SnowTricks ! Ce projet utilise le framework Symfony pour créer une application web dédiée aux astuces de snowboard.</p>

<h2>Mise en place du projet</h2>

<h3>Clonage du dépôt Git</h3>

<p>Clonez le dépôt Git sur votre machine locale en utilisant la commande suivante :</p>

<pre><code>git clone https://github.com/Galypso-Unreal/SnowTricks.git
</code></pre>

<h3>Installation des dépendances</h3>

<p>Accédez au répertoire du projet fraîchement cloné :</p>

<pre><code>cd SnowTricks
</code></pre>

<p>Installez les dépendances du projet avec Composer :</p>

<pre><code>composer install
</code></pre>

<h3>Configuration de l'environnement</h3>

<p>Copiez le fichier <code>.env</code> en tant que <code>.env.local</code> et configurez vos paramètres d'environnement, notamment la connexion à la base de données.</p>

<pre><code>cp .env .env.local
</code></pre>

<h3>Création de la base de données</h3>

<p>Créez la base de données et appliquez les migrations :</p>

<pre><code>php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
</code></pre>

<h3>Chargement des données initiales (facultatif)</h3>

<p>Si vous souhaitez charger des données initiales (fixtures) dans la base de données pour tester l'application, exécutez la commande suivante :</p>

<pre><code>php bin/console doctrine:fixtures:load
</code></pre>

<h3>Démarrage du serveur local</h3>

<p>Démarrez le serveur de développement Symfony :</p>

<pre><code>php bin/console server:run
</code></pre>

<p>Vous devriez maintenant pouvoir accéder à l'application en ouvrant votre navigateur à l'adresse <a href="http://127.0.0.1:8000">http://127.0.0.1:8000</a>.</p>
