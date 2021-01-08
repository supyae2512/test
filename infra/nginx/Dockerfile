FROM nginx:latest

LABEL maintainer "5Degrees KlG"

RUN apt-get update && apt-get install -y ca-certificates

RUN rm -f /etc/nginx/conf.d/default.conf

COPY fastcgi_params /etc/nginx/fastcgi_params
COPY default.template /etc/nginx/conf.d/default.template

ADD server.cert /etc/ssl/server.cert
ADD server.key /etc/ssl/server.key

RUN update-ca-certificates
