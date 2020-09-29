#include "sensors.h"


Sensor::Sensor(String name)
{
    this->_name = name;
}

String Sensor::getName()
{
    return this->_name;
}

AnalogSensor::AnalogSensor(uint8_t pin, String name)
{
    this->_pin = pin;
    this->_name = name;
}

uint16_t AnalogSensor::getValue()
{
    return analogRead(this->_pin);
}

I2CSensor::I2CSensor(uint8_t SDA, uint8_t SCL, uint8_t CHIPSEL, String name)
{
    this->_SDA = SDA;
    this->_SCL = SCL;
    this->_CHIPSEL = CHIPSEL;
    this->_name = name;
}

// TODO dit afmaken maar eerst goed uitdenken