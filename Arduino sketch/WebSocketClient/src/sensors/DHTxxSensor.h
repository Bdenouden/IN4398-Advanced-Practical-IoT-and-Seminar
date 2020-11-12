#ifndef DHTxxSensor_h
#define DHTxxSensor_h
#include "Sensors.h"
#include "DHTxxSensor.h"
#include <stdint.h>
#include <dhtnew.h>

class DHTxxSensor : public Sensor
{
public:
    DHTxxSensor(unsigned int linkId, const char *type, uint8_t pin);
    ~DHTxxSensor();
    uint16_t getValue(uint8_t number);

private:
    uint8_t _pin; // pin where the dht sensor is connected
    DHTNEW *dht;
};

#endif