<?php
/* アクセスログ表示スクリプト		*/
/* @unadonv							*/
/* u-u-club.ddo.jp					*/
/* Ver 0.90 2005/7/28				*/

//mode=special_graph
class ACCESS_ANALYZE
{
    public $xoopsDB;
    public $day_total    = '';
    public $day_unique   = '';
    public $day_access   = '';
    public $day_count    = '';
    public $day_host     = '';
    public $day_referer  = '';
    public $day_agent    = '';
    public $day_log      = '';
    public $week         = '';
    public $u_week       = '';
    public $t_week       = '';
    public $week_host    = '';
    public $week_referer = '';
    public $week_agent   = '';
    //var $week_log			= '';
    public $page_log     = '';
    public $month_unique = '';
    public $month_total  = '';
    public $month        = '';
    public $delete_da    = '';
    public $just_sql     = '';
    public $archive_file = '';
    public $monthfile    = true;
    public $montharchive = true;
    public $just         = [];
    public $datay        = [];
    public $datay2       = [];
    public $count        = [];
    public $column       = [];
    public $low          = 0;
    public $G_PV         = [];
    public $G_UQ         = [];
    public $g_start      = [];
    public $im           = '';
    public $log_list     = [
        'month_host'    => _U_AC_HOST,
        'month_referer' => _U_AC_REF,
        'month_os'      => 'OS',
        'month_browser' => _U_AC_BROWSER,
        'month_engine'  => _U_AC_ENGINE,
        'month_access'  => _U_AC_GRAPHE,
    ];
    public $time_zone    = [
        '00:00:00' => 0,
        '01:00:00' => 0,
        '02:00:00' => 0,
        '03:00:00' => 0,
        '04:00:00' => 0,
        '05:00:00' => 0,
        '06:00:00' => 0,
        '07:00:00' => 0,
        '08:00:00' => 0,
        '09:00:00' => 0,
        '10:00:00' => 0,
        '11:00:00' => 0,
        '12:00:00' => 0,
        '13:00:00' => 0,
        '14:00:00' => 0,
        '15:00:00' => 0,
        '16:00:00' => 0,
        '17:00:00' => 0,
        '18:00:00' => 0,
        '19:00:00' => 0,
        '20:00:00' => 0,
        '21:00:00' => 0,
        '22:00:00' => 0,
        '23:00:00' => 0,
    ];
    public $OS_LIST      = [
        '67'                => 'Win67',
        'XP'                => 'Windows XP',
        '9x 4.90'           => 'Windows Me',
        '95'                => 'Windows 95',
        'NT 5.0'            => 'Windows 2000',
        'NT 5.1'            => 'Windows XP',
        'NT'                => 'Windows NT',
        '2000'              => 'Windows 2000',
        '98'                => 'Windows 98',
        'CE'                => 'Windows CE',
        '32'                => 'Win32',
        'Mac OS X'          => 'Mac OS X',
        'Mac_PowerPC'       => 'Macintosh',
        'Macintosh'         => 'Macintosh',
        'FreeBSD'           => 'FreeBSD',
        'Linux'             => 'Linux',
        'IRIX'              => 'IRIX',
        'SunOS'             => 'SunOS',
        'OS/2'              => 'OS/2',
        'DreamPassport'     => 'DreamCast',
        'DoCoMo'            => 'DoCoMo',
        'J-PHONE'           => 'J-PHONE',
        'UP.Browser'        => 'EzWeb',
        'ASTEL'             => 'ASTEL',
        'sharp pda browser' => 'Zaurus',
    ];
    public $BROWSER_LIST = [
        'MSIE 3.'           => 'InternetExplorer 3.x',
        'MSIE 4.'           => 'InternetExplorer 4.x',
        'MSIE 5.'           => 'InternetExplorer 5.x',
        'MSIE 6.'           => 'InternetExplorer 6.x',
        'Mozilla/3'         => 'Netscape 3.x',
        'Mozilla/4'         => 'Netscape 4.x',
        'Mozilla/5'         => 'Netscape 6',
        'Googlebot'         => 'Googlebot',
        'DoCoMo'            => 'DoCoMo',
        'J-PHONE'           => 'J-PHONE',
        'ASTEL'             => 'ASTEL',
        'UP.Browser'        => 'EZweb',
        'Safari'            => 'Safari',
        'Sleipnir'          => 'Sleipnir',
        'TuringOS'          => 'Anonymizer',
        'Konqueror'         => 'Konqueror',
        'Netscape6'         => 'Netscape 6',
        'Netscape/7'        => 'Netscape 7',
        'Gecko'             => 'Mozilla',
        'Asahina-Antenna'   => _U_AC_BROWSER_LIST0,
        'DreamPassport'     => 'DreamPassport',
        'iCab'              => 'iCab',
        'Internet Ninja'    => 'Internet Ninja',
        'PDXGW'             => 'H"',
        'L-mode'            => 'L-mode',
        'Lynx'              => 'Lynx',
        'NetCaptor'         => 'NetCaptor',
        'OmniWeb'           => 'OmniWeb',
        'w3m'               => 'w3m',
        'Opera'             => 'Opera',
        'PerMan'            => _U_AC_BROWSER_LIST1,
        'Pockey'            => 'GetHTMLW',
        'SpaceBison'        => 'Proxomitron',
        'HotJava'           => 'HotJava',
        'AVE-Front'         => 'Tron',
        'NF'                => 'Tron',
        'sharp pda browser' => 'Zaurus',
        'WWWC'              => 'WWWC',
        'WWWD'              => 'WWWD',
        'Wget'              => 'Wget',
        'fetch'             => 'Fetch',
        'MSProxy'           => 'MSProxyServer',
        'InfoSeek'          => 'InfoSeek',
        'Lycos_Spider'      => 'Lycos',
        'ZyBorg'            => 'Lycos',
        'Slurp'             => 'goo',
        'mognet'            => 'goo',
        'ArchitextSpider'   => 'Excite',
        'Scooter'           => 'Altavista',
        'Mercator'          => 'Altavista',
        'AV Fetch'          => 'Altavista',
        'wwwget'            => 'Crawler',
        'Crawler'           => 'Crawler',
        'Gulliver'          => 'Northern Light',
        'Aruyo'             => 'AAA! Cafe',
        'aruyo'             => 'AAA! Cafe',
        'Iron33'            => 'Verno',
        'indexpert'         => 'Fresheye',
        'libwww-perl'       => 'libwww-perl',
        'Microsoft URL'     => 'Microsoft URL',
        'FAST-Real'         => 'FAST-RealWebCrawler',
        'Bookmark'          => 'Bookmark Renewal Check Agent',

    ];
    public $ENG_LIST     = [
        'www.google.com'           => 'Google',
        'google.co.jp'             => 'Google',
        'goo.ne.jp'                => 'goo',
        'search.yahoo.co.jp'       => 'Yahoo!',
        'websearch.yahoo.co.jp'    => 'Yahoo!',
        'google.yahoo.co.jp'       => 'Yahoo!/Google',
        'www.infoseek.co.jp'       => 'InfoSeek',
        'infoseek.com'             => 'InfoSeek',
        'www.lycos.co.jp'          => 'Lycos',
        'www.excite.co.jp'         => 'search,	Excite',
        'search.msn.co.jp'         => 'MSN',
        'search.fresheye.com'      => 'Fresheye',
        'cgi.search.biglobe.ne.jp' => 'Biglobe',
        'www.altavista.com'        => 'AltaVista',
        'www.alltheweb.com'        => 'FastSearch',
        'www.go.com'               => 'GO.com',
        'www.bookmark.ne.jp'       => 'bookmark',
        'serch.excite.com'         => 'Excite.com',
        'fastsearch.com'           => 'alltheweb',
    ];
    public $day_title    = '';

