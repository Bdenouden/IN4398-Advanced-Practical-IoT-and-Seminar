from datetime import datetime
from .api import API
import threading

import time


class Node:
    knownDevices = {}
    __isActive = False

    def __init__(self, chipId, version, sensorList=None):
        self.chipId = chipId
        self.version = version
        self.created_at = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
        self.updated_at = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
        if sensorList is None:
            self.sensorList = []
        else:
            self.sensorList = sensorList
        Node.knownDevices[self.chipId] = self

    def saveNewDeviceToDb(self):
        """

        """
        t = time.time()
        newDeviceApi=API(path='/new_device' , params={"node_id": self.chipId})
        response = newDeviceApi.post()
        elapsed_time = time.time()-t
        print(f"[NEW DEVICE] {response.status_code}, took {elapsed_time}s")

    def getFromDb(self, chipId):
        # TODO dit goed implementeren
        pass

    def add_sensor(self, sensor):
        """ Add individual sensors to this node's sensor list"""
        if sensor not in self.sensorList:
            self.sensorList.append(sensor)

    def set_sensors(self, sensors):
        """ Replace this node's sensor list with the list `sensors`"""
        self.sensorList = sensors

    def remove_sensor(self, sensor):
        """ Remove individual sensors from this node's sensor list"""
        if sensor in self.sensorList:
            self.sensorList.remove(sensor)

    def print_sensorList(self):
        for sensor in self.sensorList:
            print(f"-->{sensor.type}")

    def print_attributes(self):
        print(f"Attributes from node {self.chipId}")
        print(f"--> chipId = {self.chipId}")
        print(f"--> version = {self.version}")
        print(f"--> created_at = {self.created_at}")
        print(f"--> updated_at = {self.updated_at}")
        if(not self.sensorList):
            print(f"--> sensorList = []")
        else:
            print(f"--> sensorList = [")
            self.print_sensorList()
            print(f"    ]")

    @classmethod  # create node object from json data
    def from_JSON(cls, json):
        node = Node.knownDevices.get(json['chipID'])
        if(node is not None):
            print(f'[NODE] Node {json["chipID"]} is a\033[92m KNOWN\033[0m device')
            node.updated_at = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
            return node
        print(f'[NODE] Node {json["chipID"]} is a\033[93m NEW\033[0m device')

        # FIXME put this in a thread
        # problem: node object is destroyed before thread has time to execute completely
        # result: thread will not execute, no http request made

        node = cls(json['chipID'], json['version'])
        # t = threading.Thread(target=node.saveNewDeviceToDb, args=[json["chipID"]])
        # t.start()
        node.saveNewDeviceToDb()
        

        # t = time.time()
        # newDeviceApi=API(path='/api/new_device' , params={"node_id": json["chipID"]})
        # response = newDeviceApi.post()
        # elapsed_time = time.time()-t
        # print(f"[NEW DEVICE] {response.status_code}, took {elapsed_time}s")

        # end of thread

        print(f'[NODE] # known devices: {len(Node.knownDevices)}')
        return node

    @staticmethod  # initialise known devices from json
    def knownDevices_from_JSON(json):
        for item in json:
            # print(f'item: {item}\n')
            node = Node.knownDevices.get(item['id'])
            if (node is None):
                node = Node(int(item["id"]), 'unknown')  # FIXME version
                # print(f"Device with id {item['id']} is now known")

    # "chipID": 9159476,
    # "version": "V1 Sep 16 2020 16:34:41",
    # "battery": 5,
    # "soil_moisture": 1,
    # "air_humidity": 2,
    # "temperature": 3,
    # "pH": 4
