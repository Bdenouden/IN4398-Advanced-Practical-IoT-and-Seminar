/*
   WebSocketClient.ino
   based on the example 'webSocketClient' from the webSocketsServer library
   Created on: 15.09.2020

*/

#include <Arduino.h>

#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>

#include <WebSocketsClient.h>

#include <Hash.h>

ESP8266WiFiMulti WiFiMulti;
WebSocketsClient * webSocket;

#define USE_SERIAL Serial

const char * ssid = "SSID";
const char * password = "Password";

const char *SERVER_ADDR = "laptop-bram.local";
const uint16_t PORT = 8765;

void webSocketEvent(WStype_t type, uint8_t * payload, size_t length) {

  USE_SERIAL.print(type);
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
//
//      USE_SERIAL.printf("exit message: %s\n ",((char*)payload == "bye") ? "true":"false");
//        if ((char*)payload ==(char*)"bye"){
//          USE_SERIAL.print("test");
          disconnect(); // disconnect after transmission
//        }

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
  // USE_SERIAL.begin(921600);
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

  WiFiMulti.addAP(ssid, password);

  //WiFi.disconnect();
  while (WiFiMulti.run() != WL_CONNECTED) {
    delay(100);
  }

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