    public function __construct($str)
    {
        $this->xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        if (!defined('_JPGRAPH')) {
            require XOOPS_ROOT_PATH . '/modules/ac_counter/include/countef_conf.php';
        }
        setlocale(LC_TIME, _LC_TIME);
        (DAY_STR == '') ? $this->daystring = date('Y-m-d', time()) : $this->daystring = DAY_STR;

        //先月末TRUE
        $check_month = 'SELECT DATE_SUB(CURRENT_DATE(), INTERVAL EXTRACT(DAY FROM CURRENT_DATE()) DAY)';

        //時間帯ページビュー
        $this->day_total = 'SELECT count(*),EXTRACT(HOUR FROM days) as hh FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday = '%s' GROUP BY hh";

        //時間帯ユニーク
        $this->day_unique = 'SELECT EXTRACT(HOUR FROM days) as dd,counter FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday = '%s' GROUP BY EXTRACT(HOUR FROM days),counter";

        //日－_U_AC_NO_HOST
        $this->day_host = 'SELECT count(*) as cc,inday,host,remote FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday = '%s' GROUP BY remote,inday,host";

        //日－参照元
        $this->day_referer = 'SELECT count(*) as cc,inday,referer FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday = '%s' GROUP BY inday,referer";

        //日－OS/ブラウザ
        $this->day_agent = 'SELECT inday,useragent FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday = '%s'";
        //日－ログ
        $this->day_log = 'SELECT *,days as t FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday = '%s' ORDER BY days";

        //週７日
        $this->week = "SELECT DATE_FORMAT(DATE_SUB('%s', INTERVAL %d DAY), '%s')";

        //期間ユニーク
        $this->u_week = 'SELECT min(counter) as mi, max(counter) as ma, inday as dd FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday BETWEEN '%s' AND '" . $this->daystring . "' GROUP BY inday";

        //期間全部
        $this->t_week = 'SELECT count(*),inday FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday BETWEEN '%s' AND '%s' GROUP BY inday";

        //期間－_U_AC_NO_HOST
        $this->week_host = 'SELECT count(*) as cc,inday,host,remote FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday BETWEEN '%s' AND '%s' GROUP BY remote,inday,host";

        //期間－参照元
        $this->week_referer = 'SELECT count(*) as cc,inday,referer FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday BETWEEN '%s' AND '%s' GROUP BY inday,referer";

        //期間－OS/ブラウザ
        $this->week_agent = 'SELECT inday,useragent FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday BETWEEN '%s' AND '%s'";

        //ページごと累積
        $this->page_log = 'SELECT count(pageid),pageid FROM ' . $this->xoopsDB->prefix('ac_counter') . ' GROUP BY pageid';
        //SELECT count(pageid),pageid FROM ".$this->xoopsDB->prefix("ac_counter")." WHERE inday = '%s' GROUP BY pageid,inday ORDER BY pageid DESC;

        //月－日ごとユニーク
        $this->month_unique = 'SELECT min(counter) as mi, max(counter) as ma, inday as dd FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE EXTRACT(YEAR_MONTH FROM inday) = '%s' GROUP BY inday";

        //月－日ごとページビュー
        $this->month_total = 'SELECT count(*) as cc,inday FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE EXTRACT(YEAR_MONTH FROM inday) = '%s' GROUP BY inday";

        //今月月初－月末
        $this->month = 'SELECT DATE_ADD(DATE_SUB(CURRENT_DATE(), INTERVAL EXTRACT(DAY FROM CURRENT_DATE()) DAY), INTERVAL 1 DAY) AS sday,DATE_ADD(DATE_SUB(CURRENT_DATE(), INTERVAL EXTRACT(DAY FROM CURRENT_DATE()) DAY), INTERVAL 1 MONTH) AS eday';

        $this->day_title = '【&nbsp;' . $this->daystring . '&nbsp;】';

        if (preg_match('week', $str)) {
            $this->seven_days();
            $this->day_title = '【&nbsp;' . $this->datax[6] . '&nbsp;-&nbsp;' . $this->datax[0] . '&nbsp;】';
        } elseif (preg_match('month', $str)) {
            $before_month     = $this->class_ac_get_one($check_month);
            $this->filename   = $before_month . '-1.pl';
            $this->month_days = $this->GET_QUERY(sprintf($this->month, $this->daystring, $this->daystring));
            $row              = $this->xoopsDB->fetchArray($this->month_days);
            $this->month_days($row['sday'], $row['eday']);
            $this->day_title = '【&nbsp;' . $row['sday'] . '&nbsp;-&nbsp;' . $row['eday'] . '&nbsp;】';
            if ($before_month == $row['eday']) {
                if (!file_exists(XOOPS_ROOT_PATH . '/modules/ac_counter/archive/' . $this->filename)) {
                    $this->monthfile = false;
                    $this->make_month_archive($row['sday'], $row['eday']);
                }
            } else {
                $this->filename = $row['eday'] . '-1.pl';
                /*
                if(! file_exists(XOOPS_ROOT_PATH."/modules/ac_counter/archive/".$this->filename)) {
                    $this->monthfile = false;
                    $this->make_month_archive($row['sday'], $row['eday']);
                }
                */
            }
        }
        switch (true) {
            case ($str == 'day' || $str == 'str_payment'):
                $this->datax      = range(0, 23);
                $this->arr_total  = $this->Fetch_Array(sprintf($this->day_total, $this->daystring));
                $this->arr_unique = $this->Fetch_Array_MySQL(sprintf($this->day_unique, $this->daystring));
                if ($str == 'str_payment') {
                    $this->t_res = [];
                    $this->q_res = [];
                    $loop        = count($this->arr_total);
                    for ($i = 0; $i < $loop; $i++) {
                        $key               = key($this->time_zone);
                        $this->t_res[$key] = $this->arr_total[$i];
                        $this->q_res[$key] = $this->arr_unique[$i];
                        next($this->time_zone);
                    }
                    $this->Info_HTML();
                }

                if ($this->arr_total || $this->arr_unique) {
                    $this->hight = max($this->arr_total) + 5;
                } else {
                    echo $this->daystring . _U_AC_NO_LOG0;
                    exit;
                }
                break;
            case (preg_match('host', $str)):
                if (preg_match('week', $str)) {
                    $this->Host_view(sprintf($this->week_host, $this->datax[6], $this->daystring));
                } elseif (preg_match('month', $str)) {
                    $this->Host_view(sprintf($this->week_host, $row['sday'], $row['eday']));
                } else {
                    $this->Host_view(sprintf($this->day_host, $this->daystring));
                }
                break;
            case (preg_match('referer', $str)):
                if (preg_match('week', $str)) {
                    $this->Referer_view(sprintf($this->week_referer, $this->datax[6], $this->daystring));
                } elseif (preg_match('month', $str)) {
                    $this->Referer_view(sprintf($this->week_referer, $row['sday'], $row['eday']));
                } else {
                    $this->Referer_view(sprintf($this->day_referer, $this->daystring));
                }
                break;
            case (preg_match('os', $str) || preg_match('browser', $str)):
                if (preg_match('week', $str)) {
                    $str = str_replace('week', 'day', $str);//
                    $this->OS_LIST($str, sprintf($this->week_agent, $this->datax[6], $this->daystring));
                } elseif (preg_match('month', $str)) {
                    $str = str_replace('month', 'day', $str);
                    $this->OS_LIST($str, sprintf($this->week_agent, $row['sday'], $row['eday']));
                } else {
                    $this->OS_LIST($str, sprintf($this->day_agent, $this->daystring));
                }
                break;
            case ($str == 'log_view'):
                $this->log_view();
                break;

            case ($str == 'archive'):
                $this->archive_open();
                break;

            case ($str == 'archive_view'):
                $this->archive_view();
                break;

            case ($str == 'special_graph'):
                $this->g = true;
                $this->archive_open();
                break;
            case (preg_match('engine', $str)):
                if (preg_match('week', $str)) {
                    $this->Search_Engine(sprintf($this->week_referer, $this->datax[6], $this->daystring));
                } elseif (preg_match('month', $str)) {
                    $this->Search_Engine(sprintf($this->week_referer, $row['sday'], $row['eday']));
                } else {
                    $this->Search_Engine(sprintf($this->day_referer, $this->daystring));
                }
                break;
            case ($str == 'week_access'):
                $this->arr_total  = $this->Fetch_Array(sprintf($this->t_week, $this->datax[6], $this->daystring));
                $this->arr_unique = $this->Fetch_Array_MySQL_week(sprintf($this->u_week, $this->datax[6]));
                if ($this->arr_total || $this->arr_unique) {
                    $this->hight = max($this->arr_total) + 10;
                } else {
                    echo $this->datax[6] . _U_AC_NO_LOG1;
                    exit;
                }
                sort($this->datax);
                break;
            case ($str == 'month_access'):
                [$my, $mm,] = explode('-', $row['sday']);
                $this->arr_total  = $this->Fetch_Array(sprintf($this->month_total, $my . $mm));
                $this->arr_unique = $this->Fetch_Array_MySQL_week(sprintf($this->month_unique, $my . $mm));
                if ($this->arr_total || $this->arr_unique) {
                    $this->hight = max($this->arr_total) + 5;
                } else {
                    echo $row['sday'] . '-' . $row['eday'] . _U_AC_NO_LOG2;
                    exit;
                }
                break;
            case ($str == 'week_trend'):
                $total_trend  = $this->Fetch_Array(sprintf($this->t_week, $this->datax[6], $this->daystring));
                $unique_trend = $this->Fetch_Array_MySQL_week(sprintf($this->u_week, $this->datax[6]));
                if ($total_trend || $unique_trend) {
                    $this->hight = max($total_trend) + 50;
                    $this->low   = min($total_trend) - 50;
                } else {
                    echo $this->datax[6] . _U_AC_NO_LOG1;
                    exit;
                }
                sort($this->datax);
                $this->arr_total[] = $total_trend[0];
                for ($i = 1; $i < 7; $i++) {
                    $this->arr_total[] = $total_trend[$i] - $total_trend[$i - 1];
                }
                $this->arr_unique[] = $unique_trend[0];
                for ($i = 1; $i < 7; $i++) {
                    $this->arr_unique[] = $unique_trend[$i] - $unique_trend[$i - 1];
                }
                break;
            case ($str == 'pageid'):
                $result = $this->xoopsDB->query($this->page_log);
                $sql    = 'SELECT dirname FROM ' . $this->xoopsDB->prefix('modules') . ' WHERE mid = %s';
                if (!$this->xoopsDB->error($result)) {
                    $this->pages = [];
                    while (false !== ($row = $this->xoopsDB->fetchRow($result))) {
                        if ($row[1] == '/') {
                            $this->pages['/'] = $row[0];
                        } else {
                            $name                  = $this->xoopsDB->fetchRow($this->xoopsDB->query(sprintf($sql, $row[1])));
                            $this->pages[$name[0]] = $row[0];
                        }
                    }
                    $this->xoopsDB->freeRecordSet($result);
                }
                break;
            case ($str == 'just_counter'):
                $this->just_sql = "SELECT *,DATE_FORMAT(days, '%Y-%m-%d %T') as f_day FROM " . $this->xoopsDB->prefix('just_counter') . ' as t1 LEFT OUTER JOIN ' . $this->xoopsDB->prefix('users') . ' as t2 USING(uid) ORDER BY t1.counter';
                $this->just_counter();
                break;
            default:
                exit;
        }
    }

