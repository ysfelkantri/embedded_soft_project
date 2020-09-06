#################################################
#				used libararies					#
#################################################

import manipulating_sensor_file
import asyncio

#################################################
#					constants:					#
#################################################
HIGH_LEVEL_OF_WATER = 90.0
LOW_LEVEL_OF_WATER = 87.0

#################################################
#					variables					#
#################################################

#################################################
#					modules:					#
#################################################

#################################################################################################
# import a shared library wrotten in C that contains all functions for writing in GPIO Pins 	#
# we used 3 pins : 													 							#
#		* pin pour marche/arret de la pompe 											 		#
#		* pin pour indiquer que le niveau d'eau dans le reservoire atteidre le niveau max 		#
#		* pin pour indiquer que le niveau d'eau dans le reservoire atteidre le niveau min 		#
#################################################################################################

from ctypes import *

# chemin pour shared library qu'on a cree 
so_file = "/home/pi/embedded_soft_project/embedded_soft_project/Serveur/Drivers/my_functions.so"

# make the functions as a python module 
my_functions = CDLL(so_file)

# fonction pour initialisation des 3 pins 
my_functions.init_all_used_GPIO()


#################################################################################################
# emulation du capteur pour le rmplissage du reservoire (incrementation de la valeur dans		#		
# le fichier emulateur de capteur de niveau)													#
#################################################################################################
def remplissage_du_reservoire_par_la_pompe():

	while manipulating_sensor_file.get_sensor_value() <= HIGH_LEVEL_OF_WATER :
		if manipulating_sensor_file.get_sensor_value() >= LOW_LEVEL_OF_WATER :
			#low_level_led = OFF
			my_functions.turn_low_level_led_off()
		manipulating_sensor_file.increment_level_while_pump_turned_on() 
		print("la pompe pompe maintenant ..., pompe = ON")
		await asyncio.sleep(3)

	print("niveau d'eau est superieur au niveau maximal du reservoir")

#################################################################################################
# emulation du capteur pour la diminution du quantitee d'eau (decrementation de la valeur dans	#		
# le fichier emulateur de capteur de niveau)										 			#
#################################################################################################
async def decreasing_water_level():
	while True :
		manipulating_sensor_file.init_sensor()
		manipulating_sensor_file.set_sensor_value()
		await asyncio.sleep(2)

##################################################################################################
# etteindre les led lorsque le niveau d'eau est moyen											 #
##################################################################################################
async def medium_level_of_water():
	while True :
		if (manipulating_sensor_file.get_sensor_value() <= HIGH_LEVEL_OF_WATER) and (manipulating_sensor_file.get_sensor_value() >= LOW_LEVEL_OF_WATER) :
			print("niveau d'eau est au niveau moyen du reservoir")
			#high_level_led = OFF
			my_functions.turn_high_level_led_off()
			#low_level_led = OFF
			my_functions.turn_low_level_led_off()
		await asyncio.sleep(2)
	
#################################################################################################
# emulation du capteur lors du remplissage du reservoire LEDs									#
#################################################################################################
async def marche_arret_pompe_to_increase_water_level():
	while True :
		if manipulating_sensor_file.get_sensor_value() <= LOW_LEVEL_OF_WATER :
			print("niveau d'eau est inferieur au niveau minimal du reservoir")
			my_functions.turn_low_level_led_on()
			my_functions.turn_pump_on()
			
			remplissage_du_reservoire_par_la_pompe()
			
			my_functions.turn_high_level_led_on()
			my_functions.turn_pump_off()
			print("pompe = OFF")

		await asyncio.sleep(2)
		
		
##################################################################################################
#synchronization entre l'augmentation et la diminution de l'eau dans le reservoir				 #
################################################################################################## 
async def main_function() :
	manipulating_sensor_file.init_sensor()
	await asyncio.gather(
		decreasing_water_level(),
		medium_level_of_water(),
		marche_arret_pompe_to_increase_water_level()
	)
		
asyncio.run(main_function())
