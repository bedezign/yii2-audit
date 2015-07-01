<?php

return [
    [
        'id' => 1,
        'entry_id' => 1,
        'successful' => 1,
        'to' => 'to@example.com',
        'from' => 'from@example.com',
        'reply' => 'reply@example.com',
        'cc' => 'cc@example.com',
        'bcc' => 'bcc@example.com',
        'subject' => 'test email subject',
        'text' => 'test email text',
        'html' => 'test email <b>html</b>',
        'created' => '2015-06-25 01:02:03',
        'data' => 's:865:"Message-ID: <57d02698758ad379ddb7b615f7323d20@0.0.0.0>
Date: Thu, 25 Jun 2015 01:02:03 +0000
Subject: test email subject
From: My Application <from@example.com>
Reply-To: My Application <reply@example.com>
To: My Application <to@example.com>
Cc: My Application <cc@example.com>
Bcc: My Application <bcc@example.com>
MIME-Version: 1.0
Content-Type: multipart/alternative;
 boundary="_=_swift_v4_1435716062_728dec06ded94dcb8315d1485d80f8d4_=_"


--_=_swift_v4_1435716062_728dec06ded94dcb8315d1485d80f8d4_=_
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: quoted-printable

test email text

--_=_swift_v4_1435716062_728dec06ded94dcb8315d1485d80f8d4_=_
Content-Type: text/html; charset=utf-8
Content-Transfer-Encoding: quoted-printable

test email <b>html</b>

--_=_swift_v4_1435716062_728dec06ded94dcb8315d1485d80f8d4_=_--
";',
    ],
];
