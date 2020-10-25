<?php

require dirname(__DIR__, 3) . '/include/cp_header.php';
if (file_exists('../language/' . $xoopsConfig['language'] . '/main.php')) {
    include '../language/' . $xoopsConfig['language'] . '/main.php';
} else {
    include '../language/english/main.php';
}

//XOOPS_URL."/modules/ac_counter/images/

if (!defined('_JPGRAPH')) {
    require XOOPS_ROOT_PATH . '/modules/ac_counter/include/countef_conf.php';
}
require_once XOOPS_ROOT_PATH . '/modules/ac_counter/include/analyze.php';
setlocale(LC_TIME, _LC_TIME);

$mode = '';
$nengatsu = '';

if (isset($_GET['mode'])) {
    $mode = urldecode(trim($_GET['mode']));
}
if (isset($_GET['hoge'])) {
    $nengatsu = urldecode(trim($_GET['hoge']));
}

if ($_POST) {
    foreach ($_POST as $key => $val) {
        switch ($key) {
        case 'cal_select':
            $nengatsu = implode('-', trim(strip_tags($_POST['select'])));
            $mode = urldecode($_GET['mode']);
            break;
        default:
            $mode = null;
        }
    }
}

if (!defined('DAY_STR')) {
    define('DAY_STR', $nengatsu);
}
define('MODE', $mode);

if (DAY_STR == '') {
    [$y, $m, $d] = explode('-', date('Y-n-j', time()));

    if (preg_match('week', MODE)) {
        $sql = (XOOPS_DB_TYPE == 'mysql') ? "SELECT DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 7 DAY), '%Y-%m-%d')" : "SELECT to_char(CURRENT_TIMESTAMP - '7 days'::INTERVAL, 'YYYY-MM-DD')";

        $back = ac_get_one($sql);
    } else {
        if (preg_match('month', MODE)) {
            $sql = (XOOPS_DB_TYPE == 'mysql') ? "SELECT DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m-%d')" : "SELECT to_char(CURRENT_TIMESTAMP - '1 month'::INTERVAL, 'YYYY-MM-DD')";

            $back = ac_get_one($sql);
        } else {
            $sql = (XOOPS_DB_TYPE == 'mysql') ? "SELECT DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 DAY), '%Y-%m-%d')" : "SELECT to_char(CURRENT_TIMESTAMP - '1 days'::INTERVAL, 'YYYY-MM-DD')";

            $back = ac_get_one($sql);
        }
    }

    $next = '';
} else {
    [$y, $m, $d] = explode('-', DAY_STR);

    $days = mktime(0, 0, 0, $m, $d, $y);

    if (preg_match('week', MODE)) {
        $sql = (XOOPS_DB_TYPE == 'mysql') ? "SELECT DATE_ADD('" . DAY_STR . "', INTERVAL 7 DAY)" : "SELECT to_char('" . DAY_STR . "'::timestamp + '7 days'::INTERVAL, 'YYYY-MM-DD')";

        $next = ac_get_one($sql);

        $sql = (XOOPS_DB_TYPE == 'mysql') ? "SELECT DATE_SUB('" . DAY_STR . "', INTERVAL 7 DAY)" : "SELECT to_char('" . DAY_STR . "'::timestamp - '7 days'::INTERVAL, 'YYYY-MM-DD')";

        $back = ac_get_one($sql);
    } else {
        if (preg_match('month', MODE)) {
            $sql = (XOOPS_DB_TYPE == 'mysql') ? "SELECT DATE_ADD('" . DAY_STR . "', INTERVAL 1 MONTH)" : "SELECT to_char('" . DAY_STR . "'::timestamp + '1 month'::INTERVAL, 'YYYY-MM-DD')";

            $next = ac_get_one($sql);

            $sql = (XOOPS_DB_TYPE == 'mysql') ? "SELECT DATE_SUB('" . DAY_STR . "', INTERVAL 1 MONTH)" : "SELECT to_char('" . DAY_STR . "'::timestamp - '1 month'::INTERVAL, 'YYYY-MM-DD')";

            $back = ac_get_one($sql);
        } else {
            $sql = (XOOPS_DB_TYPE == 'mysql') ? "SELECT DATE_ADD('" . DAY_STR . "', INTERVAL 1 DAY)" : "SELECT to_char('" . DAY_STR . "'::timestamp + '1 days'::INTERVAL, 'YYYY-MM-DD')";

            $next = ac_get_one($sql);

            $sql = (XOOPS_DB_TYPE == 'mysql') ? "SELECT DATE_SUB('" . DAY_STR . "', INTERVAL 1 DAY)" : "SELECT to_char('" . DAY_STR . "'::timestamp - '1 days'::INTERVAL, 'YYYY-MM-DD')";

            $back = ac_get_one($sql);
        }
    }
}

$opt = ac_make_day_options($y, $m, $d, $y, 'select[month');
$view['monthselect'] = '
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?mode=' . MODE . '">
	<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=' . MODE . '&amp;hoge=' . $back . '">
	<img src="' . XOOPS_URL . '/modules/ac_counter/images/left.gif" width="100" height="16"  border="0" alt=" "></a>&nbsp;' . $opt .
    '&nbsp;<input type="submit" name="cal_select" value="年月日選択">&nbsp;
	<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=' . MODE . '&amp;hoge=' . $next . '">
	<img src="' . XOOPS_URL . '/modules/ac_counter/images/right.gif" width="100" height="16" border="0" alt=" "></a></form>';

