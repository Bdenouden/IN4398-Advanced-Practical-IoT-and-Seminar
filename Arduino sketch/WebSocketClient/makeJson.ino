#include <ArduinoJson.h>
const size_t capacity = JSON_OBJECT_SIZE(8); // the number of JSON key value pairs
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
  doc["config_version"] = config_version;
  doc["battery"] = battery;
  doc["soil_moisture"] = soil_moisture;
  doc["air_humidity"] = air_humidity;
  doc["temperature"] = temperature;
  doc["pH"] = pH;

  serializeJson(doc, json);

  return json;
}


String jsonData() {
  const size_t capacity = JSON_OBJECT_SIZE(3+sizeof(sensorList)); // the number of JSON key value pairs
  DynamicJsonDocument doc2(capacity);

  String json; // temporary string to store the output of the json formatter
  doc2["chipID"] = ESP.getChipId();
  doc2["version"] = SOFTWARE_DATE;
  doc2["config_version"] = config_version;

  for(uint8_t i = 0; i<attached_sensors;i++){
    doc2[String(sensorList[i]->getLinkId())] = sensorList[i]->getValue();
  }
  

  serializeJson(doc2, json);

  return json;
}
