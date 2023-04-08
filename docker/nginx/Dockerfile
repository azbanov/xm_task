FROM nginx:1.23.4-alpine
COPY ./docker/nginx/config/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/config/default.conf.template /etc/nginx/templates/
WORKDIR /app
COPY . /app
CMD ["nginx", "-g", "daemon off;"]
