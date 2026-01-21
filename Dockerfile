FROM php:8.5.2-fpm-alpine3.23
WORKDIR /C:/projeto

COPY requirements.txt ./
RUN yarn install --production

COPY . .
EXPOSE 81

RUN 
USER projeto