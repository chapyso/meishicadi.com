#!/bin/bash

# Database Export Script for HOONA Project
# This script exports the database with all tables and data

# Load environment variables
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
else
    echo "Error: .env file not found!"
    exit 1
fi

# Set variables from .env
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

# Output file
OUTPUT_FILE="database.sql"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
OUTPUT_FILE_TIMESTAMPED="database_${TIMESTAMP}.sql"

echo "Starting database export..."
echo "Database: $DB_DATABASE"
echo "Host: $DB_HOST:$DB_PORT"

# Export database with data
mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" \
    --single-transaction \
    --routines \
    --triggers \
    --events \
    --add-drop-table \
    --complete-insert \
    --extended-insert \
    --quick \
    --lock-tables=false \
    "$DB_DATABASE" > "$OUTPUT_FILE"

if [ $? -eq 0 ]; then
    echo "✓ Database exported successfully to $OUTPUT_FILE"
    
    # Create timestamped copy
    cp "$OUTPUT_FILE" "$OUTPUT_FILE_TIMESTAMPED"
    echo "✓ Timestamped copy created: $OUTPUT_FILE_TIMESTAMPED"
    
    # Get file size
    SIZE=$(du -h "$OUTPUT_FILE" | cut -f1)
    echo "✓ File size: $SIZE"
else
    echo "✗ Error: Database export failed!"
    exit 1
fi

