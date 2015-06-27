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
                'cookie' => '_csrf=eef13a3679738be3e17292e63c7939aa559f4f9d83f83a648484e5a91ff3d67da%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22ayJ_ofLvqmhRjidnDTMcS_jc4tuJ_jN0%22%3B%7D; PHPSESSID=a8u4tqpk2l9did1lut7p3dbqn2; _identity=8450b41366d4812549c3fdb96ecbf2454d888618d408c94652dfd6e89fd07ff9a%3A2%3A%7Bi%3A0%3Bs%3A9%3A%22_identity%22%3Bi%3A1%3Bs%3A46%3A%22%5B1%2C%22Bq6HnQ4Z07uhCsxF_XRlReMKLPjpcGwt%22%2C1209600%5D%22%3B%7D',
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
        'data' => file_get_contents(__DIR__ . '/blob/mail.txt'),
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
];



