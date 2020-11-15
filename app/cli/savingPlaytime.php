#!/usr/bin/env php
<?php

/* CLI script for saving helpers playtime. (every week - cron)*/

namespace App\Cli;

use App\Bootstrap;
use Nette\Database\Context;

$bt = Bootstrap::bootCli()->createContainer();
/** @var Context $context */
$cxt = $bt->getByType(Context::class);
$arr = [];
$helpers = $cxt->query("SELECT t2.username, t1.uuid, t1.permission, t1.server, t3.playtime FROM luckperms_user_permissions AS t1
LEFT JOIN luckperms_players AS t2 ON t1.uuid = t2.uuid INNER JOIN playtime AS t3 ON t2.username = t3.username;")->fetchAll();
foreach ($helpers as $helper) array_push($arr, [$helper->username, $helper->playtime]);
$cxt->table("playtime_week")->insert($arr);