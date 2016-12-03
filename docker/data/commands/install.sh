# return 1 if global command line program installed, else 0
# example
# echo "node: $(program_is_installed node)"
function program_is_installed() {
  # set to 1 initially
  local return_=1
  # set to 0 if not found
  type $1 >/dev/null 2>&1 || { local return_=0; }
  # return value
  echo "$return_"
}

# return 1 if local npm package is installed at /usr/local/lib/node_modules, else 0
# example
# echo "gruntacular : $(npm_package_is_installed gruntacular)"
function npm_package_is_installed(){
  # set to 1 initially
  local return_=1
  # set to 0 if not found
  ls /usr/local/lib/node_modules | grep $1 >/dev/null 2>&1 || { local return_=0; }
  # return value
  echo "$return_"
}

source $(dirname $0)/../variables.sh

echo '---------------------------------------------------------------------------';
echo '- INIT APP '$APP_NAME;
echo '---------------------------------------------------------------------------';


if [ ! -f /tmp/apt-update.lock ]; then
    apt-get update
    touch /tmp/apt-update.lock
fi
DEBIAN_FRONTEND=noninteractive 
declare -a AppList=("wget" "cron" "curl" "php7.0" "php-mbstring" "php7.0-imagick" "php7.0-mysql" "mysql-client" "mysql-server" "zip" "unzip" "nano" "git" "nodejs" "npm" "default-jre")
for i in "${AppList[@]}"
    do
        if [ ! -f /tmp/$i ]; then
            if [ "$(program_is_installed $i)" != "1" ]; then

                if [ "$i" == "mysql-server" ]; then
                    debconf-set-selections <<< 'mysql-server mysql-server/root_password password '$DB_PASSWORD
                    debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password '$DB_PASSWORD
                fi

                if [ "$i" == "php7.0" ]; then
                    echo '---------------------------------------------------------------------------';
                    echo '- Install locales & add ppa for php';
                    echo '---------------------------------------------------------------------------';
                    apt-get install -y locales
                    locale-gen UTF-8
                    echo "deb http://packages.dotdeb.org jessie all" | tee -a /etc/apt/sources.list
                    echo "deb-src http://packages.dotdeb.org jessie all" | tee -a /etc/apt/sources.list
                    wget https://www.dotdeb.org/dotdeb.gpg -P /tmp/dotdeb.gpg
                    apt-key /tmp/add dotdeb.gpg
                    apt-get update
                fi
                echo '---------------------------------------------------------------------------';
                echo '- Install '$i;
                echo '---------------------------------------------------------------------------';
                apt-get install -q -y --force-yes $i
                if [ "$i" == "php7.0" ]; then
                    echo 'ServerName '$DOMAIN_NAME >> /etc/apache2/apache2.conf
                    a2enmod rewrite
                    service apache2 start
                    update-rc.d -f apache2 enable
                fi
                if [ "$i" == "mysql-server" ]; then
                    mkfifo /var/run/mysqld/mysqld.sock
                    chown -R mysql /var/run/mysqld
                    chmod 0777 /var/run/mysqld/mysqld.sock

                    service mysql start
                fi
            fi
            touch /tmp/$i
        fi
done
        
        mysqlHost='%';
        mysql -u root -p$DB_PASSWORD -Bse "CREATE DATABASE "$DB_NAME";" &>/dev/null
        mysql -u root -p$DB_PASSWORD -Bse "CREATE USER '$DB_USER'@'$mysqlHost' IDENTIFIED BY '$DB_PASSWORD';"  &>/dev/null
        mysql -u root -p$DB_PASSWORD -Bse "USE "$DB_NAME";"  &>/dev/null
        mysql -u root -p$DB_PASSWORD -Bse "GRANT ALL privileges on $DB_NAME.* to "$DB_USER"@'$mysqlHost' identified by '$DB_PASSWORD' WITH GRANT OPTION;"  &>/dev/null
        mysql -u root -p$DB_PASSWORD -Bse "GRANT RELOAD,PROCESS ON *.* TO '$DB_USER'@'$mysqlHost';"  &>/dev/null
        mysql -u root -p$DB_PASSWORD -Bse "FLUSH PRIVILEGES;"  &>/dev/null

 
        
    if ! grep "sql_mode" /etc/mysql/my.cnf -R
        then
            echo '[mysqld]' >> /etc/mysql/my.cnf
            echo 'sql_mode="STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"' >> /etc/mysql/my.cnf
    fi
        #search_mn='skip-external-locking'
        #replace_mn='#skip-external-locking'
        #sed -i "s/${search_mn}/${replace_mn}/g" /etc/mysql/my.cnf
        #sed -i "s/${search_mn}/${replace_mn}/g" /etc/mysql/mysql.conf.d/mysqld.cnf
        search_mbind='127.0.0.1'
        replace_mbind='0.0.0.0'
        sed -i "s/${search_mbind}/${replace_mbind}/g" /etc/mysql/my.cnf
        sed -i "s/${search_mbind}/${replace_mbind}/g" /etc/mysql/mysql.conf.d/mysqld.cnf
        service mysql restart



declare -a NpmAppsList=("less" "less-plugin-clean-css")
for i in "${NpmAppsList[@]}"
    do
        if [ "$(npm_package_is_installed $i)" != "1" ]; then
            echo '---------------------------------------------------------------------------';
            echo '- Install npm app : '$i;
            echo '---------------------------------------------------------------------------';
            npm install -g $i
        fi
done
ln -s /usr/bin/nodejs /usr/bin/node
    

