<?php

class Open_Counter
{
    public function __construct()
    {
        global $_SERVER;
        $this->xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        if (!defined('_JPGRAPH')) {
            require XOOPS_ROOT_PATH . '/modules/ac_counter/include/countef_conf.php';
        }
        $this->addr = $_SERVER['REMOTE_ADDR'];
        $this->host = $_SERVER['REMOTE_HOST'];
        if (($this->host == $this->addr || $this->host == '') && isset($this->addr)) {
            $this->host = gethostbyaddr($this->addr);
        }
        $this->agent = (!isset($_SERVER['HTTP_USER_AGENT'])) ? 'unknown' : $this->u_sql_escape_strings($_SERVER['HTTP_USER_AGENT']);
        if (!isset($_SERVER['HTTP_REFERER'])) {
            if (isset($_SERVER['REDIRECT_URL'])) {
                $this->ref = $this->u_sql_escape_strings($_SERVER['REDIRECT_URL']);
            } else {
                $this->ref = 'none';
            }
        } else {
            $this->ref = $this->u_sql_escape_strings($_SERVER['HTTP_REFERER']);
        }
    }

    public function u_Dos_Counter()
    {
        $sql    = (XOOPS_DB_TYPE == 'mysql') ? 'SELECT * FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE days > DATE_SUB(NOW(), INTERVAL 1 MINUTE) AND remote = '" . $this->addr . "'" : 'SELECT * FROM '
                                                                                                                                                                                                 . $this->xoopsDB->prefix('ac_counter')
                                                                                                                                                                                                 . " WHERE days > ( CURRENT_TIMESTAMP - '1 minute'::INTERVAL) AND remote = '"
                                                                                                                                                                                                 . $this->addr
                                                                                                                                                                                                 . "'";
        $result = $this->xoopsDB->query($sql);
        if (!$this->xoopsDB->error($result) && $this->xoopsDB->getRowsNum($result) > _U_DOS) {
            if (_REGISTER_BAD_IPS === true) {
                $this->antidos_register_bad_ips($this->addr);
            }
            header('Location: ' . _U_DOS_KICK);
            exit;
        }
    }

    public function antidos_register_bad_ips($ip)
    {
        $rs = $this->xoopsDB->query('SELECT conf_value FROM ' . $this->xoopsDB->prefix('config') . " WHERE conf_name='bad_ips' AND conf_modid=0 AND conf_catid=1");
        [$bad_ips_serialized] = $this->xoopsDB->fetchRow($rs);
        $bad_ips    = unserialize($bad_ips_serialized);
        $bad_ips[]  = $ip;
        $conf_value = addslashes(serialize(array_unique($bad_ips)));
        $sql        = 'UPDATE ' . $this->xoopsDB->prefix('config') . " SET conf_value='$conf_value' WHERE conf_name='bad_ips' AND conf_modid=0 AND conf_catid=1";
        $res        = $this->result_sql($sql);
    }

    public function Counter()
    {
        global $xoopsUser, $xoopsModule;
        (is_object($xoopsUser)) ? $this->uid = $xoopsUser->getVar('uid') : $this->uid = 0;
        (is_object($xoopsModule)) ? $this->page = $xoopsModule->getVar('mid') : $this->page = '/';
        $sql    = (XOOPS_DB_TYPE == 'mysql') ? 'SELECT * FROM ' . $this->xoopsDB->prefix('ac_counter') . ' WHERE days > DATE_SUB(NOW(), INTERVAL ' . _REPEAT . " MINUTE) AND remote = '" . $this->addr . "'" : 'SELECT * FROM '
                                                                                                                                                                                                               . $this->xoopsDB->prefix('ac_counter')
                                                                                                                                                                                                               . " WHERE days > ( CURRENT_TIMESTAMP - '"
                                                                                                                                                                                                               . _REPEAT
                                                                                                                                                                                                               . " minute'::INTERVAL ) AND remote = '"
                                                                                                                                                                                                               . $this->addr
                                                                                                                                                                                                               . "'";
        $result = $this->xoopsDB->query($sql);
        if ($this->xoopsDB->error($result)) {
            $this->counter = 0;
        } else {
            $this->counter = $this->max_counter();
            $this->counter++;
            $repeat = false;
            if ($this->xoopsDB->getRowsNum($result) > 0) {
                $this->counter--;
                $repeat = true;
            }
        }

        $ins_sql = (XOOPS_DB_TYPE == 'mysql') ? 'INSERT INTO ' . $this->xoopsDB->prefix('ac_counter') . " (uid,remote,host,referer,useragent,pageid,counter,inday) VALUES ($this->uid, '$this->addr', '$this->host', '$this->ref', '$this->agent', '$this->page' , $this->counter, NOW())" : 'INSERT INTO '
                                                                                                                                                                                                                                                                                             . $this->xoopsDB->prefix(
                'ac_counter'
            )
                                                                                                                                                                                                                                                                                             . " (uid,remote,host,referer,useragent,pageid,counter) VALUES ($this->uid, '$this->addr', '$this->host', '$this->ref', '$this->agent', '$this->page' , $this->counter)";
        if (false === strpos($this->addr, _SELF_ADDR)) {
            $res = $this->result_sql($ins_sql);
            if ($this->counter % 1000 == 0 && $this->counter > 990 && $repeat === false) {
                $this->just_counter();
            }
            if (preg_match('^[1]+$|^[2]+$|^[3]+$|^[4]+$|^[5]+$|^[6]+$|^[7]+$|^[8]+$|^[9]+$', $this->counter) && $this->counter > 100 && $repeat === false) {
                $this->just_counter();
            }
        }
        if (_U_USE_ANTIDOS === true) {
            $this->u_Dos_Counter();
        }
        srand((double)microtime() * 100000);
        if (rand(0, _AC_CUT_EXEC) <= 10) {
            switch (_AC_CUT_TYPE) {
                case 0:
                    $this->counter_logcut_number();
                    break;
                case 1:
                    $this->counter_logcut_day();
                    break;
                case 2:
                    $this->counter_logcut_month();
                    break;
                default:
                    $this->counter_logcut_number();
                    break;
            }
        }
    }