    public function make_month_archive($sday, $eday)
    {
        $this->montharchive = false;
        $this->Host_view(sprintf($this->week_host, $sday, $eday));
        $this->make_arr_clear();
        $this->Referer_view(sprintf($this->week_referer, $sday, $eday));
        $this->make_arr_clear();
        $this->OS_LIST('day_os', sprintf($this->week_agent, $sday, $eday));
        $this->make_arr_clear();
        $this->OS_LIST('day_browser', sprintf($this->week_agent, $sday, $eday));
        $this->make_arr_clear();
        $this->Search_Engine(sprintf($this->week_referer, $sday, $eday));
        $this->make_arr_clear();
        $this->count  = $this->Fetch_Array(sprintf($this->month_total, $sday));
        $this->column = $this->Fetch_Array_MySQL_week(sprintf($this->month_unique, $sday));
        if (is_array($this->count)) {
            $this->month_archive(_U_AC_GRAPHE);
        }
        $this->make_arr_clear();
    }

    public function make_arr_clear()
    {
        unset($this->count);
        unset($this->column);
    }

    public function archive_view()
    {
        $this->montharchive = true;
    }

    public function archive_Thawing($filename, $log)
    {
        $logs = @file(XOOPS_ROOT_PATH . '/modules/ac_counter/archive/' . $filename, 'r');
        $res  = $this->archive_type($log);
        if ($log != 'month_access') {
            foreach ($logs as $line) {
                [$type, $y_axis, $x_axis] = explode("\t", $line);
                if ($this->str == $type) {
                    $this->count = unserialize($y_axis);
                    if (!$y_axis) {
                        return false;
                    }
                    $this->column = unserialize($x_axis);
                    break;
                }
            }
            $this->MAKE_HTML($this->str);
            return $this->im;
        } else {
            foreach ($logs as $line) {
                [$type, $y_axis, $x_axis, $time_zone] = explode("\t", $line);
                if ($this->str == $type) {
                    $this->arr_total = unserialize($y_axis);
                    if (!$y_axis) {
                        return false;
                    }
                    $this->arr_unique = unserialize($x_axis);
                    $datax            = unserialize($time_zone);
                    $this->datax      = range(1, count($datax));
                    $this->hight      = max($this->arr_total) + 5;
                    $this->low        = 0;
                    $arr_file         = sscanf($filename, '%d-%d-%d-%d.pl', $y, $m, $d, $u);
                    $this->daystring  = sprintf('%s/%s', $y, $m);
                    break;
                }
            }
            $this->is_data('archive');
        }
    }

    public function archive_type($log)
    {
        foreach ($this->log_list as $key => $val) {
            if ($key == $log) {
                $this->str = $val;
                return true;
            }
        }
    }

