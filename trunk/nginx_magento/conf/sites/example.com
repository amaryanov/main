server
{
	include sites/listen/example.com;
	include sites/server/example.com;
	return 404;
}
#server
#{
#	include sites/listen/example.com.ssl;
#	include sites/server/example.com;
#	return 404;
#}
server
{
	include sites/listen/example.com;
	include sites/server/www.example.com;
}
#server
#{
#	include sites/listen/example.com.ssl;
#	include sites/server/www.example.com;
#}
