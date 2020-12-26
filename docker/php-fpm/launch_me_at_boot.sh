#!/bin/bash

#LOCKDIR="/var/www/lock"
#LOCKFILE=${LOCKDIR}"/launch_me_at_boot.lock"
#
#if [ ! -e ${LOCKDIR} ]; then
#    mkdir -p ${LOCKDIR};
#fi

#if [ ! -e ${LOCKFILE} ]; then
    /bin/bash /var/www/make-pems.sh
    /bin/bash /var/www/composer_migrate.sh    

#    touch ${LOCKFILE};
#else
#    rm ${LOCKFILE};
#    # Ah, ha, ha, ha, stayin' alive...
##    while true; do :; done & kill -STOP $! && wait $!
#
#fi

/usr/local/sbin/php-fpm
