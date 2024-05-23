#!/bin/bash

docker-compose --file ../docker-compose.yml exec node npm $*
