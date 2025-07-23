const WebSocket = require('ws');

const wss = new WebSocket.Server({ port: 3000, host: '0.0.0.0' });
const clientsByRole = {
  kitchen: new Set(),
  waiter: new Set(),
  cashier: new Set(),
};

wss.on('connection', function connection(ws) {
  let role = null;
  ws.isAlive = true;


  ws.on('message', function incoming(message) {
    try {
      const data = JSON.parse(message);

      if (data.type === 'register' && data.role) {
        role = data.role;
        if (clientsByRole[role]) {
          clientsByRole[role].add(ws);
          console.log(`Client registered as ${role}`);
        }
      }

      if (data.type === 'new_order') {
        console.log('[📦] New order received:', data.order);
        broadcastToRoles(['kitchen', 'waiter', 'cashier'], {
          type: 'new_order',
          order: data.order,
        });
      }
      else if (data.type === 'item_prepared') {
        console.log('[📦] New order received:', data.order);
        broadcastToRoles(['waiter'], {
          type: 'item_prepared',
          order: data.item,
        });
      }
    } catch (err) {
      console.error('Invalid message format:', message);
    }
  });

  ws.on('close', () => {
    if (role && clientsByRole[role]) {
      clientsByRole[role].delete(ws);
      console.log(`Client disconnected from ${role}`);
    }
  });

  ws.on('pong', () => {
    console.log("📶 Pong received from client");
    ws.isAlive = true;
  });
});

setInterval(() => {
  wss.clients.forEach((ws) => {
    if (ws.isAlive === false) return ws.terminate();
    ws.isAlive = false;
    ws.ping(() => {});
  });
}, 30000);

function broadcastToRoles(roles, message) {
  const msg = JSON.stringify(message);
  roles.forEach(role => {
    clientsByRole[role]?.forEach(client => {
      if (client.readyState === WebSocket.OPEN) {
        client.send(msg);
      }
    });
  });
}

console.log('Push server running on ws://localhost:3000');
