<?php

// Запрет прямого доступа.
defined('_JEXEC') or die;

	function AdvaloremBuildRoute(&$query)
	{
        /*
        echo 'Router Build:<br>';
        foreach($query as $key=>$el){echo $key.'-'.$el.'<br>';}
        */

        $segments = array();

        /*
        if(isset($query['view']))
        {
            $segments[] = $query['view'];
            unset($query['view']);
        }
        if(isset($query['id']))
        {
            $segments[] = $query['id'];
            unset($query['id']);
        };

        $segments[] = $query['option'];
        //$segments[] = 'search';
        */

        //$segments[] = 'view';

        return $segments;

    }

    function AdvaloremParseRoute($segments)
    {
        $total = count($segments);

        /*
        echo 'Router Parse:'.$total.'<br>';
        foreach($segments as $key=>$el){echo $key.'-'.$el.'<br>';}
        */

        $vars = array();

        switch(isset($segments[0]))
        {

            case 'view':
                $vars['task'] = 'view';
                $id = explode(':', $segments[1]);
                $vars['uid'] = (int)$id[0];
                break;

            /*
            case 'categories':
                $vars['view'] = 'categories';
                break;
            case 'category':
                $vars['view'] = 'category';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int)$id[0];
                break;
            case 'article':
                $vars['view'] = 'article';
                $id = explode(':', $segments[1]);
                $vars['id'] = (int)$id[0];
                break;
            */
        }


        return $vars;
    }



?>