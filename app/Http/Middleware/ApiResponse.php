<?php
/**
 * Created by PhpStorm.
 * User: krzysztof
 * Date: 03.10.17
 * Time: 08:09
 */

namespace App\Response;

use Response, Input;
use App\Exception\ApiException;

class ApiResponse extends Response
{

    protected static $page  = 1;
    protected static $pages = 1;
    protected static $limit = 20;
    const MAX_PER_PAGE = 100;

    public static function makeResponse($data, $nodeName = 'data')
    {
        $final = [];
        $meta     = self::generateMeta($data);
        if (is_array($data)) {
            $final[$nodeName] = array_slice($data, (self::$page - 1) * self::$limit, self::$limit);
            unset($data);
        } else {
            if (is_object($data)) {
                $final[$nodeName] = $data;
            }
        }

        $final['metadata'] = $meta;

        self::$page  = 1;
        self::$pages = 1;
        self::$limit = 20;


        $response = Response::json($final);
        $response->header('Content-Type', 'application/json');

        return $response;
    }

    public static function generateMeta($data)
    {
        if (request()->has('limit') && request()->get('limit') > 0 && request()->get('limit') <= self::MAX_PER_PAGE) {
            self::$limit = request()->get('limit');
        } else {
            self::$limit = 20;
        }

        self::$pages = ceil(count($data) / self::$limit);

        if (request()->has('page') && request()->get('page') > 0 && request()->get('page') <= self::$pages) {
            self::$page = request()->get('page');
        } elseif (request()->has('page') && request()->get('page') > self::$pages) {
            throw new ApiException(400, '400_page_limit', array(
                'page'  => request()->get('page'),
                'pages' => self::$pages
            ));
        }

        if (isset($_SERVER) && array_key_exists('REQUEST_URI', $_SERVER)) {

            $link = $_SERVER['REQUEST_URI'];
            // remove api_key from the link
            if (strstr($link, 'api_key')) {
                $link = preg_replace('/api_key=[a-z0-9]{24}\&?/', '', $link);
                if (substr($link, -1) === '&') {
                    $link = substr($link, 0, -1);
                }
            }
        } else {
            $link = '';
        }

        if (strpos($link, '?')) {
            preg_match('/page=[0-9]+/', $link, $matches);
            if (count($matches)) {
                $link = preg_replace('/page=[0-9]+/', 'page={page}', $link);
            } else {
                if (substr($link, -1) == '?') {
                    $link .= 'page={page}';
                } else {
                    $link .= '&page={page}';
                }
            }
            preg_match('/limit=[0-9]+/', $link, $matches);
            if (count($matches)) {
                $link = preg_replace('/limit=[0-9]+/', 'limit={limit}', $link);
            } else {
                if (substr($link, -1) == '?') {
                    $link .= 'limit={limit}';
                } else {
                    $link .= '&limit={limit}';
                }
            }
        } else {
            $link .= '?page={page}&limit={limit}';
        }

        if (is_array($data) || $data instanceof \Iterator) {
            $metaData = [
                'total' => count($data),
                'page'  => (int)self::$page,
                'pages' => (int)self::$pages,
                'limit' => (int)self::$limit,
                'links' => [
                    'self'  => [
                        'href' => self::tokens([
                            'page'  => self::$page,
                            'limit' => self::$limit
                        ], $link)
                    ],
                    'first' => [
                        'href' => self::tokens([
                            'page'  => 1,
                            'limit' => self::$limit
                        ], $link)
                    ],
                    'last'  => [
                        'href' => self::tokens([
                            'page'  => self::$pages,
                            'limit' => self::$limit
                        ], $link)
                    ]
                ]
            ];
            if (self::$page > 1) {
                $metaData['links']['previous']['href'] = self::tokens([
                    'page'  => (self::$page - 1),
                    'limit' => self::$limit
                ], $link);
            }
            if (self::$page < self::$pages) {
                $metaData['links']['next']['href'] = self::tokens([
                    'page'  => (self::$page + 1),
                    'limit' => self::$limit
                ], $link);
            }

            if (self::$pages == 0) {
                unset($metaData['links']['first']);
                unset($metaData['links']['last']);
            }

            return $metaData;
        }

        if (is_object($data)) {
            $metaData = [
                'total' => 1,
                'page'  => 1,
                'pages' => 1,
                'limit' => (int)self::$limit,
                'links' => [
                    'self' => [
                        'href' => self::tokens([
                            'page'  => self::$page,
                            'limit' => self::$limit
                        ], $link)
                    ]
                ]
            ];

            return $metaData;
        }

        throw new \Exception('Not supported type');
    }

    protected static function tokens(array $tokens, $link)
    {
        foreach ($tokens as $key => $value) {
            $link = str_replace('{' . $key . '}', $value, $link);
        }

        return $link;
    }
}