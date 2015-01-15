#!/bin/sh


yum -y install mysql-server httpd php-fpm php-mysql php-pgsql php-devel php-pear gcc gcc-c++ autoconf automake unzip wget nano


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

mysql -uroot -e "set global net_buffer_length=1000000; set global max_allowed_packet=1000000000;"

MYSQL_RESULT=`mysqlshow --user=root db7199_wrdp | grep -v Wildcard | grep -o db7199_wrdp`

if [ "$MYSQL_RESULT" != "db7199_wrdp" ]; then
    echo "create database db7199_wrdp" | mysql -u root
fi

mysql -u root db7199_wrdp < /vagrant/provisioning/mysql/db7199_wrdp.sql
