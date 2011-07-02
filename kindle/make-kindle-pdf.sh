#!/bin/sh
pdf="$1"
tempimagename="`mktemp`"
tempimagepbm="${tempimagename}-000.pbm"
tempimageppm="${tempimagename}-000.ppm"
temprespdf="${tempimagename}.pdf"
temppdf="`mktemp`.pdf"
temppdf2="`mktemp`"
pages=`pdfinfo "$pdf" | grep -i pages | sed 's/.*\s\([0-9]\+\)$/\1/'`
start="511"
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
	convert "$tempimage" -crop `convert "$tempimage" -virtual-pixel edge -blur 0x13 -fuzz 13% -trim -format '%wx%h%O' info:` +repage \
		-quality 100% -resize 750x -density 167 -compress lossless "$temprespdf"
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
