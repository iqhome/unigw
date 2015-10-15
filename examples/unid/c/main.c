
/* Sample UDP client */

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <netdb.h>
#include <libgen.h>

#define COM_GETMODULEINFO   "00000200FFFF"
#define COM_GETNODENUM      "00000000FFFF"

#define BUFSIZE             1024

/*
 * error - wrapper for perror
 */
void error(char *msg) {
    perror(msg);
    exit(0);
}

int main(int argc, char **argv) {
    int sockfd, n;
    socklen_t serverlen;
    struct sockaddr_in serveraddr;
    struct hostent *server;
    char buf[BUFSIZE];

    const char hostname[]   = "127.0.0.1";
    int portno = 5000;

    /* socket: create the socket */
    sockfd = socket(AF_INET, SOCK_DGRAM, 0);
    if (sockfd < 0){
        error("ERROR opening socket");
    }

    /* gethostbyname: get the server's DNS entry */
    server = gethostbyname(hostname);
    if (server == NULL) {
        fprintf(stderr,"ERROR, no such host as %s\n", hostname);
        exit(0);
    }

    /* build the server's Internet address */
    bzero((char *) &serveraddr, sizeof(serveraddr));
    serveraddr.sin_family = AF_INET;
    bcopy((char *)server->h_addr,
	  (char *)&serveraddr.sin_addr.s_addr, server->h_length);
    serveraddr.sin_port = htons(portno);

    bzero(buf, BUFSIZE);

    // request
    strcpy(buf, COM_GETMODULEINFO);
    //strcpy(buf, COM_GETNODENUM);

    /* send the request to the server */
    serverlen = sizeof(serveraddr);
    printf("Send to server: %s\n", buf);
    n = sendto(sockfd, buf, strlen(buf), 0, (struct sockaddr *)&serveraddr, serverlen);
    if (n < 0)
      error("ERROR in sendto");

    /* print the server's response */
    n = recvfrom(sockfd, buf, sizeof(buf), 0, (struct sockaddr *)&serveraddr, &serverlen);
    if (n < 0)
      error("ERROR in recvfrom");
    printf("Response from server: %s\n", buf);
    return 0;
}
