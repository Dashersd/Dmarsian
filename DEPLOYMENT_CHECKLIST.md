# DigitalOcean Deployment Checklist

## Critical Steps to Fix 500 Internal Server Error

### Step 1: Pull Latest Changes
```bash
cd /var/www/your-app-directory
git pull origin main
```

### Step 2: Check Apache Error Log (MOST IMPORTANT)
```bash
sudo tail -50 /var/log/apache2/error.log
```
**This will show you the EXACT error!** Look for:
- PHP syntax errors
- Missing files
- Permission denied
- Database connection errors

### Step 3: Test Basic PHP
Visit in browser: `https://yourdomain.com/simple_test.php`
- If this works: PHP is fine, issue is in your code
- If this fails: PHP configuration problem

### Step 4: Test Full Diagnostic
Visit: `https://yourdomain.com/test.php`
This will show you exactly what's wrong.

### Step 5: Verify Critical Files Exist
```bash
ls -la config.php env-loader.php .env db_connect.php
```

### Step 6: Create .env File (If Missing)
```bash
cp env.example .env
nano .env
# Add your database credentials:
# DB_HOST=localhost
# DB_USER=your_user
# DB_PASS=your_password
# DB_NAME=capstone_db
chmod 600 .env
```

### Step 7: Install Composer Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### Step 8: Set File Permissions
```bash
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 777 uploads/
sudo chmod 600 .env
```

### Step 9: Test Database Connection
```bash
php -r "require 'config.php'; var_dump(\$conn->connect_error);"
```

### Step 10: Check PHP Syntax
```bash
php -l config.php
php -l env-loader.php
php -l get_posts.php
php -l webpage.php
```

### Step 11: Enable Apache Modules
```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo systemctl restart apache2
```

### Step 12: Temporarily Disable .htaccess (If Needed)
```bash
mv .htaccess .htaccess.bak
sudo systemctl restart apache2
# Test if site loads
# If it works, the issue is in .htaccess
```

## Common Error Messages & Fixes

### "Call to undefined function mysqli_connect()"
**Fix:** Install PHP MySQL extension
```bash
sudo apt install php8.2-mysqli
sudo systemctl restart apache2
```

### "Failed to open stream: Permission denied"
**Fix:** Set proper ownership
```bash
sudo chown -R www-data:www-data /var/www/your-app-directory
```

### "Database connection failed"
**Fix:** Check .env file and database credentials
```bash
# Verify database exists
mysql -u your_user -p -e "SHOW DATABASES;"

# Verify user has permissions
mysql -u root -p
GRANT ALL PRIVILEGES ON capstone_db.* TO 'your_user'@'localhost';
FLUSH PRIVILEGES;
```

### "Class 'PHPMailer\PHPMailer\PHPMailer' not found"
**Fix:** Install Composer dependencies
```bash
composer install --no-dev
```

### "Parse error: syntax error"
**Fix:** Check the file mentioned in error log
```bash
php -l filename.php
```

## Quick Test Sequence

1. `simple_test.php` - Basic PHP test
2. `test.php` - Full diagnostic
3. `webpage.php` - Main page
4. `get_posts.php?category=achievement` - API endpoint

## After Fixing

1. Remove test files:
   ```bash
   rm simple_test.php test.php diagnostic.php
   ```

2. Restore .htaccess if disabled:
   ```bash
   mv .htaccess.bak .htaccess
   ```

3. Run optimization:
   ```bash
   ./optimize.sh
   ```

4. Monitor error log:
   ```bash
   sudo tail -f /var/log/apache2/error.log
   ```

