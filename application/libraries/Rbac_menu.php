<?php

/*
 * Author: Himansu S
 * Updated by: Shivaraj B
 * Date: 11-APR-2018
 * 
 */

class Rbac_menu {

    public function __construct($param) {
        $this->_ci = & get_instance();
        $this->_tree = $param['tree'];
        $this->_session = $param['rbac_session'];
    }

    private $_tree;
    private $_session;
    private $_ci;

    function show_menu() {
        $tree = $this->_tree;
        $menuHtml = '<ul class="nav nav-pills" role="tablist">';
        $menu_no = 1;
        foreach ($tree as $pcode => $menus) {
            if (isset($menus[$pcode])) {
                $parent_menu = $menus[$pcode];
                unset($menus[$pcode]);
                $menu_name = $parent_menu['perm_desc'];
            } else if (is_array($menus) && $menus['perm_parent'] == 0) {
                $parent_menu = $menus;
                $menu_name = $parent_menu['perm_desc'];
                $menus = array(); //it has only one main menu
            }
            $menuHtml .= '<li class="hver' . $menu_no++ . ' dropdown">';
            //pma($menuHtml);
            $caret = (is_array($menus) && count($menus) > 0) ? '<span class="caret"></span>' : '';
            $dropdown = (is_array($menus) && count($menus) > 0) ? 'data-toggle="dropdown"' : '';
            $menuHtml .= '<a ' . $parent_menu['perm_attr'] . ' href="' . base_url() . $parent_menu['perm_url'] . '" class="' . $parent_menu['perm_class'] . '" ' . $dropdown . '>
	     <i class="fa ' . $parent_menu['perm_icon'] . '"></i>&nbsp;<span>' . $menu_name . '</span> ' . $caret . '
                    </a>';

            if (is_array($menus) && count($menus) > 0) {
                $menuHtml .= '<ul id="clk' . $menu_no++ . '" class="dropdown-menu no-padding nav" role="menu">';
                //Logic to determine large menu
                $large_menu_flag = 0;
                foreach ($menus as $cnt_loop) {
                    if (is_array($cnt_loop) && !array_key_exists('perm_id', $cnt_loop)) {
                        $large_menu_flag++;
                    }
                }
                $counter = 0;
                $chunk_size_counter = 0;
                $chunk_size = 2;
                $chunk_size_flag = true;

                foreach ($menus as $lkey => $lrow) {
                    $chunk_size_counter++;
                    if (is_array($lrow) && !array_key_exists('perm_id', $lrow)) {
                        $counter++;
                        if ($chunk_size_flag) {
                            $chunk_size = $chunk_size_counter;
                            $chunk_size_flag = false;
                        }
                    }
                }

                $chunk_menu = $menus;
                $div_class = 'col-sm-12';
                if ($counter >= 2) {
                    $chunk_menu = array_chunk($menus, $chunk_size, true);
                    $div_class = 'col-sm-6';
                    $menuHtml = str_replace('[LARGE_MENU_CLASS]', 'dropdown-menu-large', $menuHtml);
                }
                foreach ($chunk_menu as $submenu_key => $sub_menu) {
                    if ($counter >= 2) {
                        $menuHtml .= '<li class="' . $div_class . '">';
                        $menuHtml .= '<ul>';
                    }
                    if (is_array($sub_menu) && array_key_exists('perm_id', $sub_menu) && strpos($sub_menu['perm_class'], 'menu_opt_lbl') === false) {
                        $menuHtml .= $this->_create_li($sub_menu);
                        //pma($sub_menu);
                    } else if (is_array($sub_menu) && !array_key_exists('perm_id', $sub_menu)) {
                        foreach ($sub_menu as $s_skey => $s_sub_menu) {
                            if ($div_class == 'col-sm-12') {//no large menu                         
                                if (isset($sub_menu[$submenu_key])) {//populate lebel       
                                    $menuHtml .= '<li role="separator" class="divider"></li>';
                                    $menuHtml .= $this->_create_li_lebles($sub_menu[$submenu_key], 'dropdown-header2');
                                    unset($sub_menu[$submenu_key]); //show actual menu                                    
                                    $menuHtml .= $this->_create_li($s_sub_menu);
                                } else if (strpos($s_sub_menu['perm_class'], 'menu_opt_lbl') === false) {
                                    $menuHtml .= $this->_create_li($s_sub_menu);
                                }
                            } else { //for large menu 
                                if (is_array($s_sub_menu) && array_key_exists('perm_id', $s_sub_menu)) {
                                    $menuHtml .= $this->_create_li($s_sub_menu);
                                    $menu_name = '';
                                } else if (is_array($s_sub_menu) && !array_key_exists('perm_id', $s_sub_menu)) {
                                    //can have sub menu or separator
                                    //populate menu lebels                   
                                    if (array_key_exists($s_skey, $s_sub_menu)) {
                                        //$menuHtml .= '<li role="separator" class="divider"></li>';
                                        $menuHtml .= $this->_create_li_lebles($s_sub_menu[$s_skey]);
                                        unset($s_sub_menu[$s_skey]);
                                        unset($sub_menu[$submenu_key]);
                                    }
                                }
                            }
                        }
                    }
                    if ($counter >= 2) {
                        $menuHtml .= '</ul>';
                        $menuHtml .= '</li>';
                    }
                }
                $menuHtml .= '</ul>';
            }
        }
        $menuHtml .= '</ul>';
        return $menuHtml;
    }

