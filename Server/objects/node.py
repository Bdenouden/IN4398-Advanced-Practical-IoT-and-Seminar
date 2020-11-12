from datetime import datetime
from .api import API
from .sensor import Sensor


class Node:
    knownDevices = {}
    __isActive = False
    isNew = False

    def __init__(self, chip_id, version, config_version, sensor_list=None):
        self.chipId = chip_id
        self.version = version
        self.config_version = config_version
        self.created_at = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
        self.updated_at = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
        if sensor_list is None:
            self.sensorList = []
        else:
            self.sensorList = sensor_list
        Node.knownDevices[self.chipId] = self

        # print(f"[NODE] ChipId: {self.chipId}, Sensorlist: ", end='')
        # print(sensor_list)

    def sensor_data_from_json(self, json):
        # print(f"[NODE] sensordataformjson json = {json}")
        self.config_version = json.get('config_version')
        for sensor in self.sensorList:
            sensor.set_value(json.get(sensor.link_id))  # FIXME
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

    def print_sensor_list(self):
        for sensor in self.sensorList:
            print(f"-->{sensor.type}")

    def print_attributes(self):
        print(f"Attributes from node {self.chipId}")
        print(f"--> chipId = {self.chipId}")
        print(f"--> version = {self.version}")
        print(f"--> config_version = {self.config_version}")
        print(f"--> created_at = {self.created_at}")
        print(f"--> updated_at = {self.updated_at}")
        if not self.sensorList:
            print(f"--> sensorList = []")
        else:
            print(f"--> sensorList = [")
            self.print_sensor_list()
            print(f"    ]")

    def get_dict(self):
        if not self.sensorList: # return empty dict if no sensor is attached (awaiting new config)
            return {}

        temp_dict = {'measure_time': datetime.now().strftime("%Y-%m-%d %H:%M:%S")}
        for sensor in self.sensorList:
            temp_dict[sensor.link_id] = sensor.get_dict()
        return temp_dict

    def get_config(self):
        temp_list = []
        for sensor in self.sensorList:
            temp_list.append(sensor.get_config())
        return temp_list

    @classmethod  # create node object from json data
    def from_json(cls, json):
        node = Node.knownDevices.get(json['chipID'])
        if node is not None:
            print(
                f'[NODE] Node {json["chipID"]} is a\033[92m KNOWN\033[0m device')
            node.updated_at = datetime.now().strftime("%Y.%m.%d - %H:%M:%S")
        else:
            print(
                f'[NODE] Node {json["chipID"]} is a\033[93m NEW\033[0m device')
            node = cls(json['chipID'], json['version'], json['config_version'])
            node.isNew = True

        return node

    def save_new_device_to_db(self):
        """
            Saves a new device to the PWA database
        """
        new_device_api = API(path='/new_device', params={"node_id": self.chipId})
        response = new_device_api.post()
        if response is not None:
            print(f"[NEW DEVICE] {response.status_code}")
            if response.status_code == 200:
                self.isNew = False
        else:
            print(f"[NEW DEVICE] Network error: could not upload new device")

    @staticmethod  # initialise known devices from json
    def known_devices_from_json(json):
        for chipId in json:
            node = Node.knownDevices.get(int(chipId))
            if node is None:
                # get sensor list
                # print(json[chipId]['sensors'])
                print(f"[Node] chipid = {chipId}:")
                sensor_list = Sensor.sensors_from_list(json[chipId]['sensors'])
                if not sensor_list:
                    print("\t -- No sensors attached --")
                # generate new node object
                node = Node(int(chipId), 'unknown', '', sensor_list)  # FIXME version
                # print(f"Device with id {item['id']} is now known")

# {
#     "chipID": 9159476,
#     "version": "V1 Oct  7 2020 16:47:51",
#     "config_version": "12345678",
#     "8": 12,
#     "9": 0,
#     "10": 0
# }
