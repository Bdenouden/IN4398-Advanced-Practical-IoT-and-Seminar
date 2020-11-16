#include "sensors.h"
#include <stdint.h>
#include <arduino.h>
#include "DHTxxSensor.h"
#include <dhtnew.h>

DHTxxSensor::DHTxxSensor(unsigned int linkId, const char *type, uint8_t pin) : Sensor(linkId, type)
{
    this->_pin = pin;
    this->_NOValues = 2;
    this->dht = new DHTNEW(pin);
}

DHTxxSensor::~DHTxxSensor()
{
    delete this->dht;
}

uint16_t DHTxxSensor::getValue(uint8_t number)
{
    // Serial.print("DHT\t\t");
    // Serial.print(this->dht->read());
    // Serial.print("\t\t");
    // Serial.print(this->dht->getHumidity());
    // Serial.print(",\t\t");
    // Serial.println(this->dht->getTemperature());
    this->dht->read(); // dummy read to sync the sensor
    switch (number)
    {
    case 0:
        return (uint16_t)(this->dht->getHumidity() * 10); // 10x for rounding
        break;

    default:
        return (uint16_t)(this->dht->getTemperature() * 10); // 10x added for rounding
        break;
    }
}