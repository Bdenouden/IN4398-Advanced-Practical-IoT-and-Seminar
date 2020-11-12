#ifndef AM232xSensor_h
#define AM232xSensor_h
#include "Sensors.h"
#include "AM232xSensor.h"
#include <stdint.h>
#include <AM232X.h>


class AM232xSensor : public Sensor
{
public:
    AM232xSensor(unsigned int linkId, const char *type, uint8_t SDA, uint8_t SCL);
    uint16_t getValue(uint8_t number);

private:
    uint8_t _SDA; // pins where the sensor is connected
    uint8_t _SCL;
    AM232X AM232x;
};

#endif