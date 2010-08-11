	server_name example.com;
	access_log /home/amaryanov/build/data/nginx/log/example.com/access.log main;
	error_log /home/amaryanov/build/data/nginx/log/example.com/error.log;