    public function just_counter()
    {
        $ins_sql = 'INSERT INTO ' . $this->xoopsDB->prefix('just_counter') . " (uid,counter,pageid) VALUES ($this->uid, $this->counter, '$this->page')";
        $res     = $this->result_sql($ins_sql);
    }

    public function max_counter_text()
    {
        $this->get_max_counter();
        return $this->count_text;
    }

    public function counter_txt()
    {
        $this->counter_text();
        return $this->_counter_txt;
    }

    public function max_counter()
    {
        $this->get_max_counter();
        return $this->counter;
    }

    public function get_max_counter()
    {
        $counter = $this->xoopsDB->fetchRow($this->xoopsDB->query('SELECT max(counter) FROM ' . $this->xoopsDB->prefix('ac_counter')));
        ($counter) ? $this->counter = $counter[0] : $this->counter = 0;
        $this->count_text = sprintf('%06d', $this->counter);
    }

    public function counter_text()
    {
        global $xoopsModule;
        $page       = (is_object($xoopsModule)) ? $xoopsModule->getVar('mid') : '/';
        $_yesterday = [];
        $_today     = [];

        $sql_yesterday = (XOOPS_DB_TYPE == 'mysql') ? 'SELECT count(*),inday FROM ' . $this->xoopsDB->prefix('ac_counter') . ' WHERE inday = DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY) GROUP BY inday,counter' : 'SELECT count(*),dd FROM (SELECT count(counter),inday as dd FROM ' . $this->xoopsDB->prefix(
                'ac_counter'
            ) . " GROUP BY inday,counter) as tt WHERE dd = CURRENT_DATE - '1 days'::INTERVAL GROUP BY dd";

        $sql_today = (XOOPS_DB_TYPE == 'mysql') ? 'SELECT count(*),inday FROM ' . $this->xoopsDB->prefix('ac_counter') . ' WHERE inday = CURRENT_DATE() GROUP BY inday,counter' : 'SELECT count(*),dd FROM (SELECT count(counter),inday as dd FROM '
                                                                                                                                                                                  . $this->xoopsDB->prefix('ac_counter')
                                                                                                                                                                                  . ' GROUP BY inday,counter) as tt WHERE dd = CURRENT_DATE GROUP BY dd';

        $sql_all_view = 'SELECT max(id) FROM ' . $this->xoopsDB->prefix('ac_counter');

        $sql_page = 'SELECT count(pageid),pageid FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE pageid = '" . $page . "' GROUP BY pageid";

        $res = $this->xoopsDB->query($sql_yesterday);
        if (!$this->xoopsDB->error($res)) {
            $_yesterday = $this->xoopsDB->fetchArray($res);
            if (XOOPS_DB_TYPE == 'mysql' && $_yesterday) {
                $_count = 0;
                foreach ($_yesterday as $key => $val) {
                    $_count += $val[0];
                }
                $_yesterday[0] = $_count;
            }
            $this->xoopsDB->freeRecordSet($res);
        }
        $res = $this->xoopsDB->query($sql_today);
        if (!$this->xoopsDB->error($res)) {
            $_today = $this->xoopsDB->fetchArray($res);
            if (XOOPS_DB_TYPE == 'mysql' && $_today) {
                $_count = 0;
                foreach ($_today as $key => $val) {
                    $_count += $val[0];
                }
                $_today[0] = $_count;
            }
            $this->xoopsDB->freeRecordSet($res);
        }
        $res = $this->xoopsDB->query($sql_all_view);
        if (!$this->xoopsDB->error($res)) {
            $_all_view = $this->xoopsDB->fetchRow($res);
            $this->xoopsDB->freeRecordSet($res);
        }
        $res = $this->xoopsDB->query($sql_page);
        if (!$this->xoopsDB->error($res)) {
            $_page_view = $this->xoopsDB->fetchRow($res);
            $this->xoopsDB->freeRecordSet($res);
        }
        $this->_counter_txt[0] = sprintf('%06d', $_yesterday[0]);
        $this->_counter_txt[1] = sprintf('%06d', $_today[0]);
        $this->_counter_txt[2] = sprintf('%08d', $_all_view[0]);
        $this->_counter_txt[3] = sprintf('%08d', $_page_view[0]);
    }

