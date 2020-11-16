INSERT INTO `sensor_types` ( `name`, `type`, `rawMinVal`, `rawMaxVal`, `minVal`, `maxVal`, `unit`) VALUES
('Analog 10bit', 'analog', 0, 1023, 0, 100, ''),
('am2320', 'am232x', 0, 100, 0, 100, '["%","°C"]'),
('am2321', 'am232x', 0, 100, 0, 100, '["%","°C"]'),
('Analog 12bit', 'analog', 0, 4095, 0, 100, ''),
('DHT11', 'dhtxx', 0, 100, 0, 100, '["%","°C"]'),
('DHT22', 'dhtxx', 0, 100, 0, 100, '["%","°C"]');