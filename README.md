# Wordpress Containerization Boilerplate

For use as a starter for any WordPress development using Docker Compose.

This is a repo based off of my [blog post](https://andrewwippler.com/2018/11/27/docker-izing-wordpress-for-kubernetes/) of making WordPress usable by modern technology.

## Use

```
docker-compose up
```
Visit [localhost:8080](http://localhost:8080/).

Admin credentials: (can be overridden with Environment Variables below)  
```
U: admin
P: wordpress
```

## Requirements

- Docker
- Docker Compose
- An internet connection

## Environment variables

### WORDPRESS_DB_NAME
Default Value: `wordpress`  
Purpose: DB name to use

### WORDPRESS_DB_HOST
Default Value: `db`  
Purpose: DB host to connect to

### WORDPRESS_DB_USER
Default Value: `root`  
Purpose: User to connect to the database
**Note: Do not use `root` in production**

### WORDPRESS_DB_PASSWORD
Default Value: `secretPASS`  
Purpose: Password of the user

### WORDPRESS_VERSION
Default Value: `latest`  
Purpose: Version of WordPress to install

### WORDPRESS_INSTALL
Default Value: `false`  
Purpose: Should wordpress be installed (good for first run).

### WORDPRESS_PLUGINS
Default Value: `wp-smushit,wp-fastest-cache,google-sitemap-generator,jetpack`  
Purpose: Comma separated list of plugins to install. Follow guide from [GTMetrix](https://gtmetrix.com/wordpress-optimization-guide.html).

### WORDPRESS_PLUGINS_INSTALL
Default Value: `false`  
Purpose: If plugins should be installed

### WORDPRESS_USER_NAME
Default Value: `admin`  
Purpose: Admin username

### WORDPRESS_USER_PASS
Default Value: `wordpress`  
Purpose: Admin password

### WORDPRESS_USER_EMAIL
Default Value: `nobody@example.org`  
Purpose: Admin email

### WORDPRESS_USER_URL
Default Value: `"http://localhost:8080"`  
Purpose: Site URL


## Headless WordPress

Folder structure can be used for a headless wordpress install. Create a new folder in the root and edit docker-compose to your liking.

### Rough example

```
$ git clone git@github.com:andrewwippler/WordPress-Containerization-Boilerplate.git
$ cd WordPress-Containerization-Boilerplate/
$ create-react-app frontend
$ cat > frontend/Dockerfile <<EOF
FROM node:8-alpine

RUN apk add --no-cache \
            alpine-sdk \
            python \
            bash \
            lcms2-dev \
            libpng-dev \
            gcc \
            g++ \
            make \
            autoconf \
            automake 
            
WORKDIR /usr/src/app
ADD . .

RUN npm install
RUN npm run build
CMD npm run start
EOF

$ cat >> docker-compose.yml <<EOF
  frontend:
    build: frontend
    volumes:
      - ./frontend:/usr/src/app
    ports:
      - 3000:3000
EOF
$ docker-compose up
```

Or something along those lines. The frontend would call the `http://wordpress` endpoint.

## Contributing

Pull requests are welcomed.

## License

MIT