<?php


namespace App\Helpers;

use Illuminate\Support\Str;

class Helper
{
    public static function category($categories, $parent_id = 0, $char = '')
    {
        $html = '';

        foreach ($categories as $key => $category) {
            if ($category->parent_id == $parent_id) {
                $html .= '
                    <tr>   
                        <td>' . $category->id . '</td>
                        <td>' . $char . $category->name . '</td>
                        <td>' . self::active($category->active) . '</td>
                        <td>' . $category->updated_at . '</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="/admin/categories/edit/' . $category->id . '">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm"
                                onclick="removeRow(' . $category->id . ', \'/admin/categories/destroy\')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                ';

                unset($categories[$key]);

                $html .= self::category($categories, $category->id, $char . '|--');
            }
        }

        return $html;
    }

    public static function active($active = 0): string
    {
        return $active == 0 ? '<span class="btn btn-danger btn-xs">NO</span>'
            : '<span class="btn btn-success btn-xs">YES</span>';
    }




    public static function categories($categories, $parent_id = 0) :string
    {
        $html = '';
        $categories = collect($categories)->sortBy('id');
        foreach ($categories as $key => $category) {
            if ($category->parent_id == $parent_id) {
                $html .= '
                    <li>
                        <a href="/category/' . $category->id . '-' . Str::slug($category->name, '-') . '.html">
                            ' . $category->name . '
                        </a>';

                unset($categories[$key]);

                if (self::isChild($categories, $category->id)) {
                    $html .= '<ul class="sub-menu">';
                    $html .= self::categories($categories, $category->id);
                    $html .= '</ul>';   
                }

                $html .= '</li>';
            }
        }

        return $html;
    }

    public static function isChild($categories, $id) : bool
    {
        foreach ($categories as $category) {
            if ($category->parent_id == $id) {
                return true;
            }
        }

        return false;
    }

    public static function price($price = 0, $priceSale = 0)
    {
        if ($priceSale != 0) return number_format($priceSale);
        if ($price != 0)  return number_format($price);
        return '<a href="/contact.html">Liên Hệ</a>';
    }
}