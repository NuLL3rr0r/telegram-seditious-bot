<!--
  (The MIT License)

  Copyright (c) 2018 - 2019 Mamadou Babaei

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
  SOFTWARE.
-->

<?php include 'config.php'; ?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>
            <?php echo htmlspecialchars($botName); ?>
        </title>
        <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
        <style type="text/css">
            html, body {
                background: #fff;
                color: #000;
                font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            }
            iframe {
                margin: 0;
                padding: 20px;
                border: 0;
                overflow: scroll;
                width: 100%;
                height: 380px;
            }
            .wrapper {
                margin: 50px 0;
            }
        </style>
    </head>
    <body>
        <div class="wrapper row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <div class="row well well-lg">
                    <iframe scrolling="no" src="files.php"></iframe>
                </div>
                <div class="row well well-lg">
                    <iframe scrolling="no" src="message.php"></iframe>
                </div>
                <div class="row well well-lg">
                    <iframe src="fetch.php"></iframe>
                </div>
            </div>
        </div>
        <div class="clearfix">
        </div>
    </body>
</html>
