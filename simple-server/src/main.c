
/*
 * Copyright (C) Anton Maryanov
 */

#include "main.h"

/** устанавливает неблокирующий режим для указанного сокета */
int setnonblocking(int sock) {
	int opts = fcntl(sock,F_GETFL);
	if (opts < 0) {
		printf("fcntl(F_GETFL)");
		return -1;
	}
	opts = (opts | O_NONBLOCK);
	if (fcntl(sock,F_SETFL,opts) < 0) {
		printf("fcntl(F_SETFL)");
		return -1;
	}

	return 0;
}

/** инициализирует серверный сокет */
int initserver(int type, const struct sockaddr* sa, socklen_t alen, int qlen) {
	int sock_fd = 0;
	int value = 1;

	if ( (sock_fd = socket(sa->sa_family, type, 0)) < 0 ) {
		return -1;
	}

	if ( setsockopt(sock_fd, SOL_SOCKET, SO_REUSEADDR, &value, sizeof(value)) < 0 ) {
		close(sock_fd);
		return -1;
	}

	if ( setsockopt(sock_fd, SOL_SOCKET, SO_KEEPALIVE, &value, sizeof(value)) < 0 ) {
		close(sock_fd);
		return -1;
	}

	if ( setnonblocking(sock_fd) < 0 ) {
		close(sock_fd);
		return -1;
	}

	if ( bind(sock_fd, sa, alen) < 0 ) {
		close(sock_fd);
		return -1;
	}

	if ( type == SOCK_STREAM || type == SOCK_SEQPACKET ) {
		if ( listen(sock_fd, qlen) < 0 ) {
			close(sock_fd);
			return -1;
		}
	}

	return sock_fd;
}
struct sharedThings {
	pthread_mutex_t terminateMutex;
	int terminated;
};
typedef struct sharedThings sharedThings_t;

sharedThings_t * sharedThings_create(char *sharedThings_name)
{
	int fd;
	sharedThings_t *semap;
	pthread_mutexattr_t terminate_attr;

	fd = shm_open(sharedThings_name, O_RDWR | O_CREAT | O_EXCL, 0600);
	if (fd < 0)
	{
		return (NULL);
	}
	ftruncate(fd, sizeof(sharedThings_t));

	pthread_mutexattr_init(&terminate_attr);
	pthread_mutexattr_setpshared(&terminate_attr, PTHREAD_PROCESS_SHARED);

	semap = (sharedThings_t *) mmap(NULL, sizeof(sharedThings_t), PROT_READ | PROT_WRITE, MAP_SHARED, fd, 0);

	close(fd);

	pthread_mutex_init(&semap->terminateMutex, &terminate_attr);

	pthread_mutexattr_destroy(&terminate_attr);
	return semap;
}
void sharedThings_close(sharedThings_t *semap, bool destroy_mutex)
{
	if(destroy_mutex == true)
	{
		pthread_mutex_destroy(&semap->terminateMutex);
	}
	munmap((void *) semap, sizeof(sharedThings_t));
}
void cleaner(void *a)
{
	pthread_mutex_unlock((pthread_mutex_t*)a);
}

bool getTerminate(sharedThings_t *semap)
{
	bool res = false;
	pthread_mutex_lock(&semap->terminateMutex);
	pthread_cleanup_push(cleaner, &semap->terminateMutex);
	res = semap->terminated;
	printf("terminated = %d\n", res);
	pthread_mutex_unlock(&semap->terminateMutex);
	pthread_cleanup_pop(0);
	return res;
}
void setTerminate(sharedThings_t* semap, bool val)
{
	printf("set terminate to %d from %d\n", val, getpid());
	pthread_mutex_lock(&semap->terminateMutex);
	pthread_cleanup_push(cleaner, &semap->terminateMutex);
	semap->terminated = val;
	pthread_mutex_unlock(&semap->terminateMutex);
	pthread_cleanup_pop(0);
}

