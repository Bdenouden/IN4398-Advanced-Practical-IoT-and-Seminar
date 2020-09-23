class Sensor:

    raw_value = None    # raw sensor data (minValue - maxValue)
    value = None        # meaningfull data (e.g. 10 degrees C)
    _communication_method = None # I2C, analog
    _pin = {} # dict of pins connected <name>:<pin> (e.g. 'SCL':'D5', 'SDA':'D6')


    def __init__(self, type, siUnit, rawMinVal, rawMaxVal, minVal, maxVal):
        ''' 
            `type` indicates what the sensor measures.\n
            `siUnit` is the SI unit of the measured value, used as suffix when printing.\n
            `minVal` is the minimum raw value as transmitted by the node.\n
            `maxVal` is the maximum raw value as transmitted by the node.
        '''
        self.type = type                # e.g. 'temperature'
        self.siUnit = siUnit            # e.g. 'Degrees C'
        self.rawMinVal = rawMinVal      # e.g. 0
        self.rawmaxVal = rawMaxVal      # e.g. 255
        self.minVal = minVal            # e.g. -55
        self.maxVal = maxVal            # e.g. 125

    def isValid(self):
        ''' Used to determine if the transmitted value is within the specified range '''
        if(
            self.raw_value != None
            and isinstance(self.raw_value, int)
            and self.raw_value >= self.rawMinVal
            and self.raw_value <= self.rawmaxVal
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
        if not self.isValid():
            return False

        self.raw_value = raw_value
        self.value = self.calc_value()
        return True

    def calc_value(self):
        ''' This is a standard mapping function and should in most cases be overwritten in the subclass'''
        val = self.minVal + self.maxVal * (self.raw_value / self.rawmaxVal)
        return val

    def getValue(self):
        return self.value


class PH_sensor(Sensor):
    def __init__(self):
        super.__init__('pH', '', 0, 255, 0, 14)


class Soil_moisture_sensor(Sensor):
    def __init__(self):
        super.__init__('soil_moisture', '%', 0, 255, 0, 100)


class Battery(Sensor):
    def __init__(self):
        super.__init__('battery', '%', 0, 255, 0, 100)


class Humidity_sensor(Sensor):
    def __init__(self):
        super.__init__('humidity', '%', 0, 255, 0, 100)


class Temperature_sensor(Sensor):
    def __init__(self):
        super.__init__('temperature', 'C', 0, 255, -55, 125)


class Light_sensor(Sensor):
    def __init__(self):
        super.__init__('light', '', 0, 255, 0, 255)


# TODO alternatief idee: subclasses kunnen aangemaakt worden als 'Sensor' object op basis van de info uit de DB:
# bij begin executie wordt uit de sensor table alle info geplukt zoals:
# - [type]
# - [siUnit]
# - [rawMinVal]
# - [rawMaxVal]
# - [math] This column contains the mathematical logic to convert the raw data into the required values
# - [createn_at]
# - [updated_at]
