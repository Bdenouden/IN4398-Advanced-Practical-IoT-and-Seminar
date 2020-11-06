#include "sensors.h"
#include <stdint.h>
#include <arduino.h>

Sensor::Sensor(unsigned int linkId, const char *type)
{
    this->_linkId = linkId;
    this->_type = type;
}

unsigned int Sensor::getLinkId()
{
    return this->_linkId;
    // return 42;
}

uint8_t Sensor::getNOValues()
{
    return _NOValues;
}

AnalogSensor::AnalogSensor(
    unsigned int linkId,
    const char *type,
    uint8_t pin)
    : Sensor(linkId, type)
{
    this->_pin = pin;
}

void AnalogSensor::echo()
{
    Serial.println("analog - echo");
}

uint16_t AnalogSensor::getValue(uint8_t number)
{
    return analogRead(this->_pin);
}



// TODO dit afmaken maar eerst goed uitdenken