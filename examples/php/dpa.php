<?php

$_NADDR = array(
    'COORDINATOR' => 0,
    'BROADCAST' => 0xFF
);

$_HWPID = array(
    'DEFAULT' => 0,
    'ALL' => 0xFFFF,
);

$_PNUM = array(
    'PNUM_COORDINATOR'    => 0x00,
    'PNUM_NODE'           => 0x01,
    'PNUM_OS'             => 0x02,
    'PNUM_EEPROM'         => 0x03,
    'PNUM_EEEPROM'        => 0x04,
    'PNUM_RAM'            => 0x05,
    'PNUM_LEDR'           => 0x06,
    'PNUM_LEDG'           => 0x07,
    'PNUM_SPI'            => 0x08,
    'PNUM_IO'             => 0x09,
    'PNUM_THERMOMETER'    => 0x0A,
    'PNUM_PWM'            => 0x0B,
    'PNUM_UART'           => 0x0C,
    'PNUM_FRC'            => 0x0D,
    'PNUM_USER'           => 0x20,
    'PNUM_ERROR_FLAG'     => 0xFE
);

$_RESPONSE_CODE = array(
    'STATUS_NO_ERROR' =>   0, // No error
    'ERROR_FAIL' =>    1, // General fail
    'ERROR_PCMD' =>    2, // Incorrect PCMD
    'ERROR_PNUM' =>    3, // Incorrect PNUM or PCMD
    'ERROR_ADDR' =>    4, // Incorrect Address
    'ERROR_DATA_LEN' =>    5, // Incorrect Data length
    'ERROR_DATA' =>    6, // Incorrect Data
    'ERROR_HWPROFILE' =>   7, // Incorrect HW Profile ID used
    'ERROR_NADR' =>    8, // Incorrect NADR
    'ERROR_IFACE_CUSTOM_HANDLER' =>    9, // Data from interface consumed by Custom DPA Handler
    'ERROR_MISSING_CUSTOM_DPA_HANDLER' => 10, // Custom DPA Handler is missing
    'ERROR_USER_FROM' => 0x80, // Beginning of the user code error interval
    'ERROR_USER_TO' => 0xFE, // End of the user error code interval
    'STATUS_CONFIRMATION' => 0xFF // Error code used to mark confirmation
);

$_PCMD = array(
    'CMD_COORDINATOR_ADDR_INFO' =>  0,
    'CMD_COORDINATOR_DISCOVERED_DEVICES' =>  1,
    'CMD_COORDINATOR_BONDED_DEVICES' =>  2,
    'CMD_COORDINATOR_CLEAR_ALL_BONDS' =>  3,
    'CMD_COORDINATOR_BOND_NODE' =>  4,
    'CMD_COORDINATOR_REMOVE_BOND' =>  5,
    'CMD_COORDINATOR_REBOND_NODE' =>  6,
    'CMD_COORDINATOR_DISCOVERY' =>  7,
    'CMD_COORDINATOR_SET_DPAPARAMS' =>  8,
    'CMD_COORDINATOR_SET_HOPS' =>  9,
    'CMD_COORDINATOR_DISCOVERY_DATA ' => 10,
    'CMD_COORDINATOR_BACKUP ' => 11,
    'CMD_COORDINATOR_RESTORE ' => 12,
    'CMD_COORDINATOR_READ_REMOTELY_BONDED_MID ' => 15,
    'CMD_COORDINATOR_CLEAR_REMOTELY_BONDED_MID ' => 16,
    'CMD_COORDINATOR_ENABLE_REMOTE_BONDING ' => 17,
    'CMD_NODE_READ' =>  0,
    'CMD_NODE_REMOVE_BOND' =>  1,
    'CMD_NODE_READ_REMOTELY_BONDED_MID' =>  2,
    'CMD_NODE_CLEAR_REMOTELY_BONDED_MID' =>  3,
    'CMD_NODE_ENABLE_REMOTE_BONDING' =>  4,
    'CMD_NODE_REMOVE_BOND_ADDRESS' =>  5,
    'CMD_NODE_BACKUP' =>  6,
    'CMD_NODE_RESTORE' =>  7,
    'CMD_OS_READ' =>  0,
    'CMD_OS_RESET' =>  1,
    'CMD_OS_READ_CFG' =>  2,
    'CMD_OS_RFPGM' =>  3,
    'CMD_OS_SLEEP' =>  4,
    'CMD_OS_BATCH' =>  5,
    'CMD_OS_SET_USEC' =>  6,
    'CMD_OS_SET_MID' =>  7,
    'CMD_OS_RESTART' =>  8,
    'CMD_OS_WRITE_CFG_BYTE' =>  9,
    'CMD_OS_WRITE_CFG ' => 15,
    'CMD_RAM_READ' =>  0,
    'CMD_RAM_WRITE' =>  1,
    'CMD_EEPROM_READ' =>  0,
    'CMD_EEPROM_WRITE' =>  1,
    'CMD_EEEPROM_READ' =>  0,
    'CMD_EEEPROM_WRITE' =>  1,
    'CMD_LED_SET_OFF' =>  0,
    'CMD_LED_SET_ON' =>  1,
    'CMD_LED_GET' =>  2,
    'CMD_LED_PULSE' =>  3,
    'CMD_SPI_WRITE_READ' =>  0,
    'CMD_IO_DIRECTION' =>  0,
    'CMD_IO_SET' =>  1,
    'CMD_IO_GET' => 2,
    'CMD_THERMOMETER_READ' =>  0,
    'CMD_PWM_SET' =>  0,
    'CMD_UART_OPEN' =>  0,
    'CMD_UART_CLOSE' =>  1,
    'CMD_UART_WRITE_READ' =>  2,
    'CMD_FRC_SEND' =>  0,
    'CMD_FRC_EXTRARESULT' =>  1,
    'CMD_FRC_SEND_SELECTIVE' =>  2,
    'CMD_FRC_SET_PARAMS' =>  3,
    'CMD_GET_PER_INFO' => 0x3f
);

$_PERIPHERAL = array(
    'PERIPHERAL_TYPE_DUMMY' => 0x00,
    'PERIPHERAL_TYPE_COORDINATOR' => 0x01,
    'PERIPHERAL_TYPE_NODE' => 0x02,
    'PERIPHERAL_TYPE_OS' => 0x03,
    'PERIPHERAL_TYPE_EEPROM' => 0x04,
    'PERIPHERAL_TYPE_BLOCK_EEPROM' => 0x05,
    'PERIPHERAL_TYPE_RAM' => 0x06,
    'PERIPHERAL_TYPE_LED' => 0x07,
    'PERIPHERAL_TYPE_SPI' => 0x08,
    'PERIPHERAL_TYPE_IO' => 0x09,
    'PERIPHERAL_TYPE_UART' => 0x0a,
    'PERIPHERAL_TYPE_THERMOMETER' => 0x0b,
    'PERIPHERAL_TYPE_ADC' => 0x0c, (*)
    'PERIPHERAL_TYPE_PWM' => 0x0d,
    'PERIPHERAL_TYPE_FRC' => 0x0e,
    'PERIPHERAL_TYPE_USER_AREA' => 0x80
);

$_FRC = array(
    'FRC_USER_BIT_FROM' => 0x40,
    'FRC_USER_BIT_TO' => 0x7F,
    'FRC_USER_BYTE_FROM' => 0xC0,
    'FRC_USER_BYTE_TO' => 0xDF,
    'FRC_USER_2BYTE_FROM' => 0xF0,
    'FRC_USER_2BYTE_TO' => 0xFF
);








?>
