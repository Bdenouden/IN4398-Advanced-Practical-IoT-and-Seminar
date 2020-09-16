#include <ArduinoJson.h>
const size_t capacity = JSON_OBJECT_SIZE(7); // the number of JSON key value pairs
const char *SOFTWARE_DATE =  "V1 " __DATE__ " " __TIME__;
DynamicJsonDocument doc(capacity);

String jsonify(
  uint16_t soil_moisture,
  uint16_t air_humidity,
  int8_t temperature,
  uint8_t pH,
  uint8_t battery
) {
  String json; // temporary string to store the output of the json formatter
  doc["chipID"] = ESP.getChipId();
  doc["version"] = SOFTWARE_DATE;
  doc["battery"] = battery;
  doc["soil_moisture"] = soil_moisture;
  doc["air_humidity"] = air_humidity;
  doc["temperature"] = temperature;
  doc["pH"] = pH;

  serializeJson(doc, json);

  return json;
}
