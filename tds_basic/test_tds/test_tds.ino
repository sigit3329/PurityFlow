#include <EEPROM.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <WiFi.h>

#define TdsSensorPin 35
#define VREF 3.3      // Analog reference voltage (Volts) of the ADC
#define SCOUNT  30    // Number of sample points for averaging
#define alarm 19 //buzzer HIGH trigger
#define led1 16 //biru LOW trigger
#define led2 17 //hijau LOW trigger
#define led3 18 //merah LOW trigger
#define start 33 //LOW trigger (momentary)
#define jeda 25 //LOW trigger (bukan momentary, ketika di klik tombol bakal stay)
#define kran 26 //relay LOW trigger
#define reset 23 //LOW trigger (momentary)
#define resetTime 5000 // Time in milliseconds for reset (5 seconds)

LiquidCrystal_I2C lcd(0x27, 20, 4);

unsigned long previousMillis = 0;
unsigned long buttonPressTime = 0; // Variable to store the time when the button is pressed
int buzzerState = LOW;
int led1State = HIGH;
int led2State = HIGH;
int led3State = HIGH;


int analogBuffer[SCOUNT];    // Store the analog values read from the ADC
int analogBufferTemp[SCOUNT];
int analogBufferIndex = 0, copyIndex = 0;
float averageVoltage = 0, tdsValue = 0, temperature = 25; // Default temperature is 25°C

bool countdownActive = false;
bool countdownPaused = false;
const unsigned long interval = 1000; // 1 second interval
int countdownValue = 10;
int menu = 0;
bool hasRun = false;
const int EEPROM_ADDRESS = 13; // Alamat di EEPROM dimana nilai akan disimpan

const char* ssid = "sigitberkarya.com"; //Hostname Wifi
const char* password = "kontoljaran"; //Password Hostname
const char* host = "192.168.0.2"; //Ip Host Laptop (wifi ip)

void setup() {
    Serial.begin(115200);
    // Inisialisasi EEPROM
    EEPROM.begin(512);
    pinMode(start, INPUT_PULLUP);
    pinMode(jeda, INPUT_PULLUP);
    pinMode(kran, OUTPUT);
    pinMode(alarm, OUTPUT);
    pinMode(led1, OUTPUT);
    pinMode(led2, OUTPUT);
    pinMode(led3, OUTPUT);
    pinMode(reset, INPUT_PULLUP);
    pinMode(TdsSensorPin, INPUT);
    //def awal mati
    digitalWrite(alarm, LOW);
    digitalWrite(kran, HIGH);
    delay(200);
    digitalWrite(led1, led1State);
    digitalWrite(led2, led2State);
    digitalWrite(led3, led3State);

    
    lcd.init();
    lcd.backlight();
    Serial.printf("Connecting to %s ", ssid);
    lcd.setCursor(0, 0);
    lcd.print("Connecting to Server");
    lcd.setCursor(0, 1);
    lcd.print("     PurityFlow");
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
      delay(500);
      Serial.print(".");
      lcd.setCursor(0, 2);
      lcd.print("         XX         ");
    }
    Serial.println(" connected");
    lcd.setCursor(0, 3);
    lcd.print("CONNECTED TO SERVER");
    delay(1000);

    
    lcd.setCursor(0, 0);
    lcd.print("_____BOOTING..._____");
    lcd.setCursor(0, 1);
    lcd.print("_____PurityFlow_____");
    lcd.setCursor(0, 2);
    lcd.print("_________BY_________");
    lcd.setCursor(0, 3);
    lcd.print("___Sigit.Berkarya___");
    delay(2000);
    lcd.clear();
}

void resetEEPROM() {
    // Reset EEPROM at address 13
    EEPROM.put(EEPROM_ADDRESS, 0);
    EEPROM.commit();
}

void countdownAnimation() {
    for (int i = 10; i >= 0; i--) {
        lcd.clear();
        lcd.setCursor(0, 3);
        lcd.print("Selesai: ");
        lcd.setCursor(9, 3);
        lcd.print(i);
        lcd.print(" detik");
        delay(1000);
    }
    lcd.clear();
}


void startCountdown() {
    countdownActive = true;
    countdownPaused = false;
    previousMillis = millis();
    countdownValue = 10; // Reset the countdown value
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print(" MENGHITUNG MUNDUR ");
    digitalWrite(kran, LOW);
}

