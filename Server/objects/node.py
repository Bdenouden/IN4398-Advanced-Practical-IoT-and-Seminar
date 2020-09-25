from datetime import datetime
from .api import API
import threading


class Node:
    knownDevices = {}
    __isActive = False
    isNew = False

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

    def sensorDataFromJson(self, json):
        for sensor in self.sensorList:
            sensor.setValue(json.get(sensor.name))

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

    def getDict(self):
        temp_dict = {}
        for sensor in self.sensorList:
            temp_dict[sensor.name] = sensor.getDict()
        return temp_dict


    @ classmethod  # create node object from json data
    def from_JSON(cls, json):
        node = Node.knownDevices.get(json['chipID'])
        if(node is not None):
            print(
                f'[NODE] Node {json["chipID"]} is a\033[92m KNOWN\033[0m device')
            node.updated_at = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
        else:
            print(
                f'[NODE] Node {json["chipID"]} is a\033[93m NEW\033[0m device')
            node = cls(json['chipID'], json['version'])
            node.isNew = True

        return node

    def saveNewDeviceToDb(self):
        """
            Saves a new device to the PWA database
        """
        newDeviceApi = API(path='/new_device', params={"node_id": self.chipId})
        response = newDeviceApi.post()
        if response is not None:
            print(f"[NEW DEVICE] {response.status_code}")
            if response.status_code == 200:
                self.isNew = False
        else: 
            print(f"[NEW DEVICE] Network error: could not upload new device")

    @ staticmethod  # initialise known devices from json
    def knownDevices_from_JSON(json):
        for item in json:
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
