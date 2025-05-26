import express from 'express';
import http from 'http';
import { Server } from 'socket.io';

const app = express();
const server = http.createServer(app);
const io = new Server(server, { // crea una instancia del server.io y se pueden conectar desde cualquier CORS
  cors: {
    origin: '*',
  }
});

io.on('connection', (socket) => {
  console.log('Usuario conectado:', socket.id); // cuando se conecta muestra el id

  socket.on('mensaje', (mensaje) => {
    socket.broadcast.emit('mensaje', mensaje); // el participante envia un mensaje y lo recibe menos el que lo envia
  });

  socket.on('disconnect', () => {
    console.log('Usuario desconectado:', socket.id); // enseña quien se ha desconectado
  });
});

server.listen(3001, () => {
  console.log('Servidor corriendo en http://localhost:3001'); // incia el servidor en el puerto 3001
});