void updateCountdown() {
    unsigned long currentMillis = millis();
    if (currentMillis - previousMillis >= interval) {
        previousMillis = currentMillis;
        if (countdownValue >= 0) {
            lcd.setCursor(0, 1);
            lcd.print("Waktu: ");
            lcd.print(countdownValue);
            lcd.print(" detik   ");
            digitalWrite(kran, LOW);
            countdownValue--;
        } else {
            countdownActive = false;
            lcd.setCursor(0, 2);
            lcd.print("PENGISIAN SELESAI!");
            digitalWrite(kran, HIGH);
            delay(1000);
            tambah_value_galon();
            menu = 0;
        }
    }
}

void tambah_value_galon() {
    int bootCount = EEPROM.read(EEPROM_ADDRESS);
    bootCount++;
    EEPROM.write(EEPROM_ADDRESS, bootCount);
    EEPROM.commit();

}

void myFunction() {
    // Isi fungsi yang ingin dijalankan sekali
    lcd.setCursor(0, 0);
    lcd.print("     PURITYFLOW");
    lcd.setCursor(0, 1);
    lcd.print("  MODE MENGISI AIR  ");
    lcd.setCursor(0, 2);
    lcd.print(" KLIK TOMBOL START  ");
    lcd.setCursor(0, 3);
    lcd.print(" UNTUK MENGISI AIR  ");
}

void mode_mengisi() {
    if (!hasRun) {
        myFunction();   // Panggil fungsi
        hasRun = true;  // Set variabel menjadi true agar fungsi tidak dijalankan lagi
    }

    unsigned long currentMillis = millis();
    static unsigned long resetStartTime = 0;
    static unsigned long startPressTime = 0;
    int buttonStartState = digitalRead(start);
    int buttonJedaState = digitalRead(jeda);

    if (buttonStartState == LOW) {
        led1State = LOW;
        digitalWrite(led1, led1State);
        delay(100);
        led1State = HIGH;
        digitalWrite(led1, led1State);
        digitalWrite(kran, LOW);
        buzzerState = HIGH;
        digitalWrite(alarm, buzzerState);
        delay(100);
        buzzerState = LOW;
        digitalWrite(alarm, buzzerState);
        delay(100);
        buzzerState = HIGH;
        digitalWrite(alarm, buzzerState);
        delay(100);
        buzzerState = LOW;
        digitalWrite(alarm, buzzerState);
        startCountdown();
    }

    if (buttonJedaState == LOW) {
        led2State = LOW;
        digitalWrite(led2, led2State);
        delay(100);
        led2State = HIGH;
        digitalWrite(led2, led2State);
        countdownPaused = true;
        lcd.setCursor(0, 3);
        lcd.print("Paused          ");
        digitalWrite(kran, HIGH);
        buzzerState = LOW;
        digitalWrite(alarm, buzzerState);
    } else {
        countdownPaused = false;
    }

    if (countdownActive && !countdownPaused) {
        updateCountdown();
    }
    delay(50);
}

int getMedianNum(int bArray[], int iFilterLen) {
    int bTab[iFilterLen];
    for (byte i = 0; i < iFilterLen; i++)
        bTab[i] = bArray[i];

    int i, j, bTemp;
    for (j = 0; j < iFilterLen - 1; j++) {
        for (i = 0; i < iFilterLen - j - 1; i++) {
            if (bTab[i] > bTab[i + 1]) {
                bTemp = bTab[i];
                bTab[i] = bTab[i + 1];
                bTab[i + 1] = bTemp;
            }
        }
    }

    if ((iFilterLen & 1) > 0)
        bTemp = bTab[(iFilterLen - 1) / 2];
    else
        bTemp = (bTab[iFilterLen / 2] + bTab[iFilterLen / 2 - 1]) / 2;

    return bTemp;
}

