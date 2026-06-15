#!/bin/bash
set -e

SQL_DUMP="${SQL_DUMP_PATH:-/var/www/html/database/seeders/data/marketplace_data.sql}"
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE:-marketplace}"
DB_USERNAME="${DB_USERNAME:-laravel_user}"
export PGPASSWORD="${DB_PASSWORD:-laravel_password}"

if [ ! -f "$SQL_DUMP" ]; then
    echo "SQL dump not found at $SQL_DUMP — skipping seed."
    exit 0
fi

echo "Importing $SQL_DUMP..."

psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" -d "$DB_DATABASE" -v ON_ERROR_STOP=1 <<-EOSQL
TRUNCATE TABLE
    "Messages",
    "Ratings",
    "FollowedAuctions",
    "Chats",
    "AuctionsImages",
    "Auctions",
    "Users",
    "Categories",
    "Images"
RESTART IDENTITY CASCADE;
EOSQL

psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" -d "$DB_DATABASE" -v ON_ERROR_STOP=1 \
    -c "SET session_replication_role = replica" \
    -f "$SQL_DUMP" \
    -c "SET session_replication_role = origin"

echo "SQL dump imported successfully."
