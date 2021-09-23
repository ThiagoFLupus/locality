<?php

namespace Locality\connectionDB;

/**  configurar o host e a porta, conforme o ambiente de execução: **/
/* 'host'=> '192.168.171.192' */
/* 'port'=> '7000'*/

const DATA_CONN_DB= [
    'host'=> '127.0.0.1',
    'user'=> 'localit_2021_user',
    'password'=> 'localit_2021_pass',
    'database'=> 'localities',
    'port'=> '3001'
];

print_r(DATA_CONN_DB);