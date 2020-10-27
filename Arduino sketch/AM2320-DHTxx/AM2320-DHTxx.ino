//
//    FILE: AM2320.ino
//  AUTHOR: Rob Tillaart
// VERSION: 0.1.1
// PURPOSE: AM2320 demo sketch for AM2320 I2C humidity & temperature sensor
//
// HISTORY:
// 0.1.0   2017-12-11 initial version
// 0.1.1   2020-05-03 updated to 0.2.0 version of lib.
//

#include <AM232X.h>
#include <dhtnew.h>


AM232X AM2320;
DHTNEW mySensor(16);   // ESP 16  UNO 6

void setup()
{
  Wire.begin();

  Serial.begin(115200);
  Serial.println(__FILE__);
  Serial.print("LIBRARY VERSION: ");
  Serial.println(AM232X_LIB_VERSION);
  Serial.println();

  Serial.println("Type,\t\tStatus,\tHumidity (%),\tTemperature (C)");
}

void loop()
{
  Serial.println();
  // READ DATA
  Serial.print("AM2320, \t");
  int status = AM2320.read();
  switch (status)
  {
    case AM232X_OK:
      Serial.print("OK,\t");
      break;
    default:
      Serial.print(status);
      Serial.print("\t");
      break;
  }
  // DISPLAY DATA, sensor only returns one decimal.
  Serial.print(AM2320.getHumidity(), 1);
  Serial.print(",\t\t");
  Serial.println(AM2320.getTemperature(), 1);

  Serial.print("DHT");
  Serial.print(mySensor.getType());
  Serial.print(", \t\t");
  
  int chk = mySensor.read();
  switch (chk)
  {
    case DHTLIB_OK:
      Serial.print("OK,\t");
      break;
    case DHTLIB_ERROR_CHECKSUM:
      Serial.print("CRC,\t");
      break;
    case DHTLIB_ERROR_TIMEOUT_A:
      Serial.print("TOA,\t");
      break;
    case DHTLIB_ERROR_TIMEOUT_B:
      Serial.print("TOB,\t");
      break;
    case DHTLIB_ERROR_TIMEOUT_C:
      Serial.print("TOC,\t");
      break;
    case DHTLIB_ERROR_TIMEOUT_D:
      Serial.print("TOD,\t");
      break;
    case DHTLIB_ERROR_SENSOR_NOT_READY:
      Serial.print("SNR,\t");
      break;
    case DHTLIB_ERROR_BIT_SHIFT:
      Serial.print("BS,\t");
      break;
    default:
      Serial.print("U");
      Serial.print(chk);
      Serial.print(",\t");
      break;
  }
  Serial.print(mySensor.getHumidity(), 1);
  Serial.print(",\t\t");
  Serial.println(mySensor.getTemperature(), 1);


  delay(2000);
}

// -- END OF FILE --
