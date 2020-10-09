from datetime import datetime
from .api import API
from .sensor import Sensor
import threading


class Node:
    knownDevices = {}
    __isActive = False
    isNew = False

    def __init__(self, chipId, version, config_version, sensorList=None):
        self.chipId = chipId
        self.version = version
        self.config_version = config_version
        self.created_at = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
        self.updated_at = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
        if sensorList is None:
            self.sensorList = []
        else:
            self.sensorList = sensorList
        Node.knownDevices[self.chipId] = self

        print(f"[NODE] ChipId: {self.chipId}, Sensorlist: ",end='')
        print(sensorList)

    def sensorDataFromJson(self, json):
        # print(f"[NODE] sensordataformjson json = {json}")
        self.config_version = json.get('config_version')
        for sensor in self.sensorList:
            sensor.setValue(json.get(sensor.link_id)) #FIXME
            print(sensor)
            print(f"[NODE] [sensorDataFromJson] Sensor link id: {sensor.link_id}, Value: {json.get(sensor.link_id)}")

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
        print(f"--> config_version = {self.config_version}")
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
        temp_dict['measure_time'] = datetime.now().strftime(
            "%Y-%m-%d %H:%M:%S")
        for sensor in self.sensorList:
            temp_dict[sensor.link_id] = sensor.getDict()
        return temp_dict

    def getConfig(self):
        temp_list = []
        for sensor in self.sensorList:
            temp_list.append(sensor.getConfig())
        return temp_list

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
            node = cls(json['chipID'], json['version'], json['config_version'])
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
        for chipId in json:
            node = Node.knownDevices.get(int(chipId))
            if (node is None):
                # get sensor list
                # print(json[chipId]['sensors'])
                print(f"[Node] chipid = {chipId}, ", end='')
                sensorList = Sensor.sensorsFromList(json[chipId]['sensors'])

                # generate new node object
                node = Node(int(chipId), 'unknown','',
                            sensorList)  # FIXME version
                # print(f"Device with id {item['id']} is now known")

# {
#     "chipID": 9159476,
#     "version": "V1 Oct  7 2020 16:47:51",
#     "config_version": "12345678",
#     "8": 12,
#     "9": 0,
#     "10": 0
# }