    private function _create_li($menu_data, $extra_attr = null) {
        $menuHtml = '<li><a ' . $menu_data['perm_attr'] . '                             
            href="' . base_url() . $menu_data['perm_url'] . '" class="' . $menu_data['perm_class'] . '" ' . $extra_attr . '>
            <span>' . $menu_data['perm_desc'] . '</span>
			</a></li>';
        return $menuHtml;
    }

    /* function used for RBAC */

    public function show_user_menu_left($tree_array_flag = FALSE) {
        if ($this->_ci->rbac->is_login()) {
            $menu_arr = $this->_tree;

            $tree = $menu_arr;

            if ($tree_array_flag) {
                return $tree;
            }
            if ($tree && is_array($tree)) {
                $menu_str = '<ul class="app-menu" role="tablist">';
                foreach ($tree as $pcode => $menus) {
                    if (isset($menus[$pcode])) {
                        if (isset($menus[$pcode])) {
                            $parent = $menus[$pcode];
                            unset($menus[$pcode]);
                            $pmenu = $parent['perm_desc'];
                        } else if (is_array($menus) && $menus['perm_parent'] == 0) {
                            $parent = $menus;
                            $pmenu = $parent['perm_desc'];
                            $menus = array(); //it has only one main menu
                        }
                        $caret = (is_array($menus) && count($menus) > 0) ? '<i class="treeview-indicator fa fa-angle-right"></i>' : '';
                        $dtdropdown = (is_array($menus) && count($menus) > 0) ? 'data-toggle="treeview"' : '';
                        $dropdown = (is_array($menus) && count($menus) > 0) ? 'class="treeview"' : '';
                        $url = ($parent['perm_url'] == '#') ? '#' : base_url($parent['perm_url']);
                        if (strtolower($pmenu) == "feedback") {
                            $url = $parent['perm_url'];
                        }
                        $menu_str .= '<li ' . $dropdown . '>
                                   <a href="' . $url . '" ' . $dtdropdown . ' class="app-menu__item" ' . $parent['perm_attr'] . ' ><i class="app-menu__icon ' . $parent['perm_icon'] . '"></i><span class="app-menu__label">' . $pmenu . '</span>' . $caret . '</a>';
                        //$smenu_flag = 1;

                        if (is_array($menus) && count($menus) > 0) {
                            $menu_str .= '<ul class="treeview-menu" role="menu">';
                            foreach ($menus as $scode => $menu) {
                                if (isset($menu[$scode])) {
                                    $caret = (is_array($menus) && count($menus) > 0) ? '<span class="caret"></span>' : '';
                                    $dropdown = (is_array($menus) && count($menus) > 0) ? '' : '';
                                    $sparent = $menu[$scode];
                                    $pmenu = $sparent['perm_desc'];
                                    unset($menu[$scode]);
                                    $menu_str .= '<li><a class="treeview-item"  href="#"> ' . $pmenu . '</a>';
                                    //sub-sub menu
                                    $menu_str .= '<ul class="treeview-menu">';
                                    foreach ($menu as $sscode => $ssmenu) {
                                        $smenu = $ssmenu['perm_desc'];
                                        $menu_str .= '<li ' . $ssmenu['perm_attr'] . ' ><a  tabindex="-1" href="' . base_url($ssmenu['perm_url']) . '" ' . $ssmenu['perm_class'] . '>' . $smenu . '</a></li>';
                                    }
                                    $menu_str .= '</ul>';
                                    $menu_str .= '<li>';
                                } else {
                                    $smenu = $menu['perm_desc'];
                                    $menu_str .= '<li ' . $menu['perm_attr'] . ' ><a class="treeview-item" href="' . base_url($menu['perm_url']) . '" ' . $menu['perm_class'] . '> ' . $smenu . '</a></li>';
                                }
                            }
                            $menu_str .= '</ul>';
                            $menu_str .= '</li>';
                        }
                    } else {
                        $pmenu = $menus['perm_desc'];
                        $menu_str .= '<li>
                                   <a href="' . base_url($menus['perm_url']) . '">
                                       <i class="fa ' . $menus['perm_class'] . '"></i> <span>' . $pmenu . '</span>                    
                                   </a>
                               </li>';
                    }
                }
                $menu_str .= '</ul>';
                //pma($menu_str, 1);
                return $menu_str;
            }
        }
        return 1;
    }

