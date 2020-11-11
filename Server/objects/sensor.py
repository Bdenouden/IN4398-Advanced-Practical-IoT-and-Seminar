class Sensor:

    raw_value = None    # raw sensor data (minValue - maxValue)
    value = None        # meaningful data (e.g. 10 degrees C)
    _communication_method = None  # I2C, analog

    def __init__(self, link_id, sensor_type, raw_min_val, raw_max_val, min_val, max_val, pins):
        """

        :param link_id:
        :param sensor_type: indicates what the sensor measures
        :param raw_min_val:
        :param raw_max_val:
        :param min_val: is the minimum raw value as transmitted by the node
        :param max_val: is the maximum raw value as transmitted by the node
        """
        self.link_id = link_id
        # self.name = name                # e.g. 'temperature'
        # self.linkId = linkId            # ID for board-sensor combi in pwa database
        # defines the sensor class used at the arduino e.g. 'analog'
        self.type = sensor_type
        self.rawMinVal = int(raw_min_val)      # e.g. 0
        self.rawMaxVal = int(raw_max_val)      # e.g. 255
        self.minVal = int(min_val)            # e.g. -55
        self.maxVal = int(max_val)            # e.g. 125
        self.pins = pins

        print(f"[SENSOR] Link ID: {self.link_id}")

    def is_valid(self, raw_value):
        """
        Used to determine if the transmitted value is within the specified range

        :param raw_value:
        :return:
        """
        if (raw_value is not None and isinstance(raw_value, int) and self.rawMinVal <= raw_value <= self.rawMaxVal):
            return True
        return False

    def set_value(self, raw_value):
        """
        Takes the raw value as input and verifies if its within the `rawMinVal` and `rawmaxVal` range, returns `False` if not.
        If so, it sets both the `raw_value` and the `value` attribute returns `True` if successful

        :param raw_value:
        :return:
        """

        # Return false if invalid raw value
        if not self.is_valid(raw_value):
            return False

        self.raw_value = raw_value
        self.value = self.calc_value()
        return True

    def calc_value(self):
        """
        This is a standard mapping function and should in most cases be overwritten in the subclass

        :return:
        """
        val = self.minVal + self.maxVal * (self.raw_value / self.rawMaxVal)
        return round(val, 2)

    def get_value(self):
        return self.value

    def get_dict(self):
        """
        Used to generate the JSON send to the PWA or Node config

        :return:
        """
        return {
            "link_id": self.link_id,
            "value": self.value,
        }

    def get_config(self):
        return {
            "link_id": self.link_id,
            "type": self.type,
            "pins": self.pins
        }

    @classmethod
    def sensors_from_list(cls, sensor_list):
        output = []

        # TODO rewrite this to work for different sensor types
        for sensor in sensor_list:
            # sensor is a dict, sensorObj is the sensor object
            output.append(cls(
                sensor.get('link_id',''),
                sensor.get('type', ''),
                sensor.get('rawMinVal', ''),
                sensor.get('rawMaxVal', ''),
                sensor.get('minVal', ''),
                sensor.get('maxVal', ''),
                sensor.get('pins',[0])
            ))

            # print(sensor)

        print(f"[SENSOR] {len(output)} sensors created from list")

        return output


# class PH_sensor(Sensor):
#     def __init__(self):
#         super(PH_sensor, self).__init__('pH', 'I2C', '', 0, 255, 0, 14)


# class Soil_moisture_sensor(Sensor):
#     def __init__(self):
#         super(Soil_moisture_sensor, self).__init__(
#             'soil_moisture', 'analog', '%', 0, 255, 0, 100)


# class Battery(Sensor):
#     def __init__(self):
#         super(Battery, self).__init__('battery', 'analog', '%', 0, 255, 0, 100)


# class Humidity_sensor(Sensor):
#     def __init__(self):
#         super(Humidity_sensor, self).__init__(
#             'humidity', 'I2C', '%', 0, 255, 0, 100)


# class Temperature_sensor(Sensor):
#     def __init__(self):
#         super(Temperature_sensor, self).__init__(
#             'temperature', 'I2C', 'C', 0, 255, -55, 125)


# class Light_sensor(Sensor):
#     def __init__(self):
#         super(Light_sensor, self).__init__(
#             'light', 'analog', '', 0, 255, 0, 255)


# TODO alternatief idee: subclasses kunnen aangemaakt worden als 'Sensor' object op basis van de info uit de DB:
# bij begin executie wordt uit de sensor table alle info geplukt zoals:
# - [type]
# - [siUnit]
# - [rawMinVal]
# - [rawMaxVal]
# - [math] This column contains the mathematical logic to convert the raw data into the required values
# - [createn_at]
# - [updated_at]
