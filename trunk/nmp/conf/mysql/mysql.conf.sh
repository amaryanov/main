#!/bin/sh

cat <<EOF
# The following options will be passed to all MySQL clients
[client]
port		= 3306
socket		= ${datadir}run/${mysqlname}.sock

# Here follows entries for some specific programs

# The MySQL server
[mysqld]
user=${mysqluser}
datadir	= ${mysqldatadir}data/db
port		= 3306
socket		= ${datadir}run/${mysqlname}.sock

pid-file	= ${datadir}run/${mysqlname}.pid
general_log
general_log_file = ${mysqldatadir}log/general.log
log-error = ${mysqldatadir}log/error.log
log-isam = ${mysqldatadir}log/myisam.log
log-bin=${mysqldatadir}log/bin/log
log-bin-index = ${mysqldatadir}log/bin-index.log
binlog_format=mixed
expire_logs_days = 10
log-slow-admin-statements
log-slow-slave-statements
log-tc=${mysqldatadir}log/log-tc.log
log-warnings
log-output=FILE
long_query_time = 1
relay-log = ${mysqldatadir}log/relay.log
relay-log-index = ${mysqldatadir}log/relay-index.log
relay-log-info-file = ${mysqldatadir}log/relay-log-info.log
slow-query-log
slow_query_log_file = ${mysqldatadir}log/slow.log
log-queries-not-using-indexes
log-slave-updates
innodb=ON
innodb_file_per_table
innodb_data_home_dir = ${mysqldatadir}data/innodb/
innodb_data_file_path = ibdata1:10M:autoextend
innodb_log_group_home_dir = ${mysqldatadir}log/innodb/
innodb_buffer_pool_size = 16M
innodb_additional_mem_pool_size = 2M
innodb_log_file_size = 5M
innodb_log_buffer_size = 8M
innodb_flush_log_at_trx_commit = 1
innodb_lock_wait_timeout = 50
tmpdir = ${mysqldatadir}tmp/


skip-external-locking
key_buffer_size = 16M
max_allowed_packet = 1M
table_open_cache = 64
sort_buffer_size = 512K
net_buffer_length = 8K
read_buffer_size = 256K
read_rnd_buffer_size = 512K
myisam_sort_buffer_size = 8M
skip-networking
server-id	= 1

[mysqldump]
quick
max_allowed_packet = 16M

[mysql]
no-auto-rehash
# Remove the next comment character if you are not familiar with SQL
#safe-updates

[myisamchk]
key_buffer_size = 20M
sort_buffer_size = 20M
read_buffer = 2M
write_buffer = 2M

[mysqlhotcopy]
interactive-timeout
EOF
