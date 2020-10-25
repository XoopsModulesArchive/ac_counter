<?php

function b_ac_counter()
{
    require_once XOOPS_ROOT_PATH . '/modules/ac_counter/include/open_counter.php';

    if (!defined('_JPGRAPH')) {
        require XOOPS_ROOT_PATH . '/modules/ac_counter/include/countef_conf.php';
    }

    define('DAY_STR', '');

    $block = [];

    $bingo = '';

    $OPEN_C = new Open_Counter();

    $OPEN_C->Counter();

    $block['max_counter'] = $OPEN_C->max_counter_text();

    $a = 1000 - $block['max_counter'] % 1000;

    if (($a > 0 && $a < 10) && $block['max_counter'] > 900) {
        $bingo = "<br>Count Down<br>'" . ($block['max_counter'] + $a) . "' after $a";
    }

    if (($block['max_counter'] % 1000 == 0 && $block['max_counter'] > 990) ||
        (preg_match('^[1]+$|^[2]+$|^[3]+$|^[4]+$|^[5]+$|^[6]+$|^[7]+$|^[8]+$|^[9]+$', $block['max_counter']) &&
            $block['max_counter'] > 100)) {
        $bingo = '<br><img src="' . XOOPS_URL . '/modules/ac_counter/images/hiras_r.gif" width="43" height="50" alt="Congratulation"><strong>' . $block['max_counter'] . '</strong><img src="' . XOOPS_URL . '/modules/ac_counter/images/hiras_l.gif" width="43" height="50" alt="Congratulation"><br><strong>Congratulation</strong>';
    }

    $counter = $OPEN_C->counter_txt();

    $block['yesterday_counter'] = $counter[0];

    $block['today_counter'] = $counter[1] . $bingo;

    $block['all_view'] = $counter[2];

    $block['im'] = '<iframe frameborder="0" allowTransparency="true" height="120" width="150" marginheight="0" scrolling="NO" src="' . XOOPS_URL . '/modules/ac_counter/?mode=im_b&amp;hoge="" marginwidth="0" name="graph"></iframe>';

    $block['jpgraph'] = _JPGRAPH;

    return $block;
}

function b_ac_mini_counter()
{
    require_once XOOPS_ROOT_PATH . '/modules/ac_counter/include/open_counter.php';

    if (!defined('_JPGRAPH')) {
        require XOOPS_ROOT_PATH . '/modules/ac_counter/include/countef_conf.php';
    }

    define('DAY_STR', '');

    $block = [];

    $OPEN_C = new Open_Counter();

    $OPEN_C->Counter();

    $block['max_counter'] = $OPEN_C->max_counter_text();

    $counter = $OPEN_C->counter_txt();

    $block['page_view'] = $counter[3];

    $block['all_view'] = $counter[2];

    return $block;
}
