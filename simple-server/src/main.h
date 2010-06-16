
/*
 * Copyright (C) Anton Maryanov
 */


#ifndef _MAIN_H_INCLUDED_
#define _MAIN_H_INCLUDED_
#include <arpa/inet.h>
#include <errno.h>
#include <fcntl.h>
#include <netinet/in.h>
#include <pthread.h>
#include <signal.h>
#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/epoll.h>
#include <sys/mman.h>
#include <sys/socket.h>
#include <sys/time.h>
#include <sys/wait.h>
#include <unistd.h>

#define ZEROPOD(var) memset(&var, 0, sizeof(var))


#define THREAD_COUNT 2
#define PROCESS_COUNT 2

const int MYPORT = 1235;
const int qsize = 1000;
const int epsize= 1000;

static int base_socket_fd = 0;
static int epoll_fd = 0;
static struct epoll_event* events;
static struct sockaddr_in my_addr;
static struct epoll_event epoll_event;
pthread_mutex_t epoll_wait_mutex;

void (*sigset(int sig, void (*disp)(int)))(int);//without it gcc wil complain

#endif /* _MAIN_H_INCLUDED_ */
