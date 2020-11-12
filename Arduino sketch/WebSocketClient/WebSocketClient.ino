/*
   WebSocketClient.ino
   based on the example 'webSocketClient' from the webSocketsServer library
   Created on: 15.09.2020

*/



#include <Arduino.h>

#ifdef ESP8266
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#endif

#ifdef ESP32
#include <WiFi.h>
#include <WebServer.h>
#endif
//needed for wifimanager library
#include <DNSServer.h>

#include <WiFiManager.h>         //https://github.com/tzapu/WiFiManager
/* IMPORTANT
   in order to make the wifimanager work with the webserver, HTTP_HEAD must be renamed to something linke HTTP_HEAD_HTML
   This must be done in both the wifimanager.cpp and wifimanager.h files
*/

#include <WebSocketsClient.h>
#include "src/sensors/sensors.h"
#include "src/sensors/AM232xSensor.h"
#include "src/sensors/DHTxxSensor.h"

WebSocketsClient * webSocket;

#define USE_SERIAL Serial

char config_version[9]; // this represents the first 8 characters of an MD5 hash of the config file
const uint8_t max_sensors = 8;
uint8_t attached_sensors = 0;
size_t additional_array_size = 0;
Sensor *sensorList[max_sensors];


const char *SERVER_ADDR = "192.168.1.80";
const uint16_t PORT = 8765;

unsigned int reconnect_interval = 0;
unsigned int disconnect_timestamp = 0;
const char * exit_msg = "bye";

uint32_t chipID = 0;

void webSocketEvent(WStype_t type, uint8_t * payload, size_t length) {
  String data;
  String ESP_data;

  switch (type) {
    case WStype_DISCONNECTED:
      USE_SERIAL.printf("[WSc] Disconnected!\n");
      break;

    case WStype_CONNECTED:
      USE_SERIAL.printf("[WSc] Connected to url: %s\n", payload);

      //TODO data collection

      // send message to server when Connected
      //      ESP_data = jsonify(1, 2, 3, 4, 5);
      ESP_data = jsonData();
      webSocket->sendTXT(ESP_data);
      break;

    case WStype_TEXT:
      data = (char*)payload;
      USE_SERIAL.printf("[WSc] get text: %s\n", payload);

      if (data == exit_msg) websocket_disconnect(); // disconnect after transmission
      else if (data.substring(0, 3) == "tbr") { // tbr stand for 'time before reconnect'
        reconnect_interval = data.substring(4).toInt();
        USE_SERIAL.printf("[WSc] new reconnect interval set: %d ms\n", reconnect_interval);
      }
      else if (data.substring(0, 6) == "config") {
        bool success = false;
        success = parseConfig((char*)&payload[7]);
        if (success) USE_SERIAL.println("[ESP] RECONFIGURED");
        else USE_SERIAL.println("[ESP] Could not reconfigure");
        webSocket->sendTXT("[ESP] RECONFIGURED");
      }
      break;

    case WStype_BIN:
      USE_SERIAL.printf("[WSc] get binary length: %u\n", length);
      //      hexdump(payload, length);

      // send data to server
      // webSocket.sendBIN(payload, length);
      break;
    case WStype_PING:
      // pong will be send automatically
      USE_SERIAL.printf("[WSc] get ping\n");
      break;
    case WStype_PONG:
      // answer to a ping we send
      USE_SERIAL.printf("[WSc] get pong\n");
      break;
  }

}

void setup() {
#ifdef ESP8266
  chipID = ESP.getChipId();
#endif

#ifdef ESP32
  chipID = 0;
  for (int i = 0; i < 17; i = i + 8) {
    chipID |= ((ESP.getEfuseMac() >> (40 - i)) & 0xff) << i;
  }
#endif

  USE_SERIAL.begin(115200);

  //Serial.setDebugOutput(true);
  //  USE_SERIAL.setDebugOutput(true);

  USE_SERIAL.println();
  USE_SERIAL.println();
  USE_SERIAL.println();

  for (uint8_t t = 4; t > 0; t--) {
    USE_SERIAL.printf("[SETUP] BOOT WAIT %d...\n", t);
    USE_SERIAL.flush();
    delay(1000);
  }

  //WiFiManager
  //Local intialization. Once its business is done, there is no need to keep it around
  WiFiManager wifiManager;
  //reset saved settings
  //    wifiManager.resetSettings();

  String config_ssid = "NODE_" + String(chipID);
  char buf[13];
  config_ssid.toCharArray(buf, 13);
  wifiManager.autoConnect(buf);

  websocket_connect();

  USE_SERIAL.printf("[SETUP] Boot completed, handing controll to loop\n");
}

void websocket_connect() {
  USE_SERIAL.printf("\n[WSc] New connection initiated\n");
  webSocket = new WebSocketsClient();
  // server address, port and URL
  webSocket->begin(SERVER_ADDR, PORT, "/");
  webSocket->onEvent(webSocketEvent);

  // use HTTP Basic Authorization this is optional remove if not needed
  // webSocket.setAuthorization("user", "Password");

  // try ever 5000 again if connection has failed
  //  webSocket.setReconnectInterval(5000);
}

void websocket_disconnect() {
  USE_SERIAL.printf("[WSc] Disconnected! Next reconnect in %d s\n", reconnect_interval / 1000);
  webSocket->disconnect();
  delete webSocket;
  webSocket = NULL;
  disconnect_timestamp = millis();
}

void loop() {
  // handle websocket connection if the object exists
  if (webSocket) webSocket->loop();

  // Reconnect to the websocket once the communicated timeout period had passed
  else if (millis() - disconnect_timestamp >= reconnect_interval) websocket_connect();
}
