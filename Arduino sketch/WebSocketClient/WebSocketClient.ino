/*
   WebSocketClient.ino
   based on the example 'webSocketClient' from the webSocketsServer library
   Created on: 15.09.2020

*/

#include <Arduino.h>

#include <ESP8266WiFi.h>

//needed for wifimanager library
#include <DNSServer.h>
#include <ESP8266WebServer.h>
#include <WiFiManager.h>         //https://github.com/tzapu/WiFiManager
/* IMPORTANT
   in order to make the wifimanager work with the webserver, HTTP_HEAD must be renamed to something linke HTTP_HEAD_HTML
   This must be done in both the wifimanager.cpp and wifimanager.h files
*/


#include <WebSocketsClient.h>

#include <Hash.h>

WebSocketsClient * webSocket;

#define USE_SERIAL Serial


const char *SERVER_ADDR = "laptop-bram.local";
const uint16_t PORT = 8765;

void webSocketEvent(WStype_t type, uint8_t * payload, size_t length) {
  switch (type) {
    case WStype_DISCONNECTED:
      USE_SERIAL.printf("[WSc] Disconnected!\n");
      break;
    case WStype_CONNECTED:
      USE_SERIAL.printf("[WSc] Connected to url: %s\n", payload);
      // send message to server when Connected
      webSocket->sendTXT("Hello World!");
      break;
    case WStype_TEXT:
      USE_SERIAL.printf("[WSc] get text: %s\n", payload);
      
      disconnect(); // disconnect after transmission
      // send message to server
      // webSocket.sendTXT("message here");
      break;
    case WStype_BIN:
      USE_SERIAL.printf("[WSc] get binary length: %u\n", length);
      hexdump(payload, length);

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
  //  wifiManager.resetSettings();

  String config_ssid = "ESP_" + String(ESP.getChipId());
  char buf[13];
  config_ssid.toCharArray(buf, 13);
  wifiManager.autoConnect(buf);

  USE_SERIAL.println(jsonify(1, 2, 3, 4, 5));

  //  // server address, port and URL
  connect();
  //  webSocket.begin(SERVER_ADDR, PORT, "/");
  //
  //  // event handler
  //  webSocket.onEvent(webSocketEvent);

  //	// use HTTP Basic Authorization this is optional remove if not needed
  //	webSocket.setAuthorization("user", "Password");

  // try ever 5000 again if connection has failed
  //  webSocket.setReconnectInterval(5000);

}

void connect() {
  webSocket = new WebSocketsClient();
  webSocket->begin(SERVER_ADDR, PORT, "/");
  webSocket->onEvent(webSocketEvent);
}

void disconnect() {
  webSocket->disconnect();
  delete webSocket;
  webSocket = NULL;
}

void loop() {
  if (webSocket) webSocket->loop();
}
