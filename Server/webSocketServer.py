import asyncio
import websockets
import json
from datetime import datetime

current_clients = 0 

async def hello(websocket, path):
    global current_clients
    current_clients = 1
    client = websocket.remote_address
    curTime = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
    print(f'[WSS] incomming connection: {client} @ {curTime }')
    print(f'[WSS] currently connected clients: {current_clients}')

    msg_in = await websocket.recv()
    parsed = json.loads(msg_in)

    # show sensor data in console
    print(f"{client} > JSON data: ")
    print(json.dumps(parsed , indent=4, sort_keys=False))


    msg_out = f"tbr:30000"
    await websocket.send(msg_out)
    print(f"{client} < {msg_out}")

    msg_out = f"bye"
    await websocket.send(msg_out)
    print(f"{client} < {msg_out}")

    current_clients -= 1
    print('[WSS] Connection closed!')
    print(f'[WSS] currently connected clients: {current_clients}')

start_server = websockets.serve(hello, "", 8765)

print('[WSS] Server started!')
asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
