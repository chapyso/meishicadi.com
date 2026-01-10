#!/bin/bash

# Deployment script for consulum.meishicadi.com - UPDATES ONLY
# Server Information
SERVER_IP="148.135.129.127"
SSH_PORT="65002"
SSH_USER="u916293666"
DEPLOY_PATH="/home/u916293666/domains/meishicadi.com/public_html/consulum"

echo "🚀 Starting deployment of UPDATES ONLY to consulum.meishicadi.com..."

# Get list of changed files from last commit
CHANGED_FILES=$(git diff --name-only HEAD~1 HEAD | grep -v "^\.git" | grep -v "storage.zip" | grep -v "\.log$")

if [ -z "$CHANGED_FILES" ]; then
  echo "❌ No changed files found in last commit"
  exit 1
fi

echo "📝 Files to upload:"
echo "$CHANGED_FILES" | sed 's/^/  - /'
echo ""

# Upload each changed file
echo "📦 Uploading changed files..."
for file in $CHANGED_FILES; do
  if [ -f "$file" ]; then
    echo "  Uploading: $file"
    # Create directory structure on remote if needed
    dir=$(dirname "$file")
    if [ "$dir" != "." ]; then
      ssh -p $SSH_PORT $SSH_USER@$SERVER_IP "mkdir -p $DEPLOY_PATH/$dir" 2>/dev/null
    fi
    # Upload the file
    scp -P $SSH_PORT "$file" $SSH_USER@$SERVER_IP:$DEPLOY_PATH/"$file" 2>/dev/null
    if [ $? -eq 0 ]; then
      echo "    ✅ $file"
    else
      echo "    ⚠️  $file (may need manual upload)"
    fi
  else
    echo "  ⚠️  Skipping: $file (not found locally)"
  fi
done

echo ""
echo "🔧 Running deployment commands on server..."

# SSH into server and run deployment commands
ssh -p $SSH_PORT $SSH_USER@$SERVER_IP << 'ENDSSH'
    cd /home/u916293666/domains/meishicadi.com/public_html/consulum
    
    echo "🗑️  Clearing caches..."
    php artisan optimize:clear
    
    echo "💾 Caching for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    echo "✅ Deployment completed on server!"
ENDSSH

if [ $? -eq 0 ]; then
  echo ""
  echo "✅ Updates deployed successfully!"
  echo "🌐 Visit: https://consulum.meishicadi.com"
  echo ""
  echo "📋 Summary:"
  echo "   - Fixed local file serving in Utility.php"
  echo "   - User accounts and logos now pull from local storage when accessed locally"
else
  echo "❌ Error running deployment commands on server"
  exit 1
fi