    public function archive_open()
    {
        $arr_file = [];
        $a        = dir(XOOPS_ROOT_PATH . '/modules/ac_counter/archive/');
        while ($fname = $a->read()) {
            if (!preg_match('.pl', $fname)) {
                continue;
            }
            $arr_file = sscanf($fname, '%d-%d-%d-%d.pl');
            $str      = $this->archive_check($fname);
            if (!$str) {
                continue;
            }
            $this->archive_file .= '<div style="font-size : 12px;text-align : left;">' . $arr_file[0] . '年' . $arr_file[1] . '月' . '<a href="?mode=archive_view&amp;n=' . ac_encode_base64_save($fname) . '"></a>' . $str . '</div>';
        }
        $a->close();
        if (!$this->archive_file) {
            $this->g            = false;
            $this->archive_file = 'アーカイブがありません。';
        } else {
            $this->archive_file .= '<hr width="80%">' . _U_AC_PAGE_VIEW . '【' . $this->allpv . '】::' . _U_AC_UNIQUE . '【' . $this->alluq . '】';
        }
    }

    public function archive_list()
    {
        return $this->archive_file;
    }

    public function archive_check($n)
    {
        $logs = @file(XOOPS_ROOT_PATH . '/modules/ac_counter/archive/' . $n, 'r');
        $i    = 0;
        foreach ($logs as $line) {
            [$type, $cereal, $cereal2, $days] = explode("\t", $line);
            $dat = unserialize($cereal);
            //if (! $dat) return false;
            if (is_array($dat)) {
                $log = $this->archive_log($type);
                if ($type == _U_AC_GRAPHE) {
                    $dat2 = unserialize($cereal2);
                    $pv   = array_sum($dat);
                    $uc   = array_sum($dat2);
                    array_splice($this->G_PV, count($this->G_PV), 0, array_values($dat));
                    array_splice($this->G_UQ, count($this->G_UQ), 0, array_values($dat2));
                    $g_start[$i] = unserialize($days);
                    $i++;
                }
                $list .= '&nbsp;<a href="?mode=archive_view&amp;n=' . ac_encode_base64_save($n) . '&amp;log=' . $log . '">' . $type . '</a>';
            }
        }
        $list          = '：(Total)-' . $pv . '&nbsp;(Unique)-' . $uc . '&nbsp;' . $list;
        $this->allpv   += $pv;
        $this->alluq   += $uc;
        $this->g_start = $g_start[0];
        return $list;
    }

    public function archive_log($type)
    {
        foreach ($this->log_list as $key => $val) {
            if ($val == $type) {
                return $key;
            }
        }
    }

    public function seven_days()
    {
        unset($this->time_zone);
        $sevendays       = [];
        $this->time_zone = [];
        for ($i = 0; $i < 7; $i++) {
            $this->datax[]                   = $this->class_ac_get_one(sprintf($this->week, $this->daystring, $i, '%Y-%m-%d'));
            $sevendays[$i]                   = preg_replace('/', '-', $this->datax[$i]);
            $this->time_zone[$sevendays[$i]] = 0;
        }
        ksort($this->time_zone);
    }

    public function month_days($sday, $eday)
    {
        [$y, $m,] = explode('-', $sday);
        [, , $d] = explode('-', $eday);
        unset($this->time_zone);
        $this->time_zone = [];
        $this->datax     = range(1, $d);
        for ($i = 1; $i < $d + 1; $i++) {
            $a                   = $y . '-' . $m . '-' . sprintf('%02d', $i);
            $this->time_zone[$a] = 0;
        }
    }

    public function class_ac_get_one($sql)
    {
        $res = $this->xoopsDB->fetchRow($this->xoopsDB->query($sql));
        return $res[0];
    }

    public function is_data($str)
    {
        return $this->view_graph($this->datax, $this->arr_unique, $this->arr_total, $this->hight, $this->low, $this->daystring, $str);
    }

    public function is_trend($str)
    {
        return $this->view_trend($this->datax, $this->arr_unique, $this->arr_total, $this->hight, $this->low, $this->daystring, $str);
    }

    public function is_block()
    {
        return $this->view_graph_block($this->arr_unique, $this->arr_total, $this->hight);
    }

    public function is_pages()
    {
        return $this->view_pages($this->pages);
    }

    public function is_just()
    {
        return $this->just;
    }

    public function GET_HOST_VIEW()
    {
        return $this->im;
    }

    public function is_all_graph($str)
    {
        if ($this->g === false) {
            return _U_AC_NO_ARCHIVE;
        }
        $odo_pv         = $this->archive_odometer($this->G_PV);
        $odo_uq         = $this->archive_odometer($this->G_UQ);
        $this->maxhight = max($this->G_PV);
        $datax          = array_map('key_substr', array_keys($this->g_start));
        $str .= ' Start ' . $datax[0];
        return $this->view_all_graph($this->G_PV, $this->G_UQ, $odo_pv, $odo_uq, $str);
    }

    public function just_counter()
    {
        $result = $this->xoopsDB->query($this->just_sql);
        if (!$this->xoopsDB->error($result)) {
            while (false !== ($row = $this->xoopsDB->fetchArray($result))) {
                if (!$row['uname']) {
                    $row['uname'] = 'ゲスト';
                }
                $this->just[$row['counter']]['uname'] = $row['uname'];
                $this->just[$row['counter']]['f_day'] = $row['f_day'];
            }
            $this->xoopsDB->freeRecordSet($result);
        }
    }

    public function Fetch_Array_MySQL_week($sql)
    {
        $result = $this->xoopsDB->query($sql);
        $unique = [];
        while (false !== ($row = $this->xoopsDB->fetchArray($result))) {
            $unique[$row['dd']] = $row['ma'] - $row['mi'];
        }
        $unique = array_merge($this->time_zone, $unique);
        return array_values($unique);
    }

    public function Fetch_Array_MySQL($sql)
    {
        $result = $this->xoopsDB->query($sql);
        $unique = array_fill(0, 24, 0);
        while (false !== ($row = $this->xoopsDB->fetchRow($result))) {
            if ($row[1] > 0) {
                $unique[$row[0]] += 1;
            }
        }
        $unique = array_filter($unique, create_function('$a', 'if ($a === 0) return false;return true;'));
        return $this->is_dayplot_array($unique);
    }

    public function Fetch_Array($sql)
    {
        $result = $this->xoopsDB->query($sql);
        if (!$this->xoopsDB->error($result)) {
            $count = [];
            while (false !== ($row = $this->xoopsDB->fetchRow($result))) {
                $count[$row[1]] = $row[0];
            }
            $this->xoopsDB->freeRecordSet($result);
            return $this->is_dayplot_array($count);
        }
        return false;
    }

    public function is_dayplot_array($arr)
    {
        $unique_count = [];
        reset($arr);
        foreach ($arr as $key => $val) {
            if (is_int($key)) {
                $key = sprintf('%02d:00:00', $key);
            }
            if (preg_match(' ', $key)) {
                $res                   = explode(' ', $key);
                $unique_count[$res[1]] = $val;
            } else {
                $unique_count[$key] = $val;
            }
        }
        $unique_count = array_merge($this->time_zone, $unique_count);
        return array_values($unique_count);
    }

    public function GET_QUERY($sql)
    {
        $result = $this->xoopsDB->query($sql);
        if (!$this->xoopsDB->error($result) && $this->xoopsDB->getRowsNum($result) > 0) {
            return $result;
        }
        return false;
    }

