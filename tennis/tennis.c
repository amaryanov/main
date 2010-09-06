#include <string.h>
#include <magick/MagickCore.h>

#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <string.h>
#include <unistd.h>
#include <stdlib.h>
#include <stdio.h>
#include <netdb.h>

static int myHistogramCompare(const void *x, const void *y)
{
	const ColorPacket
		*color_1,
		*color_2;

	color_1 = (const ColorPacket *) x;
	color_2 = (const ColorPacket *) y;
	return((int) color_2->count - (int) color_1->count);
}
int getImg ()
{
	int s, n;
	struct sockaddr_in sin; struct hostent *hptr;
	char msg[512] = "GET /cgi-bin/video.jpg HTTP/1.1\nHost: 192.168.3.169\nUser-Agent: Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.16) Gecko/2009121601 Ubuntu/8.10 (intrepid) Firefox/3.0.16\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\nAccept-Language: en-us,en;q=0.5\nAccept-Encoding: gzip,deflate\nAccept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\nKeep-Alive: 300\nConnection: keep-alive\n\n";
	char host[15] = "192.168.3.169";
	char port[2] = "80";
	if ( (s = socket(AF_INET, SOCK_STREAM, 0 ) ) < 0)
	{ /* create socket*/
		perror("socket");  /* socket error */
		return -1;
	}
	sin.sin_family = AF_INET;              /*set protocol family to Internet */
	sin.sin_port = htons(atoi(port));  /* set port no. */
	if ( (hptr =  gethostbyname(host) ) == NULL)
	{
		fprintf(stderr, "gethostname error: %s", host);
		return  -1;
	}
	memcpy( &sin.sin_addr, hptr->h_addr, hptr->h_length);
	if (connect (s, (struct sockaddr *)&sin, sizeof(sin) ) < 0 )
	{
		perror("connect");
		return -1;   /* connect error */
	}
	if ( send(s, msg, strlen(msg) + 1,0) < 0 )
	{  /* send message to server */
		perror("write");
		return -1; /*  write error */
	}
	char buf[256];
	memset(buf, 0, 256);
	int i = 0;
	FILE * pFile;
	pFile = fopen("video.jpg", "w");
	if (pFile == NULL)
	{
		return EXIT_FAILURE;
	}
	int headers = 1;
	int chunk_length = 0;
	int previous_char = 0;
	int enter_count = 0;
	int chunk_start = 1;
	char chunk_hex[5];
	int pass_bytes = 0;
	memset(chunk_hex, 0, 5);
	while(( n = recv(s, buf, 256,0 ) ) > 0)
	{
		for(i = 0; i < n; i++)
		{
			if(pass_bytes > 0)
			{
				pass_bytes--;
				continue;
			}
			if(headers == 1)
			{
				if(previous_char == 13 && buf[i] == 10)
				{
					enter_count++;
				}
				else if(buf[i] != 13)
				{
					enter_count = 0;
				}
				if(enter_count == 2)
				{
					headers = 0;
				}
			}
			else
			{
				if(chunk_start == 1)
				{
					if(buf[i] != 13 && buf[i] != 10)
					{
						strncat(chunk_hex, &buf[i], 1);
					}
					else if(strlen(chunk_hex) > 0 && previous_char == 13 && buf[i] == 10)
					{
						chunk_start = 0;
						chunk_length = 0;
						sscanf(chunk_hex, "%x", &chunk_length);
						memset(chunk_hex, 0, 5);
					}
				}
				else
				{
					fputc(buf[i], pFile);
					chunk_length--;
					if(chunk_length == 0)
					{
						chunk_start = 1;
						pass_bytes = 2;
					}
				}
			}
			previous_char = buf[i];
		}
	}
	if (pFile != NULL)
	{
		fclose (pFile);
	}

	/* close connection, clean up socket */
	if (close(s) < 0)
	{ 
		perror("close");   /* close error */
		return -1;
	}
	return 0;
}

int main(int argc,char **argv)
{
	ExceptionInfo
		*exception;

	Image
		*image;

	ImageInfo
		*image_info;

	unsigned long
		colors;

	ColorPacket
		*histogram;

	unsigned long
		i = 0;
	char*
		img_path = "video.jpg";

	getImg();

	MagickCoreGenesis(*argv,MagickTrue);
	exception = AcquireExceptionInfo();
	image_info = CloneImageInfo((ImageInfo *) NULL);
	strcpy(image_info->filename, img_path);
	image = ReadImage(image_info,exception);
	if (exception->severity != UndefinedException)
	{
		CatchException(exception);
	}
	if (image == (Image *) NULL)
	{
		exit(1);
	}
	histogram = GetImageHistogram(image, &colors, exception);
	qsort((void *) histogram, (size_t) colors, sizeof(*histogram), myHistogramCompare);
	if (histogram == (ColorPacket*) NULL)
	{
		MagickError(exception->severity, exception->reason, exception->description);
	}
	if(colors > 0)
	{
		if(histogram[i].count > 10000
			&& ScaleQuantumToChar(RoundToQuantum(histogram[i].pixel.red)) < 35
			&& ScaleQuantumToChar(RoundToQuantum(histogram[i].pixel.green)) < 35
			&& ScaleQuantumToChar(RoundToQuantum(histogram[i].pixel.blue)) < 35)
		{
			printf("Empty\n");
		}
		else
		{
			printf("Busy\n");
		}
//		printf("%u %d %d %d\n", histogram[i].count, ScaleQuantumToChar(RoundToQuantum(histogram[i].pixel.red)), ScaleQuantumToChar(RoundToQuantum(histogram[i].pixel.green)), ScaleQuantumToChar(RoundToQuantum(histogram[i].pixel.blue)));
	}
	remove(img_path);
	histogram = (ColorPacket *) RelinquishMagickMemory(histogram);
	DestroyImage(image);
	image_info=DestroyImageInfo(image_info);
	exception=DestroyExceptionInfo(exception);
	MagickCoreTerminus();
	return(0);
}