echo '---------------------------------------------------------------------------';
echo '- configure virtual host';
echo '---------------------------------------------------------------------------';

if [ ! -f /apps/$APP_NAME/docker/data/logs/access.log ]; then
    mkdir /apps/$APP_NAME/docker/data/logs
    chmod -R 777 /apps/$APP_NAME/docker/data/logs
    cd /apps/$APP_NAME/docker/data/logs && touch access.log && touch error.log  
    chmod -R 777 /apps/$APP_NAME/docker/data/logs
fi


if ! grep "$DOMAIN_NAME" /etc/hosts -R
    then
        echo "127.0.0.1 "$DOMAIN_NAME" www."$DOMAIN_NAME >> /etc/hosts >/dev/null 2>/dev/null
fi

if [ -f /etc/apache2/sites-available/000-default.conf ]; then
    rm  /etc/apache2/sites-available/000-default.conf
    rm  /etc/apache2/sites-enabled/000-default.conf
fi
if [ -f /etc/apache2/sites-available/$APP_NAME.conf ]; then
    rm /etc/apache2/sites-available/$APP_NAME.conf
fi
cp /apps/$APP_NAME/docker/data/sites-available/000-default.conf /etc/apache2/sites-available/$APP_NAME.conf
 
search_domain_name='DOMAIN_NAME'
replace_domain_name=$DOMAIN_NAME
sed -i "s/${search_domain_name}/${replace_domain_name}/g" /etc/apache2/sites-available/$APP_NAME.conf

search_app_name='APP_NAME'
replace_app_name=$APP_NAME
sed -i "s/${search_app_name}/${replace_app_name}/g" /etc/apache2/sites-available/$APP_NAME.conf
a2ensite $APP_NAME
service apache2 restart


id -u $APP_NAME &>/dev/null || useradd $APP_NAME
if [ ! -f /usr/local/bin/composer ]; then
    echo '---------------------------------------------------------------------------';
    echo '- Install composer';
    echo '---------------------------------------------------------------------------';
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    composer global require "fxp/composer-asset-plugin:^1.2.0"
fi

echo '---------------------------------------------------------------------------';
echo '- install yii2 core';
echo '---------------------------------------------------------------------------';
chmod 777 /apps/$APP_NAME
chmod -R 777 /apps/$APP_NAME/web/assets
chmod -R 777 /apps/$APP_NAME/runtime
if [ ! -d /apps/$APP_NAME/web/public/uploads ]; then
    mkdir /apps/$APP_NAME/web/public/uploads
    chmod -R 777 /apps/$APP_NAME/web/public/uploads
fi
rm -rf ~/.composer/cache*
cd /apps/$APP_NAME && composer install




echo '---------------------------------------------------------------------------';
echo '- configure app db';
echo '---------------------------------------------------------------------------';

if [ ! -f /apps/$APP_NAME/config/db.php ]; then
    cp /apps/$APP_NAME/config/db.example.php /apps/$APP_NAME/config/db.php
else
    rm /apps/$APP_NAME/config/db.php
    cp /apps/$APP_NAME/config/db.example.php /apps/$APP_NAME/config/db.php
fi

search_dbn='DB_NAME'
replace_dbn=$DB_NAME
sed -i "s/${search_dbn}/${replace_dbn}/g" /apps/$APP_NAME/config/db.php

search_dbu='DB_USER'
replace_dbu=$DB_USER
sed -i "s/${search_dbu}/${replace_dbu}/g" /apps/$APP_NAME/config/db.php

search_dbp='DB_PASSWORD'
replace_dbp=$DB_PASSWORD
sed -i "s/${search_dbp}/${replace_dbp}/g" /apps/$APP_NAME/config/db.php

search_appn='APP_NAME'
replace_appn=$APP_NAME
sed -i "s/${search_appn}/${replace_appn}/g" /apps/$APP_NAME/config/db.php

search_appp='MYSQL_PORT'
replace_appp=$MYSQL_PORT
sed -i "s/${search_appp}/${replace_appp}/g" /apps/$APP_NAME/config/db.php

chmod 777 /apps/$APP_NAME/yii
cd /apps/$APP_NAME/ && ./yii migrate --interactive=0

echo '---------------------------------------------------------------------------';
echo '- configure cron jobs';
echo '---------------------------------------------------------------------------';
if [ ! -f /var/spool/cron/crontabs/$APP_NAME ]; then
    cp /apps/$APP_NAME/docker/data/crontabs/app /var/spool/cron/crontabs/$APP_NAME
else
    rm /var/spool/cron/crontabs/$APP_NAME
    cp /apps/$APP_NAME/docker/data/crontabs/app /var/spool/cron/crontabs/$APP_NAME
fi
search_appn='APP_NAME'
replace_appn=$APP_NAME
sed -i "s/${search_appn}/${replace_appn}/g" /var/spool/cron/crontabs/$APP_NAME
crontab /var/spool/cron/crontabs/$APP_NAME
service cron start


echo '---------------------------------------------------------------------------';
echo '- Installing '$APP_NAME' is completed';
echo '- SITE ADDRESS: http://localhost:'$HTTP_PORT'/ OR http://'$DOMAIN_NAME':'$HTTP_PORT'/';
echo '- DATABASE NAME: '$DB_NAME;
echo '- DATABASE USER NAME: '$DB_USER;
echo '- DATABASE PASSWORD: '$DB_PASSWORD;
echo '- DATABASE PORT: '$MYSQL_PORT;
echo '---------------------------------------------------------------------------';
