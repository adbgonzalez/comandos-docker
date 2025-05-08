
# 💾 Traballo con Volumes en Docker

📚 Referencia oficial: [Volumes en Docker](https://docs.docker.com/storage/volumes/)

Un volume en Docker é un directorio especial asignado a un contedor para persistir datos, aloxado en:

```
/var/lib/docker/volumes
```

---

## 📦 Comandos básicos de volume

```bash
docker volume

Usage:  docker volume COMMAND

Manage volumes

Commands:
  create      Create a volume
  inspect     Display detailed information on one or more volumes
  ls          List volumes
  prune       Remove all unused local volumes
  rm          Remove one or more volumes
```

```bash
docker volume ls
```

Exemplo de saída:

```
DRIVER               VOLUME NAME
local                0bf977f8de99b90fa1f2d7250415700cad4a6aaeff80c1e0c7bbd934aecaac19
```

---

## 🔀 Usar `-v` ou `--mount`

### 🔹 Iniciar un contedor con volume

```bash
docker run -d   --name devtest   --mount source=myvol2,target=/app   nginx:latest

docker run -d   --name devtest   -v myvol2:/app   nginx:latest
```

---

## 🔧 Laboratorio: WordPress + MySQL con volume persistente

```bash
# Parar e eliminar contedores existentes
docker stop $(docker ps -a -q)
docker rm -f $(docker ps -a -q)

# Crear directorio para persistencia
mkdir /bd

# Lanzar contedor MySQL con volume
docker run -dti --name servidor_mysql -e MYSQL_ROOT_PASSWORD=000000 -v /bd:/var/lib/mysql mysql:5.7

# Lanzar WordPress conectado a MySQL
docker run -dti --name servidor_wp -p 8000:80 --link servidor_mysql:mysql wordpress:5.6.2-php7.3

# Acceder desde navegador:
http://192.168.33.10:8000/
```

---

## 1️⃣ Primeira forma de usar volumes (automáticos)

```bash
docker run -dtiP --name centos6-lanp -v /var/www/html docker.io/nickistre/centos-lamp
docker inspect centos6-lanp
docker volume inspect <nome_volume>
```

Ruta típica:

```
/var/lib/docker/volumes/<nome_volume>/_data
```

---

## 2️⃣ Segunda forma: volume ligado a directorio local

```bash
mkdir /web
cd /web
echo "prueba de volumen" > index.html

# Volume con acceso total
docker run -dtiP --name centos6-prueba-web -v /web:/var/www/html docker.io/nickistre/centos-lamp

# Volume só lectura
docker run -dtiP --name centos6-prueba-web2 -v /web:/var/www/html:ro docker.io/nickistre/centos-lamp
```

---

## 3️⃣ Terceira forma: volume nomeado + volume ligado

```bash
docker volume create --name webapp
docker volume ls
docker volume inspect webapp
```

Engadir ficheiro no volume:

```bash
echo "prueba de volumen" > /var/lib/docker/volumes/webapp/_data/index.html
```

Lanzar contedor con volumes:

```bash
mkdir /web
docker run -dtiP --name centos6-pruebacreacion3 -v webapp:/var/www/html -v /web:/datos docker.io/nickistre/centos-lamp
docker inspect centos6-pruebacreacion3 | grep -A 5 -i mounts
docker exec -ti centos6-pruebacreacion3 /bin/bash
ls /datos
```

Eliminar contedores en estado "Exited":

```bash
docker rm $(docker ps -a -f status=exited -q)
```

---

## 🧪 Exemplo con volume e `--mount`

```bash
docker run -d   --name devtest   --mount source=myvol2,target=/app   nginx:latest

docker volume ls
docker volume inspect myvol2
```

Ver montaxe no contedor:

```bash
docker exec -ti devtest bash
df -h
```

---

## 🔐 Volume en modo lectura

```bash
docker run -d   --name=nginxtest   --mount source=nginx-vol,destination=/usr/share/nginx/html,readonly   nginx:latest

docker run -d   --name=nginxtest   -v nginx-vol:/usr/share/nginx/html:ro   nginx:latest
```
