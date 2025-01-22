#!/bin/bash

# Wait for MySQL to be available
echo "Waiting for MySQL to be available..."
until mysql -h db -u root -proot -e "SELECT 1"; do
  echo "Waiting for MySQL..."
  sleep 5
done
echo "MySQL is available."

# Check if the migrations and key generation have already run
FLAG_FILE="/var/www/html/storage/.initialized"

if [ ! -f "$FLAG_FILE" ]; then
  echo "Setting up environment and running migrations..."

  # Copy .env.example to .env if .env doesn't exist
  if [ ! -f "/var/www/html/.env" ]; then
    cp /var/www/html/.env.example /var/www/html/.env
    echo ".env file created from .env.example"
  fi

  # Generate the application key if vendor is not present
  if [ ! -d "/var/www/html/vendor" ]; then
    echo "Running composer install..."
    composer install --no-dev --optimize-autoloader
  fi

  # Generate the application key
  php artisan key:generate --force

  # Run Laravel migrations
  php artisan migrate --force

  # Create the flag file to indicate initialization is complete
  touch "$FLAG_FILE"
else
  echo "Environment setup, migrations, and app key generation already completed."
fi

# Run the original command (Apache)
exec "$@"
