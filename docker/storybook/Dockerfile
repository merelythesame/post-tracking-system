FROM node:20-alpine
WORKDIR /app
COPY frontend/package*.json ./
RUN npm install
COPY frontend/ .
RUN npm run build-storybook
RUN npm install -g serve
EXPOSE 6007
CMD ["serve", "storybook-static", "-l", "6007"]