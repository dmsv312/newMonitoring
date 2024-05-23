
# Install frontend part
docker run --rm -v $PWD\..\www\frontend:/usr/src/app -w /usr/src/app node:lts-alpine npm install

# Install backend part
docker-compose --file ../docker-compose.yml run --rm php ./install.sh

# Stop all containers
./stop.sh
