FROM damiandennis/yii2-rest-app-nginx:latest

ARG SERVER_NAME
ARG PHP_CONTAINER_NAME
ARG NODEJS_CONTAINER_NAME

COPY files/etc/nginx/conf.d/default.conf /etc/nginx/conf.d/default.tmpl
RUN envsubst '${SERVER_NAME} ${PHP_CONTAINER_NAME} ${NODEJS_CONTAINER_NAME}' </etc/nginx/conf.d/default.tmpl > /etc/nginx/conf.d/default.conf
