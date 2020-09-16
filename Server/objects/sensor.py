class Sensor:

    value = None

    def __init__(self, type, siUnit, minVal, maxVal):
        self.type = type           # e.g. 'temperature'
        self.siUnit = siUnit       # e.g. 'Degrees C'
        self.minVal = minVal       # e.g. -10
        self.maxVal = maxVal       # e.g. 60

    def isValid(self):
        if(
            self.value != None and
            self.value >= self.minVal and
            self.value <= self.maxVal
        ):
            return True
        return False
