#include <ArduinoJson.h>
#include "src/sensors/sensors.h"


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

  attached_sensors = cfg["config"].size(); //cfg["config"] is a JsonArray object
  uint8_t invalid_sensors = 0;
  Serial.printf("[CONFIG] size of config: %d\n", attached_sensors);

  // delete old config
  for (int i = 0; i++; i < max_sensors) {
    delete sensorList[i];
  }
  additional_array_size = 0;

  // allocate new sensors
  for (int i = 0; i < attached_sensors; i++) {
    JsonObject sensor = cfg["config"][i];
    unsigned int linkId = sensor["link_id"].as<unsigned int>();
    const char* type = sensor["type"].as<char*>();
    Serial.printf("\t[%d] -> Link-id: %d, type: %s\n", i, linkId, type);

    if (strcmp("analog", type) == 0) {
      sensorList[i] = new AnalogSensor(
        linkId,
        type,
        sensor["pins"][0] // first number in pin list
      );
    }
    else if (strcmp("I2C", type) == 0) {
      Serial.println("[CONFIG] I2C sensor type found");
      // TODO implement pins
      invalid_sensors++; // remove after I2C is implemented
    }
    else if (strcmp("am232x", type) == 0) {
      additional_array_size += JSON_ARRAY_SIZE(2); // humidity and temperature
      sensorList[i] = new AM232xSensor(
        linkId,
        type,
        22,
        21
        );
    }
    else if (strcmp("dhtxx", type) == 0) {
      additional_array_size += JSON_ARRAY_SIZE(2);
      sensorList[i] = new DHTxxSensor(
        linkId,
        type,
        sensor["pins"][0] // first number in pin list
      );
    }
    else {
      Serial.printf("[CONFIG] Invalid sensor type: %s\n", type);
      invalid_sensors++;
    }
  }


  strcpy(config_version, cfg["config-version"]);

  // avoid null pointer error by adjusting for invalid sensors
  attached_sensors = attached_sensors - invalid_sensors;
  return true;
}
