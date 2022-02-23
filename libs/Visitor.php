<?php

class Visitor
{
    const Table = 'visitors';
    const Rows = ['ip_address', 'user_agent', 'view_date', 'page_url', 'views_count'];

    public function getIp(): string
    {
        return ip2long($_SERVER['REMOTE_ADDR']);
    }

    public function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function getViewDate(): string
    {
        return (new \DateTime('now'))->format(Mysql::DATE_FORMAT);
    }

    public function getPageUrl(): string
    {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }

    public function getViewsCount(): string
    {
        return 1;
    }
}