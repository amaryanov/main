#!/bin/sh

cat <<EOF
<?xml version="1.0" ?>
<!--
start php-fpm: ${phpdir}sbin/php-fpm start
stop php-fpm: ${phpdir}sbin/php-fpm stop
-->
<configuration>

	All relative paths in this config are relative to php's install prefix

	<section name="global_options">

		Pid file
		<value name="pid_file">${datadir}run/php-fpm.pid</value>

		Error log file
		<value name="error_log">${phpdatadir}log/php-fpm.log</value>

		Log level
		<value name="log_level">notice</value>

		When this amount of php processes exited with SIGSEGV or SIGBUS ...
		<value name="emergency_restart_threshold">10</value>

		... in a less than this interval of time, a graceful restart will be initiated.
		Useful to work around accidental curruptions in accelerator's shared memory.
		<value name="emergency_restart_interval">1m</value>

		Time limit on waiting child's reaction on signals from master
		<value name="process_control_timeout">5s</value>

		Set to 'no' to debug fpm
		<value name="daemonize">yes</value>

	</section>

	<workers>

		<section name="pool">

			Name of pool. Used in logs and stats.
			<value name="name">default</value>

			Address to accept fastcgi requests on.
			Valid syntax is 'ip.ad.re.ss:port' or just 'port' or '/path/to/unix/socket'
			<value name="listen_address">${datadir}run/php.sock</value>

			<value name="listen_options">

				Set listen(2) backlog
				<value name="backlog">-1</value>

				Set permissions for unix socket, if one used.
				In Linux read/write permissions must be set in order to allow connections from web server.
				Many BSD-derrived systems allow connections regardless of permissions.
				<value name="owner">$(id -nu)</value>
				<value name="group">$(id -ng)</value>
				<value name="mode">0600</value>
			</value>

			Unix user of processes
			<value name="user">$(id -nu)</value>

			Unix group of processes
			<value name="group">$(id -ng)</value>

			Process manager settings
			<value name="pm">

				Sets style of controling worker process count.
				Valid values are 'static' and 'apache-like'
				<value name="style">static</value>
			</value>

			The timeout (in seconds) for serving a single request after which the worker process will be terminated
			Should be used when 'max_execution_time' ini option does not stop script execution for some reason
			'0s' means 'off'
			<value name="request_terminate_timeout">0s</value>

			The timeout (in seconds) for serving of single request after which a php backtrace will be dumped to slow.log file
			'0s' means 'off'
			<value name="request_slowlog_timeout">3s</value>

			The log file for slow requests
			<value name="slowlog">${phpdatadir}log/slow.log</value>

			Set open file desc rlimit
			<value name="rlimit_files">1024</value>

			Set max core size rlimit
			<value name="rlimit_core">0</value>

			Chroot to this directory at the start, absolute path
			<value name="chroot"></value>

			Chdir to this directory at the start, absolute path
			<value name="chdir"></value>

			Redirect workers' stdout and stderr into main error log.
			If not set, they will be redirected to /dev/null, according to FastCGI specs
			<value name="catch_workers_output">yes</value>

			How much requests each process should execute before respawn.
			Useful to work around memory leaks in 3rd party libraries.
			For endless request processing please specify 0
			Equivalent to PHP_FCGI_MAX_REQUESTS
			<value name="max_requests">200</value>

			Comma separated list of ipv4 addresses of FastCGI clients that allowed to connect.
			Equivalent to FCGI_WEB_SERVER_ADDRS environment in original php.fcgi (5.2.2+)
			Makes sense only with AF_INET listening socket.
			<value name="allowed_clients"></value>

			Pass environment variables like LD_LIBRARY_PATH
			All \$VARIABLEs are taken from current environment
			<value name="environment">
				<value name="HOSTNAME">\$HOSTNAME</value>
				<value name="PATH">/usr/local/bin:/usr/bin:/bin</value>
				<value name="TMP">${phpdatadir}tmp</value>
				<value name="TMPDIR">${phpdatadir}tmp</value>
				<value name="TEMP">${phpdatadir}tmp</value>
				<value name="OSTYPE">\$OSTYPE</value>
				<value name="MACHTYPE">\$MACHTYPE</value>
				<value name="MALLOC_CHECK_">2</value>
			</value>

		</section>

	</workers>

</configuration>
EOF
