# unigw
Examples for [IQHome Universal Gateway][iqhome-unigw]

### Universal Gateway

The Universal Gateway is based on the [IQRF DPA][iqrfdpa] solution.

These examples shows how to use the pre-installed unid daemon on the gateway.


The unid daemon services:

 - Manage IQRF communication via SPI
 - Simple UDP interface with HEX strings - IQRF DPA v2.20+ commands
 - [IQRF IDE][iqrfide] 4.31+ UDP device compatibility
 - Mirroring IQRF communication to IQRF IDE to simplify development and debugging

### Available simple examples

 - C
 - Java
 - PHP
 - Python

### Simple WEB Application

The simple WEB application uses the pre-installed unid daemon's UDP interface.



[iqhome]: <https://iqhome.org>
[iqhome-unigw]: <https://iqhome.org>
[iqrf]: <http://iqrf.org>
[iqrfdpa]: <http://www.iqrf.org/weben/index.php?sekce=highlights&id=dpa>
[iqrfide]: <http://www.iqrf.org/weben/index.php?sekce=products&id=iqrf-ide-v400&ot=development-tools&ot2=development-sw>
