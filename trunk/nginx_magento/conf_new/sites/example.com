server
{
#	include sites/listen/example.com;
	listen 80;
	server_name localhost;
	access_log /home/amaryanov/build/data/nginx/log/example.com/access.log main;
	error_log /home/amaryanov/build/data/nginx/log/example.com/error.log;
#	include sites/server/example.com;
	location /index.php
	{
		include fastcgi_params;
		fastcgi_pass 127.0.0.1:12345;
		fastcgi_param SCRIPT_FILENAME /home/amaryanov/build/build/nginx-0.7.67/html/index.php;
	}
}
#server
#{
#	include sites/listen/example.com.ssl;
#	include sites/server/example.com;
#	return 404;
#}
#server
#{
#	include sites/listen/example.com;
#	include sites/server/www.example.com;
#}
#server
#{
#	include sites/listen/example.com.ssl;
#	include sites/server/www.example.com;
#}
