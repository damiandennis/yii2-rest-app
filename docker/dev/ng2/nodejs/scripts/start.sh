#!/bin/bash

WEB_DIR=/var/www/html/web
NGX_DIR=$WEB_DIR/ng2
DEV_START='ng serve --host 0.0.0.0 --port=80'

# Need to generate angular setup, if does not exist already.
if [ ! -d $NGX_DIR ]; then
    cd $WEB_DIR

    # need dev packages to initiate build.
    NODE_ENV=development ng new ng2
    if [ $? -eq 1 ]; then
        echo "failed to install ngx"
        exit 1
    fi
    cd $NGX_DIR

    # need to release control from cli for advanced options.
    json -I -f package.json -e "this.scripts.start='${DEV_START}'"
    chown -R node:node $NGX_DIR
fi

cd $NGX_DIR
npm run start
