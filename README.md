# [IQHome Universal Gateway][iqhome-unigw]

```
$ git clone
```

## Universal Gateway

The RPi based device is a gateway between your [IQRF DPA][iqrfdpa] wireless network and your custom application.
The Universal Gateway is simplify the development process with IQRF networks.

## unid
The unid daemon services:

 - [IQRF IDE][iqrfide] 4.31+ UDP gateway device compatibility
 - Manage IQRF communication via SPI
 - Simple UDP interface with hex strings - IQRF DPA v2.20+ commands
 - Mirroring IQRF communication to IQRF IDE to simplify development and debugging

## Examples

### Simple examples

The examples contains some dummy example on different programming language for simplify the start of the development proces with the Universal Gateway. The examples shows how to communicate from your custom application with the IQRF DPA netwrk via unid daemon on the gateway.
Avilable examples:
 - C
 - Java
 - PHP
 - Python

#### C example

You can compile and run the example code with:
```
$ make
$ ./gateway
```


#### Java example

Java doesn't installed on the default RPi image due to it's large size.
You can install with:
```
# apt-get update && apt-get upgrade
# apt-get install openjdk-7-jdk
```
You can run the example with:
```
$ javac Gateway.java
$ java Gateway
```

#### PHP example



#### Python example



### Simple WEB Application

The Simple WEB Application
The application on page loading build the actual network.
At first use you should

## RPi image

 - Read-Write file system
 - Read-Only file system for reduce SD card failures, recommended for

Default image contains:
 - Debian Wheezy 3.6.11+ hardfp kernel
 - automatic ntp clock updat
 - SSH server
 - unid - Universal Gateway Daemon v1.0
 - PHP v5.4.45
 - Python v2.7.3
 - Lighttpd WEB server
 - login: root, password: iqhome



[iqhome]: <http://iqhome.org>
[iqhome-unigw]: <http://www.iqhome.org/products/uni-gw/>
[iqrf]: <http://iqrf.org>
[iqrfdpa]: <http://www.iqrf.org/weben/index.php?sekce=highlights&id=dpa>
[iqrfide]: <http://www.iqrf.org/weben/index.php?sekce=products&id=iqrf-ide-v400&ot=development-tools&ot2=development-sw>
