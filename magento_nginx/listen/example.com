listen 127.0.0.1:8000;
listen 127.0.0.1:8002;
ssl_certificate		cert/ssl.crt;
ssl_certificate_key	cert/ssl.key;
