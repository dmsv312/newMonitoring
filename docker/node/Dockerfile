FROM node:lts-alpine

RUN npm install --location=global npm@latest @vue/cli

WORKDIR /usr/src/app

EXPOSE 8080

CMD ["npm", "run", "serve"]