static void waitForConnect()
{
	char buff[32];
	int rd;
	int n = 0;
	int32_t sock_fd;
	uint32_t event;
	int new_socket_fd;
	int count_fds = 0;
	char body[] = "<h1>hello</h1>";
	char response[200];
	sprintf(response, "HTTP/1.1 200 OK\r\nContent-Length: %d\r\nConnection: close\r\nContent-Type: text/html\r\nDate: Sat, 26 Apr 2008 01:13:35 GMT\r\nServer: simpleserver\r\n\r\n%s", strlen(body), body);
	socklen_t addrlen = sizeof(struct sockaddr_in);
	/** ожидаю события на дескрипторе epoll */
	count_fds = epoll_wait(epoll_fd, events, epsize, -1);
	printf("received accept in %lu %d\n", pthread_self(), getpid());
	printf("%s %d\t%lu %d count_fds = %d\n", __FILE__, __LINE__, pthread_self(), getpid(), count_fds);
	if (count_fds == -1) {
		printf("epoll_wait()");
		exit(EXIT_FAILURE);
	}
	/** цикл повторяется "count_fds" раз */
	for ( n = 0; n < count_fds; ++n) {
		/* если (events[n].data.fd == base_socket_fd) истинно -
		 *              * значит запрос от клиента на подключение
		 *                           */
		if ( events[n].data.fd == base_socket_fd ) {
			printf("%s %d\t%lu %d %s\n", __FILE__, __LINE__, pthread_self(), getpid(), "принимаем запрос на подключение");
			/** принимаем запрос на подключение */
			new_socket_fd = accept(base_socket_fd, (struct sockaddr*)&my_addr, &addrlen);
			if (new_socket_fd == -1) {
				printf("accept()");
				continue;
//				exit(EXIT_FAILURE);
			}
			if ( setnonblocking(new_socket_fd) < 0 ) {
				printf("setnonblocking()");
				close(new_socket_fd);
				continue;
			}
			/** указываем какие события на сокете мониторить */
			epoll_event.events = EPOLLIN | EPOLLET;
			epoll_event.data.fd = new_socket_fd;
			/** добавляем в epoll */
			printf("%s %d\t%lu %d %s %d\n", __FILE__, __LINE__, pthread_self(), getpid(), "добавляем в epoll", new_socket_fd);
			if (epoll_ctl(epoll_fd, EPOLL_CTL_ADD, new_socket_fd, &epoll_event) == -1) {
				printf("epoll_ctl()");
				exit(EXIT_FAILURE);
			}
			printf("%s %d\t%lu %d %s\n", __FILE__, __LINE__, pthread_self(), getpid(), "добавлен");
		} else {
			printf("%s %d\t%lu %d %s\n", __FILE__, __LINE__, pthread_self(), getpid(), "принимаем просто запрос");
			sock_fd = events[n].data.fd;
			event = events[n].events;
			/* этот дескриптор уже есть в epoll. */
			if ( event & EPOLLIN ) {
				printf("%s %d\t%lu %d %s\n", __FILE__, __LINE__, pthread_self(), getpid(), "сокет готов для чтения");
				/* сокет готов для чтения */
				printf("descriptor %d ready for read\n", sock_fd);
				while((rd = recv(sock_fd, buff, sizeof(buff), 0)) > 0)
				{
					printf("%s", buff);
				}
				printf("\n");
				if(rd == -1 && errno == EAGAIN)
				{
					printf("lets send smth in %d\n", sock_fd);
					send(sock_fd, response, strlen(response), 0);
				}
				else if(rd == 0)
				{
					printf("wait for write in %d\n", sock_fd);
					send(sock_fd, response, strlen(response), 0);
					continue;
				}
				else
				{
					printf("%s\n", buff);
				}
			}
			if ( event & EPOLLOUT ) {
				/* сокет готов для записи */
				printf("%s %d\t%lu %d %s\n", __FILE__, __LINE__, pthread_self(), getpid(), "сокет готов для записи");
				printf("descriptor %d ready for write\n", sock_fd);
				send(sock_fd, response, strlen(response), 0);
			}
			//				if ( event & EPOLLRDHUP ) {
			//					/* как я понял из документации(http://linux.die.net/man/2/epoll_ctl)
			//					 *                      * это событие закрытия удаленного сокета. так?
			//					 *                                           */
			//					printf("descriptor %d disconnect\n", sock_fd);
			//				}
			//				if ( event & EPOLLPRI ) {
			//					/* срочные данные готовы для считывания. так? */
			//					printf("descriptor %d ready for read urgent data\n", sock_fd);
			//				}
			//				if ( event & EPOLLERR ) {
			//					/* какая-то ошибка произошла на сокете. так?
			//					 *                      * какие ошибки могут происходить? и почему?
			//					 *                                           */
			//					printf("on descriptor %d error condition\n", sock_fd);
			//				}
			//				if ( event & EPOLLHUP ) {
			//					/* какое-то событие произошло на сокете. так? */
			//					printf("on descriptor %d ???\n", sock_fd);
			//				}
			//				if ( event & EPOLLET ) {
			//					/* как я понял, эта константа используется для установки
			//					 *                      * какого-то правила наблюдения за сокетом. и не понимаю,
			//					 *                                           * о чем это сыбытие может говорить(если оно происходит).
			//					 *                                                                */
			//					printf("event & EPOLLET\n");
			//				}
			//				if ( event & EPOLLONESHOT ) {
			//					/* по моему, так же как и в предыдущем блоке, это не событие.
			//					 *                      * но суть константы понятна.
			//					 *                                           */
			//					printf("event & EPOLLONESHOT\n");
			//				}
			close(sock_fd);
		}
	}
	//////////////////////////
}

