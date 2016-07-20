<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 表格模板
 */
$template = array(
    'table_open'        => '<table class="table table-bordered table-hover">',

    'thead_open'        => '<thead>',
    'thead_close'       => '</thead>',

    'heading_row_start' => '<tr>',
    'heading_row_end'   => '</tr>',
    'heading_cell_start'=> '<th>',
    'heading_cell_end'  => '</th>',

    'tbody_open'        => '<tbody>',
    'tbody_close'       => '</tbody>',

    'row_start'         => '<tr>',
    'row_end'           => '<td><button type="submit" class="btn btn-danger btn-group-lg">删除</button></td></tr>',
    'cell_start'        => '<td>',
    'cell_end'          => '</td>',

    'row_alt_start'     => '<tr>',
    'row_alt_end'       => '<td><button type="submit" class="btn btn-danger btn-group-lg">删除</button></td></tr>',
    'cell_alt_start'    => '<td>',
    'cell_alt_end'      => '</td>',

    'table_close'       => '</table>'
);

return $template;