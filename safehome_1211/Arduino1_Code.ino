#include <ArduinoJson.h>
#include <SoftwareSerial.h>

#define smokeSensorPin (1) 
#define gasSensorPin A0
#define waterSensorPin A2
#define gLed_pin A3
#define rLed_pin A4

// GSM SIM900A RX TX PINS
SoftwareSerial gsmSerial(2, 3);

int smokeSensorData; 
int gasSensorData;

// Water Sensor Variables 
int lowerThreshold = 420;
int upperThreshold = 520;
int water_val = 0;  // Value for storing water level

// SMS Alert Time Interval Variables
unsigned long previousMillis = 0;
const unsigned long interval = 60000;

// For receiveMsg()
unsigned long lastSmsCheckTime = 0;
unsigned long lastLoopTime = 0;
unsigned long loopInterval = 15000; // 15-second interval for the main loop
unsigned long smsCheckInterval = 2000;

String bfpNum = "09331877460"; // Sample BFP Number for Testing

// Threshold Values
int gasThreshold = 200;
int smokeThreshold = 350;

//Variables for receiving geoUrl, address & contact no. data from ESP8266
String contact;
String geourl;
String address;
String sensorMode;

//Variables for SMS Function
String msgBFP;
String smsMsg;
String senVal;
String message = "";
bool recSMS = false; 
bool waterSen = false;

//-----------------------------------VOID SETUP()---------------------------------------------------------------------------------------------------------------------------------------
void setup() {
  Serial.begin(19200);
  gsmSerial.begin(19200);

// Clear the serial buffer
  Serial.flush();
  gsmSerial.flush();

// Setup LEDs
  pinMode(gLed_pin, OUTPUT);
  pinMode(rLed_pin, OUTPUT);

// Wait for ESP to connect to WiFi
  standbyESP();
  delay(4000);

// Receive URL, Addr, Contact from ESP8266 -------------------------------------------------------------------------------------------------------------------------------------------
  fetchContact();
  delay(100);
  fetchAddr();
  delay(100);
  fetchURL();
  delay(100);
  fetchSensorMode();
  delay(1000);

// Check Sensor Sensitivity Value
    if(sensorMode == "Low") {
      gasThreshold = 300;
      smokeThreshold = 450; 
    } else if(sensorMode == "Average") {
      gasThreshold = 150;
      smokeThreshold = 300; 
    } else if(sensorMode == "High") {
      gasThreshold = 100;
      smokeThreshold = 250; 
    } else {
      gasThreshold = 150;
      smokeThreshold = 300; 
    }
  //Serial.println(gasThreshold);
  //Serial.println(smokeThreshold);

// Prepare SMS Message Structure for BFP
  msgBFP = smsMsg; 

//AT Command for msg receiving
  gsmSerial.println("AT+CNMI=2,2,0,0,0");
  delay(6000);
}

