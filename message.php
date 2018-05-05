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

<?php
    if (isset($_POST['text'])) {
        $text = strip_tags(trim($_POST['text']));
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>
            <?php echo htmlspecialchars($botName); ?>
        </title>
        <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
        <style type="text/css">
            html, body {
                background: #f5f5f5;
                color: #000;
                font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
        </style>
        <script type="text/javascript">
            function handleRtlCheckboxClick(combobox) {
                var element = document.getElementById("textInput");

                if (combobox.checked) {
                    element.style.direction = 'rtl';
                } else {
                    element.style.direction = 'ltr';
                }
            }
        </script>
    </head>
    <body>
<?php
    if (empty($text)) {
?>
        <div class="checkbox"><label><input type="checkbox" onclick="javascript:handleRtlCheckboxClick(this);" />Right to Left</label></div>
        <form action="message.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="textInput">Message</label>
                <textarea name="text" id="textInput" class="form-control" rows="10"></textarea>
            </div>
            <input type="submit" value="Send to <?php echo htmlspecialchars($chatName); ?>" class="btn btn-default" />
        </form>
<?php
    } else {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $api."/sendMessage?chat_id=".$chatId."&text=".urlencode($text),
            CURLOPT_RETURNTRANSFER => true
        ]);

        $result = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($result, true);
        if (isset($json['ok']) && $json['ok']) {
            echo "Your message has been sent to telegram successfully!<br /><br />";
        } else {
            echo "Error: failed to send the message!";
        }
?>
        <div class="text-center">
            <a href="message.php">Return</a>
        </div>
<?php
    }
?>
    </body>
</html>
