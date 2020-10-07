#include <ArduinoJson.h>
#include "src/sensors/sensors.h"


char config_version[9]; // this represents the first 8 characters of an MD5 hash of the config file
StaticJsonDocument<1040> cfg;

bool parseConfig(char* json) {
  // Deserialize the JSON
  DeserializationError error = deserializeJson(cfg, json);
  // Test if parsing succeeds.
  if (error) {
    Serial.print(F("deserializeJson() failed: "));
    Serial.println(error.c_str());
    Serial.println(json);
    return false;
  }

  uint8_t NOSensors = cfg["config"].size(); //cfg["config"] is a JsonArray object
  Serial.printf("[CONFIG] size of config: %d\n", NOSensors);

  // delete old config
  for (int i = 0; i++; i < sizeof(sensorList)) {
    delete sensorList[i];
  }
  // allocate new sensors
  for (int i = 0; i < NOSensors; i++) {
    JsonObject sensor = cfg["config"][i];
    Serial.printf("[%d] -> Link-id: ", i);
    Serial.print(sensor["link-id"].as<unsigned int>());
    Serial.print(", type: ");
    Serial.println(sensor["type"].as<char*>());
    
    const char* type = sensor["type"].as<char*>();
    
    sensorList[i] = new AnalogSensor(
      sensor["link-id"].as<unsigned int>(),
      type
    );
    // TODO implement pins
  }




  strcpy(config_version, cfg["config-version"]);

  return true;
}
