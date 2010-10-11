#!/bin/sh

logfile=/tmp/test.out
myecho()
{
	echo "$1" >> $logfile
}

myecho "#####################################################################################"

messagefile="`mktemp /tmp/messageXXXX`"
cat /dev/stdin > $messagefile
myecho "Created message file $messagefile"

message_part_var_name="messagepart"
valid_sender="MAILER-DAEMON"
rm_message_file=0
mysql="/home/www/server/build/mysql/bin/mysql"
mysqlconf="/home/www/server/data/mysql/conf/client.cnf"
database="cifrograd_mailer"

#delivery status
DELIVERED="2"
UNDELIVERED="3"

#exit codes
EX_OK=0
EX_DATAERR=65
EX_TERM=1

####################  FUNCTIONS  ####################

end()
{
	if [ "$rm_message_file" = "1" ]
	then
		rm -f $messagefile
	fi
	myecho "$1"
	exit $2
}

endNoRm()
{
	myecho "$1 Message file name: $messagefile"
	exit $2
}

parseParts()
{
	boundary="`grep "boundary=" $1 | tail -n 1 | sed 's/[^b]\{1,\}boundary="\([^\"]\{1,\}\)"/\1/'`"
	boundarynums="`grep -n "\-\-$boundary" $1 | sed 's/\([0-9]\{1,\}\).*/\1/'`"
	partscount="`echo "$boundarynums" | wc -l`"
	if [ "$partscount" -gt "0" ]
	then
		prev=0
		cur_part_num=1
		prev=0
		for line in $boundarynums
		do
			curpart=$(head -n `expr $line - 1` $1 | tail -n `expr $line - $prev - 1`)
			varname="$message_part_var_name$cur_part_num"
			eval $varname='$curpart' 2>/dev/null
			cur_part_num="`expr $cur_part_num + 1`"
			prev=$line
		done
		cur_part_num=1
	else
		return 1
	fi
	return 0
}

getHeader()
{
    # $1 - message part text
    # $2 - header name
    # $3 - header value part number or part name
    res="`echo -n "$1" | perl -e 'my $stdin = do {local (@ARGV,$/); <STDIN>};
        $stdin =~ /'$2'\:((\s+[^\n]+\n){1,})/ms;
        my $header = $1;
        $header =~ s/\n\s*//ms;
        $partnum="'$3'";
        if ( length($partnum) > 0 )
        {
            if ( $partnum =~ /^\d+$/ )
            {
                my @header_parts = split(/\s*;\s*/, $header);
                $header = $header_parts[$partnum];
            }
            else
            {
                $header =~ /(^|;)\s*$partnum=((\x22|\x27)?)([^;]+)(\2)/;
                $header = $4
            }
        }
        $header =~ s/(\A\s+)|(\s+\z)//g;
        print $header'`"
    echo "$res"
}

findDSN()
{
	cur_part_num=1
	while [ "$cur_part_num" -le "$partscount" ]
	do
		eval curpart=\$$message_part_var_name$cur_part_num
		res=""
		getHeader "$curpart" 'Content-Type'
		content_type="$res"
		if [ "$content_type" = "message/delivery-status" ]
		then
			return $cur_part_num
		fi
		cur_part_num="`expr $cur_part_num + 1`"
	done
	return 0
}

findMessageHeaders()
{
	cur_part_num=1
	while [ "$cur_part_num" -le "$partscount" ]
	do
		eval curpart=\$$message_part_var_name$cur_part_num
		res=""
		getHeader "$curpart" 'Content-Type'
		content_type="$res"
		if [ "$content_type" = "text/rfc822-headers" ]
		then
			return $cur_part_num
		fi
		cur_part_num="`expr $cur_part_num + 1`"
	done
	return 0
}

findMessage()
{
	cur_part_num=1
	while [ "$cur_part_num" -le "$partscount" ]
	do
		eval curpart=\$$message_part_var_name$cur_part_num
		res=""
		getHeader "$curpart" 'Content-Type'
		content_type="$res"
		if [ "$content_type" = "message/rfc822" ]
		then
			return $cur_part_num
		fi
		cur_part_num="`expr $cur_part_num + 1`"
	done
	return 0
}

trim()
{
	res="`echo -n "$1" | perl -e 'my $stdin = do {local (@ARGV,$/); <STDIN>}; $stdin =~ s/(\A\s+)|(\s+\z)//g; print $stdin'`"
}

updateStatus()
{
	res=""
	mysql_escape "$3"
	report="$res"
	$mysql --defaults-file=$mysqlconf -D $database -e "update subscriptions s, users u set \
		s.status='$4', s.report='$report' where u.email='$1' and s.user_id=u.id and s.maillist_id='$2'"
}

messageWasDelivered()
{
	myecho "Message to $1 on subscription '$2' was delivered. Delivery report: $3"
	updateStatus "$1" "$2" "$3" "$DELIVERED"
}

messageWasNotDelivered()
{
	myecho "Message to $1 on subscription '$2' was NOT delivered. Delivery report: $3"
	updateStatus "$1" "$2" "$3" "$UNDELIVERED"
}

mysql_escape()
{
	#\x00, \n, \r, \, ', " and \x1a
	res=`echo -n "$1" | perl -p -e 's/([\n\r\\\\"'"'"'\x00\x1a])/\\\\\1/g'`
}

####################  END FUNCTIONS  ####################

trap 'end "You terminated the script." $EX_TERM ' TERM INT

#parse parameters
counter=1
varpattern='\([^=]*\)\(=\(.*\)\)\{0,1\}'
while [ "$counter" -le "$#" ]
do
	var="`eval echo -n '$'$counter`"
	varname="`echo -n $var | sed 's/'$varpattern'/\1/'`"
	if [ "$varname" != "$var" ]
	then
		varval="`echo -n $var | sed 's/'$varpattern'/\3/'`"
		eval $varname='$varval' 2>/dev/null
	else
		eval $var='1' 2>/dev/null
	fi
	counter="`expr $counter + 1`"
done
#end parse parameters

myecho "sender = $sender"
myecho "extension = $extension"
myecho "recipient = $recipient"
myecho "valid_sender = $valid_sender"

if [ "$sender" != "$valid_sender" ]
then
	end "Sender is not $valid_sender"
fi

#split message parts
parseParts $messagefile
if [ "$?" = "1" ]
then
	endNoRm "Cant parse message parts." $EX_DATAERR
fi

#find DSN message part
findDSN
dsn_num=$?
if [ "$dsn_num" = "0" ]
then
	endNoRm "Cant find DSN part." $EX_DATAERR
fi

#get DSN action
eval dsnpart=\$$message_part_var_name$dsn_num
res=""
getHeader "$dsnpart" "Action"
action=$res
if [ "x$action" = "x" ]
then
	end "Cant find action"
fi

recipient="`echo -n $extension | tr = @`"
if [ "x$recipient" = "x" ]
then
	res=""
	getHeader "$dsnpart" "Final-Recipient"
	recipient="`echo -n $res | tr ';' '\n' | tail -n 1`"
	res=""
	trim $recipient
	recipient="$res"
fi
if [ "x$recipient" = "x" ]
then
	endNoRm "Who did send this message?" $EX_DATAERR
fi

findMessageHeaders 
message_headers_num=$?
if [ "$message_headers_num" = "0" ]
then
	findMessage 
	message_headers_num=$?
fi
if [ "$message_headers_num" = "0" ]
then
	endNoRm "Cant find message headers part." $EX_DATAERR
fi
eval message_headers_part=\$$message_part_var_name$message_headers_num
res=""
getHeader "$message_headers_part" "X-List-Id"
subscription=$res
if [ "x$subscription" = "x" ]
then
	end "Cant find subscription"
fi

case "$action" in
	failed)
		messageWasNotDelivered "$recipient" "$subscription" "$dsnpart"
	;;

	delayed)
		messageWasNotDelivered "$recipient" "$subscription" "$dsnpart"
	;;

	delivered)
		messageWasDelivered "$recipient" "$subscription" "$dsnpart"
	;;

	relayed)
		messageWasDelivered "$recipient" "$subscription" "$dsnpart"
	;;

	expanded)
		messageWasDelivered "$recipient" "$subscription" "$dsnpart"
	;;

	*)
		end 'Unknown action' $EX_DATAERR
	;;

esac

end "Exit successfuly" $EX_OK
