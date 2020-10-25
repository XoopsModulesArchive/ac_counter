<?php

include './header.php';
require XOOPS_ROOT_PATH . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'counter_index.html';

if (!defined('_JPGRAPH')) {
    require XOOPS_ROOT_PATH . '/modules/ac_counter/include/countef_conf.php';
}
require_once XOOPS_ROOT_PATH . '/modules/ac_counter/include/analyze.php';

$mode = '';
$nengatsu = '';
$view = '';
if (isset($_GET['mode'])) {
    $mode = urldecode(strip_tags($_GET['mode']));
}
if (isset($_GET['hoge'])) {
    $nengatsu = urldecode(trim($_GET['hoge']));
}
if (isset($_GET['view'])) {
    $view = urldecode(trim($_GET['view']));
}

if (!defined('DAY_STR')) {
    define('DAY_STR', '');
}
$_today = '●<a href="' . $_SERVER['SCRIPT_NAME'] . '">今日のアクセス推移</a>';
$_month = '●<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=monthly">今月のアクセス推移</a>';
$_trend = '●<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=week_trend">ウイークリートレンド</a>';
$_pages = '●<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=pageid">ページ別カウント</a>';
//$_archi = '●<a href="'.$_SERVER['SCRIPT_NAME'].'?mode=cumulation">先月末までの累積</a>';
$_archi = '●先月末までの累積';
$_just = '<br>◆<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=just_counter">キリ番を獲得された方々</a>';

