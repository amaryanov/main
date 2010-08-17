server
{
	include sites/listen/example.com;
	server_name example.com;
	error_log log/example.com/error.log;
	include sites/common/admin;
}
server
{
	include sites/listen/example.com;
	server_name www.example.com;
	error_log log/www.example.com/error.log;
	access_log log/$server_name/access.log main;
	rewrite ^(.*)$ $scheme://example.com:$server_port$1 permanent;
}