static void *doit(void *arg)
{
	bool ret = false;
	pthread_t i = pthread_self();
	sharedThings_t* semap = (sharedThings_t*)arg;

	for(;;)
	{
		printf("%s %d\t%lu %d %s\n", __FILE__, __LINE__, pthread_self(), getpid(), "cycle start");

		pthread_mutex_lock(&epoll_wait_mutex);
		printf("try to lock terminator...%lu %d\n", i, getpid());
		if(getTerminate(semap) == false)
		{
			printf("success %lu %d\n", i, getpid());

			printf("start %lu %d\n", i, getpid());

			printf("waiting for accept in %lu %d\n", i, getpid());
			waitForConnect();
		}
		else
		{
			printf("failed %lu %d\n", i, getpid());
			ret = true;
		}
		pthread_mutex_unlock(&epoll_wait_mutex);

		printf("end %lu %d\n", i, getpid());
		printf("%s %d\t%lu %d %s\n", __FILE__, __LINE__, pthread_self(), getpid(), "cycle preend");
		if(ret == true)
		{
			break;
		}
		printf("%s %d\t%lu %d %s\n", __FILE__, __LINE__, pthread_self(), getpid(), "cycle end");
	}
	return NULL;
}

static void childSignalWaiter(sharedThings_t* semap)
{
	int status = 0;
	sigset_t waitset;
	siginfo_t info;
	sigfillset( &waitset );
	sigprocmask( SIG_BLOCK, &waitset, NULL );
	while((status = sigwaitinfo( &waitset, &info )) != -1)
	{
		if(status == SIGTERM)
		{
			printf("User wish to exit in %d.\n", getpid());
			break;
		}
		else if(status == SIGINT)
		{
			printf("I'm wish to exit in %d\n", getpid());
			close(base_socket_fd);
			close(epoll_fd);
			break;
		}
		else if(status == SIGPIPE)
		{
			printf("\n\n\t\tPIPE IS CLOSED %d\n", getpid());
			break;
		}
		else
		{    /* Non-standard case -- may never happen */
			printf("\n\n\t\tUnexpected status (0x%x)\n", status);
		}
	}
}

static void mainSignalWaiter(sharedThings_t* semap, pid_t* pids)
{
	int status = 0;
	sigset_t waitset;
	siginfo_t info;
	sigfillset( &waitset );
	sigprocmask( SIG_BLOCK, &waitset, NULL );
	int i;
	while((status = sigwaitinfo( &waitset, &info )) != -1)
	{
		if (status == SIGCHLD)
		{
			printf("child exited, status=%d\n", WEXITSTATUS(status));
		}
		else if(status == SIGTERM)
		{
			printf("User wish to exit in parent.\n");
			setTerminate(semap, true);
			close(base_socket_fd);
			close(epoll_fd);
			printf("Closed base_socket_fd and epoll_fd\n");

			for(i = 0; i < PROCESS_COUNT; i++)
			{
				kill(pids[i], SIGTERM);
			}
			break;
		}
		else if(status == SIGINT)
		{
			printf("I'm wish to exit in parent.\n");
			setTerminate(semap, true);
			close(base_socket_fd);
			close(epoll_fd);
			printf("Closed base_socket_fd and epoll_fd\n");
			break;
		}
		else if(status == SIGPIPE)
		{
			printf("\n\nPIPE IS CLOSED %d\n", getpid());
			break;
		}
		else
		{
			printf("Unexpected status (0x%x)\n", status);
		}
	}
}

static void waitForChildTerminate(pid_t* pids)
{
	int i, status;
	pid_t wpid;
	for(i = 0; i < PROCESS_COUNT; i++)
	{
		do
		{
			printf("waiting for %d\n", pids[i]);
			wpid = waitpid(pids[i], &status, WUNTRACED
#ifdef WCONTINUED       /* Not all implementations support this */
					| WCONTINUED
#endif
			);
			if (wpid == -1)
			{
				printf("waitpid");
				exit(EXIT_FAILURE);
			}
			if (WIFEXITED(status))
			{
				printf("child exited, status=%d\n", WEXITSTATUS(status));
			}
			else if (WIFSIGNALED(status))
			{
				printf("child killed (signal %d)\n", WTERMSIG(status));
			}
			else if (WIFSTOPPED(status))
			{
				printf("child stopped (signal %d)\n", WSTOPSIG(status));
#ifdef WIFCONTINUED     /* Not all implementations support this */
			}
			else if (WIFCONTINUED(status))
			{
				printf("child continued\n");
#endif
			}
			else
			{    /* Non-standard case -- may never happen */
				printf("Unexpected status (0x%x)\n", status);
			}
		} while (!WIFEXITED(status) && !WIFSIGNALED(status));
	}
}

