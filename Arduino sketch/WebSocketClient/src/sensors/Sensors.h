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
	void echo();
	unsigned int getLinkId();
	virtual uint16_t getValue() = 0;
	// virtual ~Sensor() = 0;

// protected:
	unsigned int _linkId = 0;  // e.g. 8
	const char *_type = ""; // e.g. analog/I2C
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
	uint16_t getValue();

private:
	uint8_t _pin;// = A0; // pin where the analog sensor is connected
};

class I2CSensor : public Sensor
{
public:
	I2CSensor(unsigned int linkId, const char *type, uint8_t SDA, uint8_t SCL, uint8_t CHIPSEL);

private:
	uint8_t _SDA; // pin where the analog sensor is connected
	uint8_t _SCL;
	uint8_t _CHIPSEL;

	char *_attached_sensors[5]; //e.g.  {'temperature','humidity'}
};

// class Mux
// {
// public:
// 	Mux(uint8_t Z, uint8_t select_pins[]);
// 	void addSensor(uint32_t *sensor);
// 	void removeSensor(uint32_t *sensor);
// 	void clearSensors();

// private:
// 	uint8_t _z;
// 	uint8_t _select_pins[]; //

// 	uint32_t _sensors[]; // array containing pointers to connected sensors
// };
#endif
