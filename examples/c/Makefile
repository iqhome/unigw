#This is a makefile

CC		:= gcc
CFLAGS		:= -c -Wall

LDFLAGS		:=
DEPS		:=

EXEC		:= gateway

SRC  		:= $(wildcard *.c)
#$(info $(SRC))

OBJ 		:= $(SRC:%.c=%.o)

all: $(EXEC)

$(EXEC): $(OBJ)
	$(CC) $(LDFLAGS) $(OBJ) -o $@ $(DEPS)

%.o: %.c
	$(CC)  $(CFLAGS)  $< -o $@

clean:
	rm -rf *o $(EXEC)
