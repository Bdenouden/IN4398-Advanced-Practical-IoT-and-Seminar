import asyncio
import websockets
import json
import threading
import time
import os
import sys
import fnmatch
from datetime import datetime
from datetime import timedelta
from objects import Node, API  # , PH_sensor, Soil_moisture_sensor, Battery, API
import hashlib


current_clients = 0
threads = []

# time between the current and next interaction, in ms, as send to the ESP
timeBeforeReconnect = 30*1000

# seconds between sensor updates to the pwa
update_delay = 1*60


def get_known_devices():
    api = API()
    print("[SETUP] Awaiting known_devices")
    response = api.get()

    if response is None:
        print(f"[API] An error occurred, could not load known devices")
    elif response.status_code == 200:
        # print(f"[API] Response code {response.status_code}")
        known_devices = json.loads(response.text)

        Node.known_devices_from_json(known_devices)
    else:
        print(
            f"[API] Response code {response.status_code}, could not load known devices")

    print(f"[SETUP] {len(Node.knownDevices)} device(s) were found:")


def http_new_device(node):
    # print(f"[WSS] New device thread started")
    thread = threading.Thread(target=node.save_new_device_to_db)
    threads.append(thread)
    thread.start()


async def event_handler(websocket, path):
    global current_clients, timeBeforeReconnect, threads

    # update active clients
    current_clients += 1

    client = websocket.remote_address
    current_time = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
    print(f'\n[WSS] incoming connection: {client} @ {current_time }')
    print(f'[WSS] currently connected clients: {current_clients}')

    # receive the json containing all required information from the node
    msg_in = await websocket.recv()
    parsed = json.loads(msg_in)

    # show sensor data in console
    print(f"{client} > JSON data: ")
    print(json.dumps(parsed, indent=4, sort_keys=False))

    # get/create node object
    node = Node.from_json(parsed)
    if node.isNew:
        http_new_device(node)
    node.sensor_data_from_json(parsed)

    print(f"[WSS] Node {node.chipId} has successfully transmitted its data")

    # print attributes of the node
    # node.print_attributes()

    # send time before reconnect
    msg_out = f"tbr:{timeBeforeReconnect}"
    await websocket.send(msg_out)
    # print(f"{client} < {msg_out}")

    # added to prove the server can handle multiple clients at once provided no blocking actions take place
    # see https://websockets.readthedocs.io/en/stable/faq.html
    # await asyncio.sleep(5)

    config = node.get_config()
    config_string = json.dumps(config)
    config_hash = hashlib.md5(config_string.encode()).hexdigest()[0:8]

    if config_hash != node.config_version:
        print(f"[WSS] New config available")
        dict_out = {
            "config-version": config_hash,
            "config": config
        }

        msg_out = f"config:{dict_out}"
        await websocket.send(msg_out)

        # check if update successful
        msg_in = await websocket.recv()
        print(f"[WSS] msg_in = {msg_in}")
    else:
        print(f"[WSS] Node config up to date!")

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


def send_update(known_devices):
    global update_delay

    while True:
        transmission_time = datetime.now() + timedelta(seconds=update_delay)
        print(
            f'[UPDATE] delay set for {update_delay} seconds, next update: {transmission_time.strftime("%Y.%m.%d - %H:%M:%S")}')

        time.sleep(update_delay)

        temp_dict = {}
        api = API(path='/update')

        # FIXME Node.knownDevices is not thread safe: must be converted to argument of the function
        for _, node in known_devices.items():
            temp_dict[node.chipId] = node.get_dict()

        api.json = temp_dict

        print("[UPDATE] /update JSON output: ")
        print(json.dumps(temp_dict, indent=4, sort_keys=False))

        response = api.post()

        if response is None:
            print(f"[UPDATE] An error occured!")
            create_backlog_file(temp_dict)

        elif response.status_code == 200:
            print(f"[UPDATE] Sensor values successfully uploaded!")
            print(f"[UPDATE] PWA response: ")
            print(response.text)
            send_backlog()  # Since there is an established connection now, attempt to transmit the backlog
            Node.knownDevices = {}  # clear known devices to get new config
            get_known_devices()

        else:
            print(
                f"[UPDATE] An error occured! Response code: {response.status_code}")
            print(response.text)
            create_backlog_file(temp_dict)


def create_backlog_file(data):
    """
    Write a JSON file containing the information found in the dict `data`
    :param data:
    :return: 
    """
    curTime = datetime.now().strftime("%Y%m%d-%H%M%S")
    with open(sys.path[0] + '/data/'+curTime+'.json', 'w+') as outFile:
        json.dump(data, outFile)


def send_backlog():
    """
    Cycle through all failed attempts to update and retry to transmit, deleting files if succesfull

    :return:
    """
    path = sys.path[0] + '/data'
    api = API(path='/update')

    files = fnmatch.filter(os.listdir(path), '*.json')
    for file in files:
        with open(path + '/' + file, 'r') as f:
            api.json = json.load(f)

        response = api.post()
        if response is not None:
            print(f"[UPDATE] Response status code: {response.status_code}")
            print(f"[UPDATE] Response text: {response.text}")

        if response is not None and response.status_code == 200:
            os.remove(path+'/'+file)
            print(
                f"[UPDATE] Backlog file {file} has been transmitted and deleted")
        else:
            print(
                f'[UPDATE] Could not transmit backlog file {file} due to a network error')


get_known_devices()

start_server = websockets.serve(event_handler, "", 8765)
print('[WSS] Server started!')

# TODO possible alternative for web communication: https://realpython.com/python-concurrency/
t = threading.Thread(target=send_update, args=(Node.knownDevices,))
threads.append(t)
t.start()

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
