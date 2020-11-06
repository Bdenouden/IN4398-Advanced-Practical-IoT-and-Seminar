#include <ArduinoJson.h>
const size_t capacity = JSON_OBJECT_SIZE(8); // the number of JSON key value pairs
const char *SOFTWARE_DATE =  "V1 " __DATE__ " " __TIME__;
//DynamicJsonDocument doc(capacity);


String jsonData() {
  const size_t capacity = JSON_OBJECT_SIZE(3 + sizeof(sensorList)) + additional_array_size; // the number of JSON key value pairs
  DynamicJsonDocument doc(capacity);
  uint8_t NOValues = 1;

  String json; // temporary string to store the output of the json formatter
  doc["chipID"] = chipID;
  doc["version"] = SOFTWARE_DATE;
  doc["config_version"] = config_version;

  for (uint8_t i = 0; i < attached_sensors; i++) {
    NOValues = sensorList[i]->getNOValues();
    if (NOValues > 1) {
      JsonArray value = doc.createNestedArray(String(sensorList[i]->getLinkId()));
      for (uint8_t j = 0; j < sensorList[i]->getNOValues(); j ++) { // cycle through all possible measures, create array and append to jsondata
        value.add(sensorList[i]->getValue(j));
      }
    }
    else {
      doc[String(sensorList[i]->getLinkId())] = sensorList[i]->getValue(1);
    }
  }


  serializeJson(doc, json);

  return json;
}
