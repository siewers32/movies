<?php

return function($action, $method="post", $data=[]) {
    return <<< EOT
    <form action="{$action}" method="{$method}">
    <div class="stars_reverse">
        <label class="ratelabel" for="star5">5</label>
        <input class="rate" type="radio" id="star5" name="rate" value="5">
        <label class="ratelabel" for="star4">4</label>
        <input class="rate" type="radio" id="star4" name="rate" value="4">
        <label class="ratelabel" for="star3">3</label>
        <input class="rate" type="radio" id="star3" name="rate" value="3">
        <label class="ratelabel" for="star2">2</label>
        <input class="rate" type="radio" id="star2" name="rate" value="2">
        <label class="ratelabel" for="star1">1</label>
        <input class="rate" type="radio" id="star1" name="rate" value="1">
    </div>
    </form>
    EOT;
};