    public function result_sql($sql)
    {
        $sql = (XOOPS_DB_TYPE == 'mysql') ? $result = $this->xoopsDB->queryF($sql) : $result = $this->result_pg_sql($sql);
        return $result;
    }

    public function result_pg_sql($sql)
    {
        $result = pg_query($this->xoopsDB->conn, 'BEGIN');
        $result = pg_query($this->xoopsDB->conn, $sql);
        if ($this->xoopsDB->error($result)) {
            $result = pg_query($this->xoopsDB->conn, 'ABORT');
            //$this->xoopsDB->close();
            //exit;
            return $result;
        }
        $result = pg_query($this->xoopsDB->conn, 'COMMIT');
        return $result;
    }

    public function counter_logcut_number()
    {
        $id = $this->xoopsDB->fetchRow($this->xoopsDB->query('SELECT max(id) FROM ' . $this->xoopsDB->prefix('ac_counter')));
        if (!$this->xoopsDB->error($id) || $id[0] > 0) {
            $con = $id[0] - _AC_CUT;
            if ($con > _AC_CUT) {
                $result = $this->result_sql('DELETE FROM ' . $this->xoopsDB->prefix('ac_counter') . ' WHERE id < ' . $con);
            }
        }
    }

    public function counter_logcut_day()
    {
        $id = (XOOPS_DB_TYPE == 'mysql') ? $this->xoopsDB->fetchRow($this->xoopsDB->query('SELECT count(*) FROM ' . $this->xoopsDB->prefix('ac_counter') . ' WHERE DATE_SUB(CURRENT_DATE(), INTERVAL ' . _AC_CUT_DAYS . ' DAY) > days')) :

            $this->xoopsDB->fetchRow($this->xoopsDB->query('SELECT count(*) FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE (CURRENT_DATE - '" . _AC_CUT_DAYS . " days'::INTERVAL) > days"));
        if (!$this->xoopsDB->error($id) || $id[0] > 0) {
            $result = (XOOPS_DB_TYPE == 'mysql') ? $this->result_sql('DELETE FROM ' . $this->xoopsDB->prefix('ac_counter') . ' WHERE DATE_SUB(CURRENT_DATE(), INTERVAL ' . _AC_CUT_DAYS . ' DAY) > days AND id > 1') : $this->result_sql(
                'DELETE FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE (CURRENT_DATE - '" . _AC_CUT_DAYS . " days'::INTERVAL) > days AND id > 1"
            );
        }
    }

    public function counter_logcut_month()
    {
        $id = (XOOPS_DB_TYPE == 'mysql') ? $this->xoopsDB->fetchRow($this->xoopsDB->query('SELECT count(*) FROM ' . $this->xoopsDB->prefix('ac_counter') . ' WHERE inday < DATE_SUB(CURRENT_DATE(), INTERVAL ' . _AC_CUT_MONTH . ' MONTH)')) :

            $this->xoopsDB->fetchRow($this->xoopsDB->query('SELECT count(*) FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday < date_trunc('month', CURRENT_TIMESTAMP) - '" . _AC_CUT_MONTH . " month'::INTERVAL"));
        if (!$this->xoopsDB->error($id) || $id[0] > 0) {
            $result = (XOOPS_DB_TYPE == 'mysql') ? $this->result_sql('DELETE FROM ' . $this->xoopsDB->prefix('ac_counter') . ' WHERE inday < DATE_SUB(CURRENT_DATE(), INTERVAL ' . _AC_CUT_MONTH . ' MONTH)') : $this->result_sql(
                'DELETE FROM ' . $this->xoopsDB->prefix('ac_counter') . " WHERE inday < date_trunc('month', CURRENT_TIMESTAMP) - '" . _AC_CUT_MONTH . " month'::INTERVAL"
            );
        }
    }

    public function u_sql_escape_strings($sql)
    {
        if (XOOPS_DB_TYPE == 'pgsql' && function_exists('pg_escape_string')) {
            return pg_escape_string($sql);
        } elseif (function_exists('$GLOBALS['xoopsDB']->escape')) {
        return $GLOBALS['xoopsDB']->escape($sql);
    } else if (function_exists('mysql_escape_string')) {
        return $GLOBALS['xoopsDB']->escape($sql);
    } else {
        return addslashes($sql);
    }
		return $sql;
	}
}



