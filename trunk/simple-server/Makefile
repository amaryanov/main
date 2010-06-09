C=gcc
CFLAGS=-c -Wall
LDFLAGS=-lpthread -lrt
SOURCES=main.c
OBJDIR=obj
SUBDIRS=$(OBJDIR)
OBJECTS=$(SOURCES:.c=.o)
EXECUTABLE=simple-server

all: $(SUBDIRS) $(SOURCES) $(EXECUTABLE)

.PHONY: $(SUBDIRS)

$(SUBDIRS):
	mkdir -p $@
	
$(EXECUTABLE): $(OBJECTS) 
	$(CC) $(LDFLAGS) $(OBJECTS) -o $@

%.o: %.c
	$(CC) $(CFLAGS) $< -o $@

clean:
	rm -rf $(OBJDIR)
