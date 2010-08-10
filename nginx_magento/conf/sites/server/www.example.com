	server_name www.example.com;
	access_log /home/amaryanov/build/data/nginx/log/www.example.com/access.log main;
	error_log /home/amaryanov/build/data/nginx/log/www.example.com/error.log;
	rewrite ^(.*)$ $scheme://example.com:$server_port$1 permanent;
