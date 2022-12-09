const express = require('express');

const app = express();

const server = require('http').createServer(app)

const io = require("socket.io")(server,{
    cors : {
        origin:"*"
    }
});

var users = [];

io.on('connection', (socket)=>{

    // when new user connects
    socket.on('user_connection', (data)=>{
        for(let i=1; i<data.length; i++){
            users[`${data[0]}${data[i]}`] = socket.id;
        }
        console.log(`user ${data} connected`);
        console.log(users)

    })

    // when user send private message
    socket.on('send_private_message', (data) => {
        console.log('Send Message',data)
        let socketId = users[`${data.receiver.type}${data.receiver.id}`];

        io.to(socketId).emit('new_private_message', data);
    })


    // when user start typing
    socket.on('typing_private_message', (data)=>{
        console.log('Start Typing',data)
        let socketId = users[`${data.receiverType}${data.receiver.id}`];

        io.to(socketId).emit('new_typing_private_message', data);

    });

    // when user stop typing
    socket.on('delete_typing_private_message', (data)=>{
        console.log('Stop Typing', data)
        let socketId = users[`${data.receiverType}${data.receiver.id}`];

        io.to(socketId).emit('delete_typing_private_message', data);
    });

    // when user delete private message
    socket.on('delete_private_message', (data)=>{
        console.log('Delete', data);
        let socketId = users[`${data.receiverType}${data.receiver.id}`];

        io.to(socketId).emit('user_delete_private_message', data);
    })

    // when user send group message
    socket.on('send_group_message', (data) => {
        console.log('Send Message Group',data)
        for(let i=0;i<data.receiver.length ; i++){
            let socketId = users[`${data.receiver[i].type}${data.receiver[i].id}`];
            io.to(socketId).emit('new_group_message', data);
        }
    })

    // when user delete group message
    socket.on('delete_group_message', (data)=>{
        console.log('Delete', data);
        for(let i=0;i<data.receiver.length ; i++){
            let socketId = users[`${data.receiver[i].type}${data.receiver[i].id}`];
            io.to(socketId).emit('user_delete_group_message', data);
        }
    })

    // when user start typing
    socket.on('typing_group_message', (data)=>{
        console.log('Start Typing',data)
        for(let i=0;i<data.receiver.length ; i++){
            let socketId = users[`${data.receiver[i].type}${data.receiver[i].id}`];
            io.to(socketId).emit('new_typing_group_message', data);
        }

    });

    // when user stop typing
    socket.on('delete_typing_group_message', (data)=>{
        console.log('Stop Typing', data)
        for(let i=0;i<data.receiver.length ; i++){
            let socketId = users[`${data.receiver[i].type}${data.receiver[i].id}`];
            io.to(socketId).emit('delete_typing_group_message', data);
        }
    });

    // when user disconnected
    socket.on('disconnect', (socket)=>{
        console.log('disconnected');
    })
});


server.listen(3000, ()=>{
    console.log('listening on port 3000');
})
