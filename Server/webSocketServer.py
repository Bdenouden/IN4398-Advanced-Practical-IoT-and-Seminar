import asyncio
import websockets

current_clients = 0 

async def hello(websocket, path):
    global current_clients
    current_clients = 1
    client = websocket.remote_address
    print(f'[WSS] incomming connection: {client}')
    print(f'[WSS] currently connected clients: {current_clients}')

    msg_in = await websocket.recv()
    print(f"{client} > {msg_in}")

    msg_out = f"Received message: '{msg_in}'!"

    await websocket.send(msg_out)
    print(f"{client} < {msg_out}")
    current_clients -= 1
    print('[WSS] Connection closed!')
    print(f'[WSS] currently connected clients: {current_clients}')

start_server = websockets.serve(hello, "", 8765)

print('[WSS] Server started!')
asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
