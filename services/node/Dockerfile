# Use the latest LTS Node.js base image
FROM node:lts

COPY app/ /usr/src/app

# Create and set working directory
WORKDIR /usr/src/app


# Expose the WebSocket server port
EXPOSE 3000

# Start the server
CMD ["node", "index.js"]