switch (true) {
case (MODE == 'str_payment'):
    $ANALYZE = new ACCESS_ANALYZE('str_payment');
    $view['im'] = $ANALYZE->GET_HOST_VIEW();
    break;
case (MODE == 'special_graph'):
    unset($view['monthselect']);
    $view['im'] = '<iframe frameborder="0" height="600" width="500" marginheight="0" scrolling="NO" src="' . $_SERVER['SCRIPT_NAME'] . '?mode=all_graph&amp;hoge=' . DAY_STR . '" marginwidth="0" name="graph"></iframe>';
    $view['jpgraph'] = '折れ線グラフはアクセス数の伸び．バーグラフは日別アクセス数を表す．<br>左Y目盛り【日別アクセス数】右Y目盛り【トータルアクセス数】<br>' . _JPGRAPH . '::' . _UNADON;
    break;
case (MODE == 'all_graph'):
    $ANALYZE = new ACCESS_ANALYZE('special_graph');
    $im = $ANALYZE->is_all_graph('ALL GRAPH');
    exit;
case (MODE == 'view_pageid'):
    $ANALYZE = new ACCESS_ANALYZE('pageid');
    $im = $ANALYZE->is_pages();
    exit;
case (MODE == 'pageid'):
    $view['im'] = '<iframe frameborder="0" height="600" width="500" marginheight="0" scrolling="NO" src="' . $_SERVER['SCRIPT_NAME'] . '?mode=view_pageid" marginwidth="0" name="graph"></iframe>';
    $view['jpgraph'] = _JPGRAPH . '::' . _UNADON;
    break;
case (MODE == 'week_trend'):
    $view['im'] = '<iframe frameborder="0" height="600" width="500" marginheight="0" scrolling="NO" src="' . $_SERVER['SCRIPT_NAME'] . '?mode=view_trend&amp;hoge=' . DAY_STR . '" marginwidth="0" name="graph"></iframe>';
    $view['jpgraph'] = _JPGRAPH . '::' . _UNADON;
    break;
case (MODE == 'view_trend'):
    $ANALYZE = new ACCESS_ANALYZE('week_trend');
    $im = $ANALYZE->is_trend('Weekly Trend');
    exit;
case (MODE && !preg_match('im', MODE) && !preg_match('access', MODE) && !preg_match('trend', MODE) && !preg_match('archive', MODE)):
    $ANALYZE = new ACCESS_ANALYZE(MODE);
    $view['im'] = $ANALYZE->GET_HOST_VIEW();
    break;
case (MODE == 'week_access' || MODE == 'month_access'):
    (MODE == 'week_access') ? $type = 'im_week' : $type = 'im_month';
    $view['im'] = '<iframe frameborder="0" height="600" width="500" marginheight="0" scrolling="NO" src="' . $_SERVER['SCRIPT_NAME'] . '?mode=' . $type . '&amp;hoge=' . DAY_STR . '" marginwidth="0" name="graph"></iframe>';
    $view['jpgraph'] = _JPGRAPH . '::' . _UNADON;
    break;
case (preg_match('im', MODE)):
    if (MODE == 'im') {
        $ANALYZE = new ACCESS_ANALYZE('day');

        $str = 'access of a day';
    } else {
        if (MODE == 'im_week') {
            $ANALYZE = new ACCESS_ANALYZE('week_access');

            $str = 'access of a week';
        } else {
            if (MODE == 'im_month') {
                $ANALYZE = new ACCESS_ANALYZE('month_access');

                $str = 'access of a month';
            }
        }
    }
    $im = $ANALYZE->is_data($str);
    exit;
case (MODE == 'archive'):
    $ANALYZE = new ACCESS_ANALYZE(MODE);
    $view['im'] = '<h4>月別アーカイブ</h4>' . $ANALYZE->archive_list();
    unset($view['monthselect']);
    break;
case (MODE == 'archive_view'):
    $ANALYZE = new ACCESS_ANALYZE(MODE);
    unset($view['monthselect']);
    $filename = ac_decode_base64_load($n);
    $arr_file = sscanf($filename, '%d-%d-%d-%d.pl', $y, $m, $d, $u);
    $view['im'] = '<h4>' . sprintf('%s年%s月', $y, $m) . '月別アーカイブ</h4>' . $ANALYZE->archive_Thawing($filename, $log);
    break;
case (MODE == 'vacuum'):
    /*
    $result = pg_query($xoopsDB->conn, "vacuum --analyze --table ".$xoopsDB->prefix("ac_counter")." ".XOOPS_DB_NAME);
    if (! $xoopsDB->error($result)) {
        redirect_header('index.php', 3, '最適化しました。');
        exit;
    } else {
        redirect_header('index.php', 3, '最適化に失敗しました。');
        exit;
    }
    */
    break;
default:
    $view['im'] = '<iframe frameborder="0" height="600" width="500" marginheight="0" scrolling="NO" src="' . $_SERVER['SCRIPT_NAME'] . '?mode=im&amp;hoge=' . DAY_STR . '" marginwidth="0" name="graph"></iframe>';
    $view['jpgraph'] = _JPGRAPH . '::' . _UNADON;
    break;
}

xoops_cp_header();

require XOOPS_ROOT_PATH . '/modules/ac_counter/include/analyzer.html_tpl';

xoops_cp_footer();
