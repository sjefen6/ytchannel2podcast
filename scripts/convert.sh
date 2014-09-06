#!/bin/bash
# Requires crontab:
#>crontab -e
#*/1 * * * * /home/alumninati/projects/alumninati.no/scripts/convert.sh > /dev/null

UNPROCESSED=/home/alumninati/projects/raw.alumninati.no/unprocessed/*
AUDIO=/home/alumninati/projects/raw.alumninati.no/audio/
VIDEO=/home/alumninati/projects/raw.alumninati.no/video/

for f in $UNPROCESSED
do
	if [[ "$f" == *.mp4 ]]
	then
		echo "Processing $f..."
		NEWFILE=${f##*/}
  		NEWFILE=$AUDIO${NEWFILE%.mp4}.mp3

  		ffmpeg -i "$f" -ar 44100 -ab 96k -ac 2 "$NEWFILE"
  		mv $f $VIDEO
  		echo "$f completed."
	fi
done
