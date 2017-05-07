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
        echo "failed to install ng2"
        exit 1
    fi
    cd $NGX_DIR

    # change port and allowed host for package.
    json -I -f package.json -e "this.scripts.start='${DEV_START}'"
    chown -R node:node $NGX_DIR
fi

cd $NGX_DIR
npm run build -- --prod
chown -R node:node $NGX_DIR/dist

cd $WEB_DIR
if [ ! -d node_modules ]; then
    npm install
    chown -R node:node $WEB_DIR/node_modules
fi
pm2 start -x index.js --no-daemon --watch
