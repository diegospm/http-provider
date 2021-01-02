<?php

declare(strict_types=1);

namespace HttpProvider;

class UriFormatter
{
    public function makeUri(string $base_uri, string $endpoint, array $params = []): string
    {
        $endpoint = $this->formatEndpoint($endpoint, $params);

        if (!preg_match("/^\//", $endpoint)) {
            $endpoint = '/' . $endpoint;
        }

        return $base_uri . $endpoint . $this->makeQuery($params);
    }

    private function formatEndpoint(string $endpoint, array &$params): string
    {
        preg_match_all('/\:(\w+)/im', $endpoint, $matches);

        foreach ($matches[1] as $match) {
            if (isset($params[$match])) {
                $endpoint = str_replace(':' . $match, $params[$match], $endpoint);

                unset($params[$match]);
            }
        }

        return $endpoint;
    }

    private function makeQuery(array $args): string
    {
        $params = array_filter($args, function ($param, $key) {
            if (!is_array($param) && $key !== 'body') {
                return $param;
            }
        }, \ARRAY_FILTER_USE_BOTH);

        return empty($params) ? '' : '?' . http_build_query($params, '', '&');
    }
}
