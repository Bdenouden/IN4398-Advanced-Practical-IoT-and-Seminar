#include <ArduinoJson.h>



char version[8]; // this represents the first 8 characters of an MD5 hash of the config file
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

  Serial.printf("[CONFIG] size of config: %d\n",sizeof(cfg["config"]));

  return true;
}
