import asyncio
import random

import websockets
import json

uri = "ws://localhost:8765"
data = {
    "chipID": 1234567,
    "version": "testClientV1",
    "battery": 100,
    "soil_moisture": 50,
    "air_humidity": 250,
    "temperature": 125,
    "pH": 70
}
exit_msg = "bye"


async def client():
    global uri, data, exit_msg

    while True:
        try:
            async with websockets.connect(uri) as websocket:
                print(f"[WSC] Connected to {uri}")

                data["battery"] = random.randint(0, 255)
                data["soil_moisture"] = random.randint(0, 255)
                data["air_humidity"] = random.randint(0, 255)
                data["temperature"] = random.randint(0, 255)
                data["pH"] = random.randint(0, 255)

                json_out = json.dumps(data)
                await websocket.send(json_out)
                print("[WSC] JSON sent")
                print(json_out)

                tbr_string = await websocket.recv()
                print(f"[WSC] incomming message: {tbr_string}")
                tbr = int(tbr_string.split(':')[-1])

                msg = ''
                while msg != exit_msg:
                    msg = await websocket.recv()
                    print(f"[WSC] msg in: {msg}")
                print(
                    f"[WSC] connection closed, reconnecting in {int(tbr/1000)} seconds\n")
                await asyncio.sleep(int(tbr/1000))
        except:
            print(f"[WSC] Could not connect to {uri}, will attempt again in a few seconds")
            await asyncio.sleep(10)



loop = asyncio.get_event_loop()
loop.run_until_complete(client())
loop.run_forever()
