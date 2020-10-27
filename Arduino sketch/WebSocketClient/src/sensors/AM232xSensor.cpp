#include "sensors.h"
#include "AM232xSensor.h"
#include <AM232X.h>
#include <stdint.h>
#include <arduino.h>

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
}

uint16_t AM232xSensor::getValue(uint8_t number){
    switch (number)
    {
    case 0:
        return this->AM232x.getHumidity();
        break;
    
    default:
        return this->AM232x.getTemperature();
        break;
    }
}