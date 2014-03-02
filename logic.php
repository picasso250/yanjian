<?php
function is_hongbao($base)
{
    return 1 == rand(1, $base);
}

function has_hongbao_today($open_id)
{
    return ORM::for_table('hongbao')
        ->where_gte('create_time', date('Y-m-d 00:00:00'))
        ->where_lte('create_time', date('Y-m-d 23:59:59'))
        ->where('open_id', $open_id)
        ->find_one();
}

function save_hongbao($open_id, $is_hongbao)
{
    $hongbao = ORM::for_table('hongbao')->create();
    $hongbao->open_id = $open_id;
    $hongbao->is_hongbao = $is_hongbao;
    $hongbao->set_expr('create_time', 'now()');
    $hongbao->save();
    return $hongbao->id;
}

function get_all() {
    return ORM::for_table('hongbao')
        ->order_by_asc('id')
        ->find_many();
}
