#!/bin/bash

    cd /var/www/l-extended_todo/ ;
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX /var/www/l-extended_todo/public/build
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX /var/www/l-extended_todo/public/build
    if [ $APPLY_ACL_TO_ROOT = 1 ] ; then
        setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX /var/www/l-extended_todo/public/build
        setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX /var/www/l-extended_todo/public/build
    fi