    function _tree_view($results, $parent_id) {
        $tree = array();
        $counter = sizeof($results);
        $results = array_values($results);
        for ($i = 0; $i < $counter; $i++) {
            if ($results[$i]['perm_parent'] == $parent_id) {
                //echo $results[$i]['perm_code'];
                if ($this->_has_child($results, $results[$i]['perm_id'])) {
                    $sub_menu = $this->_tree_view($results, $results[$i]['perm_id']);
                    $index = strtoupper($results[$i]['perm_code'] . '_' . $results[$i]['perm_id']);
                    $tree[$index] = $sub_menu;
                    $tree[$index][$index] = $results[$i];
                } elseif ($this->_is_self_parent($results[$i])) {
                    $index = strtoupper($results[$i]['perm_code'] . '_' . $results[$i]['perm_id']);
                    $tree[$index] = array($index => $results[$i]);
                } else {
                    $index = strtoupper($results[$i]['perm_code'] . '_' . $results[$i]['perm_id']);
                    if (count($results[$i]) > 1) {
                        if ($results[$i]['perm_parent'] == 0) {
                            $tree[$index] = $results[$i];
                        } else {
                            $tree[$i] = $results[$i];
                        }
                    } else {
                        $tree[$index] = $results[$i];
                    }
                }
            }
        }
        return $tree;
    }

    private function _has_child($results, $menu_id) {
        $counter = sizeof($results);
        for ($i = 0; $i < $counter; $i++) {
            if ($results[$i]['perm_parent'] == $menu_id) {
                return true;
            }
        }
        return false;
    }

    private function _is_self_parent($results) {
        if ($results['perm_parent'] == 0) {
            return true;
        }
        return false;
    }

    private function _create_li_lebles($menu_data, $class = 'dropdown-submenu') {
        $menu_name = $menu_data['perm_desc'];

        $menuHtml = '<li style="color:blue" class="' . $class . ' ' . $menu_data['perm_class'] . '"><span>' . $menu_name . '</span></li>';
        return $menuHtml;
    }

