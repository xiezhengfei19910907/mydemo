<?php

/**
 * 实际操作下 看下结果 并总结
 */



$process = new \Swoole\Process(function ($process) use ($file, $position, $db_config, $rsync_db_config, $tables) {
    $db_name = $db_config['name'];
    $link = binlog_connect("mysql://{$db_config['user']}:{$db_config['passwd']}@{$db_config['host']}:{$db_config['port']}");
    binlog_set_position($link, $position, $file);
    $count = 0;
    $cur_position = 0;
    while ($event = binlog_wait_for_next_event($link, $db_name)) {
        if (isset($event['next_position'])) {
            $event['cur_position'] = $cur_position;
            $cur_position = $event['next_position'];
        }

        switch ($event['type_code']) {
            case BINLOG_DELETE_ROWS_EVENT:
                break;
            case BINLOG_WRITE_ROWS_EVENT:
                break;
            case BINLOG_UPDATE_ROWS_EVENT:
                break;
            case BINLOG_QUERY_EVENT:
                if (!in_array($event['query'], ['# Dum', 'BEGIN', 'COMMIT'])) {
                    //获取表名
                    preg_match("/delete\s+from\s+(.+?)\s|update\s+(.+?)\s|insert\s+into\s+(.+?)\s|alter\s+table\s+(.+?)\s|truncate\s+(.+)/ie",
                        $event['query'], $matches);
                    unset($matches[0]);
                    foreach ($matches as $t) {
                        if (!empty($t)) {
                            $table_name_str = trim(str_replace("`", "", $t));
                            if (strpos($table_name_str, ".") !== false) {
                                list($db_name, $table_name) = explode(".", $table_name_str);
                            } else {
                                $table_name = $table_name_str;
                            }
                        }
                    }
                    if (!isset($tables[$table_name])) {
                        continue;
                    }
                    $count++;
                    $params['db_name'] = $db_name;
                    $params['rsync_db_config'] = $rsync_db_config;
                    $params['table_name'] = $table_name;
                    $params['start_time'] = time();
                    $params['key'] = "{$rsync_db_config}_{$file}_{$event['next_position']}";
                    $params['count'] = $count;
                    $params['is_binlog'] = true;
                    $params['data'] = $event['query'];
                    $process->write(\swoole_serialize::pack($params));
                }
                break;
            default:
                break;
        }
    }
});