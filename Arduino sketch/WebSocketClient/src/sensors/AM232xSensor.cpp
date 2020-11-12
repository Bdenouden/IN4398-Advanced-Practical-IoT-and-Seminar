#include "sensors.h"
#include <stdint.h>
#include <arduino.h>
#include "AM232xSensor.h"
#include <AM232X.h>

AM232xSensor::AM232xSensor(
    unsigned linkId,
    const char *type,
    uint8_t SDA,
    uint8_t SCL)
    : Sensor(linkId, type)
{
    this->_SDA = SDA;
    this->_SCL = SCL;
    this->_NOValues = 2;
    Wire.begin();
}

uint16_t AM232xSensor::getValue(uint8_t number)
{
    Serial.print(this->AM232x.read());
    Serial.print("\t\t");
    Serial.print(this->AM232x.getHumidity());
    Serial.print(",\t\t");
    Serial.println(this->AM232x.getTemperature());

    switch (number)
    {
    case 0:
        return (uint16_t)(this->AM232x.getHumidity() * 10); // 10x for rounding
        break;

    default:
        return (uint16_t)(this->AM232x.getTemperature() * 10); // 10x added for rounding
        break;
    }
}