    public function Referer_view($sql)
    {
        global $_SERVER;
        $result = $this->GET_QUERY($sql);
        $refs   = [];
        $src    = ['http://', $_SERVER['HTTP_HOST']];
        if ($result) {
            while (false !== ($row = $this->xoopsDB->fetchArray($result))) {
                $url = [];;
                $column = ($row['referer']) ? mb_convert_encoding(htmlspecialchars($row['referer'], ENT_QUOTES | ENT_HTML5), 'EUC-JP', 'auto') : 'none';
                if (eregi('///', $column)) {
                    continue;
                }
                $url = parse_url($column);
                if ('1' == $column) {
                    $refs = _U_AC_BOOKMARK;
                } elseif ($column == 'none') {
                    $ref = _U_AC_BOOKMARK;
                } elseif (eregi('xxxx', $column)) {
                    $ref = _U_AC_REFCHANGE;
                } elseif (isset($url['host'])) {
                    $ref = $url['host'] . $url['path'];
                } else {
                    $ref = $column;
                }
                $ref                 = str_replace($src, '', $ref);
                $refs[$ref]['count'] = (isset($refs[$ref])) ? $refs[$ref]['count'] + $row['cc'] : $row['cc'];
            }
            $this->count_group($refs);
            $this->MAKE_HTML(_U_AC_REF);
        } else {
            $this->im = $this->daystring . _U_AC_NO_LOG0;
        }
    }

    public function Host_view($sql)
    {
        $hosts  = [];
        $result = $this->GET_QUERY($sql);
        if ($result) {
            while (false !== ($row = $this->xoopsDB->fetchArray($result))) {
                ($row['host']) ? $host = $row['host'] : $host = $row['remote'];
                if (eregi('yahoobb', $host)) {
                    $host = 'yahoobb************.bbtec.net';
                } elseif (eregi('googlebot.com', $host)) {
                    $host = 'googlebot.com';
                } else {
                    $host = eregi_replace("(.*)\.(.*\.(.*\..*$))", "\\2", $host);
                }
                $hosts[$host]['count'] = (isset($hosts[$host])) ? $hosts[$host]['count'] + $row['cc'] : $row['cc'];
            }
            $this->count_group($hosts);
            $this->MAKE_HTML(_U_AC_HOST);
        } else {
            $this->im = $this->daystring . _U_AC_NO_LOG0;
        }
    }

    public function OS_LIST($mode, $sql)
    {
        $useragent = [];
        $result    = $this->GET_QUERY($sql);
        if ($result) {
            while (false !== ($row = $this->xoopsDB->fetchArray($result))) {
                if ($mode == 'day_os') {
                    $useragent[] = $this->Match_List($this->OS_LIST, $row['useragent']);
                } elseif ($mode == 'day_browser') {
                    $useragent[] = $this->Match_List($this->BROWSER_LIST, $row['useragent']);
                }
            }
            $this->is_group($useragent);
            if ($mode == 'day_os') {
                $this->MAKE_HTML('OS');
            } elseif ($mode == 'day_browser') {
                $this->MAKE_HTML(_U_AC_BROWSER);
            }
        } else {
            $this->im = $this->daystring . _U_AC_NO_LOG0;
        }
    }

    public function count_group($arr)
    {
        arsort($arr);
        foreach ($arr as $key => $val) {
            $this->count[]  = (int)$val['count'];
            $this->column[] = $key;
        }
    }

    public function is_group($arr)
    {
        $arrs = @array_count_values($arr);
        arsort($arrs);
        $this->count  = array_values($arrs);
        $this->column = array_keys($arrs);
    }

    public function Match_List($q_list, $str)
    {
        $find = false;
        foreach ($q_list as $key => $val) {
            if (eregi($key, $str)) {
                $foo  = $val;
                $find = true;
                break;
            }
        }
        if (!$find) {
            $foo = _U_AC_UNKNOWN;
        }
        return $foo;
    }

    public function Search_Engine($sql)
    {
        $this->arr_eng = $this->GET_QUERY($sql);
        if ($this->arr_eng) {
            while (false !== ($row = $this->xoopsDB->fetchArray($this->arr_eng))) {
                $eng = $this->Match_List($this->ENG_LIST, $row['referer']);
                if ($eng == _U_AC_UNKNOWN) {
                    continue;
                }
                $this->count[] = $row['count'];
                $str           = mb_convert_encoding(htmlspecialchars($row['referer'], ENT_QUOTES | ENT_HTML5), 'EUC-JP', 'auto');
                if (preg_match('///', $row['referer'])) {
                    continue;
                }
                $url = parse_url($str);
                mb_parse_str($url['query'], $url_query);
                $str = $eng . " 【 '._U_AC_WORDS.'<strong> ";
                foreach ($url_query as $key => $val) {
                    if ($val == '') {
                        continue;
                    }
                    if (eregi('UTF-8|lang_ja|SJIS|off', $val)) {
                        continue;
                    }
                    $src = ['Google', 'N', 'navclient'];
                    $val = str_replace($src, '', $val);
                    if ($val == '') {
                        continue;
                    }
                    if (eregi('q', $key) || eregi('[0-9]', $val)) {
                        if (eregi('cache', $val) || eregi('[0-9]', $val)) {
                            continue;
                        }
                        $str .= mb_convert_encoding($val, 'EUC-JP', 'auto') . ':';
                    }
                }
                $str            .= '</strong>】';
                $str            = str_replace(':</', '</', $str);
                $this->column[] = str_replace('ja', '', $str);
            }
            if (count($this->column) < 1) {
                $this->im = $this->daystring . _U_AC_NO_LOG2 . $this->Engine();
            } else {
                $this->MAKE_HTML(_U_AC_ENGINE);
            }
        } else {
            $this->im = $this->daystring . _U_AC_NO_LOG0;
        }
    }

    public function Engine()
    {
        $str = "\n<br><strong>" . _U_AC_ENGINES . "</strong><br>\n";
        foreach ($this->ENG_LIST as $key => $val) {
            $str .= $key . '&nbsp;(&nbsp;' . $val . ")<br>\n";
        }
        return $str;
    }

