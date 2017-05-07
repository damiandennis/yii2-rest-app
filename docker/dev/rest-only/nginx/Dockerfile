FROM damiandennis/yii2-rest-app-nginx:latest

ARG SERVER_NAME
ARG PHP_CONTAINER_NAME

COPY files/etc/nginx/conf.d/default.conf /etc/nginx/conf.d/default.tmpl
RUN envsubst '${PHP_CONTAINER_NAME} ${SERVER_NAME}' </etc/nginx/conf.d/default.tmpl > /etc/nginx/conf.d/default.conf