//-------------VOID LOOP------------------------------------------------------------------------------------------------------------------------------------------------------------------
void loop() { 
  unsigned long currentMillis = millis();
  unsigned long currentTime = millis();

 if (currentTime - lastSmsCheckTime >= smsCheckInterval) { //--------------------------------------------------------------------------------------------------------------------------
  if (recSMS) { 
    receiveMsg(); // Check for SMS messages
      }
      lastSmsCheckTime = currentTime; // Update the last SMS check time
  } //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

// SENSOR READINGS // ------------------------------------------------------------------------------------------------------------------------------------------------------------------
  if (currentTime - lastLoopTime >= loopInterval) {
  
  // GAS SENSOR READING
  gasSensorData = analogRead(gasSensorPin); 
    if(gasSensorData >= gasThreshold){  
      if (currentMillis - previousMillis >= interval) {
        senVal = String(gasSensorData);
        delay(100);
        smsMsg = "ALERT! GAS Detected!";
        delay(100);
        userSMS(contact, "ALERT! GAS DETECTED");
        delay(3300);
        recSMS = true; // Enable receiveMsg()
        previousMillis = currentMillis;  // Reset the timer
      }
    }

  // SMOKE SENSOR READING
  smokeSensorData = analogRead(smokeSensorPin);
  if(smokeSensorData >= smokeThreshold){    
    if (currentMillis - previousMillis >= interval) {
      senVal = String(smokeSensorData);
      delay(100);
      smsMsg = "ALERT! SMOKE Detected!";
      delay(100);
      userSMS(contact, "ALERT! SMOKE DETECTED");
      delay(3300);
      recSMS = true; // Enable receiveMsg()
      previousMillis = currentMillis;  // Reset the timer
      }    
  }

  // Create a JSON object
  StaticJsonDocument<200> jsonDoc; 
  // Add sensor readings to the JSON object
  jsonDoc["Gas Sensor"] = gasSensorData;
  jsonDoc["Smoke Sensor"] = smokeSensorData;

  // WATER LEVEL READING
  int waterLevel = readSensor();

  if (waterLevel == 0) {
    jsonDoc["Water Level"] = "Empty";

  } else if (waterLevel > 0 && waterLevel <= lowerThreshold) {
    jsonDoc["Water Level"] = "Low";

  } else if (waterLevel > lowerThreshold && waterLevel <= upperThreshold) {
    jsonDoc["Water Level"] = "Medium";
      if (currentMillis - previousMillis >= interval) {    
        waterSen = true;
        delay(100);
        smsMsg = "ALERT! WATER Level Detected! (MED)"; 
        userSMS(contact, "ALERT! WATER DETECTED");
        delay(3300);
        recSMS = true; // Enable receiveMsg()
        previousMillis = currentMillis;  // Reset the timer
      }

  } else if (waterLevel > upperThreshold) {
    jsonDoc["Water Level"] = "High";
    if (currentMillis - previousMillis >= interval) { 
      waterSen = true;
      delay(100); 
      smsMsg = "ALERT! WATER Level Detected! (HIGH)"; 
      userSMS(contact, "ALERT! WATER DETECTED");
      delay(3300);
      recSMS = true; // Enable receiveMsg()
    }
  }

  // Serialize the JSON object to a string
  String jsonString;
  serializeJson(jsonDoc, jsonString);
  // Print the JSON string to the Serial monitor
  Serial.println(jsonString);

  lastLoopTime = currentTime;
	 //delay(15000); 
  }
}

//////// FUNCTIONS ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

int readSensor() { // For Water Level Sensor
	//digitalWrite(waterSensorPower, HIGH); 
	delay(10);
	water_val = analogRead(waterSensorPin);
	//digitalWrite(waterSensorPower, LOW);
	return water_val;
}

// Wait for ESP8266 to connect to WiFi Network 
void standbyESP() { 
    while (!Serial.available()) {
    delay(4000);
    Serial.print(".");
  }
  delay(1000);

  // Read the signal
  String signal = Serial.readStringUntil('\n');
  message = signal;
  //Serial.println(signal);

  while (message.indexOf("SignalToArduino:Connected") == -1){
    digitalWrite(rLed_pin, HIGH);
    message = Serial.readStringUntil('\n');
  }

  Serial.println("ESP8266 is connected to Wi-Fi");
  digitalWrite(rLed_pin, LOW);
  digitalWrite(gLed_pin, HIGH);
}

void fetchContact() {
    if (Serial.available() > 0) { // CONTACT NO. Fetching //
    contact = Serial.readStringUntil('\n');
    delay(100); 
    //userContact = contact.substring(1);
    Serial.println(contact);
    delay(6500);
  } else {
    Serial.println("No Contact No.");
    digitalWrite(rLed_pin, HIGH);
    delay(2500);
  }
}

void fetchAddr() {
    if (Serial.available() > 0) { // ADDRESS Fetching //
    address = Serial.readStringUntil('\n');
    delay(100);
    Serial.println(address);
    delay(5600);
  } else {
    Serial.println("No Address");
    digitalWrite(rLed_pin, HIGH);
    delay(2500);
  }
}

