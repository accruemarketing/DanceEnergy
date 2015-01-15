#!/bin/sh


yum -y install mysql-server httpd php-fpm php-mysql php-pgsql php-devel php-pear gcc gcc-c++ autoconf automake unzip wget nano at php-xmlwriter


sed -i 's/html_errors = Off/html_errors = On/g' /etc/php.ini

chkconfig httpd on
chkconfig mysqld on

sed -i 's/KeepAlive Off/KeepAlive On/g' /etc/httpd/conf/httpd.conf
sed -i 's/AllowOverride None/AllowOverride All/g' /etc/httpd/conf/httpd.conf
sed -i 's/#EnableSendfile off/EnableSendfile off/g' /etc/httpd/conf/httpd.conf

/etc/init.d/mysqld start
/etc/init.d/httpd start

service iptables stop
chkconfig iptables off

mysql -u root -e "set global net_buffer_length=1000000; set global max_allowed_packet=1000000000;"

MYSQL_RESULT=`mysqlshow --user=root  dev_site | grep -v Wildcard | grep -o dev_site`

if [ "$MYSQL_RESULT" != "dev_site" ]; then
    echo "create database dev_site" | mysql -u root 
fi

mysql -u root dev_site < /vagrant/provisioning/mysql/db7199_wrdp.sql
#Set local new urls
mysql -u root -e "use dev_site; update wp_options set option_value = 'http://localhost.localdomain:8080' where option_id = 1; update wp_options set option_value = 'http://localhost.localdomain:8080' where option_id = 1464; update wp_options set option_value = 'http://localhost.localdomain:8080' where option_id = 36;"