    public function show_user_menu_top($tree_array_flag = FALSE) {
        if ($this->_ci->rbac->is_login()) {
            $menu_arr = $this->_tree;

            $tree = $menu_arr;

            if ($tree_array_flag) {
                return $tree;
            }
            if ($tree && is_array($tree)) {
                $menu_str = '<ul class="nav navbar-nav">';
                foreach ($tree as $pcode => $menus) {
                    if (isset($menus[$pcode])) {
                        if (isset($menus[$pcode])) {
                            $parent = $menus[$pcode];
                            unset($menus[$pcode]);
                            $pmenu = $parent['perm_desc'];
                        } else if (is_array($menus) && $menus['perm_parent'] == 0) {
                            $parent = $menus;
                            $pmenu = $parent['perm_desc'];
                            $menus = array(); //it has only one main menu
                        }
                        $caret = (is_array($menus) && count($menus) > 0) ? '<span class="caret"></span>' : '';
                        $dtdropdown = (is_array($menus) && count($menus) > 0) ? 'data-toggle="dropdown"' : '';
                        $dropdown = (is_array($menus) && count($menus) > 0) ? 'class="dropdown"' : '';
                        $url = ($parent['perm_url'] == '#') ? '#' : base_url($parent['perm_url']);
                        if (strtolower($pmenu) == "feedback") {
                            $url = $parent['perm_url'];
                        }
                        $menu_str .= '<li ' . $dropdown . '>
                                   <a href="' . $url . '" ' . $dtdropdown . ' ' . $parent['perm_attr'] . ' >' . $pmenu . $caret . '</a>';
                        //$smenu_flag = 1;
                        if (is_array($menus) && count($menus) > 0) {
                            $menu_str .= '<ul class="dropdown-menu" role="menu">';
                            foreach ($menus as $scode => $menu) {
                                if (isset($menu[$scode])) {
                                    $caret = (is_array($menus) && count($menus) > 0) ? '<span class="caret"></span>' : '';
                                    $dropdown = (is_array($menus) && count($menus) > 0) ? '' : '';
                                    $sparent = $menu[$scode];
                                    $pmenu = $sparent['perm_desc'];
                                    unset($menu[$scode]);
                                    $menu_str .= '<li class="dropdown"><a tabindex="-1" href="#"> ' . $pmenu . '</a>';
                                    //sub-sub menu
                                    $menu_str .= '<ul class="dropdown-menu" role="menu">';
                                    foreach ($menu as $sscode => $ssmenu) {
                                        $smenu = $ssmenu['perm_desc'];
                                        $menu_str .= '<li ' . $ssmenu['perm_attr'] . ' ><a  tabindex="-1" href="' . base_url($ssmenu['perm_url']) . '" ' . $ssmenu['perm_class'] . '>' . $smenu . '</a></li>';
                                    }
                                    $menu_str .= '</ul>';
                                    $menu_str .= '<li>';
                                } else {
                                    $smenu = $menu['perm_desc'];
                                    $menu_str .= '<li ' . $menu['perm_attr'] . ' ><a href="' . base_url($menu['perm_url']) . '" ' . $menu['perm_class'] . '> ' . $smenu . '</a></li>';
                                }
                            }
                            $menu_str .= '</ul>';
                            $menu_str .= '</li>';
                        }
                    } else {
                        $pmenu = $menus['perm_desc'];
                        $menu_str .= '<li>
                                   <a href="' . base_url($menus['perm_url']) . '">
                                       <i class="fa ' . $menus['perm_class'] . '"></i> <span>' . $pmenu . '</span>                    
                                   </a>
                               </li>';
                    }
                }
                $menu_str .= '</ul>';
                //pma($menu_str, 1);
                return $menu_str;
            }
        }
        return 1;
    }

}
