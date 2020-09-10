#!/usr/bin/env python

import sqlite3
import os
import time
import glob
import random
import sys
# global variables
speriod=(5) 
dbname='/home/pi/embedded_soft_project/embedded_soft_project/Client/BD/farm_water.db'

# get water level
# returns None on error, or the temperature as a float
def get_water_level():
    file = open("/home/pi/embedded_soft_project/embedded_soft_project/Serveur/Python/sensor_file_emulator.txt", "r")
    tempvalue = file.read() 
    return tempvalue

# store the temperature in the database
def log_water_level(water_level):
    conn=sqlite3.connect(dbname)
    curs=conn.cursor()
    curs.execute("INSERT INTO water_level_table values(datetime('now'), (?))", (water_level,))
    # commit the changes
    conn.commit()
    conn.close()

# display the contents of the database
def display_data():
    conn=sqlite3.connect(dbname)
    curs=conn.cursor()
    for row in curs.execute("SELECT * FROM water_level_table WHERE rowid= (SELECT MAX(rowid)  FROM water_level_table );"):
    #for row in curs.execute("SELECT * FROM water_level_table ;"):
        print ("{}  |  {}".format(str(row[0]),str(row[1])))
    conn.close()

# main function
def main():
    while True:
        # get the temperature from the device file
        water_level = get_water_level()
#        temperature = random.uniform(15,30)
        if water_level != None:
            print ("water level= {}".format(str(water_level)))
        else:
            # Sometimes reads fail on the first attempt
            # so we need to retry
            water_level = get_water_level()
            print ("water level= {}".format(str(water_level)))
        # Store the temperature in the database
        log_water_level(water_level)
        # display the contents of the database
        display_data()
        time.sleep(speriod)


if __name__=="__main__":
        main()
