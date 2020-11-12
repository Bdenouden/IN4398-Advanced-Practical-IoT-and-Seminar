#include "sensors.h"
#include <stdint.h>
#include <arduino.h>

Sensor::Sensor(unsigned int linkId, const char *type)
{
    this->_linkId = linkId;
    this->_type = type;
}

Sensor::~Sensor(){
    delete[] this->_type;
}

unsigned int Sensor::getLinkId()
{
    return this->_linkId;
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

uint16_t AnalogSensor::getValue(uint8_t number)
{
    uint16_t val = analogRead(this->_pin);
    // Serial.printf("Analog value of pin %d is %d", this->_pin, val);
    return val;
}
