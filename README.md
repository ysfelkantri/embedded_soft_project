# embedded_soft_project
Client-Server application based on an embedded platform

## foctionnement :

a smart farm that can provide water for cattles using a pump and a lequid level sensor, 
	high level  = 90 %
	low level = 85% just for test 

### 1st step :(install required libraries)
##### asyncio 
The asyncio module provides infrastructure for writing single-threaded concurrent code using coroutine, so install just tap this command :
```bash
	pip install asyncio
```
```bash
pi@raspberrypi:/www $ sudo nano /etc/lighttpd/lighttpd.conf 
pi@raspberrypi:/www $ sudo service lighttpd force-reload
pi@raspberrypi:/www $ sudo service lighttpd restart
```

sudo apt update
sudo apt install cron
sudo systemctl enable cron
sudo crontab -e

run code after 1 min all day 
``` bash
* * * * * python /home/pi/embedded_soft_project/embedded_soft_project/Serveur/Python/script_capteur_niveau_d\'eau_for_db.py
```