    public function Info_HTML()
    {
        $t_sum = array_sum($this->arr_total);
        $u_sum = array_sum($this->arr_unique);
        $t_avg = $t_sum / count($this->arr_total);
        $u_avg = $u_sum / count($this->arr_unique);
        arsort($this->t_res);
        arsort($this->q_res);
        $t_dev    = $this->deviation($this->arr_total, $t_avg);
        $u_dev    = $this->deviation($this->arr_unique, $u_avg);
        $this->im = '
			<table border="0" cellpadding="0" cellspacing="0" width="98%">
				<caption style="font-size : 12px;font-weight : bold;">' . $this->day_title . 'の統計</caption>
				<tbody>
					<tr style="color : black; background-color : #FFFFFF; font-size : 11px;">
						<td width="50%" style="text-align : left;">総訪問者数</td>
						<td width="50%" style="text-align : right;">' . $t_sum . '&nbsp;件</td>
					</tr>
					<tr style="color : black; background-color : #F5F5F5; font-size : 11px;">
						<td width="50%" style="text-align : left;">ユニーク訪問者数</td>
						<td width="50%" style="text-align : right;">' . $u_sum . '&nbsp;件</td>
					</tr>
					<tr style="color : black; background-color : #FFFFFF; font-size : 11px;">
						<td width="50%" style="text-align : left;">ユニーク訪問者数割合</td>
						<td width="50%" style="text-align : right;">' . number_format(($u_sum / $t_sum) * 100, 2) . '&nbsp;%</td>
					</tr>
					<tr style="color : black; background-color : #F5F5F5; font-size : 11px;">
						<td width="50%" style="text-align : left;">時間ごとの平均（総訪問者）</td>
						<td width="50%" style="text-align : right;">' . number_format($t_avg, 2) . '&nbsp;件</td>
					</tr>
					<tr style="color : black; background-color : #FFFFFF; font-size : 11px;">
						<td width="50%" style="text-align : left;">時間ごとの平均（ユニーク）</td>
						<td width="50%" style="text-align : right;">' . number_format($u_avg, 2) . '&nbsp;件</td>
					</tr>
					<tr style="color : black; background-color : #F5F5F5; font-size : 11px;">
						<td width="50%" style="text-align : left;">最多アクセス時間帯（総訪問者）</td>
						<td width="50%" style="text-align : right;">' . key($this->t_res) . '&nbsp;台&nbsp;' . current($this->t_res) . '&nbsp;件</td>
					</tr>
					<tr style="color : black; background-color : #FFFFFF; font-size : 11px;">
						<td width="50%" style="text-align : left;">最多アクセス時間帯（ユニーク）</td>
						<td width="50%" style="text-align : right;">' . key($this->q_res) . '&nbsp;台&nbsp;' . current($this->q_res) . '&nbsp;件</td>
					</tr>
					<tr style="color : black; background-color : #F5F5F5; font-size : 11px;">
						<td width="50%" style="text-align : left;">総訪問者１日の標準偏差と不偏分散</td>
						<td width="50%" style="text-align : right;">' . round($t_dev[0], 5) . '&nbsp;－&nbsp;' . round($t_dev[1], 5) . '</td>
					</tr>
					<tr style="color : black; background-color : #FFFFFF; font-size : 11px;">
						<td width="50%" style="text-align : left;">ユニーク訪問者１日の標準偏差と不偏分散</td>
						<td width="50%" style="text-align : right;">' . round($u_dev[0], 5) . '&nbsp;－&nbsp;' . round($u_dev[1], 5) . '</td>
					</tr>

				</tbody>
		</table>';
    }

    public function deviation($arr, $avg)
    {
        $z_all = 0;
        foreach ($arr as $key => $val) {
            (float)$z_all += ($val - (float)$avg) * ($val - (float)$avg);
        }
        $num   = count($arr) - 1;
        $dev[] = sqrt((float)$z_all / $num);
        $dev[] = (float)$z_all / $num;
        return $dev;
    }

    public function MAKE_HTML($str)
    {
        if ($this->montharchive === false) {
            $this->month_archive($str);
        } else {
            $this->im = '
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<caption style="font-size : 12px;font-weight : bold;">' . $this->day_title . '</caption>
				<tbody>
					<tr style="color : black;font-size : 13px;" class="head">
						<td width="55%">' . $str . '</td>
						<td width="25%">表</td>
						<td width="10%">割合</td>
						<td width="10%">件数</td>
					</tr>';
            $total    = array_sum($this->count);
            $loop     = count($this->count);
            $style    = 'odd';
            for ($i = 0; $i < $loop; $i++) {
                $_percent = round(($this->count[$i] / $total * 100), 2);
                $this->im .= '
					<tr class="' . $style . '">
						<td style="text-align : left;">' . $this->column[$i] . '</td>
						<td style="text-align : left;">
						<table border="0" cellspacing="3" style="color : navy;background-color : navy;width : ' . $_percent . '%;">
							<tr><td></td></tr>
						</table>
						</td>
						<td style="text-align : right;">' . number_format($_percent, 2) . '%</td>
						<td style="text-align : right;">' . $this->count[$i] . '</td>
					</tr>' . "\n";
                $style    = ($style == 'odd') ? 'even' : 'odd';
            }
            $this->im .= '
			</tbody>
		</table>';
        }
    }

    public function log_view()
    {
        global $_SERVER;
        $this->arr_log = $this->GET_QUERY(sprintf($this->day_log, $this->daystring));

        $this->im = '
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<caption style="font-size : 12px;font-weight : bold;">' . $this->day_title . '</caption>
				<tbody>
					<tr style="color : black;font-size : 13px;" class="head">
						<td><strong>時間：[<font color="#ff0000">カウンター</font>]-IP-[<font color="#0000ff">' . _U_AC_HOST . '</font>]--[<font color="#800000">OS</font>]-' . _U_AC_BROWSER . '&lt;改行&gt;' . _U_AC_REF . '</strong></td>
					</tr>';
        if ($this->arr_log) {
            $url   = [];
            $style = 'odd';
            while (false !== ($row = $this->xoopsDB->fetchArray($this->arr_log))) {
                $url = [];
                $ref = '';
                if ($row['host'] == '') {
                    $row['host'] = $row['remote'];
                }
                if (!eregi('///', $row['referer'])) {
                    $url = parse_url($row['referer']);
                    if (isset($url['query']) && $url['host'] == $_SERVER['HTTP_HOST']) {
                        $ref = $url['path'] . urldecode($url['query']);
                    } elseif (isset($url['query'])) {
                        $ref = $url['scheme'] . '://' . $url['host'] . $url['path'] . mb_convert_encoding(urldecode($url['query']), 'EUC-JP', 'auto');
                    } else {
                        $ref = $row['remote'];
                    }
                }
                $os        = $this->Match_List($this->OS_LIST, $row['useragent']);
                $useragent = $this->Match_List($this->BROWSER_LIST, $row['useragent']);
                $this->im  .= '
						<tr class="'
                              . $style
                              . '">
							<td style="text-align : left;">'
                              . $row['t']
                              . ':[<font color="#ff0000">'
                              . $row['counter']
                              . '</font>]&nbsp;'
                              . $row['remote']
                              . '&nbsp;-&nbsp;[<font color="#0000ff">'
                              . $row['host']
                              . '</font>]&nbsp;-&nbsp;[<font color="#800000">'
                              . $os
                              . '</font>]&nbsp;'
                              . $useragent
                              . '<br>'
                              . _U_AC_REF
                              . '：'
                              . htmlspecialchars($ref, ENT_QUOTES | ENT_HTML5)
                              . '</td>
							<td>
						</tr>';
                $style     = ($style == 'odd') ? 'even' : 'odd';
            }
            $this->im .= '
				</tbody>
			</table>';
        } else {
            $this->im = $this->daystring . _U_AC_NO_LOG0;
        }
    }

    public function view_graph_block($datay, $datay2, $maxhight)
    {
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph.php');
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph_line.php');

        $graph = new Graph(150, 120, 'auto');
        $graph->SetScale('linlin', 0, $maxhight);

        $graph->SetFrame(true, 'white');
        $graph->SetBackgroundGradient('#FFF0F5', '#FFFFFF:1.0', GRAD_HOR, BGRAD_PLOT);

        $graph->img->SetMargin(5, 5, 5, 5);//左，右，上，下
        $graph->SetMarginColor('lavender');
        //$graph->SetShadow();
        $graph->SetGridDepth(DEPTH_FRONT);

        $p1 = new LinePlot($datay);
        $p1->SetFillColor('thistle');

        $p2 = new LinePlot($datay2);
        $p2->SetColor('mediumblue');
        $p2->SetFillColor('#F5FFFA');

        $graph->Add($p2);
        $graph->Add($p1);
        return $graph->Stroke();
    }

