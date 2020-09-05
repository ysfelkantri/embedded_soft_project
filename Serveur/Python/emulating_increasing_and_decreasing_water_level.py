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
ON  = 1 
OFF = 0

#################################################
#					variables					#
#################################################
pump_value = OFF ;


#################################################
#					modules:					#
#################################################


##################################################################################################
#emulation du capteur pour la diminution du quantitee d'eau										 #
##################################################################################################
async def level_decrease():
	while True :
		manipulating_sensor_file.init_sensor()
		manipulating_sensor_file.set_sensor_value()
		await asyncio.sleep(2)


##################################################################################################
#emulation du capteur lors du remplissage du reservoire 										 #
##################################################################################################
async def level_increase():
	while True :
		if manipulating_sensor_file.get_sensor_value() >= HIGH_LEVEL_OF_WATER :
			print("réservoir déjà plein")
			pump_value = OFF
	
		elif manipulating_sensor_file.get_sensor_value() <= LOW_LEVEL_OF_WATER :
			pump_value =ON
			while manipulating_sensor_file.get_sensor_value() <= HIGH_LEVEL_OF_WATER :
				manipulating_sensor_file.increment_level_while_pump_turned_on() 
				print("la pompe pompe maintenant ..., pompe = ON")
				await asyncio.sleep(3)
			pump_value = OFF
			print("réservoir est plein , pompe = OFF")
		await asyncio.sleep(2)
		
		
##################################################################################################
#synchronization entre l'augmentation et la diminution de l'eau dans le reservoir				 #
################################################################################################## 
async def main_function() :
	manipulating_sensor_file.init_sensor()
	await asyncio.gather(
		level_decrease(),
		level_increase()
	)
		
asyncio.run(main_function())
