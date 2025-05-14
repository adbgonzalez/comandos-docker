# WordPress como alternativa

Neste proxecto imos despregar WordPress cunha base de datos MariaDB. A seguinte configuración engade outro `docker-compose.yml` para iso.

## `docker-compose-wordpress.yml`

```yaml
version: '3.8'

services:
  wordpress:
    image: wordpress:latest
    ports:
      - "8081:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: usuario
      WORDPRESS_DB_PASSWORD: contrasinal
      WORDPRESS_DB_NAME: proxecto
    volumes:
      - wp_data:/var/www/html
    depends_on:
      - db

  db:
    image: mariadb:10.5
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: rootpass
      MYSQL_DATABASE: proxecto
      MYSQL_USER: usuario
      MYSQL_PASSWORD: contrasinal
    volumes:
      - db_data:/var/lib/mysql

volumes:
  wp_data:
  db_data:
```

## Iniciar WordPress

```bash
docker-compose -f docker-compose-wordpress.yml up -d
```

Accede a WordPress en: [http://localhost:8081](http://localhost:8081)

Segue o asistente de instalación e xa terás WordPress listo para usar.
