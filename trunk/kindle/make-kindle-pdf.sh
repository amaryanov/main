#!/bin/sh
pdf="$1"
tempimagename="`mktemp`"
tempimagepbm="${tempimagename}-000.pbm"
tempimageppm="${tempimagename}-000.ppm"
temprespdf="${tempimagename}.pdf"
temppdf="`mktemp`.pdf"
temppdf2="`mktemp`"
pages=`pdfinfo "$pdf" | grep -i pages | sed 's/.*\s\([0-9]\+\)$/\1/'`
start="1"
i="$start"

rmTemp()
{
	rm -f "$tempimagepbm"
	rm -f "$tempimageppm"
	rm -f "$temprespdf"
	rm -f "$temppdf2"
	rm -f "$tempimagename"
}

rmTemp

while [ $i -le $pages ]
do
	echo -n "Processing page $i..."
	pdfimages -f $i -l $i "$pdf" "$tempimagename"
	if [ -e $tempimagepbm ]
	then
		tempimage="$tempimagepbm"
	elif [ -e $tempimageppm ]
	then
		tempimage="$tempimageppm"
	else
		echo "no file"
		break
	fi
#hacks:
#	convert "$tempimage" -crop -15-15 "$tempimage"
#	if [ $i -ge 7 -a `expr $i % 2` -eq "1" ]
#	then
#		convert "$tempimage" -crop +520+200 "$tempimage"
#		convert "$tempimage" -crop -0+50 "$tempimage"
#	fi
#	convert "$tempimage" -crop `convert "$tempimage" -virtual-pixel edge -blur 0x13 -fuzz 13% -trim -format '%wx%h%O' info:` +repage \
#	-quality 100% -resize 1024x -compress lossless "$temprespdf"
##############
	convert "$tempimage" -crop `convert "$tempimage" -virtual-pixel edge -blur 0x13 -fuzz 13% -trim -format '%wx%h%O' info:` +repage \
		-quality 100% -resize 750x -density 300 -compress lossless "$temprespdf"
	if [ "$?" = "0" ]
	then
		if [ "$i" = "$start" ]
		then
			mv "$temprespdf" "$temppdf"
		else
			pdftk "$temppdf" "$temprespdf" cat output "$temppdf2"
			mv "$temppdf2" "$temppdf"
		fi
	else
		echo "error"
		break
	fi
	echo "DONE"
	rmTemp
	i=`expr $i + 1`
done
rmTemp
echo "Result file is: $temppdf"
evince $temppdf
