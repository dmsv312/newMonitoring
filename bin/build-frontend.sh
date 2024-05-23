#!/bin/bash

rm -rf ../www/frontend/web
docker-compose --file ../docker-compose.yml exec node npm run build
mv -f ../www/frontend/dist ../www/frontend/web
