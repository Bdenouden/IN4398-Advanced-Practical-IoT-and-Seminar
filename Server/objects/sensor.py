class Sensor:

    raw_value = None    # raw sensor data (minValue - maxValue)
    value = None        # meaningfull data (e.g. 10 degrees C)
    _communication_method = None  # I2C, analog
    # dict of pins connected <name>:<pin> (e.g. 'SCL':'D5', 'SDA':'D6')
    _pin = {}

    def __init__(self, name, type, siUnit, rawMinVal, rawMaxVal, minVal, maxVal):
        ''' 
            `type` indicates what the sensor measures.\n
            `siUnit` is the SI unit of the measured value, used as suffix when printing.\n
            `minVal` is the minimum raw value as transmitted by the node.\n
            `maxVal` is the maximum raw value as transmitted by the node.
        '''
        self.name = name                # e.g. 'temperature'
        # defines the sensor class used at the arduino e.g. 'analog'
        self.type = type
        self.siUnit = siUnit            # e.g. 'Degrees C'
        self.rawMinVal = rawMinVal      # e.g. 0
        self.rawmaxVal = rawMaxVal      # e.g. 255
        self.minVal = minVal            # e.g. -55
        self.maxVal = maxVal            # e.g. 125

    def isValid(self, raw_value):
        ''' Used to determine if the transmitted value is within the specified range '''
        if(
            raw_value is not None
            and isinstance(raw_value, int)
            and raw_value >= self.rawMinVal
            and raw_value <= self.rawmaxVal
        ):
            return True
        return False

    def setValue(self, raw_value):
        ''' 
            Takes the raw value as input and verfies if its within the `rawMinVal` and `rawmaxVal` range, returns `False` if not.
            If so, it sets both the `raw_value` and the `value` attribute \n\n
            returns `True` if succesfull
        '''

        # Return false if invalid raw value
        if not self.isValid(raw_value):
            return False

        self.raw_value = raw_value
        self.value = self.calc_value()
        return True

    def calc_value(self):
        ''' This is a standard mapping function and should in most cases be overwritten in the subclass'''
        val = self.minVal + self.maxVal * (self.raw_value / self.rawmaxVal)
        return round(val, 2)

    def getValue(self):
        return self.value

    def getDict(self):
        '''
            Used to generate the JSON send to the PWA
        '''
        return {
            "name": self.name,
            "value": self.value,
            "unit": self.siUnit
        }
    
    @classmethod
    def sensorsFromList(cls, sensorList):
        output = []
        
        # TODO rewrite this to work for different sensor types
        for sensor in sensorList:
            # sensor is a dict, sensorObj is the sensor object
            output.append(cls(
                sensor.get('name',''),
                sensor.get('type',''),
                sensor.get('siUnit',''),
                sensor.get('rawMinVal',''),
                sensor.get('rawMaxVal',''),
                sensor.get('minVal',''),
                sensor.get('maxVal','')
                 ))

            # print(sensor)


        print(f"[SENSOR] {len(output)} sensors created from list")

        return output


class PH_sensor(Sensor):
    def __init__(self):
        super(PH_sensor, self).__init__('pH', 'I2C', '', 0, 255, 0, 14)


class Soil_moisture_sensor(Sensor):
    def __init__(self):
        super(Soil_moisture_sensor, self).__init__(
            'soil_moisture', 'analog', '%', 0, 255, 0, 100)


class Battery(Sensor):
    def __init__(self):
        super(Battery, self).__init__('battery', 'analog', '%', 0, 255, 0, 100)


class Humidity_sensor(Sensor):
    def __init__(self):
        super(Humidity_sensor, self).__init__(
            'humidity', 'I2C', '%', 0, 255, 0, 100)


class Temperature_sensor(Sensor):
    def __init__(self):
        super(Temperature_sensor, self).__init__(
            'temperature', 'I2C', 'C', 0, 255, -55, 125)


class Light_sensor(Sensor):
    def __init__(self):
        super(Light_sensor, self).__init__(
            'light', 'analog', '', 0, 255, 0, 255)


# TODO alternatief idee: subclasses kunnen aangemaakt worden als 'Sensor' object op basis van de info uit de DB:
# bij begin executie wordt uit de sensor table alle info geplukt zoals:
# - [type]
# - [siUnit]
# - [rawMinVal]
# - [rawMaxVal]
# - [math] This column contains the mathematical logic to convert the raw data into the required values
# - [createn_at]
# - [updated_at]
