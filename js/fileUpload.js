/**
 * JS файл: js/fileUpload.js
 * Скрипт дозволяє користувачу завантажити текстовий файл (.txt) або Word (.docx)
 * і автоматично записати його в textarea з id="text".
 *
 * Залежності для .docx:
 * 1) Додайте в <head> CDN скрипт Mammoth.js:
 *    <script src="https://unpkg.com/mammoth/mammoth.browser.min.js"></script>
 * 2) Input приймає обидва формати: .txt, .docx
 *
 */

document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const fileButton = document.getElementById('fileButton');
    const textArea = document.getElementById('text');
    if (!fileInput || !fileButton || !textArea) return;

    // Приховуємо стандартний input[type="file"]
    fileInput.style.display = 'none';
    fileInput.accept = '.txt,.docx';

    // Клік по кнопці відкриває вікно вибору файлу
    fileButton.addEventListener('click', function() {
        fileInput.click();
    });

    // Обробляємо вибір файлу
    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Визначаємо розширення
        const ext = file.name.split('.').pop().toLowerCase();

        if (ext === 'txt') {
            // Зчитуємо як текст
            const reader = new FileReader();
            reader.onload = function(e) {
                textArea.value = e.target.result;
            };
            reader.onerror = function() {
                alert('Помилка при читанні .txt файлу.');
            };
            reader.readAsText(file);

        } else if (ext === 'docx') {
            // Зчитуємо як ArrayBuffer та парсимо через Mammoth
            const reader = new FileReader();
            reader.onload = function(e) {
                mammoth.extractRawText({arrayBuffer: e.target.result})
                    .then(function(result) {
                        textArea.value = result.value;
                    })
                    .catch(function(err) {
                        alert('Помилка обробки .docx: ' + err.message);
                    });
            };
            reader.onerror = function() {
                alert('Помилка при читанні .docx файлу.');
            };
            reader.readAsArrayBuffer(file);

        } else {
            alert('Непідтримуваний формат. Оберіть .txt або .docx файл.');
        }
    });
});
