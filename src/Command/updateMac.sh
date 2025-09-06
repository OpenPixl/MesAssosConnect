#!/bin/bash

# Définir le répertoire de l'application Symfony
APP_DIR="/path/to/your/symfony/project"
NAME_BRANCH="STABLE"
# Aller dans le répertoire de l'application
cd $APP_DIR

# Tirer les dernières modifications du dépôt GitHub
echo "Fetching latest changes from GitHub..."
git pull origin main

# Mettre à jour les dépendances avec Composer
echo "Updating Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Exécuter les migrations de base de données
echo "Running database migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

# Vider le cache de Symfony
echo "Clearing Symfony cache..."
php bin/console cache:clear

echo "Update complete!"