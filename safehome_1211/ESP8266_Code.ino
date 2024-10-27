#include <WiFiManager.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <ESP8266WiFi.h>
#include <ArduinoJson.h>

#define ledPin A0

String serverIP = "192.168.5.227";
String geourl_raw = "";
String geourl = "";
String address = "";
String contact = "";
String sensorMode = "";
String userID = "";


void setup() {

 
  Serial.flush();
  Serial.begin(19200);
  pinMode(ledPin, OUTPUT);
  Serial.flush(); 
  

    WiFiManager wifiManager;
    
    wifiManager.setDebugOutput(false);

    wifiManager.resetSettings();


    WiFiManagerParameter userID_field("id", "Enter User ID", "", 5);
    wifiManager.addParameter(&userID_field);
    
    if (!wifiManager.autoConnect("SafeHome_ESP/AP")) {
        Serial.println("Failed to connect and hit timeout. Restarting...");
        delay(3000);
        ESP.restart();
    }
    delay(2000);
    Serial.println("SignalToArduino:Connected");
    userID = userID_field.getValue();

    delay(3000);


  fetchDataFromGeoserver();
    
  int equalSignIndex = geourl_raw.indexOf('=', geourl_raw.indexOf('=') + 1);

  String geourl = geourl_raw.substring(equalSignIndex + 1);


  Serial.println("" + contact + "");
    delay(7000);
  Serial.println("" + address + "");
    delay(6000);
  Serial.println("" + geourl + "");
    delay(4000);
  Serial.println("" + sensorMode + "");
    delay(2000);

  delay(12000); 
}

void loop() {

  if(Serial.available()>0) { 
    String jsonString = Serial.readStringUntil('\n');
    delay(100);
    Serial.print("Received JSON: ");
    Serial.println(jsonString);


    StaticJsonDocument<200> jsonDoc; 
    DeserializationError err = deserializeJson(jsonDoc, jsonString);

    if (err == DeserializationError::Ok) {
     
      int gasSensorValue = jsonDoc["Gas Sensor"];
      int smokeSensorValue = jsonDoc["Smoke Sensor"];
      String waterLevel = jsonDoc["Water Level"];

      StaticJsonDocument<200> sensorDataJson;
      sensorDataJson["Gas Sensor"] = gasSensorValue;
      sensorDataJson["Smoke Sensor"] = smokeSensorValue;
      sensorDataJson["Water Level"] = waterLevel;
      sensorDataJson["Id"] = userID;
  
      
      String sensorDataJsonStr; 
      delay(1000);
      serializeJson(sensorDataJson, sensorDataJsonStr);

      
      sendDataToServer(sensorDataJsonStr);
      sendDataToMySQL(sensorDataJsonStr);
    } else {
      Serial.println("JSON parsing failed.");
    }
  }
  delay(10000);
}

void sendDataToServer(const String& jsonStr) { 
  HTTPClient http;
  WiFiClient client;
  if (http.begin(client, "http://" + serverIP + "/safehome_1030/json_server.php")) {
    http.addHeader("Content-Type", "application/json");
    int httpResponseCode = http.POST(jsonStr);
    String payload = http.getString();
      Serial.println(payload);

    if (httpResponseCode == HTTP_CODE_OK) {
      Serial.println("Data sent successfully");

      digitalWrite(ledPin, HIGH);
      delay(150);
      digitalWrite(ledPin, LOW);
      delay(150); 
    } else {
      String payload = http.getString();
      Serial.println(payload);
      Serial.print("HTTP POST failed, error code: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  } else {
    Serial.println("Unable to connect to the server");
  }
}

void sendDataToMySQL(const String& jsonStr) { 
  HTTPClient http;
  WiFiClient client;

  if (http.begin(client, "http://" + serverIP + "/safehome_1030/sqlserver.php")) { 
    http.addHeader("Content-Type", "application/json");
    int httpResponseCode = http.POST(jsonStr);
    String payload = http.getString();
    Serial.println(payload);

    if (httpResponseCode == HTTP_CODE_OK) {
      Serial.println("Data sent to MySQL successfully");
    } else {
      Serial.print("HTTP POST failed, error code: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  } else {
    Serial.println("Unable to connect to the server");
  }
}

void fetchDataFromGeoserver() { 
  HTTPClient http;
  WiFiClient client;
  if (http.begin(client, "http://" + serverIP + "/safehome_1030/geoserver.php?action=fetchData&id=" + String(userID))) {
    int httpResponseCode = http.GET();
    if (httpResponseCode == HTTP_CODE_OK) {
      String payload = http.getString();
     


      StaticJsonDocument<512> jsonDoc; 
      DeserializationError err = deserializeJson(jsonDoc, payload);

      if (err == DeserializationError::Ok) {
        JsonObject firstElement = jsonDoc[0];

  if (firstElement.containsKey("geo_url")) { 
    geourl_raw = firstElement["geo_url"].as<String>();

  } else {
    Serial.println("Geo URL field not found in the JSON response.");
  }


  if (firstElement.containsKey("address")) { 
    address = firstElement["address"].as<String>();
    
  } else {
    Serial.println("Address field not found in the JSON response.");
  }


  if (firstElement.containsKey("contact")) { 
    contact = firstElement["contact"].as<String>();
   
  } else {
    Serial.println("Contact field not found in the JSON response.");
  }

  if (firstElement.containsKey("threshold_mode")) { 
    sensorMode = firstElement["threshold_mode"].as<String>();

  } else {
    Serial.println("Contact field not found in the JSON response.");
  }
      } else {
        Serial.println("JSON parsing failed (geoserver)");
        Serial.print("JSON deserialization failed: ");
        Serial.println(err.c_str());
      }
    } else {
      Serial.print("HTTP GET failed, error code: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  } else {
    Serial.println("Unable to connect to geoserver.");
  }
}
