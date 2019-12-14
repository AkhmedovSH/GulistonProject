restart pg
invoke-rc.d postgresql restart

cd /etc/nginx/sites-available/default
sudo nano /etc/nginx/sites-available/default
service nginx restart

cd /var/www/html/DolphinDelivery/public
