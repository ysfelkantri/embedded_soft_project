#!/usr/bin/env python

import sqlite3
import os
import time
import glob
import random
import sys
# global variables
dbname='/home/pi/embedded_soft_project/embedded_soft_project/Client/BD/farm_water.db'

# get water level

# store the time when the pump start pumping in the database
def log_time_of_launching_pump():
    conn=sqlite3.connect(dbname)
    curs=conn.cursor()
    curs.execute("INSERT INTO pompe_table values(datetime('now'));")
    # commit the changes
    conn.commit()
    conn.close()

# display the contents of the database
def display_data():
    conn=sqlite3.connect(dbname)
    curs=conn.cursor()
    #for row in curs.execute("SELECT * FROM pompe_table WHERE rowid= (SELECT MAX(rowid)  FROM pompe_table );"):
    for row in curs.execute("SELECT * FROM pompe_table ;"):
		print ("time of launching : {}".format(str(row[0])))
    conn.close()

