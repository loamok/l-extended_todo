#!/bin/bash

LOCKDIR="/var/www/lock"
LOCKFILE=${LOCKDIR}"/launch_me_at_boot.lock"

if [ ! -e ${LOCKDIR} ]; then
    mkdir -p ${LOCKDIR};
fi

if [ ! -e ${LOCKFILE} ]; then
    /bin/bash /var/www/make-acls.sh
    npm install --force
    npm rebuild node-sass
    npm run dev

    touch ${LOCKFILE};
else
    rm ${LOCKFILE};
    echo "Ah, ha, ha, ha, stayin' alive...\n"
    while true; do :; done & kill -STOP $! && wait $!

fi

