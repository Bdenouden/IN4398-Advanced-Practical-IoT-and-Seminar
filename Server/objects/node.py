from datetime import datetime

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

    def saveToDb(self):
        """

        """
        pass

    def getFromDb(self, chipId):
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
            print(f'[NODE] Node {json["chipID"]} is a KNOWN device')
            node.updated_at = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
            return node
        print(f'[NODE] Node {json["chipID"]} is a NEW device')
        return cls(json['chipID'], json['version'])

    # "chipID": 9159476,
    # "version": "V1 Sep 16 2020 16:34:41",
    # "battery": 5,
    # "soil_moisture": 1,
    # "air_humidity": 2,
    # "temperature": 3,
    # "pH": 4

    @staticmethod
    # TODO: remove me
    def test():
        print('IM ALIVEEEE')
