<?php

namespace App\Pithos\common;

class Constant {
	const DEFAULT_PAGE = 10;
	
	const statusCodes = [
		'continue'                    => ['code'                    => 100, 'message'                    => 'Continue,Informational'],
		'switch-protocol'             => ['code'             => 101, 'message'             => 'Switching Protocols,Informational'],
		'ok'                          => ['code'                          => 200, 'message'                          => 'OK,Successful'],
		'created'                     => ['code'                     => 201, 'message'                     => 'Created,Successful'],
		'accepted'                    => ['code'                    => 202, 'message'                    => 'Accepted,Successful'],
		'non-authorize'               => ['code'               => 203, 'message'               => 'Non-Authoritative Information,Successful'],
		'no-content'                  => ['code'                  => 204, 'message'                  => 'No Content,Successful'],
		'reset'                       => ['code'                       => 205, 'message'                       => 'Reset Content,Successful'],
		'partial'                     => ['code'                     => 206, 'message'                     => 'Partial Content,Successful'],
		'multiple'                    => ['code'                    => 300, 'message'                    => 'Multiple Choices,Redirection'],
		'moved'                       => ['code'                       => 301, 'message'                       => 'Moved Permanently,Redirection'],
		'found'                       => ['code'                       => 302, 'message'                       => 'Found,Redirection'],
		'see'                         => ['code'                         => 303, 'message'                         => 'See Other,Redirection'],
		'not-modified'                => ['code'                => 304, 'message'                => 'Not Modified,Redirection'],
		'use-proxy'                   => ['code'                   => 305, 'message'                   => 'Use Proxy,Redirection'],
		'tempo-redirect'              => ['code'              => 307, 'message'              => 'Temporary Redirect,Redirection'],
		'bad-request'                 => ['code'                 => 400, 'message'                 => 'Bad Request,Client Error'],
		'unauthorized-client'         => ['code'         => 401, 'message'         => 'Unauthorized,Client Error'],
		'payment-required'            => ['code'            => 402, 'message'            => 'Payment Required,Client Error'],
		'forbidden'                   => ['code'                   => 403, 'message'                   => 'Forbidden,Client Error'],
		'not-found'                   => ['code'                   => 404, 'message'                   => 'There is no content found'],
		'method-not-allow'            => ['code'            => 405, 'message'            => 'Method Not Allowed,Client Error'],
		'not-accept'                  => ['code'                  => 406, 'message'                  => 'Not Acceptable,Client Error'],
		'proxy-authenticate-required' => ['code' => 407, 'message' => 'Proxy Authentication Required,Client Error'],
		'request-timeout'             => ['code'             => 408, 'message'             => 'Request Timeout,Client Error'],
		'conflict'                    => ['code'                    => 409, 'message'                    => 'Conflict,Client Error'],
		'gone'                        => ['code'                        => 410, 'message'                        => 'Gone,Client Error'],
		'length-required'             => ['code'             => 411, 'message'             => 'Length Required,Client Error'],
		'precondition-failed'         => ['code'         => 412, 'message'         => 'Precondition Failed,Client Error'],
		'request-exceed'              => ['code'              => 413, 'message'              => 'Request Entity Too Large,Client Error'],
		'url-long'                    => ['code'                    => 414, 'message'                    => 'Request-URI Too Long,Client Error'],
		'unsupported-media'           => ['code'           => 415, 'message'           => 'Unsupported Media Type,Client Error'],
		'range-exceed'                => ['code'                => 416, 'message'                => 'Requested Range Not Satisfiable,Client Error'],
		'expectation-fail'            => ['code'            => 417, 'message'            => 'Expectation Failed,Client Error'],
		'unprocess'                   => ['code'                   => 422, 'message'                   => 'Unprocessable Entity'],
		'many-requests'               => ['code'               => 429, 'message'               => 'Too Many Requests'],
		'internal-server-error'       => ['code'       => 500, 'message'       => 'Internal Server Error,Server Error'],
		'not-implemented'             => ['code'             => 501, 'message'             => 'Not Implemented,Server Error'],
		'bad-gateway'                 => ['code'                 => 502, 'message'                 => 'Bad Gateway,Server Error'],
		'service-unavailable'         => ['code'         => 503, 'message'         => 'Service Unavailable,Server Error'],
		'gateway-timeout'             => ['code'             => 504, 'message'             => 'Gateway Timeout,Server Error'],
		'http-v-error'                => ['code'                => 505, 'message'                => 'HTTP Version Not Supported,Server Error'],
	];

	const Image_path = '/posts/photo';

	const Video_path = '/posts/video';

	const Other_path = '/posts/other';

	const POST_UPLOAD_PATH = '/posts/upload/';

	const CATEGORY_UPLOAD_PATH = '/categories/upload/';

	const GROUP_UPLOAD_PATH = '/groups/upload/';

}