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
        <meta http-equiv="refresh" content="<?php echo $fetchInterval; ?>;url=fetch.php" />
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
                var element = document.getElementById("messagesTable");

                if (combobox.checked) {
                    element.style.direction = 'rtl';
                } else {
                    element.style.direction = 'ltr';
                }
            }

            function fetchUpdates() {
                location.reload();
            }
        </script>
    </head>
    <body>
<?php
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $api."/getUpdates?chat_id=".$chatId,
        CURLOPT_RETURNTRANSFER => true
    ]);

    $result = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($result, true);
    if (isset($json['ok']) && $json['ok']) {
        echo 'Latest updates from <code>'.$chatName.'</code><br />';
        echo 'Auto-fetch interval is '.$fetchInterval.' second(s)</code><br /><br/>';
    } else {
        echo 'Error: failed to fetch the updates!';
    }

    if (isset($json['result']) && $json['result']) {
        echo '<input type="button" class="btn btn-default" value="Fetch Updates" onclick="fetchUpdates()" />';
        echo '<div class="checkbox"><label><input type="checkbox" onclick="javascript:handleRtlCheckboxClick(this);" />Right to Left</label></div>';
        echo '<br />';

        $result = $json['result'];
        echo '<table class="table table-striped table-hover" id="messagesTable">';
        echo '<tbody>';

        foreach ($result as $key => $value) {
            if (isset($value['message']) && $value['message']) {
                $message = $value['message'];

                if (!isset($message['chat']) || !$message['chat']) {
                    continue;
                }

                $chat = $message['chat'];
                if (!isset($chat['id']) || !$chat['id']) {
                    continue;
                }

                $chat_id = $chat['id'];
                if ($chatId != $chat_id) {
                    continue;
                }

                if (isset($message['from']) && $message['from']) {
                    $from = $message['from'];

                    $bFirstName = false;

                    if (isset($from['first_name']) && $from['first_name']) {
                        $first_name = $from['first_name'];
                        $bFirstName = true;
                    }

                    $bLastName = false;

                    if (isset($from['last_name']) && $from['last_name']) {
                        $last_name = $from['last_name'];
                        $bLastName = true;
                    }

                    if ($bLastName || $bFirstName) {
                        echo '<tr>';
                        echo '<th>';

                        if ($bFirstName) {
                            echo $first_name;
                        }
                        
                        if ($bLastName) {
                            if ($bFirstName) {
                                echo '&nbsp;';
                            }

                            echo $last_name;
                        }

                        echo '</th>';
                        echo '<td>';

                        $bInsertNewLine = false;

                        if (isset($message['reply_to_message']) && $message['reply_to_message']) {
                            $bInsertNewLine = true;
                            echo '<code>Reply</code>&nbsp;';
                        } else if (isset($message['forward_from_chat']) && $message['forward_from_chat']) {
                            $bInsertNewLine = true;
                            echo '<code>Forwarded</code>&nbsp;';
                        } 

                        if (isset($message['audio']) && $message['audio']) {
                            $bInsertNewLine = true;
                            echo '<code>Audio</code>&nbsp;';
                        } else if (isset($message['document']) && $message['document']) {
                            $bInsertNewLine = true;
                            echo '<code>Document</code>&nbsp;';
                        } else if (isset($message['photo']) && $message['photo']) {
                            $bInsertNewLine = true;
                            echo '<code>Photo</code>&nbsp;';
                        } else if (isset($message['video']) && $message['video']) {
                            $bInsertNewLine = true;
                            echo '<code>Video</code>&nbsp;';
                        }

                        if ($bInsertNewLine) {
                            echo '<br /><br />';
                        }

                        if (isset($message['text']) && $message['text']) {
                            $text = nl2br($message['text']);
                            echo ${text};
                        } else if (isset($message['caption']) && $message['caption']) { 
                            $caption = nl2br($message['caption']);
                            echo ${caption};
                        }

                        echo '</td>';
                        echo '</tr>';
                    }
                }
            }
        }

        echo "</tbody>";
        echo "</table>";
    }
?>
    </body>
</html>
