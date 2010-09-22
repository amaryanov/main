#! /bin/sh

cat << EOF
#!/bin/sh
# postfix daemon start/stop script.

# Usually this is put in /etc/init.d (at least on machines SYSV R4 based
# systems) and linked to /etc/rc3.d/S99postfix and /etc/rc0.d/K01postfix.
### BEGIN INIT INFO
# Provides: postfix
# Required-Start: \$local_fs \$network \$remote_fs
# Required-Stop: \$local_fs \$network \$remote_fs
# Default-Start:  2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: start and stop postfix
# Description: postfix.
### END INIT INFO
postfix=${postfixbuilddir}bin/postfix

iffailed()
{
	if [ "\$?" != "\$1" ]
	then
		echo " \$2"
		exit 1
	fi
}

case "\$1" in
	start)
		echo -n "Starting postfix "
		\$postfix status
		iffailed 1 " already running"
		\$postfix start
		iffailed 0 " failed"
		echo " done"
	;;

	stop)
		echo -n "Stoping postfix "
		\$postfix status
		iffailed 0 " is not running"
		\$postfix stop
		iffailed 0 " failed"
		echo " done"
	;;

	*)
		echo "Usage: \$0 {start|stop}"
		exit 1
	;;

esac
EOF
