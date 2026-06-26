(function() {
    'use strict';

    function loadB24Form() {
        const container = document.getElementById('b24-form-container');
        if (!container || container.dataset.loaded === 'true') return;

        const script = document.createElement('script');
        script.dataset.b24Form = 'inline/9/af3zv3';
        script.dataset.skipMoving = 'true';
        script.textContent = `(function(w,d,u){
            var s=d.createElement('script');
            s.async=true;
            s.src=u+'?'+(Date.now()/180000|0);
            var h=d.getElementsByTagName('script')[0];
            h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn-ru.bitrix24.ru/b34586700/crm/form/loader_9.js');`;

        container.appendChild(script);
        container.dataset.loaded = 'true';
        console.log('Форма Битрикс24 загружена');
    }

    function initB24Modal() {
        const modal = document.querySelector('[data-graph-target="modal-b24"]');
        if (!modal) return;

        // Отслеживаем открытие
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
-                    if (modal.classList.contains('is-open')) {
+                    if (modal.classList.contains('graph-modal-open')) {
                        loadB24Form();
                    }
                }
            });
        });
        observer.observe(modal, { attributes: true });

        // Закрываем после отправки
        document.addEventListener('b24:form:send', function() {
-            modal.classList.remove('is-open');
+            modal.classList.remove('graph-modal-open');
            document.body.style.overflow = '';
        });
    }

    document.addEventListener('DOMContentLoaded', initB24Modal);
})();