static bool forkChilds(pid_t* pids)
{
	int i;
	for(i = 0; i < PROCESS_COUNT; i++)
	{
		switch((pids[i] = fork()))
		{
			case -1:
				//todo error
				exit(2);
				break;
			case 0:
				//child
				return false;
				break;
			default:
				//parent
				printf("forked %d\n", pids[i]);
		}
	}
	return true;
}

static void sharedThingsUnlink(char* shm_name, sharedThings_t* semap)
{
	printf("shm_unlink %d\n", getpid());
	if (shm_unlink(shm_name) != 0)
	{
		char errstr[30];
		switch(errno)
		{
			case ENOENT:
				strcpy(errstr, "does not exist");
				break;
			case EACCES:
				strcpy(errstr, "no permission");
		}
		printf("SHM_UNLINK %s %d\n", errstr, getpid());
	}
}

static void startServer()
{
	int i;
	struct timeval tim;
	pid_t pids[PROCESS_COUNT];
	char shm_name[50];

	srand ( time(NULL) );
	gettimeofday(&tim, NULL);
	sprintf(shm_name, "/SIMPLESERVER_%d_%d_%d", rand(), (int)tim.tv_sec, (int)tim.tv_usec);
	sharedThings_t* semap = sharedThings_create(shm_name);
	if (semap == NULL)
	{
		exit(1);
	}
	semap->terminated = false;

	if(forkChilds(pids) == true)
	{
		sigset(SIGPIPE, SIG_IGN);
		mainSignalWaiter(semap, pids);
		waitForChildTerminate(pids);

		sharedThings_close(semap, true);
		sharedThingsUnlink(shm_name, semap);
	}
	else
	{
		//child


	pthread_mutexattr_t epoll_wait_mutex_attr;
	pthread_mutexattr_init(&epoll_wait_mutex_attr);
	pthread_mutex_init(&epoll_wait_mutex, &epoll_wait_mutex_attr);

		sigset(SIGPIPE, SIG_IGN);

		/** epoll */
		struct epoll_event static_events[epsize];
		/*struct epoll_event* */events = &static_events[0];
		ZEROPOD(epoll_event);
		ZEROPOD(static_events);

		/** создаю epoll с начальным размером очереди epsize */
		epoll_fd = epoll_create(epsize);
		if (epoll_fd == -1) {
			printf("epoll_create()");
			exit(EXIT_FAILURE);
		}

		/** создается елемент события для слушающего сокета */
		epoll_event.events = EPOLLIN | EPOLLHUP | EPOLLERR;
		epoll_event.data.fd = base_socket_fd;
		if (epoll_ctl(epoll_fd, EPOLL_CTL_ADD, base_socket_fd, &epoll_event) == -1) {
			printf("epoll_ctl()");
			exit(EXIT_FAILURE);
		}

		pthread_t id[THREAD_COUNT];
		for (i = 0; i < THREAD_COUNT; i++)
		{
			pthread_create(&id[i], NULL, doit, (void*)semap);
		}
		childSignalWaiter(semap);
		for (i = 0; i < THREAD_COUNT; i++)
		{
			printf("join %lu %d\n", id[i], getpid());
			pthread_join(id[i], NULL);
			printf("joined %lu %d\n", id[i], getpid());
		}
		sharedThings_close(semap, false);
		close(epoll_fd);
	}
	printf("done %d\n", getpid());
}

int main(void)
{

	/** адреса */
	struct sockaddr_in their_addr;
	socklen_t addrlen = sizeof(struct sockaddr_in);
	ZEROPOD(my_addr);
	ZEROPOD(their_addr);

	/** инициализирую адреса */
	my_addr.sin_family = AF_INET;
	my_addr.sin_port = htons(MYPORT);
	my_addr.sin_addr.s_addr = inet_addr("127.0.0.1");

	/** создаю слушающий сокет */
	base_socket_fd=initserver(SOCK_STREAM, (struct sockaddr*)&my_addr, addrlen, qsize);
	if ( base_socket_fd < 0) {
		printf("initserver()");
		return EXIT_FAILURE;
	}

	startServer();
	printf("\n\n\t\tWTF\n\n");
	return EXIT_SUCCESS;
}
