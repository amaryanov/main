#! /bin/sh

cat << EOF
#! /bin/sh
# MySQL daemon start/stop script.

# Usually this is put in /etc/init.d (at least on machines SYSV R4 based
# systems) and linked to /etc/rc3.d/S99mysql and /etc/rc0.d/K01mysql.
### BEGIN INIT INFO
# Provides: mysql
# Required-Start: \$local_fs \$network \$remote_fs
# Should-Start: ypbind nscd ldap ntpd xntpd
# Required-Stop: \$local_fs \$network \$remote_fs
# Default-Start:  2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: start and stop MySQL
# Description: MySQL is a very fast and reliable SQL database engine.
### END INIT INFO
mysqladmin=${mysqlinstalldir}bin/mysqladmin
mysqld_safe=${mysqlinstalldir}bin/mysqld_safe
mycnf=${mysqldatadir}conf/my.cnf
mysqlpid=${datadir}run/${mysqlname}.pid


opts="--defaults-file=\$mycnf"


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
		echo -n "Starting mysql "

		\$mysqld_safe \$opts &

		if [ "\$?" != 0 ] ; then
			echo " failed"
			exit 1
		fi

		wait_for_pid created \$mysqlpid

		if [ -n "\$try" ] ; then
			echo " failed"
			exit 1
		else
			echo " done"
		fi
	;;

	stop)
		echo -n "Gracefully shutting down mysql "

		if [ ! -r \$mysqlpid ] ; then
			echo "warning, no pid file found - mysql is not running ?"
			exit 1
		fi

		\$mysqladmin \$opts -v shutdown

		if [ "$?" != 0 ] ; then
			echo " failed"
			exit 1
		fi

		wait_for_pid removed \$mysqlpid

		if [ -n "\$try" ] ; then
			echo " failed. Use force-exit"
			exit 1
		else
			echo " done"
		fi
	;;
	flush-logs)
		echo -n "Flushing logs "
		\$mysqladmin \$opts -v flush-logs
		if [ \$? != "0" ]
		then
			echo " error occured(\$?)"
			exit 1
		else
			echo " done successfuly"
		fi
	;;
	flush-tables)
		echo -n "Flushing tables "
		\$mysqladmin \$opts -v flush-tables
		if [ \$? != "0" ]
		then
			echo " error occured(\$?)"
			exit 1
		else
			echo " done successfuly"
		fi
	;;

	*)
		echo "Usage: \$0 {start|stop}"
		exit 1
	;;

esac
EOF
