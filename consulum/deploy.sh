#!/bin/bash

# Deployment script for consulum.meishicadi.com
# Server Information
SERVER_IP="148.135.129.127"
SSH_PORT="65002"
SSH_USER="u916293666"
DEPLOY_PATH="/home/u916293666/domains/meishicadi.com/public_html/consulum"

echo "🚀 Starting deployment to consulum.meishicadi.com..."
echo "📦 Syncing files to server..."

# Use rsync to sync files (excludes .git, node_modules, etc.)
rsync -avz --progress \
  --exclude='.git' \
  --exclude='.env' \
  --exclude='node_modules' \
  --exclude='storage/logs/*.log' \
  --exclude='storage/framework/cache/*' \
  --exclude='storage/framework/sessions/*' \
  --exclude='storage/framework/views/*' \
  --exclude='vendor' \
  --exclude='.idea' \
  --exclude='.vscode' \
  --exclude='*.zip' \
  --exclude='*.sql' \
  --exclude='.DS_Store' \
  --exclude='HOOLLA' \
  --exclude='HOONA_project.zip' \
  -e "ssh -p $SSH_PORT" \
  ./ $SSH_USER@$SERVER_IP:$DEPLOY_PATH/

if [ $? -eq 0 ]; then
  echo "✅ Files uploaded successfully!"
  echo "🔧 Running deployment commands on server..."
  
  # SSH into server and run deployment commands
  ssh -p $SSH_PORT $SSH_USER@$SERVER_IP << 'ENDSSH'
    cd /home/u916293666/domains/meishicadi.com/public_html/consulum
    
    echo "📦 Installing dependencies..."
    composer install --optimize-autoloader --no-dev --quiet
    
    echo "🔗 Creating storage link..."
    php artisan storage:link 2>/dev/null || true
    
    echo "🗑️  Clearing caches..."
    php artisan optimize:clear
    
    echo "💾 Caching for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    echo "🔐 Setting permissions..."
    chmod -R 775 storage bootstrap/cache 2>/dev/null || true
    chmod -R 775 public/storage 2>/dev/null || true
    
    echo "✅ Deployment completed on server!"
ENDSSH

  if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Deployment completed successfully!"
    echo "🌐 Visit: https://consulum.meishicadi.com"
  else
    echo "❌ Error running deployment commands on server"
    exit 1
  fi
else
  echo "❌ Error uploading files"
  exit 1
fi

