<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 5/22/17
 * Time: 2:52 PM
 */

return [
	//	计算时间戳时，起始基准时间
	'ms_base_time'		=> '2017-01-01',

	//	时间戳记录位数，考虑当前时间毫秒时间戳距离ms_base_time数字，大概使用39bit即可表示
	'ms_time_bit_len'	=> '39',

	//	业务线ID
	'op_id'				=> '1',

	//	业务线ID记录位数，预计小于15个业务线以内，大概需要4位
	'op_id_bit_len'		=> '4',

	//	机房ID
	'mr_id'				=> '1',

	//	机房ID记录位数，预计小于4个机房，两位记录
	'mr_id_bit_len'		=> '2',

	//	服务器ID
	'server_id'			=> '1',

	//	每个机房机器小于100台，预留7位记录
	'server_id_bit_len'	=> '7',

	//	预留字段，默认值为0
	'left'				=> '0',

	//	总共64位，扣除其他，剩余5位
	'left_bit_len'		=> '5',

	//	毫秒内序列号，方便随机，并可以根据该字段进行hash，方便分库分表
	'in_ms_sn_bit_len'	=> '7',

];