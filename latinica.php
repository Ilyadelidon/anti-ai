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
    <title>Кирилиця → Латиниця з підсвічуванням</title>
    <style>
        body { font-family: sans-serif; margin: 2em; }
        textarea { width: 100%; height: 150px; font-size: 1rem; }
        .result {
            background: #f7f7f7;
            padding: 1em;
            margin-top: 1em;
            white-space: pre-wrap;
            word-wrap: break-word;
            border: 1px solid #ccc;
        }
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }
        button {
            padding: 0.5em 1em;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<form method="post">

    <textarea id="text" name="text" placeholder="Тут ваш текст…"><?= htmlspecialchars($input, ENT_QUOTES, 'UTF-8') ?></textarea><br><br>
    <button type="submit">go</button>
</form>

<?php if ($output !== ''): ?>
    <h2>Результат:</h2>
    <div class="result"><?= $output ?></div>
<?php endif; ?>
</body>
</html>
