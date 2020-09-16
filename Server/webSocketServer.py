import asyncio
import websockets
import json
from datetime import datetime
from objects import Node
from objects import Sensor


print(Node.knownDevices.get('test'))


current_clients = 0  # can later be replaced by Node.knownDevices.lenth() (keeping in mind that the clients must be active at the time)
# time between the current and next interaction, as send to the ESP
timeBeforeReconnect = 30000


async def hello(websocket, path):
    global current_clients, timeBeforeReconnect
    current_clients += 1
    client = websocket.remote_address
    curTime = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
    print(f'\n[WSS] incomming connection: {client} @ {curTime }')
    print(f'[WSS] currently connected clients: {current_clients}')

    msg_in = await websocket.recv()
    parsed = json.loads(msg_in)

    # show sensor data in console
    print(f"{client} > JSON data: ")
    print(json.dumps(parsed, indent=4, sort_keys=False))

    node = Node.from_JSON(parsed)
    print(f"[WSc] Node {node.chipId} has succesfully transmitted its data")
    node.print_attributes()

    msg_out = f"tbr:{timeBeforeReconnect}"
    await websocket.send(msg_out)
    print(f"{client} < {msg_out}")

    msg_out = f"bye"
    await websocket.send(msg_out)
    print(f"{client} < {msg_out}")

    current_clients -= 1
    print('[WSS] Connection closed!')
    print(f'[WSS] currently connected clients: {current_clients}')
    print(f'# known devices: {len(Node.knownDevices)}')

start_server = websockets.serve(hello, "", 8765)

print('[WSS] Server started!')
print(f'# known devices: {len(Node.knownDevices)}')
asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
