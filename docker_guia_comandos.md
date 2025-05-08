
# 🐳 Guía práctica de comandos Docker

## 🔻 Parar e eliminar contedores

```bash
# Parar todos os contedores
docker stop $(docker ps -a -q)

# Eliminar todos os contedores
docker rm -f $(docker ps -a -q)

# Eliminar todas as imaxes
docker rmi -f $(docker images -a -q)
```

---

## 📦 Traballar con imaxes

Sitios útiles:

- [Docker Hub](https://hub.docker.com/)
- [dive (inspección de capas)](https://github.com/wagoodman/dive)

### 🔍 Buscar imaxes

```bash
docker images
docker search nginx
docker search --help
docker search -f is-official=true nginx
docker search --filter=stars=20 nginx
docker search --filter stars=10 nginx
docker search agarciaf
```

### 📥 Descargar imaxes

```bash
docker pull nginx
docker pull httpd
docker pull mysql:5.7
docker pull mysql
docker pull agarciaf/intranet
```

### 🧾 Inspección de imaxes

```bash
docker history nginx
docker inspect nginx
docker rmi nginx
docker rmi -f nginx
docker rmi --help
```

Ruta local de almacenamento:

```bash
/var/lib/docker/image
```

---

## 🚀 Lanzar contedores (run/create)

```bash
docker run --help
docker create --help

docker run -dti httpd
docker run -dti --rm --name web1 httpd
docker run -dti --name web-2 httpd echo hola

docker create --rm --name web-create httpd
docker start web-create

docker container run -dti --rm --name web3 httpd
docker run -dti --name web2 nginx
docker run -dti nginx
docker run -dti --name bd2 -e MYSQL_ROOT_PASSWORD=000000 mysql

# Este dará erro
docker run -dti --name bd3 mysql
```

### 🖥️ Acceder ao contedor

```bash
docker exec -ti bd2 /bin/bash
# Dentro do contedor:
mysql -u root -p
```

```bash
docker exec -ti bd3 /bin/bash
docker inspect web1
```

---

## 📋 Ver contedores

```bash
docker ps -a
docker ps -l
docker container ls --help
docker container ls --all
```

---

## 🔄 Interacción e xestión de contedores

```bash
docker inspect web1
docker exec -ti web1 /bin/bash
docker stop nome-contenedor
docker start nome-contenedor
docker restart nome-contenedor
docker pause nome-contenedor
docker unpause nome-contenedor
docker rm nome-contenedor
docker rm -f nome-contenedor
docker logs nome-contenedor
docker logs --help
docker logs -f nome-contenedor
docker logs -t nome-contenedor
docker top contenedor1
exit
```

---

## 📊 Estatísticas e actualización de contedores

```bash
docker stats
docker update --help
docker update -m 512m --memory-swap -1 web1
```

### 🔧 Opcións de `docker update`

```text
Usage:  docker update [OPTIONS] CONTAINER [CONTAINER...]

Update configuration of one or more containers

Options:
  --blkio-weight uint16        Block IO weight (10–1000)
  --cpu-period int             CPU CFS period
  --cpu-quota int              CPU CFS quota
  --cpu-rt-period int          Real-time period
  --cpu-rt-runtime int         Real-time runtime
  -c, --cpu-shares int         CPU shares
  --cpus decimal               Number of CPUs
  --cpuset-cpus string         Allowed CPUs (e.g. 0-3)
  --cpuset-mems string         Allowed memory nodes
  --kernel-memory bytes        Kernel memory limit
  -m, --memory bytes           Memory limit
  --memory-reservation bytes   Soft memory limit
  --memory-swap bytes          Swap limit ('-1' = unlimited)
  --pids-limit int             PIDs limit (-1 = unlimited)
  --restart string             Restart policy
```

```bash
docker run -dti --name web1 httpd
docker ps -a
docker update -m 256m --memory-swap -1 web1
```

---

## 🔁 Políticas de reinicio

```text
--restart:

off              → Non reiniciar
on-failure       → Reiniciar só en caso de erro
always           → Reiniciar sempre que se pare
unless-stopped   → Reiniciar salvo que se detivese explicitamente
```

```bash
docker run -dtiP --name web5 --restart always nginx
docker run -dti -p 8084:80 --name web1 --restart unless-stopped httpd
```

---

## 🧹 Outras opcións útiles

```text
--rm       → Elimina o contedor ao deterse
-w         → Directorio de traballo dentro do contedor
-u         → Usuario que executa os comandos no contedor
```

---

## 🌐 Publicar servizos e NAT de portos

```bash
docker run -dtiP --name web11 httpd
docker run -dti -p 8085:80 --name web2 nginx
docker run -dti -p 3307:3306 --name bd2 -e MYSQL_ROOT_PASSWORD=000000 mysql

iptables -t nat -L DOCKER -v -n
docker ps -a
docker port web11
```

Accede desde o navegador:

```
http://192.168.33.10:PUERTO
```

```text
-P  → Publica todos os portos dispoñibles aleatoriamente
-p  → Publicación manual dun porto (erro se xa está en uso)
```

---

## 🎯 Exposición de rangos de portos

```Dockerfile
EXPOSE 7000-8000
```

```bash
docker run --expose=7000-8000
docker run -p 7000-8000:7000-8000 imaxe-docker
```

# 🐳 Docker: Copias, conversións e publicación de imaxes

## 🚀 Lanzamento de contedores

```bash
docker run -dtiP --name web2-lab httpd
docker run -dti --name web3 -p 81:80 nginx
```

### 🔍 Ver contedores en execución

```bash
docker ps -a
iptables -t nat -L DOCKER -v -n
```

IP do contedor:

```
192.168.33.10
```

---

## 💾 Copias de seguridade de imaxes

```bash
docker images | grep httpd
docker save -o httpd.tar httpd
docker rmi httpd
docker images
```

### 🔁 Importar copia de seguridade

```bash
docker load -i httpd.tar
docker images | grep httpd
```

---

## 🏗️ Converter un contedor nunha imaxe

```bash
docker run -dtiP --name centos6 docker.io/nickistre/centos-lamp
docker exec -ti centos6 /bin/bash
docker commit -m "Intranet con joomla" centos6 intranet-martes31
docker run -ditP --name intranet1 intranet-martes31
```

Exemplo de saída de `docker ps -l`:

```
CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS              PORTS
f0bd287fae86        intranet            "supervisord -n"    7 seconds ago       Up 6 seconds        0.0.0.0:32774->22/tcp, 0.0.0.0:32773->80/tcp
```

### 🏷️ Etiquetar e executar imaxe

```bash
docker tag intranet intranet-martes31:v1
docker run -ditP --name intranet-martes31:v1
```

### 📂 Volumes montados

```bash
docker run -ditP --name intranet1 -v /bd2:/var/lib/mysql -v /web2:/var/www/html intranet-martes31
docker run -ditP --name intranet1 -v bd2:/var/lib/mysql -v web2:/var/www/html intranet-martes31
```

---

## 📤 Publicar unha imaxe en Docker Hub
Precisades unha conta de *DockerHub*. Substituir "usuario" polo voso usuario de DockerHub.

```bash
# Etiquetado previo
docker tag intranet-lunes19:v1 usuario/intranet

# Subida ao repositorio
docker login
docker push usuario/intranet
docker logout
```

### Outros comandos útiles

```bash
docker pull usuario/intranet
docker tag intranet miusuariodockerhub/intranet
cat /root/.docker/config.json
docker search usuario
docker search miusuariodockerhub
```

---

## 🖼️ Publicar nginx personalizado

```bash
docker tag nginx usuario/nginx-lab
docker login
cat /root/.docker/config.json
docker push usuario/nginx-lab
docker logout
docker search usuario
```
