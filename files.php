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
    if (isset($_POST['filetype'])) {
        $filetype = strip_tags(trim($_POST['filetype']));
    }

    if (isset($_POST['caption'])) {
        $caption = strip_tags(trim($_POST['caption']));
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
                var element = document.getElementById("captionInput");

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
    if (empty($filetype) && empty($_FILES["files"])) {
?>
        <div class="checkbox"><label><input type="checkbox" onclick="javascript:handleRtlCheckboxClick(this);" />Right to Left</label></div>
        <form action="files.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileTypeInput">File Type</label>
                <select name="filetype" id="fileTypeInput" class="form-control">
                    <option value="audio">Audio</option>
                    <option value="document" selected="selected">Document</option>
                    <option value="photo">Photo</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fileInput">File(s)</label>
                <input type="file" multiple name="files[]" id="fileInput" />
            </div>
            <div class="form-group">
                <label for="captionInput">Caption</label>
                <textarea name="caption" id="captionInput" class="form-control" rows="3"></textarea>
            </div>
            <input type="submit" value="Send to <?php echo htmlspecialchars($chatName); ?>" class="btn btn-default" />
        </form>
<?php
    } else {
        $bValidFileType = false;
        $bAnyFiles = false;

        if ($filetype == "audio"
                || $filetype == "document"
                || $filetype == "photo"
                || $filetype == "video") {
                $bValidFileType = true;
                $method = "send".ucfirst($filetype);
        } else {
            echo "Error: invalid file type!";
        }

        if (isset($_FILES['files']) && $_FILES['files']['name'][0] != null) {
            $bAnyFiles = true;
        } 

        if (!$bAnyFiles) {
            echo "Error: no file has been uploaded!";
        }

        if ($bValidFileType && $bAnyFiles) {
            $fileIndex = 0;

            foreach($_FILES['files']['name'] as $fileName) {
                $tmpName = $_FILES['files']['tmp_name'][$fileIndex];

                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => $api."/".$method."?chat_id=".$chatId."&caption=".urlencode($caption),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: multipart/form-data'
                    ],
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => [
                        $filetype => curl_file_create($tmpName, mime_content_type($tmpName), $fileName)
                    ]
                ]);

                $result = curl_exec($curl);
                curl_close($curl);

                $json = json_decode($result, true);
                if (isset($json['ok']) && $json['ok']) {
                    echo "'".$fileName."' has been sent to telegram successfully!<br /><br />";
                } else {
                    echo "Error: failed to upload your file(s)!";
                }

                ++$fileIndex;
            }
        }
?>
        <div class="text-center">
            <a href="files.php">Return</a>
        </div>
<?php
    }
?>
    </body>
</html>
