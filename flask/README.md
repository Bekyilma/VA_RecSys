# Visual Art RecSys Services

Different RecSys engines will provide a series of recommendations based on the user preferences.

## Install

Python >= 3.5 and pip3 are required:
```sh
sudo apt install python3 python3-pip
```

Install Python dependencies:
```sh
pip3 install -r requirements.txt
```

Download and unzip services data:
```sh
wget https://project-banana.eu/va-recsys/vadata.zip
unzip -q vadata.zip
```
## Running the services

```sh
bash start.sh
```

## Stopping the services

```sh
bash stop.sh
```

## Restarting the services

```sh
bash restart.sh
```

## Monitoring the services

```sh
bash status.sh
```


**Note:** You should run all services through a [WSGI application in production](https://flask.palletsprojects.com/en/2.0.x/deploying/fastcgi/), for better performance.

<p align="center"> 
<img width="1100"  src="figs/feature_learning.png"/> Overview of our approaches to learn latent semantic representations of paintings.
</p>
