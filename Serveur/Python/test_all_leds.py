from ctypes import *

so_file = "/home/pi/embedded_soft_project/C_led_test/test_led.so"
my_functions = CDLL(so_file)

my_functions.init_all_used_GPIO()

my_functions.turn_pump_on()
my_functions.turn_high_level_led_on()
my_functions.turn_low_level_led_on()

print(type(my_functions))
