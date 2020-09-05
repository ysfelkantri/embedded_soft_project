#!/usr/bin/env python
import sqlite3
#import RPi.GPIO as GPIO
import os
import time
import glob
import random
import sys
# global variables
speriod=(5) 
dbname='/www/tempbase.db'

# get temerature
# returns None on error, or the temperature as a float
def get_temp():
    file = open("/home/pi/lab_5/temp.txt", "r") 
    tempvalue = file.read() 
    return tempvalue

# store the temperature in the database
def log_temperature(temp):
    conn=sqlite3.connect(dbname)
    curs=conn.cursor()
    curs.execute("INSERT INTO temps values(datetime('now'), (?))", (temp,))
    # commit the changes
    conn.commit()
    conn.close()

# display the contents of the database
def display_data():
    conn=sqlite3.connect(dbname)
    curs=conn.cursor()
    #for row in curs.execute("SELECT * FROM temps"):
    for row in curs.execute("SELECT * FROM temps WHERE rowid= (SELECT MAX(rowid)  FROM temps);"):
        print str(row[0])+"  |  "+str(row[1])
    conn.close()

# main function
def main():
#    while True:
        # get the temperature from the device file
        temperature = get_temp()
#        temperature = random.uniform(15,30)
        if temperature != None:
            print "temperature= "+str(temperature)
        else:
            # Sometimes reads fail on the first attempt
            # so we need to retry
            temperature = get_temp()
            print "temperature="+str(temperature)
        # Store the temperature in the database
        log_temperature(temperature)
        # display the contents of the database
        display_data()
#        time.sleep(speriod)


if __name__=="__main__":
        main()



