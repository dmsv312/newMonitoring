# lk.nsunet.ru

lk = personal office

## Installation

The preferred way is to install via Docker. If you don't have Docker, please install it by following [this instruction](https://docs.docker.com/engine/install/).

After that, execute this command:

```bash
cd bin
./install.sh
```

Edit Laravel settings in `www/backend/.env` file like a database connection and mail server settings.

## Start

Execute this command in `bin` directory to start container:

```bash
./start.sh
```

Now site is available on this link: http://localhost

## Stop

Execute this command in `bin` directory to stop container:

```bash
./stop.sh
```

## Other scripts

The following scripts from `bin` directory are calling, when project is already launched.

- artisan.sh
- build-frontend.sh
- composer.sh
- npm.sh

For example, if you need to create a Laravel model you can do it like this:

```bash
./artisan.sh make:model User
```
# monitoring
