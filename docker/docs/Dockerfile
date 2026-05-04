FROM node:18-alpine
WORKDIR /app
COPY frontend/docs ./docs
RUN npm install -g serve
EXPOSE 6060
CMD ["serve", "docs", "-l", "6060"]