void fetchURL() {
    if (Serial.available() > 0) { // GEO URL Fetching //
    geourl = Serial.readStringUntil('\n');
    Serial.println(geourl);
    delay(4000);
  } else {
    Serial.println("No LatLng URL");
    digitalWrite(rLed_pin, HIGH);
    delay(2500);
  }
}

void fetchSensorMode() {
    if (Serial.available() > 0) { // GEO URL Fetching //
    sensorMode = Serial.readStringUntil('\n');
    cleanSenMode(sensorMode);
    Serial.println(sensorMode);
    delay(3000);
  } else {
    Serial.println("No Sensor Mode");
    digitalWrite(rLed_pin, HIGH);
    delay(2500);
  }
}

// Check for incoming SMS messages (For "SOS")
void receiveMsg() {
  if (gsmSerial.available()>0) {
    String inchar = gsmSerial.readStringUntil('\n'); // Read info from GSM Serial Monitor
    message = inchar;
    Serial.print(inchar);  // Print received character for debugging

    // Check if the message contains "SOS"
    if (message.indexOf("SOS") != -1) { 
      Serial.println("SOS Confirmed");
      if(waterSen) {
        sendMsgBFP1(msgBFP);
        delay(8000);
        waterSen = false;
      } else {
        sendMsgBFP(msgBFP);
        delay(8200);
      }
      recSMS = false;
      smsMsg = "";
      message = "";  // Clear the message variable
      }
    }
}

void cleanSenMode(String &senMode) {
  // Remove leading and trailing whitespace
  senMode.trim();
  // Remove newline characters
  senMode.replace("\n", "");
}

void cleanPhoneNumber(String &contact) {
  // Remove leading and trailing whitespace
  contact.trim();
  // Remove newline characters
  contact.replace("\n", "");
}

void userSMS(String phoneNumber, String message) {
  // Clean the phone number
  cleanPhoneNumber(phoneNumber);
  delay(50);

  gsmSerial.println("AT+CMGF=1"); // Set SMS text mode
  delay(1000);
  gsmSerial.print("AT+CMGS=\""); 
  gsmSerial.print(phoneNumber); // Use the provided phoneNumber parameter
  gsmSerial.print("\"\r"); 
  delay(300);
  gsmSerial.println(); 
  gsmSerial.println(message); 
  gsmSerial.println("Send \"SOS\" to alert local BFP "); 
  delay(1500);
  gsmSerial.write(26);
  delay(500);
  Serial.println("SMS sent!");
}

void sendMsgBFP(String message) {
  String url1 = String("google.com/maps?f=q&q="); 

  gsmSerial.println("AT+CMGF=1"); // Set SMS text mode
  delay(1000);
  gsmSerial.println("AT+CMGS=\"" + bfpNum + "\"\r");  // "09297194570"
  delay(500);
 
  gsmSerial.println(smsMsg); 
  delay(250);
  gsmSerial.print("Sensor Reading: "); 
  delay(150);
  gsmSerial.print(senVal); 
  // delay(250);
  // gsmSerial.println("ppm"); 
  // delay(250);

  gsmSerial.println();
  gsmSerial.println(address); 
  delay(2100);
  gsmSerial.print(url1);
  delay(1500); 
  gsmSerial.print(geourl);
  delay(1500); 

  gsmSerial.write(26);
  delay(500);
  gsmSerial.println();
  Serial.println("BFP message sent!");
}

void sendMsgBFP1(String message) {
  String url1 = String("google.com/maps?f=q&q="); 

  gsmSerial.println("AT+CMGF=1"); // Set SMS text mode
  delay(1000);
  gsmSerial.println("AT+CMGS=\"09331877460\"\r");  
  delay(500);
 
  gsmSerial.println(smsMsg); 
  delay(200);
  gsmSerial.println();
  gsmSerial.println(address); 
  delay(2100);
  gsmSerial.print(url1);
  delay(1500); 
  gsmSerial.print(geourl);
  delay(1500); 

  gsmSerial.write(26);
  delay(500);
  gsmSerial.println();
  Serial.println("BFP message sent!");
}