    public function view_graph($datax, $datay, $datay2, $maxhight, $minhight, $arr, $str2)
    {
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph.php');
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph_line.php');

        $graph = new Graph(500, 600, 'auto');
        $graph->SetScale('textlin', 0, $maxhight);

        $graph->SetFrame(true, 'white');
        $graph->SetBackgroundGradient('#FFF0F5', '#FFFFFF:1.0', GRAD_HOR, BGRAD_PLOT);

        $graph->tabtitle->Set($arr);
        $graph->tabtitle->SetFont(FF_FONT1, FS_NORMAL, 11);

        $graph->xgrid->Show();
        $graph->xgrid->SetColor('black@0.5');
        $graph->xaxis->SetTickLabels($datax);
        $graph->xaxis->SetFont(FF_FONT1, FS_NORMAL, 11);

        $graph->ygrid->Show();
        $graph->ygrid->SetColor('black@0.5');
        $graph->yaxis->title->Set('Access Count');

        $graph->img->SetMargin(60, 40, 150, 50);//左，右，上，下
        $graph->SetMarginColor('lavender');
        $graph->SetShadow();
        $graph->SetGridDepth(DEPTH_FRONT);

        $graph->title->Set($str2);
        $graph->title->SetFont(FF_FONT2, FS_BOLD, 13);

        $graph->legend->SetColor('navy', 'navy');
        $graph->legend->SetFillColor('white@0.25');
        $graph->legend->SetFont(FF_FONT1, FS_NORMAL, 8);
        $graph->legend->SetShadow('darkgray@0.4', 3);
        $graph->legend->SetPos(0.05, 0.05, 'right', 'top');

        $a     = array_sum($datay);
        $a_ave = $a / count($datay);
        $aa    = array_fill(0, count($datay), $a_ave);

        $p3 = new LinePlot($aa);
        $p3->mark->SetType(MARK_STAR);
        $p3->SetColor('red');
        $p3->mark->SetWidth(1);
        $p3->SetLegend('Unique Average ' . round($a_ave, 2));

        $p1 = new LinePlot($datay);
        $p1->SetFillColor('thistle');
        $p1->mark->SetType(MARK_FILLEDCIRCLE);
        $p1->mark->SetFillColor('red');
        $p1->mark->SetWidth(1);
        $p1->SetLegend('Unique ' . $a);

        $b     = array_sum($datay2);
        $b_ave = $b / count($datay2);
        $bb    = array_fill(0, count($datay2), $b_ave);

        $p4 = new LinePlot($bb);
        $p4->mark->SetType(MARK_X);
        $p4->SetColor('green');
        $p4->mark->SetWidth(1);
        $p4->SetLegend('Total Average ' . round($b_ave, 2));

        $p2 = new LinePlot($datay2);
        $p2->SetFillColor('#F5FFFA');
        $p2->SetColor('mediumblue');
        $p2->mark->SetType(MARK_FILLEDCIRCLE);
        $p2->mark->SetFillColor('mediumblue');
        $p2->mark->SetWidth(1);
        $p2->SetLegend('Total ' . $b);

        $graph->Add($p2);
        $graph->Add($p1);
        $graph->Add($p4);
        $graph->Add($p3);
        return $graph->Stroke();
    }

    public function view_trend($datax, $datay, $datay2, $maxhight, $minhight, $arr, $str2)
    {
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph.php');
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph_bar.php');
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph_line.php');

        $datalevel = [0, 0, 0, 0, 0, 0, 0];

        $graph = new Graph(500, 600, 'auto');
        $graph->img->SetMargin(50, 30, 100, 50);//左，右，上，下
        $graph->SetScale('textlin', $minhight, $minhight);
        $graph->SetMarginColor('#F5FFFA');
        $graph->SetShadow();

        $graph->SetFrame(true, 'white');
        $graph->SetBackgroundGradient('#FFF0F5', '#FFFFFF:1.0', GRAD_HOR, BGRAD_PLOT);

        $graph->title->Set($str2);
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 13);
        $graph->title->SetColor('darkred');

        $graph->tabtitle->Set($arr);
        $graph->tabtitle->SetFont(FF_FONT1, FS_NORMAL, 11);

        $graph->xaxis->SetFont(FF_FONT1, FS_NORMAL, 10);
        $graph->yaxis->SetFont(FF_FONT1, FS_NORMAL, 10);

        $graph->yscale->ticks->SupressZeroLabel(true);
        $graph->yaxis->title->Set('Trend');

        $graph->xaxis->SetTickLabels($datax);

        $graph->xaxis->SetPos('min');

        $graph->legend->SetColor('navy', 'navy');
        $graph->legend->SetFillColor('white@0.25');
        $graph->legend->SetFont(FF_ARIAL, FS_BOLD, 8);
        $graph->legend->SetShadow('darkgray@0.4', 3);
        $graph->legend->SetPos(0.05, 0.05, 'right', 'top');

        $p1 = new LinePlot($datalevel);

        $bplot = new BarPlot($datay2);
        $bplot->SetWidth(0.5);
        $bplot->SetLegend('Total', 'blue');

        $bplot2 = new BarPlot($datay);
        $bplot2->SetWidth(0.5);
        $bplot2->SetLegend('Unique', 'blue');

        $bplot->SetFillGradient('navy', 'steelblue', GRAD_MIDVER);
        $bplot2->SetFillGradient('#C71585', '#DB7093', GRAD_MIDVER);

        $bplot->value->SetFormat('%d');
        $bplot->value->Show();
        $bplot->value->SetColor('navy');
        $bplot->value->SetFont(FF_FONT1, FS_BOLD, 10);
        $bplot->value->SetMargin(2);

        $bplot2->value->SetFormat('%d');
        $bplot2->value->Show();
        $bplot2->value->SetColor('orange');
        $bplot2->value->SetFont(FF_FONT1, FS_BOLD, 10);
        $bplot2->value->SetMargin(2);

        $bplot->SetColor('navy');
        $bplot2->SetColor('#C71585');

