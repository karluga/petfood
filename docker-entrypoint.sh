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
  echo "Running migrations and generating app key..."

  # Run Laravel migrations
  php artisan migrate --force

  # Generate the application key
  php artisan key:generate --force

  # Create the flag file to indicate initialization is complete
  touch "$FLAG_FILE"
else
  echo "Migrations and app key generation already completed."
fi

# Run the original command (Apache)
exec "$@"
