/* 
    Sensors.h is part of a library written for the 
	'IN4398 - Advanced Practical IoT and Seminar' course at TU Delft.
	Bram den Ouden and Maarten de Jong 

	september 2020	
*/

#ifndef Sensors_h
#define Sensors_h
#include "Sensors.h"
#include <stdint.h>

class Sensor
{
public:
	Sensor();
	Sensor(unsigned int linkId, const char *type);
	unsigned int getLinkId();
	virtual uint16_t getValue(uint8_t number) = 0;
	uint8_t getNOValues(); // how many values does this sensor measure
	// virtual ~Sensor() = 0;

protected:
	unsigned int _linkId = 0;  // e.g. 8
	const char *_type = ""; // e.g. analog/I2C
	uint8_t _NOValues = 1;
	// uint8_t _pin;
	// uint8_t _SDA; 
	// uint8_t _SCL;
	// uint8_t _CHIPSEL;
};

class AnalogSensor : public Sensor
{
public:
	AnalogSensor(unsigned int linkId, const char *type, uint8_t pin);
	void echo();
	uint16_t getValue(uint8_t number);

private:
	uint8_t _pin;// = A0; // pin where the analog sensor is connected
};

// class I2CSensor : public Sensor
// {
// public:
// 	I2CSensor(unsigned int linkId, const char *type, uint8_t SDA, uint8_t SCL, uint8_t ADDR);

// private:
// 	uint8_t _SDA; // pin where the analog sensor is connected
// 	uint8_t _SCL;
// 	uint8_t _ADDR;
// };




class DHTxxSensor : public Sensor
{
	public:
		DHTxxSensor(unsigned int linkId, const char *type, uint8_t pin);
		uint16_t getValue();
		
};


#endif
