import asyncio
import websockets
import json
import threading
import time
import os
import sys
from datetime import datetime
from datetime import timedelta
from objects import Node
from objects import PH_sensor
from objects import Soil_moisture_sensor
from objects import Battery
from objects import API

current_clients = 0
threads = []

# time between the current and next interaction, in ms, as send to the ESP
timeBeforeReconnect = 30*1000

# seconds between sensor updates to the pwa
update_delay = 0.1*60


def get_known_devices():
    api = API()
    print("[SETUP] Awaiting known_devices")
    response = api.get()

    if response is None:
        print(f"[API] An error occured, could not load known devices")
    elif response.status_code == 200:
        # print(f"[API] Response code {response.status_code}")
        known_devices = json.loads(response.text)
        # print(json.dumps(known_devices, indent=4, sort_keys=False))
        Node.knownDevices_from_JSON(known_devices)
    else:
        print(
            f"[API] Response code {response.status_code}, could not load known devices")

    print(f"[SETUP] {len(Node.knownDevices)} device(s) were found:")


def HTTP_new_device(node):
    # print(f"[WSS] New device thread started")
    t = threading.Thread(target=node.saveNewDeviceToDb)
    threads.append(t)
    t.start()


async def eventHandler(websocket, path):
    global current_clients, timeBeforeReconnect, threads

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
    if node.isNew:
        HTTP_new_device(node)
    # TODO version update
    # TODO check which sensor data is received but not used -> alert UI
    node.sensorDataFromJson(parsed)

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


def send_update():
    global update_delay

    while True:
        transmission_time = datetime.now() + timedelta(seconds=update_delay)
        print(
            f'[UPDATE] delay set for {update_delay} seconds, next update: {transmission_time.strftime("%Y.%m.%d - %H:%M:%S")}')

        time.sleep(update_delay)

        temp_dict = {}
        api = API(path='/update')

        for _, node in Node.knownDevices.items():
            temp_dict[node.chipId] = node.getDict()

        api.json = temp_dict

        # print("[UPDATE] /update JSON output: ")
        # print(json.dumps(temp_dict, indent=4, sort_keys=False))

        # TODO this is a blocking statement, ruining the async method. this should be put into a thread
        response = api.post()

        if response is None:
            print(f"[UPDATE] An error occured!")
            curTime = datetime.now().strftime("%Y%m%d-%H%M%S")
            with open(sys.path[0]+ '/data/'+curTime+'.txt', 'w+') as outFile:
                json.dump(temp_dict, outFile)
        elif response.status_code == 200:
            print(f"[UPDATE] Sensor values successfully uploaded!")
        else:
            print(
                f"[UPDATE] An error occured! Response code: {response.status_code}")


get_known_devices()

# TODO remove from here after test
for key in Node.knownDevices:
    node = Node.knownDevices.get(key)
    node.add_sensor(Soil_moisture_sensor())
    node.add_sensor(PH_sensor())
    node.add_sensor(Battery())
# Remove up to here


start_server = websockets.serve(eventHandler, "", 8765)
print('[WSS] Server started!')

t = threading.Thread(target=send_update)
threads.append(t)
t.start()

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
