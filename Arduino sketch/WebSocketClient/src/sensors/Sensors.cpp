#include "sensors.h"
#include <stdint.h>
#include <arduino.h>

Sensor::Sensor(uint64_t linkId, const char * type)
{
    this->_linkId = linkId;
    this->_type = type;
}

AnalogSensor::AnalogSensor(
    uint64_t linkId, 
    const char * type
    // , uint8_t pin
    ) 
    : Sensor(linkId, type)
{
    // this->_pin = pin;
}

uint16_t AnalogSensor::getValue()
{
    return analogRead(this->_pin);
}

// I2CSensor::I2CSensor(uint64_t linkId, char * type, uint8_t SDA, uint8_t SCL, uint8_t CHIPSEL)
//     : Sensor(linkId, type)
// {
//     this->_SDA = SDA;
//     this->_SCL = SCL;
//     this->_CHIPSEL = CHIPSEL;
// }

// TODO dit afmaken maar eerst goed uitdenken