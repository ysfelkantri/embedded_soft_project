# embedded_soft_project
Client-Server application based on an embedded platform

## foctionnement :

a smart farm that can provide water for cattles using a pump and a lequid level sensor, 
	high level  = 90 %
	low level = 85% just for test 

### 1st step :(install required libraries)
##### asyncio 
The asyncio module provides infrastructure for writing single-threaded concurrent code using coroutine, so install just tap this command (python version >= 3.7 required) :
```bash
	pip install asyncio
```

##### Ctype (CDLL) 
we create a module in C to be used in python script as a shared library  
to generate this shared library you should use this command :
```bash
 cc -fPIC -shared -o my_functions.so my_functions.c 
```
for more informations : click here : https://www.journaldev.com/31907/calling-c-functions-from-python

### 2nd step :(configure lighttpd server and start it)
```bash
pi@raspberrypi:/www $ sudo nano /etc/lighttpd/lighttpd.conf 
pi@raspberrypi:/www $ sudo service lighttpd force-reload
pi@raspberrypi:/www $ sudo service lighttpd restart
```
### 3rd step :(configure periodic task in BG, using crontab)

to execute the task in fixed period you should use the cron tool :
```bash
sudo apt update
sudo apt install cron
sudo systemctl enable cron
sudo crontab -e
```
run code after 1 min all day 
``` bash
* * * * * python /home/pi/embedded_soft_project/embedded_soft_project/Serveur/Python/script_capteur_niveau_d\'eau_for_db.py
```

### 4th step :(allow external user to execute a code with sudo privileges )
to execute a code with an external user you should modify permissions of the program you want to execute 
```bash
sudo chmod 4755 /www/C-bin/start_pumping.py 
```

• open file : 010_pi-nopasswd :
```bash
 sudo nano etc/sudoers.d/010_pi-nopasswd
```
• Ajouter les instructions suivantes:
```bash
 pi ALL=(ALL) NOPASSWD: ALL
 www-data ALL=(ALL) NOPASSWD: /usr/bin/python /www/*
```
### 5th step :(run enslaved system )
```bash
python home/pi/embedded_soft_project/embedded_soft_project/Serveur/Python/emulating_increasing_and_decreasing_water_level.py
```






