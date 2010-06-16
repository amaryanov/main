CC			= gcc
CFLAGS		= -c -Wall
LDFLAGS		= -lpthread -lrt
SOURCES		= main.c
OBJDIR		= obj
SUBDIRS		= $(OBJDIR)
OBJECTS		= obj/$(SOURCES:.c=.o)
EXECUTABLE	= simple-server

all: $(SUBDIRS) $(SOURCES) $(EXECUTABLE)

$(SUBDIRS):
	mkdir $@
	
$(EXECUTABLE): $(OBJECTS) 
	$(CC) $(LDFLAGS) $(OBJECTS) -o $@

obj/%.o: %.c
	$(CC) $(CFLAGS) $< -o $@

clean:
	rm -rf $(OBJDIR) $(EXECUTABLE)
