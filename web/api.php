<?php

include dirname(__DIR__) . '/config.php';

use LogSystem\LogSystem;

// 鉴定权限
if (empty ($_GET['password']) || $_GET['password'] != '123456') {
    echo "Access Denied";
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';


function returnJson($data)
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Content-Type: text/html;charset=UTF8");
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function _get($name, $default = false)
{
    if (!isset($_GET[$name])) {

        returnJson(array('status' => 1, 'msg' => sprintf('参数%s获取失败', $name)));
    }

    if (empty($_GET[$name])) {
        return $default;
    }

    return $_GET[$name];
}

function _post($name, $default = false)
{
    if (!isset($_POST[$name])) {

        if ($default !== false) {
            return $default;
        }

        returnJson(array('status' => 1, 'msg' => sprintf('参数%s获取失败', $name)));
    }

    return $_POST[$name];
}


$storage = DI::Storage();


switch (strtolower($action)) {
    case "init":
        $system_arr = array();
        foreach (LogSystem::$AllowSystem as $key => $value) {
            $system_arr [] = array(
                'Name'  => $key,
                'Value' => $value,
                'Key'   => 'SystemId',
            );
        }

        $levels_arr = array();
        foreach (LogSystem::getLevels() as $key => $value) {
            $levels_arr [] = array(
                'Name'  => $key,
                'Value' => $value,
                'Key'   => 'Level',
            );
        }

        $type_arr = array();
        foreach (LogSystem::getTypes() as $key => $value) {
            $type_arr [] = array(
                'Name'  => $key,
                'Value' => $value,
                'Key'   => 'Type',
            );
        }

        $list = array(
            'SystemName' => array(
                'title'   => 'SystemName',
                'records' => $system_arr
            ),
            'Level'      => array(
                'title'   => 'Level',
                'records' => $levels_arr
            ),
            'Type'       => array(
                'title'   => 'Type',
                'records' => $type_arr
            ),
        );

        returnJson($list);
        break;
    case "query":
        //参数
        $offset     = (int)_post('Offset', 0);
        $limit      = _post('Limit', 10);
        $system_id  = _post('SystemId', 1);
        $time_start = _post('TimeStart', 0);
        $time_end   = _post('TimeEnd', time());
        $level      = _post('Level', 1);
        $type       = _post('Type', 1);

        $exists = LogSystem::existsSystemId($system_id);
        if (!$exists) {
            $info = array('status' => 1, 'msg' => '不存在这个系统的日志', 'data' => array());
            returnJson($info);
        }

        //请求数据
        $time_start = substr($time_start, 0, 10);
        $time_end   = substr($time_end, 0, 10);
        $records    = $storage->find($system_id, $time_start, $time_end, $level, $type, null, null, $limit, $offset);
        $count      = $storage->count($system_id, $time_start, $time_end, $level, $type, null, null);

        if (!empty($records)) {
            foreach ($records as $key => $value) {
                $records[$key]['Time']  = date('Y-m-d H:i:s', $records[$key]['Time']);
                $records[$key]['Level'] = array_search($records[$key]['Level'], LogSystem::getLevels());
                $records[$key]['Type']  = array_search($records[$key]['Type'], LogSystem::getTypes());
            }
        }

        $arr  = array(
            'records' => $records,
            'count'   => $count
        );
        $info = array('status' => 0, 'msg' => '', 'data' => $arr);
        returnJson($info);

        break;
    case "queryone":
        //参数
        $system_id = _get('SystemId');
        $id        = _get('Id');

        $exists = LogSystem::existsSystemId($system_id);
        if (!$exists) {
            $info = array(
                'status' => 1,
                'msg'    => '不存在这个系统的日志',
                'data'   => array()
            );
            returnJson($info);
        }

        $log  = $storage->fetch($system_id, $id);
        $info = array(
            'status' => 0,
            'msg'    => '',
            'data'   => $log ?: array()
        );

        returnJson($info);

        break;
}





