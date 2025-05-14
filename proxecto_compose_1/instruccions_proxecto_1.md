# Proxecto Docker: Servidor Web + Base de Datos

## Estrutura do proxecto

```
meu-proxecto/
├── docker-compose.yml
├── nginx/
│   └── default.conf
├── php/
│   └── Dockerfile
│   └── index.php
└── db/ (creada automaticamente polo volume)
```

## `docker-compose.yml`

```yaml
version: '3.8'

services:
  nginx:
    image: nginx:alpine
    ports:
      - "8080:80"
    volumes:
      - ./php:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php

  php:
    build: ./php
    volumes:
      - ./php:/var/www/html

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
  db_data:
```

## `php/Dockerfile`

```Dockerfile
FROM php:8.1-fpm

RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /var/www/html
```

## `php/index.php`

```php
<?php
$mysqli = new mysqli("db", "usuario", "contrasinal", "proxecto");

if ($mysqli->connect_error) {
    die("Erro de conexión: " . $mysqli->connect_error);
}
echo "Conexión á base de datos correcta!<br>";

$result = $mysqli->query("SHOW DATABASES;");
while ($row = $result->fetch_assoc()) {
    echo "Base de datos: " . $row['Database'] . "<br>";
}
?>
```

## `nginx/default.conf`

```nginx
server {
    listen 80;
    server_name localhost;

    root /var/www/html;
    index index.php index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME /var/www/html$fastcgi_script_name;
    }
}
```

## Iniciar o proxecto

```bash
docker-compose up -d --build
```

Accede en navegador a: [http://localhost:8080](http://localhost:8080)
