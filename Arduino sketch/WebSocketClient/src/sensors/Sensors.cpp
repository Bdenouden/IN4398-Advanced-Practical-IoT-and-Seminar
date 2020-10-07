#include "sensors.h"
#include <stdint.h>
#include <arduino.h>

Sensor::Sensor(unsigned int linkId, const char * type)
{
    this->_linkId = linkId;
    this->_type = type;
}

unsigned int Sensor::getLinkId(){
    return this->_linkId;
    // return 42;
}

void Sensor::echo(){
    Serial.println("echo");
}

AnalogSensor::AnalogSensor(
    unsigned int linkId, 
    const char * type
    , uint8_t pin
    ) 
    : Sensor(linkId, type)
{
    this->_pin = pin;
}

void AnalogSensor::echo(){
    Serial.println("analog - echo");
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