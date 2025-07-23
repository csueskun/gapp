class SocketManager {
  constructor() {
    this.socket = null;
    this.messageHandler = null;
    this.messageTypeHandlers = {}; // <-- changed to object (or use Map)
  }

  connect(url) {
    if (this.socket && this.socket.readyState === WebSocket.OPEN) {
      console.log("✅ Already connected");
      return;
    }

    if (this.socket && this.socket.readyState === WebSocket.CONNECTING) {
      console.log("⏳ Connection is in progress");
      return;
    }

    this.socket = new WebSocket(url);

    this.socket.addEventListener('message', (event) => {
      console.log("📩 Message received:", event.data);
      const data = JSON.parse(event.data);

      // If type-specific handler exists, call it
      if (data.type && this.messageTypeHandlers[data.type]) {
        this.messageTypeHandlers[data.type](data);
      }
      // Fallback to generic message handler
      else if (typeof this.messageHandler === 'function') {
        this.messageHandler(data);
      }
    });

    this.socket.addEventListener('close', () => {
      console.log("❌ Disconnected from", url);
      this.socket = null;
    });

    this.socket.addEventListener('error', (error) => {
      console.error("⚠️ WebSocket error:", error);
    });
  }

  onMessage(callback) {
    this.messageHandler = callback;
  }

  onMessageType(type, callback) {
    this.messageTypeHandlers[type] = callback;
  }

  send(message) {
    if (this.socket && this.socket.readyState === WebSocket.OPEN) {
      console.log("📤 Sending message:", message);
      this.socket.send(message);
    } else {
      this.socket.addEventListener('open', () => {
        console.log("📤 Sending message (on open):", message);
        this.socket.send(message);
      });
    }
  }

  disconnect() {
    if (this.socket) {
      this.socket.close();
    }
  }

  isConnected() {
    return this.socket && this.socket.readyState === WebSocket.OPEN;
  }

  getSocket() {
    return this.socket;
  }
}