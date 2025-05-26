FROM node:22.14.0
RUN mkdir -p /usr/src/webserver
WORKDIR /usr/src/webserver
COPY package.json .
RUN npm install
COPY . .
VOLUME /usr/src/webserver
CMD ["npm", "start"]
