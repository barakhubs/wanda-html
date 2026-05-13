<?php

/**
 * Shared Quill rich-text editor bootstrap.
 *
 * Include AFTER admin/partials/head.php so the CSS link is injected into <head>.
 * The script block at the bottom wires the editor up to the hidden textarea
 * with id="body-hidden" on the page's <form>.
 *
 * Usage (in any admin controller):
 *   $adminExtraHead    = QUILL_HEAD_CSS;
 *   $adminExtraScripts = QUILL_INIT_JS;
 */

// Quill CSS injected into <head> via $adminExtraHead
define(
    'QUILL_HEAD_CSS',
    '<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.7/quill.snow.css">'
        . '<style>.ql-editor{min-height:260px}</style>'
);

// Quill JS injected before </body> via $adminExtraScripts
define('QUILL_INIT_JS', <<<'HTML'
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
  var quill = new Quill('#editor', { theme: 'snow', modules: { toolbar: [
    ['bold','italic','underline','strike'],
    [{ list:'ordered' },{ list:'bullet' }],
    ['link','blockquote','code-block'],
    [{ header:[1,2,3,false] }],
    ['clean']
  ]}});
  document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('body-hidden').value = quill.root.innerHTML;
  });
</script>
HTML);
