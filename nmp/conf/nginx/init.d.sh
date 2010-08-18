#! /bin/sh

cat << EOF
#! /bin/sh
# nginx daemon start/stop script.

# Usually this is put in /etc/init.d (at least on machines SYSV R4 based
# systems) and linked to /etc/rc3.d/S99nginx and /etc/rc0.d/K01nginx.
### BEGIN INIT INFO
# Provides: nginx
# Required-Start: \$local_fs \$network \$remote_fs
# Required-Stop: \$local_fs \$network \$remote_fs
# Default-Start:  2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: start and stop nginx
# Description: nginx.
### END INIT INFO
nginx=${nginxdir}sbin/nginx
nginxpid=${datadir}run/nginx.pid

wait_for_pid () {
	try=0

	while test \$try -lt 35 ; do

		case "\$1" in
			'created')
			if [ -f "\$2" ] ; then
				try=''
				break
			fi
			;;

			'removed')
			if [ ! -f "\$2" ] ; then
				try=''
				break
			fi
			;;
		esac

		echo -n .
		try=\`expr \$try + 1\`
		sleep 1

	done

}

case "\$1" in
	start)
		echo -n "Starting nginx "

		cd \$(dirname \`dirname \$nginxpid\`)
			\$nginx
		cd \$OLDPWD

		if [ "\$?" != 0 ] ; then
			echo " failed"
			exit 1
		fi

		wait_for_pid created \$nginxpid

		if [ -n "\$try" ] ; then
			echo " failed"
			exit 1
		else
			echo " done"
		fi
	;;

	stop)
		echo -n "Gracefully shutting down nginx "

		if [ ! -r \$nginxpid ] ; then
			echo "warning, no pid file found - nginx is not running ?"
			exit 1
		fi

		\$nginx -s quit

		wait_for_pid removed \$nginxpid

		if [ -n "\$try" ] ; then
			echo " failed. Use force-exit"
			exit 1
		else
			echo " done"
		fi
	;;

	*)
		echo "Usage: \$0 {start|stop}"
		exit 1
	;;

esac
EOF
