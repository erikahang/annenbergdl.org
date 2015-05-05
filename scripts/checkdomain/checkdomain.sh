#!/bin/bash
awk '{print $1}' < ascjportfoliolist.txt | while read ip; do
    if ping -c1 $ip >/dev/null 2>&1; then
        echo $ip IS UP
    else
        echo $ip IS DOWN
    fi
done