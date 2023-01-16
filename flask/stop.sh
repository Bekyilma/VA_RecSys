#!/usr/bin/env bash

ps faxu | grep python3 | grep flask | awk '{ print $2 }' | while read p; do kill $p; done
