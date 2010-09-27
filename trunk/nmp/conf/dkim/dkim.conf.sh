#!/bin/sh

cat << EOF
Syslog				yes
UMask				000
#Domain				example.com
#KeyFile				/path/to/private/key
#Selector			selector
Mode				s
PidFile				${rundir}dkim.pid
AutoRestart			1
AutoRestartCount	10
AutoRestartRate		10/1s
SendReports			1
Socket				local:${rundir}dkim.sock
ReportAddress		root
SendADSPReports		1
EOF
