#include <ArduinoJson.h>
const size_t capacity = JSON_OBJECT_SIZE(5); // the number of
DynamicJsonDocument doc(capacity);

String jsonify(
  uint16_t soil_moisture,
  uint16_t air_humidity,
  int8_t temperature,
  uint8_t pH,
  uint8_t battery
) {
  String json; // temporary string to store the output of the json formatter

  doc["soil_moisture"] = soil_moisture;
  doc["air_humidity"] = air_humidity;
  doc["temperature"] = temperature;
  doc["pH"] = pH;
  doc["battery"] = battery;

  serializeJson(doc, json);

  return json;
}
