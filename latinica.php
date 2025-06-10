<?php

function replaceAndHighlight(string $text): string {
    // Мапа: кириличні → латинські аналоги
    $map = [
        'о' => 'o', 'О' => 'O',
        'а' => 'a', 'А' => 'A',
        'і' => 'i', 'І' => 'I',
        'р' => 'p', 'Р' => 'P',
    ];


    if (function_exists('mb_str_split')) {
        $chars = mb_str_split($text);
    } else {
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    $result = '';
    foreach ($chars as $ch) {
        if (isset($map[$ch])) {

            $lat = $map[$ch];
            $result .= '<span class="highlight">' . htmlspecialchars($lat, ENT_QUOTES, 'UTF-8') . '</span>';
        } else {

            $result .= htmlspecialchars($ch, ENT_QUOTES, 'UTF-8');
        }
    }

    return $result;
}

$input  = $_POST['text'] ?? '';
$output = $input !== '' ? replaceAndHighlight($input) : '';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">

    <title>Anti-AI</title>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
    >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<body>
<form method="post">
    <button type="button" id="fileButton" class="btn btn-secondary mb-3">
        <i class="bi bi-file-earmark-text"></i>
    </button>
    <input type="file" id="fileInput" accept=".txt" style="display:none;">
    <textarea class="text_area_input" id="text" name="text" placeholder="Введіть текст"><?= htmlspecialchars($input, ENT_QUOTES, 'UTF-8') ?></textarea><br><br>
    <button type="submit" class="btn btn-success">Переписати</button>
</form>

<?php if ($output !== ''): ?>
    <h2 class="color_result">Результат:</h2>
    <div class="result"><?= $output ?></div>
<?php endif; ?>
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YYYYY"
        crossorigin="anonymous">
</script>
<script src="js/home.js"></script>
<script src="https://unpkg.com/mammoth/mammoth.browser.min.js"></script>

<script src="js/fileUpload.js"></script>
<link rel="stylesheet" href="css/home.css">
</body>
</html>
