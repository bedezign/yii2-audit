<?php

return [
    [
        'id' => 1,
        'entry_id' => 1,
        'type' => 'audit/request',
        'data' => serialize([
            'flashes' => [],
            'statusCode' => 200,
            'requestHeaders' => [
                'host' => '192.168.2.130:88',
                'connection' => 'keep-alive',
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36',
                'accept-encoding' => 'gzip, deflate, sdch',
                'accept-language' => 'en,en-AU;q=0.8,en-GB;q=0.6,en-US;q=0.4',
                'cookie' => '_csrf=eef13a3679738be3e17292e63c7939aa559f4f9d83f83a648484e5a91ff3d67da%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22ayJ_ofLvqmhRjidnDTMcS_jc4tuJ_jN0%22%3B%7D; PHPSESSID=a8u4tqpk2l9did1lut7p3dbqn2',
            ],
            'responseHeaders' => [
                'X-Powered-By' => 'PHP/5.5.9-1ubuntu4.7',
                'Expires' => 'Thu, 19 Nov 1981 08:52:00 GMT',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
                'Pragma' => 'no-cache',
                'Content-Type' => 'text/html; charset=UTF-8',
            ],
            'route' => 'audit/entry/view',
            'action' => 'bedezign\\yii2\\audit\\controllers\\EntryController::actionView()',
            'actionParams' => [
                0 => '2',
                1 => '',
            ],
            'requestBody' => [],
            'SERVER' => [
                'DOCUMENT_ROOT' => '/vagrant/git/yii2-audit/tests/codeception/_app/web',
                'REMOTE_ADDR' => '192.168.2.100',
                'REMOTE_PORT' => '59776',
                'SERVER_SOFTWARE' => 'PHP 5.5.9-1ubuntu4.7 Development Server',
                'SERVER_PROTOCOL' => 'HTTP/1.1',
                'SERVER_NAME' => '0.0.0.0',
                'SERVER_PORT' => '88',
                'REQUEST_URI' => '/index.php?r=audit%2Fentry%2Fview&id=2',
                'REQUEST_METHOD' => 'GET',
                'SCRIPT_NAME' => '/index.php',
                'SCRIPT_FILENAME' => '/vagrant/git/yii2-audit/tests/codeception/_app/web/index.php',
                'PHP_SELF' => '/index.php',
                'QUERY_STRING' => 'r=audit%2Fentry%2Fview&id=2',
                'HTTP_HOST' => '192.168.2.130:88',
                'HTTP_CONNECTION' => 'keep-alive',
                'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36',
                'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, sdch',
                'HTTP_ACCEPT_LANGUAGE' => 'en,en-AU;q=0.8,en-GB;q=0.6,en-US;q=0.4',
                'HTTP_COOKIE' => '_csrf=eef13a3679738be3e17292e63c7939aa559f4f9d83f83a648484e5a91ff3d67da%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22ayJ_ofLvqmhRjidnDTMcS_jc4tuJ_jN0%22%3B%7D; PHPSESSID=a8u4tqpk2l9did1lut7p3dbqn2; _identity=8450b41366d4812549c3fdb96ecbf2454d888618d408c94652dfd6e89fd07ff9a%3A2%3A%7Bi%3A0%3Bs%3A9%3A%22_identity%22%3Bi%3A1%3Bs%3A46%3A%22%5B1%2C%22Bq6HnQ4Z07uhCsxF_XRlReMKLPjpcGwt%22%2C1209600%5D%22%3B%7D',
                'REQUEST_TIME_FLOAT' => 1435383288.493,
                'REQUEST_TIME' => 1435383288,
            ],
            'GET' => [
                'r' => 'audit/entry/view',
                'id' => '2',
            ],
            'POST' => [],
            'COOKIE' => [
                '_csrf' => 'eef13a3679738be3e17292e63c7939aa559f4f9d83f83a648484e5a91ff3d67da:2:{i:0;s:5:\"_csrf\";i:1;s:32:\"ayJ_ofLvqmhRjidnDTMcS_jc4tuJ_jN0\";}',
                'PHPSESSID' => 'a8u4tqpk2l9did1lut7p3dbqn2',
                '_identity' => '8450b41366d4812549c3fdb96ecbf2454d888618d408c94652dfd6e89fd07ff9a:2:{i:0;s:9:\"_identity\";i:1;s:46:\"[1,\"Bq6HnQ4Z07uhCsxF_XRlReMKLPjpcGwt\",1209600]\";}',
            ],
            'FILES' => [],
            'SESSION' => [
                '__flash' => [],
            ],
        ]),
    ],
    [
        'id' => 2,
        'entry_id' => 1,
        'type' => 'audit/db',
        'data' => serialize([
            'messages' => [
                [
                    'SHOW FULL COLUMNS FROM `audit_entry`',
                    80,
                    'yii\\db\\Command::query',
                    1435385301.7008,
                    [],
                ],
                [
                    'SHOW FULL COLUMNS FROM `audit_entry`',
                    96,
                    'yii\\db\\Command::query',
                    1435385301.7016,
                    [],
                ],
                [
                    'SHOW CREATE TABLE `audit_entry`',
                    80,
                    'yii\\db\\Command::query',
                    1435385301.7045,
                    [],
                ],
                [
                    'SHOW CREATE TABLE `audit_entry`',
                    96,
                    'yii\\db\\Command::query',
                    1435385301.7048,
                    [],
                ],
                [
                    'INSERT INTO `audit_entry` (`route`, `user_id`, `ip`, `ajax`, `request_method`, `created`) VALUES (\'audit/entry/index\', 0, \'192.168.2.100\', 0, \'GET\', \'2015-06-27 06:08:21\')',
                    80,
                    'yii\\db\\Command::execute',
                    1435385301.7137,
                    [],
                ],
                [
                    'INSERT INTO `audit_entry` (`route`, `user_id`, `ip`, `ajax`, `request_method`, `created`) VALUES (\'audit/entry/index\', 0, \'192.168.2.100\', 0, \'GET\', \'2015-06-27 06:08:21\')',
                    96,
                    'yii\\db\\Command::execute',
                    1435385301.7149,
                    [],
                ],
            ]
        ]),
    ],
    [
        'id' => 3,
        'entry_id' => 1,
        'type' => 'audit/log',
        'data' => serialize([
            'messages' => [
                [
                    'Bootstrap with bedezign\\yii2\\audit\\Bootstrap::bootstrap()',
                    8,
                    'yii\\base\\Application::bootstrap',
                    1435383751.7968,
                    [],
                ],
                [
                    'SHOW FULL COLUMNS FROM `audit_entry`',
                    4,
                    'yii\\db\\Command::query',
                    1435383751.9483,
                    [],
                ],
                [
                    'Opening DB connection: mysql:host=localhost;dbname=audit_test',
                    4,
                    'yii\\db\\Connection::open',
                    1435383751.9484,
                    [],
                ],
                [
                    'SHOW CREATE TABLE `audit_entry`',
                    4,
                    'yii\\db\\Command::query',
                    1435383751.9635,
                    [],
                ]
            ],
        ]),
    ],
    [
        'id' => 4,
        'entry_id' => 1,
        'type' => 'audit/profiling',
        'data' => serialize([
            'memory' => 14578616,
            'time' => 12.821942090988,
            'messages' => [
                [
                    'Opening DB connection: mysql:host=localhost;dbname=audit_test',
                    80,
                    'yii\\db\\Connection::open',
                    1435383751.9484,
                    [],
                ],
                [
                    'Opening DB connection: mysql:host=localhost;dbname=audit_test',
                    96,
                    'yii\\db\\Connection::open',
                    1435383751.9549,
                    [],
                ],
                [
                    'SHOW FULL COLUMNS FROM `audit_entry`',
                    80,
                    'yii\\db\\Command::query',
                    1435383751.955,
                    [],
                ],

            ],
        ]),
    ],
    [
        'id' => 5,
        'entry_id' => 1,
        'type' => 'audit/extra',
        'data' => serialize([
            [
                'type' => 'type or identifier',
                'data' => 'extra data can be an integer, string, array, object or whatever',
            ],
        ]),

    ],
    [
        'id' => 6,
        'entry_id' => 1,
        'type' => 'audit/mail',
        'data' => file_get_contents(__DIR__ . '/mail/data.txt'),
    ],
    [
        'id' => 7,
        'entry_id' => 1,
        'type' => 'app/views',
        'data' => serialize([
            '/vagrant/git/yii2-audit/src/views/entry/index.php',
            '/vagrant/git/yii2-audit/src/views/layouts/main.php',
        ]),
    ],
    [
        'id' => 8,
        'entry_id' => 1,
        'type' => 'audit/curl',
        'data' => serialize([
          0 =>
          [
            'starting_url' => 'http://testing.com/start',
            'headers' => [
              0 => "HTTP/1.1 401 Unauthorized\n",
              1 => "Content-Type: text/html; charset=us-ascii\n",
              2 => "Server: Microsoft-HTTPAPI/2.0\n",
              3 => "Date: Thu, 09 Jul 2015 15:04:45 GMT\n",
              4 => "Content-Length: 341\n",
              5 => "\n",
              6 => "HTTP/1.1 200 OK\n",
              7 => "Cache-Control: no-cache\n",
              8 => "Content-Length: 33094\n",
              9 => "Content-Type: application/json;odata=verbose;charset=utf-8\n",
              10 => "Server: Microsoft-IIS/7.5\n",
              11 => "X-Content-Type-Options: nosniff\n",
              12 => "DataServiceVersion: 1.0;\n",
              13 => "X-AspNet-Version: 4.0.30319\n",
              14 => "Persistent-Auth: true\n",
              15 => "X-Powered-By: ASP.NET\n",
              16 => "Date: Thu, 09 Jul 2015 15:04:49 GMT\n",
              17 => "\n",
            ],
            'content_type' => 'application/json;odata=verbose;charset=utf-8',
            'http_code' => 200,
            'header_size' => 839,
            'request_size' => 746,
            'filetime' => -1,
            'redirect_count' => 1,
            'total_time' => 4.1798570000000002,
            'namelookup_time' => 1.2E-5,
            'connect_time' => 1.2E-5,
            'pretransfer_time' => 1.2999999999999999E-5,
            'size_download' => 33094,
            'speed_download' => 7917,
            'download_content_length' => 33094,
            'starttransfer_time' => 4.0445039999999999,
            'redirect_time' => 0.095444000000000001,
            'primary_ip' => '192.168.1.1',
            'effective_url' => 'http://testing.com/redirected',
            'log' => '* About to connect() to testing.comport 80 (#0)
        *   Trying 192.168.1.1 ... * connected
        * Connected to testing.com (192.168.1.1) port 80 (#0)
        > GET /start HTTP/1.1
        Host: testing.com:80
        Accept: */*

        < HTTP/1.1 302 Moved
        < Content-Type: text/html; charset=us-ascii
        < Server: Microsoft-HTTPAPI/2.0
        < Location: http://testing.com/redirected
        <
        * Ignoring the response-body
        * Connection #0 to host testing.com left intact
        > GET /redirected HTTP/1.1
        Host: testing.com:80
        Accept: */*

        < HTTP/1.1 200 OK
        < Cache-Control: no-cache
        < Content-Length: 33094
        < Content-Type: application/json;odata=verbose;charset=utf-8
        < Server: Microsoft-IIS/7.5
        < X-Content-Type-Options: nosniff
        < DataServiceVersion: 1.0;
        < X-AspNet-Version: 4.0.30319
        < Persistent-Auth: true
        < X-Powered-By: ASP.NET
        < Date: Thu, 09 Jul 2015 15:04:49 GMT
        <
        * Connection #0 to host testing.com left intact
        ',
            'content' => '{"test":"data","test2":["data2","data3"]}',
          ],
       ])
    ]
];



