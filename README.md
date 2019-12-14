restart pg
invoke-rc.d postgresql restart

cd /etc/nginx/sites-available
sudo nano /etc/nginx/sites-available/default
service nginx restart

cd /var/www/html/site/public


cd /etc/php/7.2/cli