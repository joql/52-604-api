#!/bin/bash

DIR=$1
changeTime=1

if [ ! -n "$DIR" ] ;then
    echo "you have not choice Application directory !"
    exit
fi

php easyswoole stop
php easyswoole start --d


fswatch -r $DIR | while read file
do
   current=`date "+%Y-%m-%d %H:%M:%S"`
   nowTimeStamp=`date -d "$current" +%s`
   val=`expr $nowTimeStamp - $changeTime`
   if [ 6 -lt $val ]; then
       changeTime=$nowTimeStamp
       echo "${file} was modify" >> ./Temp/reload.log 2>&1
       php easyswoole restart
   fi
done