        $graph->Add($p1);
        $graph->Add($bplot);
        $graph->Add($bplot2);
        return $graph->Stroke();
    }

    public function view_pages($arr)
    {
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph.php');
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph_bar.php');
        arsort($arr);
        $datay = array_values($arr);
        $datax = array_keys($arr);

        $width  = 500;
        $height = 600;

        $graph = new Graph($width, $height, 'auto');
        $graph->SetScale('textlin');

        $graph->SetFrame(true);

        $graph->Set90AndMargin(100, 20, 50, 30);

        $graph->SetMarginColor('white');

        $graph->SetBox();

        $graph->SetBackgroundGradient('white', 'lightblue', GRAD_HOR, BGRAD_PLOT);

        $graph->title->Set('Page of popularity');
        $graph->title->SetFont(FF_FONT1, FS_BOLD, 11);
        $graph->subtitle->Set('(Non optimized)');

        $graph->xaxis->SetTickLabels($datax);
        $graph->xaxis->SetFont(FF_FONT1, FS_NORMAL, 8);

        $graph->xaxis->SetLabelMargin(20);

        $graph->xaxis->SetLabelAlign('right', 'center');

        $graph->yaxis->scale->SetGrace(40);

        $bplot = new BarPlot($datay);
        $bplot->SetShadow();

        $bplot->SetFillGradient('#DC143C', '#FFFFE0', GRAD_HOR);

        $bplot->value->Show();
        $bplot->value->SetFont(FF_FONT1, FS_BOLD, 10);
        $bplot->value->SetAlign('right', 'center');
        $bplot->value->SetColor('#F8F8FF');
        $bplot->value->SetFormat('%.1f');
        $bplot->SetValuePos('max');

        $graph->Add($bplot);

        $txt = new Text('Note: Recently data in half year.');
        $txt->SetPos(250, 599, 'center', 'bottom');
        $txt->SetFont(FF_COMIC, FS_NORMAL, 8);
        $graph->Add($txt);

        $graph->Stroke();
    }

    public function view_all_graph($arr1, $arr2, $arr3, $arr4, $str)
    {
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph.php');
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph_bar.php');
        include(XOOPS_ROOT_PATH . '/modules/ac_counter/include/src/jpgraph_line.php');

        $graph = new Graph(500, 600, 'auto');
        $graph->SetScale('linlin', 0, max($arr1) * 3);
        $graph->SetY2Scale('lin');

        $graph->SetFrame(true, 'white');
        $graph->SetBackgroundGradient('#FFF0F5', '#FFFFFF:1.0', GRAD_HOR, BGRAD_PLOT);

        $graph->tabtitle->Set($str);
        $graph->tabtitle->SetFont(FF_FONT1, FS_NORMAL, 11);

        $graph->xgrid->Show();
        $graph->xgrid->SetColor('black@0.5');
        $graph->xaxis->SetTickLabels(array_keys($this->G_PV));
        $graph->xaxis->SetFont(FF_FONT0, FS_NORMAL, 9);

        $graph->ygrid->SetColor('black@0.5');
        $graph->yscale->ticks->SupressZeroLabel(true);

        $graph->yaxis->SetColor('blue@0.5');
        $graph->yaxis->title->Set('Each day is BAR Graph');
        $graph->yaxis->title->SetColor('black');
        $graph->yaxis->SetTitleMargin(30);
        $graph->yaxis->title->SetFont(FF_FONT1, FS_NORMAL, 11);

        $graph->y2axis->SetColor('black@0.5');
        $graph->y2axis->title->Set('Multiplication');
        $graph->y2axis->title->SetColor('black');
        $graph->y2axis->SetTitleMargin(30);

        $graph->img->SetMargin(60, 60, 120, 50);//左，右，上，下
        $graph->SetMarginColor('#F5F5F5');
        $graph->SetShadow();
        $graph->SetGridDepth(DEPTH_FRONT);

        $graph->title->Set($str);
        $graph->title->SetFont(FF_FONT2, FS_BOLD, 13);

        $graph->legend->SetColor('navy', 'navy');
        $graph->legend->SetFont(FF_FONT1, FS_NORMAL, 8);
        $graph->legend->SetShadow('darkgray@0.4', 3);
        $graph->legend->SetPos(0.05, 0.05, 'right', 'top');

        $p3 = new LinePlot($arr3);
        $p3->SetColor('#FF69B4');
        $p3->mark->SetType(MARK_FILLEDCIRCLE);
        $p3->mark->SetFillColor('#FF69B4');
        $p3->mark->SetWidth(1);
        $p3->SetLegend('added PV ' . max($arr3));

        $p4 = new LinePlot($arr4);
        $p4->SetColor('#228B22');
        $p4->mark->SetType(MARK_FILLEDCIRCLE);
        $p4->mark->SetFillColor('#800000');
        $p4->mark->SetWidth(1);
        $p4->SetLegend('added Unique ' . max($arr4));

        $bplot = new BarPlot($arr1);
        $bplot->SetWidth(0.05);
        $bplot->SetLegend('Total MAX= ' . max($arr1));

        $bplot2 = new BarPlot($arr2);
        $bplot2->SetWidth(0.05);
        $bplot2->SetLegend('Unique MAX= ' . max($arr2));

        $bplot->SetColor('#F0FFFF');
        $bplot2->SetColor('#C71585');

        $bplot->SetFillGradient('navy', 'steelblue', GRAD_MIDVER);
        $bplot2->SetFillGradient('#C71585', '#DB7093', GRAD_MIDVER);

        $graph->AddY2($p3);
        $graph->AddY2($p4);

        $graph->Add($bplot);
        $graph->Add($bplot2);

        return $graph->Stroke();
    }

    public function archive_odometer($arr)
    {
        $odo    = [];
        $odo[0] = $arr[0];
        $dummy  = $arr[0];
        $loop   = count($arr);
        for ($i = 1; $i < $loop; $i++) {
            $dummy   = $arr[$i] + $dummy;
            $odo[$i] = $dummy;
        }
        return $odo;
    }

    public function month_archive($type)
    {
        $fp = fopen(XOOPS_ROOT_PATH . '/modules/ac_counter/archive/' . $this->filename, 'a+');

        $count = serialize($this->count);
        $col   = serialize($this->column);
        if ($type == _U_AC_GRAPHE) {
            $days = serialize($this->time_zone);
            $dat  = $type . "\t" . $count . "\t" . $col . "\t" . $days . "\n";
        } else {
            $dat = $type . "\t" . $count . "\t" . $col . "\n";
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $dat);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}

function key_substr($usdat)
{
	$val = sscanf($usdat, '%d-%d-%d', $y, $m, $d);
	$str = sprintf('%s/%s', $y, $m);
	return $str;
}

//ファイル変換
function ac_encode_base64_save($data)
{
	$b = serialize($data);
	$b = base64_encode($b);
	return $b;
}

//ファイル復元
function ac_decode_base64_load($data)
{
	$p = base64_decode($data);
	$p = unserialize($p);
	return $p;
}

function ac_get_one($sql)
{
	global $xoopsDB;
	$res = $xoopsDB->fetchRow($xoopsDB->query($sql));
	return $res[0];
}

function ac_make_day_options($y, $m, $d, $limit_y, $obj)
{
	if (!$y && !$m && !$d) {
		$today = date('Y-n-j', time());
		[$y, $m, $d] = explode('-', $today);
	}
	($limit_y) ? $ylist = range(2000, $limit_y) : $ylist = range(2000, 2010);
	$date_option = '<select name="' . $obj . '_y]"><option value="--">--年</option>' . "\n";
	$loop = count($ylist);
	for($i = 0; $i < $loop; $i++) {
		$select = '';
        if ($ylist[$i] == $y) $select = ' selected';
        $date_option .= '<option value="' . $ylist[$i] . '"' . $select . '>' . $ylist[$i] . '年</option>' . "\n";
	}
	if ($y != 'none') {
        $date_option .= '</select>&nbsp;<select name="' . $obj . '_m]"><option value="--">--月</option>' . "\n";
		for($i = 1; $i < 13; $i++) {
			$select = '';
            if ($i == $m) $select = ' selected';
            $date_option .= '<option value="' . $i . '"' . $select . '>' . (string)$i . '月</option>' . "\n";
		}
	}
	if ($d) {
		$date_option .= '</select>&nbsp;<select name="' . $obj . '_d]"><option value="--">--日</option>' . "\n";
		$d_last = date('t', mktime(0, 0, 0, $m, 1, $y, 0));
		$select = '';
        for($i = 1; $i < $d_last + 1; $i++) {
			$select = '';
            if ($i == $d) $select = ' selected';
            $date_option .= '<option value="' . $i . '"' . $select . '>' . (string)$i . '日</option>' . "\n";
		}
	}
	return $date_option . '</select>' . "\n";
}


