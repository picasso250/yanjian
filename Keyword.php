<?php

class KeywordModel
{
    protected $limit = 4;
    
    /**
     * 根据关键字搜索
     */
    public function search($q)
    {
        $list = $this->get_list_from_db($q);
        if ($list) {
            return $list;
        }

        $response = $this->get_json_response_from_bing($q);
        $obj = json_decode($response);
        $results = $obj->d->results;
        $list = $this->get_list_from_db($q);
        if ($list) {
            return $list;
        }
        $keyword = ORM::for_table('keyword')->create();
        $keyword->name = $q;
        $keyword->hit = 0;
        $keyword->set_expr('created', 'NOW()');
        $keyword->save();

        foreach ($results as $index => $e) {
            if ($index > $this->limit) {
                break;
            }
            $description = ORM::for_table('description')->create();
            $description->keyword_id = $keyword->id;
            $description->description = '**'.$e->Title.'**'."\n".$e->Description."\n[$e->DisplayUrl]($e->Url)";
            $description->set_expr('updated', 'NOW()');
            $description->save();
        }

        $list = $this->get_list_from_db($q);
        return $list;
    }

    // 从数据库中获取
    private function get_list_from_db($q)
    {
        $keyword = ORM::for_table('keyword')->where('name', $q)->find_one();
        if (!$keyword) {
            return false;
        }

        $list = ORM::for_table('description')
            ->where('keyword_id', $keyword->id)
            ->order_by_desc('updated')
            ->find_many();
        return $list;
    }

    // 从bing获取
    function get_json_response_from_bing($q)
    {
        $acctKey = 'tTWCKOHsb0tbGkFjaIvsr1Bba15057Hj2hPloJgu0p4';
        $rootUri = 'https://api.datamarket.azure.com/Bing/Search';
        $query = urlencode("'{$q}'");
        $serviceOp = 'Web';
        $requestUri = "$rootUri/$serviceOp?\$format=json&Query=$query";

        $auth = base64_encode("$acctKey:$acctKey");
        $data = array(
            'http' => array(
            'request_fulluri' => true,
            // ignore_errors can help debug – remove for production. This option added in PHP 5.2.10
            'ignore_errors' => true,
            'header' => "Authorization: Basic $auth")
        );

        $context = stream_context_create($data);

        // Get the response from Bing.
        $response = file_get_contents($requestUri, 0, $context);
        return $response;
    }
}

