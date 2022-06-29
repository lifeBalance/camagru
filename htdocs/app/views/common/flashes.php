<?php
if(Flash::beFlashes())
    $flashes = Flash::getFlashes();
    if (isset($flashes))
    foreach ($flashes as $msg => $class) {
        $header = ucwords($class);
        echo <<<EOT
        <article class="message is-$class">
            <div class="message-header">
                <p>$header</p>
                <button class="delete" aria-label="delete"></button>
            </div>
            <div class="message-body">$msg.</div>
        </article>
        EOT;
    }
?>