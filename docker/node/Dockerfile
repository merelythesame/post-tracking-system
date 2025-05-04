FROM node:20

RUN apt-get update && apt-get install -y iputils-ping

WORKDIR /app

COPY package.json ./

COPY package-lock.json ./

EXPOSE 5173

