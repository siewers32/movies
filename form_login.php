<?php
return function($action, $method="post", $data=[]) {
    return <<< EOT
<form action="{$action}" method="{$method}">
    <label for="email">Email</label>
    <input type="email" name="email" id="email">
    <lable for="password">Password</lable>
    <input type="password" name="password" id="password">
    <input type="submit">
</form>
EOT;
}
?>
