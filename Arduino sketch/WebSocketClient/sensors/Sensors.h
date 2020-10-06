/* 
    Sensors.h is part of a library written for the 
	'IN4398 - Advanced Practical IoT and Seminar' course at TU Delft.
	Bram den Ouden and Maarten de Jong 

	september 2020	
*/

#ifndef Sensors_h
#define Sensors_h
#include "Sensors.h"


class Sensor
{
public:
	Sensor(String name);
	String getName();

protected:
	String _name; // e.g. temperature or DHT11
};

class AnalogSensor : public Sensor
{
public:
	AnalogSensor(uint8_t pin, String name);
	uint16_t getValue();

private:
	uint8_t _pin = A0; // pin where the analog sensor is connected
};

class I2CSensor : public Sensor
{
public:
	I2CSensor(uint8_t SDA, uint8_t SCL, uint8_t CHIPSEL, String name);

private:
	uint8_t _SDA; // pin where the analog sensor is connected
	uint8_t _SCL;
	uint8_t _CHIPSEL;

	char *_attached_sensors[5]; //e.g.  {'temperature','humidity'}
};

class Mux
{
public:
	Mux(uint8_t Z, uint8_t select_pins[]);
	void addSensor(uint32_t *sensor);
	void removeSensor(uint32_t *sensor);
	void clearSensors();

private:
	uint8_t _z;
	uint8_t _select_pins[]; //

	uint32_t _sensors[]; // array containing pointers to connected sensors
};
#endif
