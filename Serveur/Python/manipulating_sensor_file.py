#!/usr/bin/env python

import sys
import random
import os

sensor_file_emulator_path = "/home/ysf/embedded_soft_project/test/sensor_file_emulator.txt"
def init_sensor():
	if (os.path.isfile(sensor_file_emulator_path)==False) or (os.path.getsize(sensor_file_emulator_path) == 0):
		f = open(sensor_file_emulator_path, "w")
		f.write("50.0")
		f.close
		
		
def get_sensor_value():
	f = open(sensor_file_emulator_path, "r")
	sensor_value = float(f.read())
	f.close()
	return sensor_value 
	
def set_sensor_value():
	sensor_value = get_sensor_value() - (random.uniform(0,10)*0.1)
	f = open(sensor_file_emulator_path, "w")
	f.write(str(sensor_value) + "" )
	print("value {} added to sensor file".format(sensor_value))
	f.close()
	
def increment_level_while_pump_turned_on(): 
	sensor_value = get_sensor_value() + 2
	f = open(sensor_file_emulator_path, "w")
	f.write(str(sensor_value) + "" )
	print("value {} added to sensor file".format(sensor_value))
	f.close()
	
#while True :

#	init_sensor()
#	set_sensor_value()
#	time.sleep(10)
		
