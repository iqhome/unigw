# unigw
Examples for [IQHome Universal Gateway][iqhome-unigw]

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

### Simple WEB Application

The Simple WEB Application
The application on page loading build the actual network.
At first use you should

## RPi image

 - RW FS
 - RO FS for reduce SD card failures

For Universal Gateway we Debian Wheezy (kernel version 3.12.25) with pre-installed dependencies.
Installed for examlpes:
 - unid - Universal Gateway Daemon
 - library dependencies for unid
 - PHP5
 - Python
 - Java JDK
 - Lighttpd WEB server
 - SSH server

 - login: root, passwd: iqhome



[iqhome]: <http://iqhome.org>
[iqhome-unigw]: <http://www.iqhome.org/termekeink/sensnet/>
[iqrf]: <http://iqrf.org>
[iqrfdpa]: <http://www.iqrf.org/weben/index.php?sekce=highlights&id=dpa>
[iqrfide]: <http://www.iqrf.org/weben/index.php?sekce=products&id=iqrf-ide-v400&ot=development-tools&ot2=development-sw>
