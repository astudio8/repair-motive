ENV=$1

### set install path
SITENAME=repairmotive.com
INSTALLDIR=/websites/${SITENAME}

### functions
# return 1 if global command line program installed, else 0
# example
# echo "node: $(program_is_installed node)"
function program_is_installed {
	# set to 1 initially
	local return_=1
	# set to 0 if not found
	type $1 >/dev/null 2>&1 || { local return_=0; }
	# return value
	echo "$return_"
}

echo " "
echo "#######################"
echo "### File management ###"
echo "#######################"

# logs/ needs to be created and chmoded
echo "Provision logs directory."
mkdir -p ${INSTALLDIR}/logs

if [ -d ${INSTALLDIR}/logs ]; then
	chmod -R 777 ${INSTALLDIR}/logs
fi


echo " "
echo "###############################"
echo "### Updating and installing ###"
echo "###############################"

apt-get update

### install the basics
export DEBIAN_FRONTEND=noninteractive
apt-get install -y php5 php5-cli apache2 git curl mysql-server mysql-client php5-mysql vim libapache2-mod-php5 php5-curl

### composer

echo " "
echo "########################"
echo "### Composer install ###"
echo "########################"

if [ -f /usr/local/bin/composer ]; then
	echo "Composer already installed. Checking for updates..."
	composer self-update
else
	echo "Installing composer..."
	curl -sS https://getcomposer.org/installer | php
	mv composer.phar /usr/local/bin/composer
fi

cd ${INSTALLDIR}
composer install

### bower

echo " "
echo "#####################"
echo "### Bower install ###"
echo "#####################"

if [ $(program_is_installed bower) == 1 ]; then
	echo "bower is already installed"
else
	echo "bower is not yet installed. Installing..."
	apt-get install -y python-software-properties python g++ make
	add-apt-repository ppa:chris-lea/node.js
	apt-get update
	apt-get install -y nodejs
	apt-get autoremove
	npm install -g bower
fi

echo "Installing bower components"
cd ${INSTALLDIR}
bower --allow-root install

### mysql db setup

echo " "
echo "########################"
echo "### Database install ###"
echo "########################"

if [ -f ${INSTALLDIR}/deploy/init.sql ]; then
	if [ ${ENV} != 'prod' ]; then
		mysql < ${INSTALLDIR}/deploy/init.sql
	fi
fi

### Apache stuff

echo " "
echo "######################"
echo "### Apache install ###"
echo "######################"

# display all PHP errors
sed -i "s/display_errors = Off/display_errors = On/g" /etc/php5/apache2/php.ini

cp ${INSTALLDIR}/deploy/httpd.conf /etc/apache2/sites-available/${SITENAME}.conf
sed -i "s/sitename/${SITENAME}/g" /etc/apache2/sites-available/${SITENAME}.conf
a2dissite 000-default.conf
a2ensite ${SITENAME}
a2enmod rewrite
a2enmod expires
service apache2 restart