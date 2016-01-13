#!/bin/sh
APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data' | grep -v root | head -1 | cut -d\  -f1`

setfacl -R -m u:"$APACHEUSER":rwX -m u:`whoami`:rwX app/cache app/logs web/
setfacl -dR -m u:"$APACHEUSER":rwX -m u:`whoami`:rwX app/cache app/logs web/


#echo "$1 parameters";

action="$1"

if [ -z "$1" ]; then
    action="-all"
fi

update_schema_with_fixtures()
{
    php app/console doctrine:database:drop --force
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
    php app/console hautelook_alice:doctrine:fixtures:load --append
}


all_run ()
{
    composer install
    update_schema_with_fixtures
    php app/console cache:clear
    php app/console cache:clear -e prod
    npm install
    ./node_modules/.bin/bower install --allow-root
    ./node_modules/.bin/gulp
}

case "$action" in
    -all)
        all_run
        ;;
    -uswf)
        update_schema_with_fixtures
        ;;
    -help)
        echo "Available options"
        echo "-all : All install"
        echo "-uswf : Update schema with fixtures"
        ;;
    *)
        echo "undefined action"
        ;;
esac