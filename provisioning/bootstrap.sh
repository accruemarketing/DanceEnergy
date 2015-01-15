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

MYSQL_RESULT=`mysqlshow --user=root  db7199_wrdp | grep -v Wildcard | grep -o db7199_wrdp`

if [ "$MYSQL_RESULT" != "db7199_wrdp" ]; then
    echo "create database db7199_wrdp" | mysql -u root 
fi

mysql -u root db7199_wrdp < /vagrant/provisioning/mysql/db7199_wrdp.sql
#Set local new urls
mysql -u root -e "use db7199_wrdp; UPDATE wp_options SET option_value = replace(option_value, 'http://dancenergy.zenutech.com', 'http://localhost.localdomain:8080'); UPDATE wp_posts SET guid = replace(guid, 'http://dancenergy.zenutech.com','http://localhost.localdomain:8080'); UPDATE wp_posts SET post_content = replace(post_content, 'http://dancenergy.zenutech.com', 'http://localhost.localdomain:8080'); UPDATE wp_postmeta SET meta_value = replace(meta_value, 'http://dancenergy.zenutech.com', 'http://localhost.localdomain:8080'); UPDATE wp_options SET option_value = replace(option_value, '/dancenergy.zenutech.com/', '/localhost.localdomain:8080/');"