void mode_monitoring() {
    static unsigned long analogSampleTimepoint = millis();
    if (millis() - analogSampleTimepoint > 40U) { // Every 40 milliseconds, read the analog value
        analogSampleTimepoint = millis();
        analogBuffer[analogBufferIndex] = analogRead(TdsSensorPin); // Read the analog value and store it in the buffer
        analogBufferIndex++;
        if (analogBufferIndex == SCOUNT)
            analogBufferIndex = 0;
    }

    static unsigned long printTimepoint = millis();
    if (millis() - printTimepoint > 800U) { // Every 800 milliseconds, process and print the TDS value
        printTimepoint = millis();
        for (copyIndex = 0; copyIndex < SCOUNT; copyIndex++)
            analogBufferTemp[copyIndex] = analogBuffer[copyIndex];

        averageVoltage = getMedianNum(analogBufferTemp, SCOUNT) * (float)VREF / 4096.0; // Convert ADC value to voltage

        float compensationCoefficient = 1.0 + 0.02 * (temperature - 25.0); // Temperature compensation formula
        float compensationVoltage = averageVoltage / compensationCoefficient; // Apply temperature compensation

        // Convert the voltage to TDS value
        tdsValue = (133.42 * compensationVoltage * compensationVoltage * compensationVoltage
                    - 255.86 * compensationVoltage * compensationVoltage
                    + 857.39 * compensationVoltage) * 0.5;

        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("     PURITYFLOW");
        lcd.setCursor(0, 1);
        lcd.print("TDS      : ");
        lcd.print(tdsValue, 0);
        lcd.print(" ppm");
        if (tdsValue >= 50 && tdsValue <= 150) {
            lcd.setCursor(0, 2);
            lcd.print("KUALITAS : TERBAIK ");
        } else if (tdsValue > 150 && tdsValue <= 250) {
            lcd.setCursor(0, 2);
            lcd.print("KUALITAS : BAIK    ");
        } else if (tdsValue > 250 && tdsValue <= 300) {
            lcd.setCursor(0, 2);
            lcd.print("KUALITAS: CUKUP    ");
        } else if (tdsValue > 300 && tdsValue <= 500) {
            lcd.setCursor(0, 2);
            lcd.print("KUALITAS : BURUK   ");
        } else {
            lcd.setCursor(0, 2);
            lcd.print("KUALITAS : WARNING!");
        }
        lcd.setCursor(0, 3);
        lcd.print("TOTAL    : ");
        lcd.print(EEPROM.read(EEPROM_ADDRESS));
        lcd.print("/250");
        hasRun = false;
    }
}

unsigned long LTimer;
void Kirim() {
    WiFiClient client;
    unsigned long NTimer = millis();
    if (NTimer - LTimer > 60000) {
        LTimer = NTimer;
        Serial.printf("\n[Connecting to %s ... ", host);
        if (client.connect(host, 80)) {
            Serial.println("connected]");
            int eepromValue = EEPROM.read(EEPROM_ADDRESS);
            String url = "/PurityFlow/public/values/" + String(tdsValue) + "/" + String(eepromValue);
            Serial.println("[Sending a request]");
            client.print(String("GET ") + url + " HTTP/1.1\r\n" +
                         "Host: " + host + "\r\n" +
                         "Connection: close\r\n\r\n");
            // Serial.println(client.status());
            Serial.println("[Response:]");
            while (client.connected() || client.available()) {
                if (client.available()) {
                    String line = client.readStringUntil('\n');
                    Serial.println(line);
                }
            }
            client.stop();
            Serial.println("\n[Disconnected]");
        } else {
            Serial.println("connection failed!]");
            client.stop();
        }
    }
}

void loop() {
    Kirim();
    // Serial.println(menu);
    static unsigned long pauseStartTime = 0;
    int buttonResetState = digitalRead(reset);

      if (menu == 0) {
          mode_monitoring();
      }

      if (menu == 1) {
          mode_mengisi();
      }

    if (buttonResetState == LOW) {
        lcd.clear();
        menu++;
        if (menu > 1) {
            menu = 0;
        }
        led3State = LOW;
        digitalWrite(led3, led3State);
        delay(100);
        led3State = HIGH;
        digitalWrite(led3, led3State);
        buttonPressTime = millis(); // Record the time when the button is pressed
        while (digitalRead(reset) == LOW); // Wait until the button is released
        unsigned long pressDuration = millis() - buttonPressTime; // Calculate the duration the button was pressed

        if (pressDuration >= resetTime) { // If button is pressed for at least 5 seconds
            resetEEPROM(); // Call function to reset EEPROM
            lcd.clear(); // Clear the LCD screen
            lcd.setCursor(0, 0); // Set cursor to the first position
            lcd.print("EEPROM Reset!"); // Display reset message
            delay(4000); // Wait for 2 seconds
            lcd.clear(); // Clear the LCD screen
            lcd.print("DONE"); // Display initial message
            delay(1000);
            menu = 0;
        }
    }
}
