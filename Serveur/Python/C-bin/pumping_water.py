#################################################
#                               used libararies                                 #
#################################################
# some_file.py
import sys
# insert at 1, 0 is the script path (or '' in REPL)
sys.path.insert(1, '/home/pi/embedded_soft_project/embedded_soft_project/Serveur/Python')

import manipulating_sensor_file
import script_pump_for_db
import time
#################################################
#                                       constants:                                      #
#################################################
HIGH_LEVEL_OF_WATER = 90.0
LOW_LEVEL_OF_WATER = 87.0
#################################################
from ctypes import *

# chemin pour shared library qu'on a cree 
so_file = "/home/pi/embedded_soft_project/embedded_soft_project/Serveur/Drivers/my_functions.so"

# make the functions as a python module 
my_functions = CDLL(so_file)

my_functions.init_all_used_GPIO()
# pump_value =ON
my_functions.turn_pump_on()
# log time in database 
script_pump_for_db.log_time_of_launching_pump()

while manipulating_sensor_file.get_sensor_value() <= HIGH_LEVEL_OF_WATER :
        manipulating_sensor_file.increment_level_while_pump_turned_on() 
        print("la pompe pompe maintenant ..., pompe = ON")
        time.sleep(2)
        print("niveau d'eau est superieur au niveau maximal du r  servoir")

# high_level_led = ON
my_functions.turn_high_level_led_on()
# pump_value = OFF
my_functions.turn_pump_off()
print("pompe = OFF")


