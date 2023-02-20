FROM nginx:latest
COPY --chown=nginx ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --chown=nginx ./public /var/www/html/public
#HEALTHCHECK --interval=1m --timeout=10s --retries=3 CMD curl --fail http://localhost/Ping || exit 1
