#!/bin/sh
pdf="$1"
tempimagename="`mktemp`"
tempimage="${tempimagename}-000.pbm"
temprespdf="${tempimage}.pdf"
touch "$tempimage"
temppdf="`mktemp`.pdf"
temppdf2="`mktemp`"
md5file="`mktemp`"
pages=`pdfinfo "$pdf" | grep -i pages | sed 's/.*\s\([0-9]\+\)$/\1/'`
md5sum "$tempimage" > $md5file
i="1"

while [ $i -le $pages ]
do
	echo -n "Processing page $i..."
	#convert -density 300 "$pdf"[$i] -resize 100% "$tempimage"
	pdfimages -f $i -l $i "$pdf" "$tempimagename"
	md5sum -c --status $md5file
	if [ "$?" = "0" ]
	then
		break
	fi
	md5sum "$tempimage" > $md5file
	convert "$tempimage" -crop `convert "$tempimage" -virtual-pixel edge -blur 0x13 -fuzz 13% -trim -format '%wx%h%O' info:` +repage \
		-quality 100% -resize 750x -density 167 -compress lossless "$temprespdf"
	if [ "$?" = "0" ]
	then
		if [ "$i" = "1" ]
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
	i=`expr $i + 1`
done
rm -f "$tempimage"
rm -f "$temprespdf"
rm -f "$temppdf2"
rm -f "$tempimagename"
rm -f "$md5file"
echo "Result file is: $temppdf"
