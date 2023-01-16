#!/usr/bin/env bash

rm *.out

nohup python3 $PWD/lda.py > lda.out 2>&1 &
nohup python3 $PWD/bert.py > bert.out 2>&1 &
nohup python3 $PWD/resnet.py > resnet.out 2>&1 &

nohup python3 $PWD/fusion.py > lda_bert.out 2>&1 &
nohup python3 $PWD/fusion.py lda25_resnet75 > lda25_resnet75.out 2>&1 &
nohup python3 $PWD/fusion.py lda50_resnet50 > lda50_resnet50.out 2>&1 &
nohup python3 $PWD/fusion.py lda75_resnet25 > lda75_resnet25.out 2>&1 &
nohup python3 $PWD/fusion.py bert25_resnet75 > bert25_resnet75.out 2>&1 &
nohup python3 $PWD/fusion.py bert50_resnet50 > bert50_resnet50.out 2>&1 &
nohup python3 $PWD/fusion.py bert75_resnet25 > bert75_resnet25.out 2>&1 &

