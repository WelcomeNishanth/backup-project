FROM nandueverurs/php7-alphine-mysql-pdo:v1.1

# copy your php source code from src folder
COPY src/ /var/www/html/

# new relic agent installation
RUN mkdir -p /opt/newrelic
WORKDIR /opt/newrelic
RUN wget http://download.newrelic.com/php_agent/release/newrelic-php5-7.5.0.199-linux-musl.tar.gz
RUN tar -zxf newrelic-php5-7.5.0.199-linux-musl.tar.gz 
ENV NR_INSTALL_SILENT true
#ENV NR_INSTALL_KEY c0a1e4c410b0da9f3ccdb915af587293f130ba2e
WORKDIR /opt/newrelic/newrelic-php5-7.5.0.199-linux-musl
RUN apk update
RUN apk add bash-doc
RUN apk add bash-completion
RUN ./newrelic-install install

#RUN apk --update upgrade && \
#    apk add curl ca-certificates && \
#    update-ca-certificates && \
#    rm -rf /var/cache/apk/*

# optional: install custom nginx config file
COPY nginx.site.conf /etc/nginx/conf.d/default.conf

RUN chown www-data:www-data -R /var/www/html \
  && sed -i -e 's/user\ \ nginx/user\ \ www-data/g' /etc/nginx/nginx.conf

# optional: install dependencies from composer.json
WORKDIR /var/www/html/
RUN composer update --no-dev --no-interaction

COPY supervisord.conf /etc/supervisord.conf

# TODO change this to log to host or to sumologic or other
RUN mkdir log && chown -R www-data. log
 
EXPOSE 80
# EXPOSE 80 443

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