switch (true) {
case ($mode && 'im_b' == $mode):
    $ANALYZE = new ACCESS_ANALYZE('day');
    $im = $ANALYZE->is_block();
    exit;
case ($mode && 'im' == $mode):
    $ANALYZE = new ACCESS_ANALYZE('day');
    $im = $ANALYZE->is_data('Today');
    exit;
case ($mode && 'trend_im' == $mode):
    $ANALYZE = new ACCESS_ANALYZE('week_trend');
    $im = $ANALYZE->is_trend('Weekly Trend');
    exit;
case ($mode && 'month_im' == $mode):
    $ANALYZE = new ACCESS_ANALYZE('month_access');
    $im = $ANALYZE->is_data('Access of This Month');
    exit;
case ($mode && 'page_im' == $mode):
    $ANALYZE = new ACCESS_ANALYZE('pageid');
    $im = $ANALYZE->is_pages();
    exit;
case ($mode && 'all_graph' == $mode):
    //$ANALYZE = new ACCESS_ANALYZE("special_graph");
    //$im = $ANALYZE->is_all_graph("CUMULATION GRAPH");
    $im = '実装されていない機能です。しばらくお待ちください。';
    exit;
case ($mode && 'pageid' == $mode):
    $xoopsTpl->assign('ac_title', '＞＞ページ別カウント－この半年');
    $xoopsTpl->assign('menu', '<br>' . $_today . '&nbsp;' . $_month . '&nbsp;' . $_trend . '&nbsp;' . $_archi . $_just);
    $xoopsTpl->assign('status', '<div style="text-align:canter;"><iframe frameborder="0" allowTransparency="true" height="600" width="500" marginheight="0" scrolling="NO" src="' . $_SERVER['SCRIPT_NAME'] . '?mode=page_im" marginwidth="0" name="graph"></iframe></div>');
    break;
case ($mode && 'monthly' == $mode):
    $xoopsTpl->assign('ac_title', '＞＞今月のアクセス推移');
    $xoopsTpl->assign('menu', '<br>' . $_today . '&nbsp;' . $_trend . '&nbsp;' . $_pages . '&nbsp;' . $_archi . $_just);
    $xoopsTpl->assign('status', '<div style="text-align:canter;"><iframe frameborder="0" allowTransparency="true" height="600" width="500" marginheight="0" scrolling="NO" src="' . $_SERVER['SCRIPT_NAME'] . '?mode=month_im" marginwidth="0" name="graph"></iframe></div>');
    break;
case ($mode && 'week_trend' == $mode):
    $xoopsTpl->assign('ac_title', '＞＞ウイークリートレンド');
    $xoopsTpl->assign('menu', '<br>' . $_today . '&nbsp;' . $_month . '&nbsp;' . $_pages . '&nbsp;' . $_archi . $_just);
    $xoopsTpl->assign('status', '<div style="text-align:canter;"><iframe frameborder="0" allowTransparency="true" height="600" width="500" marginheight="0" scrolling="NO" src="' . $_SERVER['SCRIPT_NAME'] . '?mode=trend_im" marginwidth="0" name="graph"></iframe></div>');
    $view['jpgraph'] = _JPGRAPH;
    break;
case ($mode && 'cumulation' == $mode):
    $xoopsTpl->assign('ac_title', '＞＞先月末までの累積');
    $xoopsTpl->assign('menu', '<br>' . $_today . '&nbsp;' . $_month . '&nbsp;' . $_pages . '&nbsp;' . $_trend . $_just);
    $xoopsTpl->assign('status', '<div style="text-align:canter;"><iframe frameborder="0" allowTransparency="true" height="600" width="500" marginheight="0" scrolling="NO" src="' . $_SERVER['SCRIPT_NAME'] . '?mode=all_graph" marginwidth="0" name="graph"></iframe></div>');
    $view['jpgraph'] = _JPGRAPH;
    break;
case ($mode && 'just_counter' == $mode):
    $ANALYZE = new ACCESS_ANALYZE($mode);
    $row = $ANALYZE->is_just();
    $lhira = '<img src="' . XOOPS_URL . '/modules/ac_counter/images/hiras_r.gif" width="43" height="50" alt="Congratulation" style="margin:5px;vertical-align:middle;">';
    $rhira = '<img src="' . XOOPS_URL . '/modules/ac_counter/images/hiras_l.gif" width="43" height="50" alt="Congratulation" style="margin:5px;vertical-align:middle;">';
    $tb = '
<table cellspacing="0" border="0" class="outer" width="60%">
    <tbody>
        <tr>
            <th class="head" style="text-align:center;padding:10px;font-size:15px;" colspan="3">' . $lhira . ' Congratulation ' . $rhira . '</th>';
    $style = 'odd';
    if ($row) {
        foreach ($row as $num => $name) {
            $tb .= '
			<tr class="' . $style . '">
				<td width="30%" style="text-align:center;">' . $num . '</td>
				<td width="40%" style="text-align:center;"><strong>' . $name['uname'] . '</strong></td>
				<td width="30%" style="text-align:center;">' . $name['f_day'] . '</td>
			</tr>
			';

            $style = ('odd' == $style) ? 'even' : 'odd';
        }
    }
    $tb .= '
	</tbody>
</table>';
    $xoopsTpl->assign('ac_title', '＞＞キリ番を獲得された方々');
    $xoopsTpl->assign('menu', '<br>' . $_today . '&nbsp;' . $_month . '&nbsp;' . $_pages . '&nbsp;' . $_trend . '&nbsp;' . $_archi);
    $xoopsTpl->assign('status', $tb);
    $view['jpgraph'] = _JPGRAPH;
    break;
default:
    $xoopsTpl->assign('ac_title', '＞＞今日のアクセス推移');
    $xoopsTpl->assign('menu', '<br>' . $_month . '&nbsp;' . $_trend . '&nbsp;' . $_pages . '&nbsp;' . $_archi . $_just);
    $xoopsTpl->assign('status', '<div style="text-align:canter;"><iframe frameborder="0" allowTransparency="true" height="600" width="500" marginheight="0" scrolling="NO" src="' . $_SERVER['SCRIPT_NAME'] . '?mode=im" marginwidth="0" name="graph"></iframe></div>');
    break;
}

$xoopsTpl->assign('jpgraph', _JPGRAPH . '::' . _UNADON);

require XOOPS_ROOT_PATH . '/footer.php';
