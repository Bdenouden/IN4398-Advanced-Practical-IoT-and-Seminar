import asyncio
import websockets
import json
from datetime import datetime
from objects import Node
from objects import Sensor
from objects import API

current_clients = 0  

# time between the current and next interaction, as send to the ESP
timeBeforeReconnect = 30000



api = API()
print("[SETUP] Awaiting known_devices")
response = api.get()
if response.status_code == 200:
    # print(f"[API] Response code {response.status_code}")
    known_devices = json.loads(response.text)
    # print(json.dumps(known_devices, indent=4, sort_keys=False))
    Node.knownDevices_from_JSON(known_devices)
else:
    print(f"[API] Response code {response.status_code}, could not load known devices")

print(f"[SETUP] {len(Node.knownDevices)} device(s) were found:")

async def eventHandler(websocket, path):
    global current_clients, timeBeforeReconnect

    # update active clients
    current_clients += 1
    

    client = websocket.remote_address
    curTime = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
    print(f'\n[WSS] incomming connection: {client} @ {curTime }')
    print(f'[WSS] currently connected clients: {current_clients}')

    # receive the json containing all required information from the node
    msg_in = await websocket.recv()
    parsed = json.loads(msg_in)

    # show sensor data in console
    print(f"{client} > JSON data: ")
    print(json.dumps(parsed, indent=4, sort_keys=False))

    # get/create node object
    node = Node.from_JSON(parsed)
    print(f"[WSS] Node {node.chipId} has succesfully transmitted its data")

    # print attributes of the node
    # node.print_attributes()

    # send time before reconnect
    msg_out = f"tbr:{timeBeforeReconnect}"
    await websocket.send(msg_out)
    # print(f"{client} < {msg_out}")

    # added to prove the server can handle multiple clients at once provided no blocking actions take place 
    # see https://websockets.readthedocs.io/en/stable/faq.html
    # await asyncio.sleep(5)

    # send exit message
    msg_out = f"bye"
    await websocket.send(msg_out)
    # print(f"{client} < {msg_out}")

    # update active clients
    current_clients -= 1
    print('[WSS] Connection closed!')
    print(f'[WSS] Currently connected clients: {current_clients}')

    # show the amount of known devices
    # print(f'# known devices: {len(Node.knownDevices)}')

start_server = websockets.serve(eventHandler, "", 8765)

print('[WSS] Server started!')
asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
