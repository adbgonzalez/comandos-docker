# 🐳 Contedores  
**Posta en Produción Segura – Unidade 4**

## 📋 Índice
- Introdución
- Motivación e historia
- Vantaxes
- Inconvenientes
- Ciberseguridade e contedores
- Tecnoloxías actuais
- Comparativa Docker/Podman

---

## 🕰️ Motivación e Historia

- Resolver incompatibilidades entre contornos.
- Primeiros pasos:
  - `chroot` (1979)
  - FreeBSD Jails (2000)
  - OpenVZ (2005)
  - LXC (2008)
  - LXD (2015)
- Docker populariza os contedores en 2013.

---

## ✅ Vantaxes dos contedores

- Lixeireza e velocidade
- Portabilidade entre contornos
- Illamento de procesos
- Escalabilidade e integración con CI/CD

---

## ⚠️ Inconvenientes dos contedores

- Menor illamento cós hipervisores
- Posibles riscos de seguridade
- Xestión complexa de volumes/persistencia
- Dependencia do kernel Linux

---

## 🔐 Ciberseguridade e contedores

**Riscos:**
- Container escape
- Imaxes inseguras
- Permisos excesivos

**Medidas:**
- Escaneo con `Trivy`, `Clair`
- Execución sen root
- Uso de `AppArmor`, `SELinux`

---

## 🔧 Tecnoloxías actuais

- **Docker**: contedores lixeiros e populares
- **Podman**: alternativa sen daemon
- **LXC / LXD**: máis preto dunha VM
- **Kubernetes**: orquestración
- **containerd, CRI-O**: motores backend

---

## ⚖️ Comparativa Docker vs Podman

---

## 🔍 Conceptos básicos

**Contedor** → Proceso empaquetado illado  
- Instancia executable dunha imaxe  
- Pode crearse, iniciarse, deter, moverse ou eliminarse  
- Execución local ou na nube  
- Portable e illado

**Imaxe** → Modelo para crear contedores  
- Sistema de ficheiros illado  
- Orixe: DockerHub ou Dockerfile

---

## 💻 Instalación de Docker

### En Windows
- Instalar WSL
- Instalar Docker Desktop
- Engadir usuario ao grupo `docker-users`

```bash
wsl --install -d ubuntu
```

---

### En Ubuntu

```bash
sudo apt-get update
sudo apt-get install ca-certificates curl gnupg lsb-release

curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

sudo apt-get update
sudo apt-get install docker-ce docker-ce-cli containerd.io

sudo usermod -aG docker $USER
```

Verificación:

```bash
docker run hello-world
```

---

## 📦 Comandos básicos de Docker

### 📥 Imaxes

```bash
docker pull nome_imaxe
docker image ls
docker image rm nome_imaxe
```

### 🚀 Contedores

```bash
docker run nome_imaxe
docker run -d nome_imaxe
docker run --name nome_contedor nome_imaxe
docker run -p 8080:80 nome_imaxe
```

```bash
docker ps -a
docker start nome
docker stop nome
docker rm -f nome
```

---

### 🌐 Redes

```bash
docker network ls
docker network create nome
docker network rm nome
docker network inspect nome
```

---

### 💾 Volumes

- Xestionados por Docker (`/var/lib/docker/volumes/`)
- **Bind mounts**: directorio do host → contedor
- **tmpfs**: montaxe temporal en RAM (Linux)

```bash
docker volume create nome
docker volume rm nome
docker volume inspect nome
docker run -v nome:/ruta nome_imaxe
docker run -v /host:/contedor nome_imaxe
```

---

## 🛠️ Dockerfile

```Dockerfile
FROM debian
RUN apt-get update && apt-get install -y apache2 && apt-get clean && rm -rf /var/lib/apt/lists/*
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data
ENV APACHE_LOG_DIR=/var/log/apache2
EXPOSE 80
ADD ["index.html", "/var/www/html/"]
ENTRYPOINT ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
```

Construír a imaxe:

```bash
docker build -t nome_imaxe:tag .
```

---

## 📦 Docker Compose

- Arquivo `docker-compose.yml`
- Define múltiples contedores e servizos

**Etiquetas comúns:**
- `image`, `container_name`, `hostname`
- `environment`, `ports`, `volumes`, `networks`, `command`

**Outras seccións:**
- `volumes`: definir volumes globais
- `networks`: definir redes

**Comandos:**

```bash
docker compose up
docker compose up -d
docker compose -f arquivo.yml up
docker compose down
docker compose -f arquivo.yml down
docker logs -f nome_contedor
```

---

## 📚 Referencias

- [Podman Docs](https://docs.podman.io/en/latest/Reference.html)  
- [Docker primeiros pasos](https://docs.docker.com/get-started/)  
- [Docker Compose](https://docs.docker.com/compose/gettingstarted/)  
- [Dockerfile](https://docs.docker.com/reference/dockerfile/)